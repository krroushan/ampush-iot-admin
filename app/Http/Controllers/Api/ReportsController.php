<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MotorLog;
use App\Models\User;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    /**
     * Calculate energy, water, cost for a set of logs
     */
    private function calculateMetrics($offLogs, $unitPrice, $pumpingCapacity)
    {
        $energy = 0;
        $runtime = 0;
        $water = 0;
        $cycles = $offLogs->count();

        foreach ($offLogs as $offLog) {
            $onLog = MotorLog::where('device_id', $offLog->device_id)
                ->where('motor_status', 'ON')
                ->where('timestamp', '<', $offLog->timestamp)
                ->orderBy('timestamp', 'desc')
                ->first();

            if ($onLog) {
                $powerKw = ($onLog->voltage * $onLog->current) / 1000;
                $runtimeHours = $offLog->run_time / 3600;
                $energy += $powerKw * $runtimeHours;
                $runtime += $offLog->run_time;
                $water += ($offLog->run_time / 60) * $pumpingCapacity;
            }
        }

        return [
            'energy' => $energy,
            'runtime' => $runtime,
            'water' => $water,
            'cost' => $energy * $unitPrice,
            'cycles' => $cycles
        ];
    }

    /**
     * Get device-wise breakdown
     */
    private function getDeviceWiseBreakdown($offLogs, $unitPrice, $pumpingCapacity)
    {
        $deviceWise = [];
        $deviceIds = $offLogs->pluck('device_id')->unique();

        foreach ($deviceIds as $deviceId) {
            $deviceLogs = $offLogs->where('device_id', $deviceId);
            $device = Device::find($deviceId);

            if ($device) {
                $metrics = $this->calculateMetrics($deviceLogs, $unitPrice, $pumpingCapacity);

                $deviceWise[] = [
                    'deviceId' => $device->id,
                    'deviceName' => $device->device_name,
                    'smsNumber' => $device->sms_number,
                    'energy' => round($metrics['energy'], 3),
                    'runtime' => round($metrics['runtime'] / 60, 0),
                    'water' => round($metrics['water'], 0),
                    'cost' => round($metrics['cost'], 2),
                    'cycles' => $metrics['cycles']
                ];
            }
        }

        return $deviceWise;
    }

    /**
     * Get daily report (24 hours with hourly breakdown)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function daily(Request $request)
    {
        try {
            $request->validate([
                'date' => 'nullable|date',
                'device_id' => 'nullable|exists:devices,id',
                'user_id' => 'nullable|exists:users,id',
                'phone' => 'nullable|string',
            ]);

            $date = $request->get('date', Carbon::today()->format('Y-m-d'));
            $deviceId = $request->get('device_id');
            $userId = $request->get('user_id');
            $phone = $request->get('phone');

            // Get user for unit price and pumping capacity
            $user = null;
            if ($userId) {
                $user = User::find($userId);
            } elseif ($phone) {
                $user = User::where('phone_number', $phone)->where('role', 'customer')->first();
            }

            $unitPrice = $user ? $user->unit_price : 6.00;
            $pumpingCapacity = $user ? $user->motor_pumping_capacity : 50;

            $startDate = Carbon::parse($date)->startOfDay();
            $endDate = Carbon::parse($date)->endOfDay();
            $startTs = $startDate->timestamp * 1000;
            $endTs = $endDate->timestamp * 1000;

            // Get all OFF logs with run_time for this day
            $query = MotorLog::where('motor_status', 'OFF')
                ->whereNotNull('run_time')
                ->where('run_time', '>', 0)
                ->whereBetween('timestamp', [(string)$startTs, (string)$endTs]);

            if ($deviceId) {
                $query->where('device_id', $deviceId);
            }
            if ($userId) {
                $query->where('user_id', $userId);
            }
            if ($phone) {
                $query->where('phone_number', $phone);
            }

            $offLogs = $query->orderBy('timestamp', 'asc')->get();

            // Calculate hourly data
            $hourlyData = [];
            $totalEnergy = 0;
            $totalRuntime = 0;
            $totalCost = 0;
            $totalWater = 0;
            $motorCycles = 0;

            for ($hour = 0; $hour < 24; $hour++) {
                $hourStart = $startDate->copy()->setHour($hour)->startOfHour()->timestamp * 1000;
                $hourEnd = $startDate->copy()->setHour($hour)->endOfHour()->timestamp * 1000;

                $hourLogs = $offLogs->filter(function($log) use ($hourStart, $hourEnd) {
                    return $log->timestamp >= $hourStart && $log->timestamp <= $hourEnd;
                });

                $metrics = $this->calculateMetrics($hourLogs, $unitPrice, $pumpingCapacity);

                $hourlyData[] = [
                    'hour' => sprintf('%02d:00', $hour),
                    'energy' => round($metrics['energy'], 3),
                    'power' => $metrics['runtime'] > 0 ? round(($metrics['energy'] / ($metrics['runtime'] / 3600)), 3) : 0,
                    'runtime' => round($metrics['runtime'] / 60, 0),
                    'water' => round($metrics['water'], 0),
                    'cost' => round($metrics['cost'], 2),
                    'cycles' => $metrics['cycles']
                ];

                $totalEnergy += $metrics['energy'];
                $totalRuntime += $metrics['runtime'];
                $totalWater += $metrics['water'];
                $motorCycles += $metrics['cycles'];
            }

            $totalCost = $totalEnergy * $unitPrice;

            // Get device-wise breakdown (only if not filtered by device_id)
            $deviceWiseBreakdown = null;
            if (!$deviceId && $offLogs->count() > 0) {
                $deviceWiseBreakdown = $this->getDeviceWiseBreakdown($offLogs, $unitPrice, $pumpingCapacity);
            }

            // Get current power
            $latestLog = MotorLog::where('motor_status', 'ON')
                ->when($deviceId, fn($q) => $q->where('device_id', $deviceId))
                ->when($userId, fn($q) => $q->where('user_id', $userId))
                ->orderBy('timestamp', 'desc')
                ->first();

            $currentPower = 0;
            if ($latestLog) {
                $currentPower = round($latestLog->voltage * $latestLog->current, 0);
            }

            // Get device and customer info
            $deviceInfo = null;
            $customerInfo = null;

            if ($deviceId) {
                $device = Device::find($deviceId);
                if ($device) {
                    $deviceInfo = [
                        'id' => $device->id,
                        'name' => $device->device_name,
                        'smsNumber' => $device->sms_number
                    ];
                }
            }

            if ($user) {
                $customerInfo = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phoneNumber' => $user->phone_number,
                    'unitPrice' => (float) $user->unit_price,
                    'pumpingCapacity' => $user->motor_pumping_capacity
                ];
            }

            $response = [
                'success' => true,
                'reportType' => 'daily',
                'date' => $date,
                'summary' => [
                    'currentPower' => $currentPower,
                    'dailyConsumption' => round($totalEnergy, 2),
                    'totalRuntime' => round($totalRuntime / 60, 0),
                    'totalWater' => round($totalWater, 0),
                    'totalCost' => round($totalCost, 2),
                    'motorCycles' => $motorCycles,
                    'averageRuntime' => $motorCycles > 0 ? round(($totalRuntime / 60) / $motorCycles, 1) : 0,
                    'unitPrice' => (float) $unitPrice,
                    'pumpingCapacity' => $pumpingCapacity
                ],
                'hourlyData' => $hourlyData,
                'device' => $deviceInfo,
                'customer' => $customerInfo
            ];

            // Add device-wise breakdown if available
            if ($deviceWiseBreakdown) {
                $response['deviceWiseBreakdown'] = $deviceWiseBreakdown;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Daily report error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate daily report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get monthly report (days 1-31 with daily breakdown)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function monthly(Request $request)
    {
        try {
            $request->validate([
                'month' => 'nullable|integer|min:1|max:12',
                'year' => 'nullable|integer|min:2020|max:2100',
                'device_id' => 'nullable|exists:devices,id',
                'user_id' => 'nullable|exists:users,id',
                'phone' => 'nullable|string',
            ]);

            $month = $request->get('month', Carbon::now()->month);
            $year = $request->get('year', Carbon::now()->year);
            $deviceId = $request->get('device_id');
            $userId = $request->get('user_id');
            $phone = $request->get('phone');

            // Get user for pricing
            $user = null;
            if ($userId) {
                $user = User::find($userId);
            } elseif ($phone) {
                $user = User::where('phone_number', $phone)->where('role', 'customer')->first();
            }

            $unitPrice = $user ? $user->unit_price : 6.00;
            $pumpingCapacity = $user ? $user->motor_pumping_capacity : 50;

            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
            $daysInMonth = $startDate->daysInMonth;

            // Get all OFF logs for the month
            $startTs = $startDate->timestamp * 1000;
            $endTs = $endDate->timestamp * 1000;

            $query = MotorLog::where('motor_status', 'OFF')
                ->whereNotNull('run_time')
                ->where('run_time', '>', 0)
                ->whereBetween('timestamp', [(string)$startTs, (string)$endTs]);

            if ($deviceId) {
                $query->where('device_id', $deviceId);
            }
            if ($userId) {
                $query->where('user_id', $userId);
            }
            if ($phone) {
                $query->where('phone_number', $phone);
            }

            $allMonthLogs = $query->get();

            // Calculate daily data
            $dailyData = [];
            $totalEnergy = 0;
            $totalRuntime = 0;
            $totalWater = 0;
            $totalCost = 0;
            $totalCycles = 0;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dayDate = Carbon::create($year, $month, $day);
                $dayStartTs = $dayDate->startOfDay()->timestamp * 1000;
                $dayEndTs = $dayDate->endOfDay()->timestamp * 1000;

                $dayLogs = $allMonthLogs->filter(function($log) use ($dayStartTs, $dayEndTs) {
                    return $log->timestamp >= $dayStartTs && $log->timestamp <= $dayEndTs;
                });

                $metrics = $this->calculateMetrics($dayLogs, $unitPrice, $pumpingCapacity);

                $dailyData[] = [
                    'date' => $dayDate->format('Y-m-d'),
                    'day' => $day,
                    'energy' => round($metrics['energy'], 3),
                    'runtime' => round($metrics['runtime'] / 60, 0),
                    'water' => round($metrics['water'], 0),
                    'cost' => round($metrics['cost'], 2),
                    'cycles' => $metrics['cycles']
                ];

                $totalEnergy += $metrics['energy'];
                $totalRuntime += $metrics['runtime'];
                $totalWater += $metrics['water'];
                $totalCycles += $metrics['cycles'];
            }

            $totalCost = $totalEnergy * $unitPrice;

            // Get device-wise breakdown
            $deviceWiseBreakdown = null;
            if (!$deviceId && $allMonthLogs->count() > 0) {
                $deviceWiseBreakdown = $this->getDeviceWiseBreakdown($allMonthLogs, $unitPrice, $pumpingCapacity);
            }

            $deviceInfo = null;
            $customerInfo = null;

            if ($deviceId) {
                $device = Device::find($deviceId);
                if ($device) {
                    $deviceInfo = [
                        'id' => $device->id,
                        'name' => $device->device_name,
                        'smsNumber' => $device->sms_number
                    ];
                }
            }

            if ($user) {
                $customerInfo = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phoneNumber' => $user->phone_number,
                    'unitPrice' => (float) $user->unit_price,
                    'pumpingCapacity' => $user->motor_pumping_capacity
                ];
            }

            $response = [
                'success' => true,
                'reportType' => 'monthly',
                'month' => $month,
                'year' => $year,
                'monthName' => Carbon::create($year, $month, 1)->format('F'),
                'summary' => [
                    'monthlyConsumption' => round($totalEnergy, 2),
                    'totalRuntime' => round($totalRuntime / 60, 0),
                    'totalWater' => round($totalWater, 0),
                    'totalCost' => round($totalCost, 2),
                    'motorCycles' => $totalCycles,
                    'averageDailyConsumption' => round($totalEnergy / $daysInMonth, 2),
                    'averageDailyRuntime' => round(($totalRuntime / 60) / $daysInMonth, 0),
                    'unitPrice' => (float) $unitPrice,
                    'pumpingCapacity' => $pumpingCapacity
                ],
                'dailyData' => $dailyData,
                'device' => $deviceInfo,
                'customer' => $customerInfo
            ];

            if ($deviceWiseBreakdown) {
                $response['deviceWiseBreakdown'] = $deviceWiseBreakdown;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Monthly report error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate monthly report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get yearly report (12 months with monthly breakdown)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function yearly(Request $request)
    {
        try {
            $request->validate([
                'year' => 'nullable|integer|min:2020|max:2100',
                'device_id' => 'nullable|exists:devices,id',
                'user_id' => 'nullable|exists:users,id',
                'phone' => 'nullable|string',
            ]);

            $year = $request->get('year', Carbon::now()->year);
            $deviceId = $request->get('device_id');
            $userId = $request->get('user_id');
            $phone = $request->get('phone');

            // Get user for pricing
            $user = null;
            if ($userId) {
                $user = User::find($userId);
            } elseif ($phone) {
                $user = User::where('phone_number', $phone)->where('role', 'customer')->first();
            }

            $unitPrice = $user ? $user->unit_price : 6.00;
            $pumpingCapacity = $user ? $user->motor_pumping_capacity : 50;

            // Get all OFF logs for the year
            $yearStart = Carbon::create($year, 1, 1)->startOfDay();
            $yearEnd = Carbon::create($year, 12, 31)->endOfDay();
            $yearStartTs = $yearStart->timestamp * 1000;
            $yearEndTs = $yearEnd->timestamp * 1000;

            $query = MotorLog::where('motor_status', 'OFF')
                ->whereNotNull('run_time')
                ->where('run_time', '>', 0)
                ->whereBetween('timestamp', [(string)$yearStartTs, (string)$yearEndTs]);

            if ($deviceId) {
                $query->where('device_id', $deviceId);
            }
            if ($userId) {
                $query->where('user_id', $userId);
            }
            if ($phone) {
                $query->where('phone_number', $phone);
            }

            $allYearLogs = $query->get();

            // Calculate monthly data
            $monthlyData = [];
            $totalEnergy = 0;
            $totalRuntime = 0;
            $totalWater = 0;
            $totalCost = 0;
            $totalCycles = 0;

            for ($month = 1; $month <= 12; $month++) {
                $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
                $monthEnd = Carbon::create($year, $month, 1)->endOfMonth();
                $monthStartTs = $monthStart->timestamp * 1000;
                $monthEndTs = $monthEnd->timestamp * 1000;

                $monthLogs = $allYearLogs->filter(function($log) use ($monthStartTs, $monthEndTs) {
                    return $log->timestamp >= $monthStartTs && $log->timestamp <= $monthEndTs;
                });

                $metrics = $this->calculateMetrics($monthLogs, $unitPrice, $pumpingCapacity);

                $monthlyData[] = [
                    'month' => $month,
                    'monthName' => Carbon::create($year, $month, 1)->format('F'),
                    'energy' => round($metrics['energy'], 2),
                    'runtime' => round($metrics['runtime'] / 60, 0),
                    'water' => round($metrics['water'], 0),
                    'cost' => round($metrics['cost'], 2),
                    'cycles' => $metrics['cycles']
                ];

                $totalEnergy += $metrics['energy'];
                $totalRuntime += $metrics['runtime'];
                $totalWater += $metrics['water'];
                $totalCycles += $metrics['cycles'];
            }

            $totalCost = $totalEnergy * $unitPrice;

            // Get device-wise breakdown
            $deviceWiseBreakdown = null;
            if (!$deviceId && $allYearLogs->count() > 0) {
                $deviceWiseBreakdown = $this->getDeviceWiseBreakdown($allYearLogs, $unitPrice, $pumpingCapacity);
            }

            $deviceInfo = null;
            $customerInfo = null;

            if ($deviceId) {
                $device = Device::find($deviceId);
                if ($device) {
                    $deviceInfo = [
                        'id' => $device->id,
                        'name' => $device->device_name,
                        'smsNumber' => $device->sms_number
                    ];
                }
            }

            if ($user) {
                $customerInfo = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phoneNumber' => $user->phone_number,
                    'unitPrice' => (float) $user->unit_price,
                    'pumpingCapacity' => $user->motor_pumping_capacity
                ];
            }

            $response = [
                'success' => true,
                'reportType' => 'yearly',
                'year' => $year,
                'summary' => [
                    'annualConsumption' => round($totalEnergy, 2),
                    'totalRuntime' => round($totalRuntime / 60, 0),
                    'totalWater' => round($totalWater, 0),
                    'totalCost' => round($totalCost, 2),
                    'motorCycles' => $totalCycles,
                    'averageMonthlyConsumption' => round($totalEnergy / 12, 2),
                    'averageMonthlyRuntime' => round(($totalRuntime / 60) / 12, 0),
                    'unitPrice' => (float) $unitPrice,
                    'pumpingCapacity' => $pumpingCapacity
                ],
                'monthlyData' => $monthlyData,
                'device' => $deviceInfo,
                'customer' => $customerInfo
            ];

            if ($deviceWiseBreakdown) {
                $response['deviceWiseBreakdown'] = $deviceWiseBreakdown;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Yearly report error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate yearly report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get custom date range report
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function custom(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'device_id' => 'nullable|exists:devices,id',
                'user_id' => 'nullable|exists:users,id',
                'phone' => 'nullable|string',
                'group_by' => 'nullable|in:hour,day,month',
            ]);

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $deviceId = $request->get('device_id');
            $userId = $request->get('user_id');
            $phone = $request->get('phone');
            $groupBy = $request->get('group_by', 'day');

            // Get user for pricing
            $user = null;
            if ($userId) {
                $user = User::find($userId);
            } elseif ($phone) {
                $user = User::where('phone_number', $phone)->where('role', 'customer')->first();
            }

            $unitPrice = $user ? $user->unit_price : 6.00;
            $pumpingCapacity = $user ? $user->motor_pumping_capacity : 50;

            $startTs = $startDate->timestamp * 1000;
            $endTs = $endDate->timestamp * 1000;
            $totalDays = $startDate->diffInDays($endDate) + 1;

            // Get all OFF logs for the range
            $query = MotorLog::where('motor_status', 'OFF')
                ->whereNotNull('run_time')
                ->where('run_time', '>', 0)
                ->whereBetween('timestamp', [(string)$startTs, (string)$endTs]);

            if ($deviceId) {
                $query->where('device_id', $deviceId);
            }
            if ($userId) {
                $query->where('user_id', $userId);
            }
            if ($phone) {
                $query->where('phone_number', $phone);
            }

            $offLogs = $query->orderBy('timestamp', 'asc')->get();

            // Group data based on group_by parameter
            $groupedData = [];
            $totalEnergy = 0;
            $totalRuntime = 0;
            $totalWater = 0;
            $totalCycles = 0;

            if ($groupBy === 'day') {
                $currentDate = $startDate->copy();
                while ($currentDate <= $endDate) {
                    $dayStartTs = $currentDate->startOfDay()->timestamp * 1000;
                    $dayEndTs = $currentDate->endOfDay()->timestamp * 1000;

                    $dayLogs = $offLogs->filter(function($log) use ($dayStartTs, $dayEndTs) {
                        return $log->timestamp >= $dayStartTs && $log->timestamp <= $dayEndTs;
                    });

                    $metrics = $this->calculateMetrics($dayLogs, $unitPrice, $pumpingCapacity);

                    $groupedData[] = [
                        'date' => $currentDate->format('Y-m-d'),
                        'energy' => round($metrics['energy'], 3),
                        'runtime' => round($metrics['runtime'] / 60, 0),
                        'water' => round($metrics['water'], 0),
                        'cost' => round($metrics['cost'], 2),
                        'cycles' => $metrics['cycles']
                    ];

                    $totalEnergy += $metrics['energy'];
                    $totalRuntime += $metrics['runtime'];
                    $totalWater += $metrics['water'];
                    $totalCycles += $metrics['cycles'];

                    $currentDate->addDay();
                }
            }

            $totalCost = $totalEnergy * $unitPrice;

            // Get device-wise breakdown
            $deviceWiseBreakdown = null;
            if (!$deviceId && $offLogs->count() > 0) {
                $deviceWiseBreakdown = $this->getDeviceWiseBreakdown($offLogs, $unitPrice, $pumpingCapacity);
            }

            $deviceInfo = null;
            $customerInfo = null;

            if ($deviceId) {
                $device = Device::find($deviceId);
                if ($device) {
                    $deviceInfo = [
                        'id' => $device->id,
                        'name' => $device->device_name,
                        'smsNumber' => $device->sms_number
                    ];
                }
            }

            if ($user) {
                $customerInfo = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phoneNumber' => $user->phone_number,
                    'unitPrice' => (float) $user->unit_price,
                    'pumpingCapacity' => $user->motor_pumping_capacity
                ];
            }

            $response = [
                'success' => true,
                'reportType' => 'custom',
                'startDate' => $startDate->format('Y-m-d'),
                'endDate' => $endDate->format('Y-m-d'),
                'dateRange' => $startDate->format('d/m/Y') . ' ~ ' . $endDate->format('d/m/Y'),
                'groupBy' => $groupBy,
                'summary' => [
                    'totalConsumption' => round($totalEnergy, 2),
                    'totalRuntime' => round($totalRuntime / 60, 0),
                    'totalWater' => round($totalWater, 0),
                    'totalCost' => round($totalCost, 2),
                    'motorCycles' => $totalCycles,
                    'totalDays' => $totalDays,
                    'averageDailyConsumption' => round($totalEnergy / $totalDays, 2),
                    'averageDailyRuntime' => round(($totalRuntime / 60) / $totalDays, 0),
                    'unitPrice' => (float) $unitPrice,
                    'pumpingCapacity' => $pumpingCapacity
                ],
                'data' => $groupedData,
                'device' => $deviceInfo,
                'customer' => $customerInfo
            ];

            if ($deviceWiseBreakdown) {
                $response['deviceWiseBreakdown'] = $deviceWiseBreakdown;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Custom report error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate custom report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
