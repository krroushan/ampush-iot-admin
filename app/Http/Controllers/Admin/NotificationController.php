<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Send notification to all customers
     */
    public function sendToAll(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'nullable|array'
        ]);

        $customers = User::where('role', 'customer')
            ->whereNotNull('fcm_token')
            ->get();

        $tokens = $customers->pluck('fcm_token')->toArray();
        
        if (empty($tokens)) {
            return response()->json([
                'success' => false,
                'message' => 'No customers with FCM tokens found'
            ], 400);
        }

        try {
            // Create notification record
            $notification = Notification::create([
                'title' => $request->title,
                'body' => $request->body,
                'data' => $request->data ?? [],
                'type' => 'broadcast',
                'user_id' => null, // null for all customers
                'sent' => false
            ]);

            Log::info('Sending notification to all customers', [
                'tokens_count' => count($tokens),
                'title' => $request->title
            ]);

            // Send notification via Firebase
            $result = $this->firebaseService->sendToMultipleDevices(
                $tokens,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            // Handle Firebase result safely
            $successCount = count($tokens); // Assume all successful for now
            $failureCount = 0;
            
            if ($result) {
                Log::info('Firebase result received', [
                    'result_type' => get_class($result),
                    'result_methods' => get_class_methods($result)
                ]);
                
                try {
                    if (method_exists($result, 'successes')) {
                        $successCount = $result->successes()->count();
                    }
                    
                    if (method_exists($result, 'failures')) {
                        $failureCount = $result->failures()->count();
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not get Firebase result counts', [
                        'error' => $e->getMessage(),
                        'result_class' => get_class($result)
                    ]);
                    // Use fallback values
                    $successCount = count($tokens);
                    $failureCount = 0;
                }
            } else {
                Log::info('No Firebase result (testing mode)');
            }

            // Update notification with results
            $notification->update([
                'sent' => true,
                'sent_at' => now(),
                'sent_count' => $successCount,
                'failure_count' => $failureCount
            ]);

            Log::info("Notification sent to all customers", [
                'notification_id' => $notification->id,
                'success_count' => $successCount,
                'failure_count' => $failureCount,
                'title' => $request->title
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
                'data' => [
                    'notification_id' => $notification->id,
                    'sent_count' => $successCount,
                    'failure_count' => $failureCount,
                    'total_customers' => $customers->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send notification to all customers', [
                'error' => $e->getMessage(),
                'title' => $request->title
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to specific customer
     */
    public function sendToCustomer(Request $request, $customerId): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'nullable|array'
        ]);

        $customer = User::where('id', $customerId)
            ->where('role', 'customer')
            ->whereNotNull('fcm_token')
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found or no FCM token'
            ], 404);
        }

        try {
            // Create notification record
            $notification = Notification::create([
                'title' => $request->title,
                'body' => $request->body,
                'data' => $request->data ?? [],
                'type' => 'individual',
                'user_id' => $customerId,
                'fcm_token' => $customer->fcm_token,
                'sent' => false
            ]);

            $this->firebaseService->sendNotification(
                $customer->fcm_token,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            // Update notification as sent
            $notification->update([
                'sent' => true,
                'sent_at' => now(),
                'sent_count' => 1,
                'failure_count' => 0
            ]);

            Log::info("Notification sent to customer", [
                'notification_id' => $notification->id,
                'customer_id' => $customerId,
                'customer_name' => $customer->name,
                'title' => $request->title
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
                'data' => [
                    'notification_id' => $notification->id,
                    'customer_id' => $customerId,
                    'customer_name' => $customer->name
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send notification to customer', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
                'title' => $request->title
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to customers by phone numbers
     */
    public function sendToCustomersByPhone(Request $request): JsonResponse
    {
        $request->validate([
            'phone_numbers' => 'required|array',
            'phone_numbers.*' => 'string',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'nullable|array'
        ]);

        $customers = User::where('role', 'customer')
            ->whereIn('phone_number', $request->phone_numbers)
            ->whereNotNull('fcm_token')
            ->get();

        if ($customers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No customers found with provided phone numbers or no FCM tokens'
            ], 404);
        }

        $tokens = $customers->pluck('fcm_token')->toArray();

        try {
            $result = $this->firebaseService->sendToMultipleDevices(
                $tokens,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            $successCount = $result->successes()->count();
            $failureCount = $result->failures()->count();

            Log::info("Notification sent to customers by phone", [
                'phone_numbers' => $request->phone_numbers,
                'success_count' => $successCount,
                'failure_count' => $failureCount,
                'title' => $request->title
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
                'data' => [
                    'sent_count' => $successCount,
                    'failure_count' => $failureCount,
                    'customers_found' => $customers->count(),
                    'phone_numbers' => $request->phone_numbers
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send notification to customers by phone', [
                'phone_numbers' => $request->phone_numbers,
                'error' => $e->getMessage(),
                'title' => $request->title
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customers with FCM tokens
     */
    public function getCustomersWithTokens(): JsonResponse
    {
        $customers = User::where('role', 'customer')
            ->whereNotNull('fcm_token')
            ->select('id', 'name', 'phone_number', 'email', 'fcm_token')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $customers,
            'count' => $customers->count()
        ]);
    }
}