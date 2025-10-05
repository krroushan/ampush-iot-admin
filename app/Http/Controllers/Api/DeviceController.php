<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    /**
     * Get all devices or filter by SMS number
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Device::with(['user:id,name,phone_number,email']);

        // Filter by SMS number
        if ($request->filled('sms_number')) {
            $query->where('sms_number', $request->sms_number);
        }

        // Filter by customer phone
        if ($request->filled('phone_number')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('phone_number', $request->phone_number);
            });
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $devices = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Devices retrieved successfully',
            'data' => $devices->items(),
            'pagination' => [
                'current_page' => $devices->currentPage(),
                'last_page' => $devices->lastPage(),
                'per_page' => $devices->perPage(),
                'total' => $devices->total(),
            ]
        ]);
    }

    /**
     * Get a single device by ID or SMS number
     * 
     * @param Request $request
     * @param string $identifier
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $identifier)
    {
        // Try to find by ID first, then by SMS number
        $device = Device::with(['user:id,name,phone_number,email'])
            ->where('id', $identifier)
            ->orWhere('sms_number', $identifier)
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }

        // Get device statistics
        $stats = [
            'total_logs' => $device->motorLogs()->count(),
            'last_activity' => $device->last_activity_at?->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Device retrieved successfully',
            'data' => array_merge($device->toArray(), ['stats' => $stats])
        ]);
    }

    /**
     * Register or update device information
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_name' => 'required|string|max:255',
            'sms_number' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if device exists by SMS number
        $device = Device::where('sms_number', $request->sms_number)->first();

        if ($device) {
            // Update existing device
            $device->update([
                'device_name' => $request->device_name,
                'description' => $request->description,
                'last_activity_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Device updated successfully',
                'data' => $device->fresh()
            ]);
        }

        // Create new device
        $device = Device::create([
            'device_name' => $request->device_name,
            'sms_number' => $request->sms_number,
            'description' => $request->description,
            'is_active' => true,
            'last_activity_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device registered successfully',
            'data' => $device
        ], 201);
    }

    /**
     * Update device last activity
     * 
     * @param Request $request
     * @param string $smsNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateActivity(Request $request, $smsNumber)
    {
        $device = Device::where('sms_number', $smsNumber)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }

        $device->updateLastActivity();

        return response()->json([
            'success' => true,
            'message' => 'Device activity updated successfully',
            'data' => $device->fresh()
        ]);
    }

    /**
     * Get devices assigned to authenticated customer
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myDevices(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $devices = Device::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'My devices retrieved successfully',
            'data' => $devices
        ]);
    }

    /**
     * Get device by phone number (for backward compatibility)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $device = Device::with(['user:id,name,phone_number,email'])
            ->where('sms_number', $request->phone_number)
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Device retrieved successfully',
            'data' => $device
        ]);
    }
}
