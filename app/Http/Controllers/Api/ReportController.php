<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MotorLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Daily Report
     * GET /api/reports/daily
     */
    public function daily(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|integer|min:0',
            'phoneNumber' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $date = Carbon::createFromTimestamp((int)$request->date / 1000);
            $startOfDay = $date->copy()->startOfDay()->timestamp * 1000;
            $endOfDay = $date->copy()->endOfDay()->timestamp * 1000;

            $query = MotorLog::dateRange($startOfDay, $endOfDay);

            if ($request->has('phoneNumber')) {
                $query->byPhone($request->phoneNumber);
            }

            $logs = $query->get();

            $report = $this->generateReport($logs, 'daily', $startOfDay, $endOfDay);

            return response()->json($report, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate daily report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Weekly Report
     * GET /api/reports/weekly
     */
    public function weekly(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'weekStart' => 'required|integer|min:0',
            'phoneNumber' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $weekStart = Carbon::createFromTimestamp((int)$request->weekStart / 1000);
            $startOfWeek = $weekStart->copy()->startOfWeek()->timestamp * 1000;
            $endOfWeek = $weekStart->copy()->endOfWeek()->timestamp * 1000;

            $query = MotorLog::dateRange($startOfWeek, $endOfWeek);

            if ($request->has('phoneNumber')) {
                $query->byPhone($request->phoneNumber);
            }

            $logs = $query->get();

            $report = $this->generateReport($logs, 'weekly', $startOfWeek, $endOfWeek);

            return response()->json($report, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate weekly report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Monthly Report
     * GET /api/reports/monthly
     */
    public function monthly(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'monthStart' => 'required|integer|min:0',
            'phoneNumber' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $monthStart = Carbon::createFromTimestamp((int)$request->monthStart / 1000);
            $startOfMonth = $monthStart->copy()->startOfMonth()->timestamp * 1000;
            $endOfMonth = $monthStart->copy()->endOfMonth()->timestamp * 1000;

            $query = MotorLog::dateRange($startOfMonth, $endOfMonth);

            if ($request->has('phoneNumber')) {
                $query->byPhone($request->phoneNumber);
            }

            $logs = $query->get();

            $report = $this->generateReport($logs, 'monthly', $startOfMonth, $endOfMonth);

            return response()->json($report, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate monthly report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Custom Date Range Report
     * GET /api/reports/custom
     */
    public function custom(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'startDate' => 'required|integer|min:0',
            'endDate' => 'required|integer|min:0',
            'phoneNumber' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $startDate = $request->startDate;
            $endDate = $request->endDate;

            $query = MotorLog::dateRange($startDate, $endDate);

            if ($request->has('phoneNumber')) {
                $query->byPhone($request->phoneNumber);
            }

            $logs = $query->get();

            $report = $this->generateReport($logs, 'custom', $startDate, $endDate);

            return response()->json($report, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate custom report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate report data from logs
     */
    private function generateReport($logs, $period, $startDate, $endDate): array
    {
        $totalOperations = $logs->count();
        $motorOnCount = $logs->where('motor_status', 'ON')->count();
        $motorOffCount = $logs->where('motor_status', 'OFF')->count();
        $statusRequests = $logs->where('command', 'STATUS')->count();

        // Calculate averages (only for non-null values)
        $voltageValues = $logs->whereNotNull('voltage')->pluck('voltage');
        $currentValues = $logs->whereNotNull('current')->pluck('current');
        $waterLevelValues = $logs->whereNotNull('water_level')->pluck('water_level');

        $averageVoltage = $voltageValues->isNotEmpty() ? round($voltageValues->avg(), 2) : 0;
        $averageCurrent = $currentValues->isNotEmpty() ? round($currentValues->avg(), 2) : 0;
        $averageWaterLevel = $waterLevelValues->isNotEmpty() ? round($waterLevelValues->avg(), 2) : 0;

        // Calculate uptime and downtime
        $totalMinutes = Carbon::createFromTimestamp($endDate / 1000)
            ->diffInMinutes(Carbon::createFromTimestamp($startDate / 1000));

        $uptimeMinutes = $this->calculateUptime($logs);
        $downtimeMinutes = $totalMinutes - $uptimeMinutes;

        $uptime = $this->formatDuration($uptimeMinutes);
        $downtime = $this->formatDuration($downtimeMinutes);

        return [
            'period' => $period,
            'startDate' => Carbon::createFromTimestamp($startDate / 1000)->toISOString(),
            'endDate' => Carbon::createFromTimestamp($endDate / 1000)->toISOString(),
            'totalOperations' => $totalOperations,
            'motorOnCount' => $motorOnCount,
            'motorOffCount' => $motorOffCount,
            'statusRequests' => $statusRequests,
            'averageVoltage' => $averageVoltage,
            'averageCurrent' => $averageCurrent,
            'averageWaterLevel' => $averageWaterLevel,
            'uptime' => $uptime,
            'downtime' => $downtime,
            'totalMinutes' => $totalMinutes,
            'uptimeMinutes' => $uptimeMinutes,
            'downtimeMinutes' => $downtimeMinutes
        ];
    }

    /**
     * Calculate uptime in minutes based on motor status changes
     */
    private function calculateUptime($logs): int
    {
        $sortedLogs = $logs->sortBy('timestamp');
        $uptimeMinutes = 0;
        $lastOnTime = null;
        $isMotorOn = false;

        foreach ($sortedLogs as $log) {
            $logTime = Carbon::createFromTimestamp($log->timestamp / 1000);

            if ($log->motor_status === 'ON' && !$isMotorOn) {
                $isMotorOn = true;
                $lastOnTime = $logTime;
            } elseif ($log->motor_status === 'OFF' && $isMotorOn) {
                $isMotorOn = false;
                if ($lastOnTime) {
                    $uptimeMinutes += $logTime->diffInMinutes($lastOnTime);
                }
            }
        }

        // If motor is still on at the end of the period, add remaining time
        if ($isMotorOn && $lastOnTime) {
            $lastLog = $sortedLogs->last();
            $endTime = Carbon::createFromTimestamp($lastLog->timestamp / 1000);
            $uptimeMinutes += $endTime->diffInMinutes($lastOnTime);
        }

        return $uptimeMinutes;
    }

    /**
     * Format duration in minutes to human readable format
     */
    private function formatDuration(int $minutes): string
    {
        $hours = intval($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $remainingMinutes);
        }

        return sprintf('%dm', $remainingMinutes);
    }

    /**
     * Get report summary for dashboard
     * GET /api/reports/summary
     */
    public function summary(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phoneNumber' => 'nullable|string|max:20',
            'days' => 'nullable|integer|min:1|max:365'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $days = $request->get('days', 7); // Default to last 7 days
            $endDate = Carbon::now()->timestamp * 1000;
            $startDate = Carbon::now()->subDays($days)->timestamp * 1000;

            $query = MotorLog::dateRange($startDate, $endDate);

            if ($request->has('phoneNumber')) {
                $query->byPhone($request->phoneNumber);
            }

            $logs = $query->get();

            $summary = [
                'period' => "last_{$days}_days",
                'startDate' => Carbon::createFromTimestamp($startDate / 1000)->toISOString(),
                'endDate' => Carbon::createFromTimestamp($endDate / 1000)->toISOString(),
                'totalOperations' => $logs->count(),
                'motorOnCount' => $logs->where('motor_status', 'ON')->count(),
                'motorOffCount' => $logs->where('motor_status', 'OFF')->count(),
                'statusRequests' => $logs->where('command', 'STATUS')->count(),
                'uniquePhoneNumbers' => $logs->pluck('phone_number')->unique()->count(),
                'averageVoltage' => $logs->whereNotNull('voltage')->avg('voltage') ?? 0,
                'averageCurrent' => $logs->whereNotNull('current')->avg('current') ?? 0,
                'averageWaterLevel' => $logs->whereNotNull('water_level')->avg('water_level') ?? 0
            ];

            return response()->json($summary, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}