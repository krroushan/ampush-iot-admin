<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use App\Models\Notification;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    /**
     * Display a listing of devices
     */
    public function index(Request $request)
    {
        $query = Device::with(['user', 'motorLogs']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('device_name', 'like', "%{$search}%")
                  ->orWhere('sms_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        // Assignment filter
        if ($request->filled('assignment')) {
            if ($request->assignment === 'assigned') {
                $query->assigned();
            } elseif ($request->assignment === 'unassigned') {
                $query->unassigned();
            }
        }

        // Customer filter
        if ($request->filled('customer_id')) {
            $query->where('user_id', $request->customer_id);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $devices = $query->paginate(15)->withQueryString();

        // Get statistics
        $stats = [
            'total_devices' => Device::count(),
            'active_devices' => Device::active()->count(),
            'inactive_devices' => Device::inactive()->count(),
            'assigned_devices' => Device::assigned()->count(),
            'unassigned_devices' => Device::unassigned()->count(),
        ];

        // Get all customers for filter dropdown
        $customers = User::where('role', 'customer')->orderBy('name')->get();

        return view('devices.index', compact('devices', 'stats', 'customers'));
    }

    /**
     * Show the form for creating a new device
     */
    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        return view('devices.create', compact('customers'));
    }

    /**
     * Store a newly created device
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_name' => 'required|string|max:255',
            'sms_number' => 'required|string|max:20|unique:devices,sms_number',
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $device = Device::create($validated);

        // Send notification if device is created with a customer assigned
        if ($device->user_id) {
            $this->sendDeviceAssignmentNotification($device, $device->user_id, true);
        }

        return redirect()->route('devices.index')
            ->with('success', 'Device created successfully!');
    }

    /**
     * Display the specified device
     */
    public function show(Device $device)
    {
        $device->load(['user', 'motorLogs' => function ($query) {
            $query->latest('timestamp')->take(50);
        }]);

        // Get device statistics
        $stats = [
            'total_logs' => $device->motorLogs()->count(),
            'on_logs' => $device->motorLogs()->where('motor_status', 'ON')->count(),
            'off_logs' => $device->motorLogs()->where('motor_status', 'OFF')->count(),
            'last_24h' => $device->motorLogs()
                ->where('timestamp', '>=', now()->subDay()->timestamp * 1000)
                ->count(),
            'avg_voltage' => number_format($device->motorLogs()->avg('voltage') ?? 0, 2),
            'avg_current' => number_format($device->motorLogs()->avg('current') ?? 0, 2),
        ];

        return view('devices.show', compact('device', 'stats'));
    }

    /**
     * Show the form for editing the specified device
     */
    public function edit(Device $device)
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        return view('devices.edit', compact('device', 'customers'));
    }

    /**
     * Update the specified device
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'device_name' => 'required|string|max:255',
            'sms_number' => 'required|string|max:20|unique:devices,sms_number,' . $device->id,
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Check if device is being assigned to a customer
        $oldUserId = $device->user_id;
        $newUserId = $validated['user_id'];
        $isBeingAssigned = $oldUserId != $newUserId && !is_null($newUserId);

        $device->update($validated);

        // Send notification if device is being assigned to a customer
        if ($isBeingAssigned) {
            $this->sendDeviceAssignmentNotification($device, $newUserId);
        }

        return redirect()->route('devices.index')
            ->with('success', 'Device updated successfully!');
    }

    /**
     * Remove the specified device
     */
    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('devices.index')
            ->with('success', 'Device deleted successfully!');
    }

    /**
     * Toggle device status
     */
    public function toggleStatus(Device $device)
    {
        $device->update(['is_active' => !$device->is_active]);

        return back()->with('success', 'Device status updated successfully!');
    }

    /**
     * Assign device to customer
     */
    public function assign(Request $request, Device $device)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $device->update($validated);

        return back()->with('success', 'Device assigned successfully!');
    }

    /**
     * Unassign device from customer
     */
    public function unassign(Device $device)
    {
        $device->update(['user_id' => null]);

        return back()->with('success', 'Device unassigned successfully!');
    }

    /**
     * Send device assignment notification to customer
     */
    private function sendDeviceAssignmentNotification(Device $device, $userId, $isNewDevice = false)
    {
        try {
            $customer = User::find($userId);
            
            if (!$customer || !$customer->fcm_token) {
                Log::info('Customer not found or no FCM token for device assignment notification', [
                    'device_id' => $device->id,
                    'user_id' => $userId,
                    'is_new_device' => $isNewDevice
                ]);
                return;
            }

            // Determine notification content based on whether it's a new device or reassignment
            $title = $isNewDevice ? 'New Device Assigned' : 'Device Assigned';
            $body = $isNewDevice 
                ? "New device '{$device->device_name}' has been created and assigned to you."
                : "Device '{$device->device_name}' has been assigned to you.";
            $action = $isNewDevice ? 'device_created' : 'device_assigned';

            // Create notification record
            $notification = Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'type' => 'device_assignment',
                'data' => json_encode([
                    'device_id' => $device->id,
                    'device_name' => $device->device_name,
                    'sms_number' => $device->sms_number,
                    'action' => $action,
                    'is_new_device' => $isNewDevice
                ]),
                'sent' => false,
                'sent_count' => 0,
                'failure_count' => 0,
            ]);

            // Send push notification
            $this->firebaseService->sendNotification(
                $customer->fcm_token,
                $title,
                $body,
                [
                    'device_id' => (string)$device->id,
                    'device_name' => $device->device_name,
                    'sms_number' => $device->sms_number,
                    'action' => $action,
                    'is_new_device' => $isNewDevice
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
                'customer_id' => $userId,
                'customer_name' => $customer->name,
                'notification_id' => $notification->id,
                'is_new_device' => $isNewDevice
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send device assignment notification', [
                'device_id' => $device->id,
                'user_id' => $userId,
                'is_new_device' => $isNewDevice,
                'error' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }
}
