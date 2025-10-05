<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MotorLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MotorLogController extends Controller
{
    /**
     * Sync Single Log
     * POST /api/logs
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'timestamp' => 'required|string|min:10',
            'motorStatus' => 'required|string|in:ON,OFF,STATUS',
            'voltage' => 'nullable|numeric|min:0',
            'current' => 'nullable|numeric|min:0',
            'waterLevel' => 'nullable|numeric|min:0|max:100',
            'runTime' => 'nullable|integer|min:0|max:3600', // Max 1 hour run time
            'mode' => 'nullable|string|max:20',
            'clock' => 'nullable|string|max:50',
            'command' => 'required|string|in:MOTORON,MOTOROFF,STATUS',
            'phoneNumber' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Store timestamp as string (no conversion needed)
            $timestamp = (string)$request->timestamp;
            
            $log = MotorLog::create([
                'timestamp' => $timestamp,
                'motor_status' => $request->motorStatus,
                'voltage' => $request->voltage,
                'current' => $request->current,
                'water_level' => $request->waterLevel,
                'run_time' => $request->runTime,
                'mode' => $request->mode,
                'clock' => $request->clock,
                'command' => $request->command,
                'phone_number' => $request->phoneNumber,
                'is_synced' => true
            ]);

            return response()->json([
                'id' => $log->id,
                'success' => true,
                'message' => 'Log synced successfully',
                'syncedAt' => $log->created_at->toISOString()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync log',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch Sync Logs
     * POST /api/logs/batch
     */
    public function batchStore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            '*' => 'required|array',
            '*.timestamp' => 'required|string|min:10',
            '*.motorStatus' => 'required|string|in:ON,OFF,STATUS',
            '*.voltage' => 'nullable|numeric|min:0',
            '*.current' => 'nullable|numeric|min:0',
            '*.waterLevel' => 'nullable|numeric|min:0|max:100',
            '*.runTime' => 'nullable|integer|min:0|max:3600', // Max 1 hour run time
            '*.mode' => 'nullable|string|max:20',
            '*.clock' => 'nullable|string|max:50',
            '*.command' => 'required|string|in:MOTORON,MOTOROFF,STATUS',
            '*.phoneNumber' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $logs = $request->all();
        $results = [];
        $hasErrors = false;

        DB::beginTransaction();

        try {
            foreach ($logs as $logData) {
                try {
                    // Store timestamp as string (no conversion needed)
                    $timestamp = (string)$logData['timestamp'];
                    
                    $log = MotorLog::create([
                        'timestamp' => $timestamp,
                        'motor_status' => $logData['motorStatus'],
                        'voltage' => $logData['voltage'] ?? null,
                        'current' => $logData['current'] ?? null,
                        'water_level' => $logData['waterLevel'] ?? null,
                        'run_time' => $logData['runTime'] ?? null,
                        'mode' => $logData['mode'] ?? null,
                        'clock' => $logData['clock'] ?? null,
                        'command' => $logData['command'],
                        'phone_number' => $logData['phoneNumber'],
                        'is_synced' => true
                    ]);

                    $results[] = [
                        'id' => $log->id,
                        'success' => true,
                        'message' => 'Log synced successfully',
                        'syncedAt' => $log->created_at->toISOString()
                    ];
                } catch (\Exception $e) {
                    $hasErrors = true;
                    $results[] = [
                        'id' => null,
                        'success' => false,
                        'message' => 'Failed to sync log: ' . $e->getMessage(),
                        'syncedAt' => null
                    ];
                }
            }

            if ($hasErrors) {
                DB::rollBack();
                return response()->json($results, 207); // Multi-Status
            }

            DB::commit();
            return response()->json($results, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Batch sync failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Logs with filtering and pagination
     * GET /api/logs
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'startDate' => 'nullable|integer|min:0',
            'endDate' => 'nullable|integer|min:0',
            'phoneNumber' => 'nullable|string|max:20',
            'motorStatus' => 'nullable|string|in:ON,OFF,STATUS',
            'page' => 'nullable|integer|min:0',
            'size' => 'nullable|integer|min:1|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $query = MotorLog::query();

            // Apply filters
            if ($request->has('startDate') && $request->has('endDate')) {
                $query->dateRange($request->startDate, $request->endDate);
            }

            if ($request->has('phoneNumber')) {
                $query->byPhone($request->phoneNumber);
            }

            if ($request->has('motorStatus')) {
                $query->byStatus($request->motorStatus);
            }

            // Pagination
            $page = $request->get('page', 0);
            $size = $request->get('size', 100);
            $offset = $page * $size;

            $totalCount = $query->count();
            $logs = $query->orderBy('timestamp', 'desc')
                         ->offset($offset)
                         ->limit($size)
                         ->get();

            $hasNext = ($offset + $size) < $totalCount;

            return response()->json([
                'logs' => $logs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'timestamp' => $log->timestamp,
                        'motorStatus' => $log->motor_status,
                        'voltage' => $log->voltage,
                        'current' => $log->current,
                        'waterLevel' => $log->water_level,
                        'mode' => $log->mode,
                        'clock' => $log->clock,
                        'command' => $log->command,
                        'phoneNumber' => $log->phone_number,
                        'isSynced' => $log->is_synced,
                        'createdAt' => $log->created_at->toISOString(),
                        'updatedAt' => $log->updated_at->toISOString()
                    ];
                }),
                'totalCount' => $totalCount,
                'page' => $page,
                'size' => $size,
                'hasNext' => $hasNext
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single log by ID
     * GET /api/logs/{id}
     */
    public function show($id): JsonResponse
    {
        try {
            $log = MotorLog::findOrFail($id);

            return response()->json([
                'id' => $log->id,
                'timestamp' => $log->timestamp,
                'motorStatus' => $log->motor_status,
                'voltage' => $log->voltage,
                'current' => $log->current,
                'waterLevel' => $log->water_level,
                'mode' => $log->mode,
                'clock' => $log->clock,
                'command' => $log->command,
                'phoneNumber' => $log->phone_number,
                'isSynced' => $log->is_synced,
                'createdAt' => $log->created_at->toISOString(),
                'updatedAt' => $log->updated_at->toISOString()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Log not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Delete log by ID
     * DELETE /api/logs/{id}
     */
    public function destroy($id): JsonResponse
    {
        try {
            $log = MotorLog::findOrFail($id);
            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Log deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete log',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unsynced logs count
     * GET /api/logs/unsynced/count
     */
    public function unsyncedCount(): JsonResponse
    {
        try {
            $count = MotorLog::unsynced()->count();

            return response()->json([
                'count' => $count,
                'message' => 'Unsynced logs count retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unsynced count',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}