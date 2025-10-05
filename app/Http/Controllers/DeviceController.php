<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
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

        Device::create($validated);

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

        $device->update($validated);

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
}
