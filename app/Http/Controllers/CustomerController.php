<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Device;
use App\Models\Notification;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    /**
     * Display a listing of customers
     */
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->latest()
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'password' => bcrypt($request->password),
            'role' => 'customer',
            'email_verified_at' => now(), // Auto-verify for admin-created customers
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'customer_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/profile-photos'), $filename);
            $data['profile_photo'] = 'images/profile-photos/' . $filename;
        }

        User::create($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer
     */
    public function show(User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        // Load customer's devices with counts
        $customer->load('devices');
        $totalDevices = $customer->devices->count();
        $activeDevices = $customer->devices->where('is_active', true)->count();
        
        // Get last activity from devices
        $lastActivity = $customer->devices()
            ->whereNotNull('last_activity_at')
            ->max('last_activity_at');

        return view('customers.show', compact('customer', 'totalDevices', 'activeDevices', 'lastActivity'));
    }

    /**
     * Show the form for editing the customer
     */
    public function edit(User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id)
            ],
            'phone_number' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($customer->profile_photo && file_exists(public_path($customer->profile_photo))) {
                unlink(public_path($customer->profile_photo));
            }
            
            // Upload new photo
            $file = $request->file('profile_photo');
            $filename = 'customer_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/profile-photos'), $filename);
            $updateData['profile_photo'] = 'images/profile-photos/' . $filename;
        }

        $customer->update($updateData);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer
     */
    public function destroy(User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Search customers
     */
    public function search(Request $request)
    {
        $query = $request->get('search');
        
        $customers = User::where('role', 'customer')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone_number', 'like', "%{$query}%")
                  ->orWhere('address_line_1', 'like', "%{$query}%")
                  ->orWhere('city', 'like', "%{$query}%")
                  ->orWhere('state', 'like', "%{$query}%")
                  ->orWhere('postal_code', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }

    /**
     * Assign device to customer
     */
    public function assignDevice(Request $request, User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $request->validate([
            'device_id' => 'required|exists:devices,id'
        ]);

        $device = Device::findOrFail($request->device_id);
        
        // Check if device is being assigned (not just reassigned)
        $oldUserId = $device->user_id;
        $isBeingAssigned = $oldUserId != $customer->id;
        
        // Assign device to customer
        $device->update(['user_id' => $customer->id]);

        // Send notification if device is being assigned to this customer
        if ($isBeingAssigned) {
            $this->sendDeviceAssignmentNotification($device, $customer);
        }

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Device assigned successfully.');
    }

    /**
     * Send device assignment notification to customer
     */
    private function sendDeviceAssignmentNotification(Device $device, User $customer)
    {
        try {
            if (!$customer->fcm_token) {
                Log::info('No FCM token for device assignment notification', [
                    'device_id' => $device->id,
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name
                ]);
                return;
            }

            // Create notification record
            $notification = Notification::create([
                'user_id' => $customer->id,
                'title' => 'Device Assigned',
                'body' => "Device '{$device->device_name}' has been assigned to you.",
                'type' => 'device_assignment',
                'data' => json_encode([
                    'device_id' => $device->id,
                    'device_name' => $device->device_name,
                    'sms_number' => $device->sms_number,
                    'action' => 'device_assigned'
                ]),
                'sent' => false,
                'sent_count' => 0,
                'failure_count' => 0,
            ]);

            // Send push notification
            $this->firebaseService->sendNotification(
                $customer->fcm_token,
                'Device Assigned',
                "Device '{$device->device_name}' has been assigned to you.",
                [
                    'device_id' => (string)$device->id,
                    'device_name' => $device->device_name,
                    'sms_number' => $device->sms_number,
                    'action' => 'device_assigned'
                ]
            );

            // Update notification as sent
            $notification->update([
                'sent' => true,
                'sent_count' => 1,
                'sent_at' => now(),
            ]);

            Log::info('Device assignment notification sent successfully', [
                'device_id' => $device->id,
                'device_name' => $device->device_name,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'notification_id' => $notification->id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send device assignment notification', [
                'device_id' => $device->id,
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }

}
