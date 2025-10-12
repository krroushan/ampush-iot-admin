<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationWebController extends Controller
{
    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Display notifications index page
     */
    public function index()
    {
        // Get statistics
        $totalCustomers = User::where('role', 'customer')->count();
        $activeCustomers = User::where('role', 'customer')->whereNotNull('fcm_token')->count();
        
        // Get notification statistics
        $totalSent = Notification::where('sent', true)->sum('sent_count');
        $totalFailures = Notification::where('sent', true)->sum('failure_count');
        $successRate = $totalSent > 0 ? (($totalSent - $totalFailures) / $totalSent) * 100 : 100;
        $todaySent = Notification::where('sent', true)
            ->whereDate('sent_at', today())
            ->sum('sent_count');
        
        $stats = [
            'total_sent' => $totalSent,
            'success_rate' => round($successRate, 1),
            'active_customers' => $activeCustomers,
            'today_sent' => $todaySent
        ];

        // Get notifications with pagination
        $notifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('stats', 'notifications'));
    }

    /**
     * Display send notification form
     */
    public function send()
    {
        $customers = User::where('role', 'customer')
            ->select('id', 'name', 'phone_number', 'email')
            ->orderBy('name')
            ->get();
            
        return view('admin.notifications.send', compact('customers'));
    }

    /**
     * Store and send notification
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'send_to' => 'required|string|in:all,specific,phone',
            'customer_id' => 'required_if:send_to,specific|nullable|exists:users,id',
            'phone_numbers' => 'required_if:send_to,phone|nullable|string',
            'data' => 'nullable|json'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $customers = collect();
            $tokens = [];

            if ($request->send_to === 'all') {
                $customers = User::where('role', 'customer')->whereNotNull('fcm_token')->get();
                $tokens = $customers->pluck('fcm_token')->toArray();
            } elseif ($request->send_to === 'specific') {
                $customer = User::where('id', $request->customer_id)
                    ->where('role', 'customer')
                    ->whereNotNull('fcm_token')
                    ->first();
                
                if ($customer) {
                    $customers = collect([$customer]);
                    $tokens = [$customer->fcm_token];
                }
            } elseif ($request->send_to === 'phone') {
                $phoneNumbers = array_filter(array_map('trim', explode("\n", $request->phone_numbers)));
                $customers = User::where('role', 'customer')
                    ->whereIn('phone_number', $phoneNumbers)
                    ->whereNotNull('fcm_token')
                    ->get();
                $tokens = $customers->pluck('fcm_token')->toArray();
            }

            if (empty($tokens)) {
                return redirect()->back()
                    ->with('error', 'No customers with FCM tokens found for the selected criteria.')
                    ->withInput();
            }

            // Create notification record
            $notification = Notification::create([
                'title' => $request->title,
                'body' => $request->body,
                'data' => $request->data ? json_decode($request->data, true) : [],
                'type' => $request->send_to === 'all' ? 'broadcast' : 'targeted',
                'user_id' => $request->send_to === 'specific' ? $request->customer_id : null,
                'sent' => false
            ]);

            Log::info('Sending notification via web interface', [
                'tokens_count' => count($tokens),
                'title' => $request->title,
                'send_to' => $request->send_to
            ]);

            // Send notification via Firebase
            $result = $this->firebaseService->sendToMultipleDevices(
                $tokens,
                $request->title,
                $request->body,
                $request->data ? json_decode($request->data, true) : []
            );

            // Handle Firebase result safely
            $successCount = count($tokens);
            $failureCount = 0;
            
            try {
                if ($result && method_exists($result, 'successes')) {
                    $successCount = $result->successes()->count();
                }
                
                if ($result && method_exists($result, 'failures')) {
                    $failureCount = $result->failures()->count();
                }
            } catch (\Exception $e) {
                Log::warning('Could not get Firebase result counts', [
                    'error' => $e->getMessage(),
                    'result_class' => get_class($result)
                ]);
            }

            // Update notification with results
            $notification->update([
                'sent' => true,
                'sent_at' => now(),
                'sent_count' => $successCount,
                'failure_count' => $failureCount
            ]);

            return redirect()->route('notifications.index')
                ->with('success', "Notification sent successfully! {$successCount} recipients notified.");

        } catch (\Exception $e) {
            Log::error('Failed to send notification via web interface: ' . $e->getMessage(), ['exception' => $e]);
            
            return redirect()->back()
                ->with('error', 'Failed to send notification: ' . $e->getMessage())
                ->withInput();
        }
    }
}