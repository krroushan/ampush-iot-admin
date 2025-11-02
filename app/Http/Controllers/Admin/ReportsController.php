<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Device;
use App\Models\MotorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display reports page with filters
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $reportType = $request->get('report_type', 'yearly');
        $userId = $request->get('user_id');
        $deviceId = $request->get('device_id');
        $city = $request->get('city');
        $state = $request->get('state');
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));

        // Get filter data
        $users = User::where('role', 'customer')->orderBy('name')->get();
        
        // Get devices - if user is selected, only show their devices
        if ($userId) {
            $devices = Device::where('user_id', $userId)->orderBy('device_name')->get();
            
            // If a device is selected but doesn't belong to this user, clear it
            if ($deviceId && !$devices->pluck('id')->contains($deviceId)) {
                $deviceId = null;
            }
        } else {
            $devices = Device::orderBy('device_name')->get();
        }
        
        // Get unique cities and states
        $cities = User::where('role', 'customer')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();
            
        $states = User::where('role', 'customer')
            ->whereNotNull('state')
            ->where('state', '!=', '')
            ->distinct()
            ->pluck('state')
            ->sort()
            ->values();

        // Build query for user filtering
        $hasUserFilter = $userId || $city || $state;
        
        if ($hasUserFilter) {
            $userQuery = User::where('role', 'customer');
            
            if ($userId) {
                $userQuery->where('id', $userId);
            }
            if ($city) {
                $userQuery->where('city', $city);
            }
            if ($state) {
                $userQuery->where('state', $state);
            }

            $filteredUsers = $userQuery->pluck('id');
        } else {
            // No user filter - get all customer IDs
            $filteredUsers = User::where('role', 'customer')->pluck('id');
        }

        // Always calculate report data (shows data immediately when page loads)
        try {
            $reportData = $this->calculateReport($reportType, $filteredUsers, $deviceId, $userId, $date, $month, $year, $startDate, $endDate);
        } catch (\Exception $e) {
            Log::error('Reports calculation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            $reportData = [
                'totalEnergy' => 0,
                'totalRuntime' => 0,
                'totalWater' => 0,
                'totalCost' => 0,
                'totalCycles' => 0,
                'deviceWiseBreakdown' => [],
                'userWiseBreakdown' => []
            ];
        }

        return view('admin.reports.index', compact(
            'reportType',
            'userId',
            'deviceId',
            'city',
            'state',
            'date',
            'month',
            'year',
            'startDate',
            'endDate',
            'users',
            'devices',
            'cities',
            'states',
            'reportData'
        ));
    }

    /**
     * Calculate report data
     */
    private function calculateReport($reportType, $userIds, $deviceId, $userId, $date, $month, $year, $startDate, $endDate)
    {
        // Build query
        $query = MotorLog::where('motor_status', 'OFF')
            ->whereNotNull('run_time')
            ->where('run_time', '>', 0);

        // Filter by users
        // If userIds is empty (no users match filter), whereIn with empty array returns no results (correct behavior)
        // If userIds has values, filter by those users
        if ($userIds->count() > 0) {
            $query->whereIn('user_id', $userIds);
        }
        // If userIds is empty and we want all users, we need to handle it differently
        // But since we already populate all user IDs when no filter is set, this should work

        // Filter by device
        if ($deviceId) {
            $query->where('device_id', $deviceId);
        }

        // Filter by date range based on report type
        if ($reportType === 'daily') {
            $startTs = Carbon::parse($date)->startOfDay()->timestamp * 1000;
            $endTs = Carbon::parse($date)->endOfDay()->timestamp * 1000;
        } elseif ($reportType === 'monthly') {
            $startTs = Carbon::create($year, $month, 1)->startOfMonth()->timestamp * 1000;
            $endTs = Carbon::create($year, $month, 1)->endOfMonth()->timestamp * 1000;
        } elseif ($reportType === 'yearly') {
            $startTs = Carbon::create($year, 1, 1)->startOfDay()->timestamp * 1000;
            $endTs = Carbon::create($year, 12, 31)->endOfDay()->timestamp * 1000;
        } else { // custom
            $startTs = Carbon::parse($startDate)->startOfDay()->timestamp * 1000;
            $endTs = Carbon::parse($endDate)->endOfDay()->timestamp * 1000;
        }

        $query->whereBetween('timestamp', [(string)$startTs, (string)$endTs]);
        $offLogs = $query->orderBy('timestamp', 'asc')->get();

        // Prepare chart data based on report type
        $chartData = [];
        
        if ($reportType === 'daily') {
            // Hourly breakdown for daily report
            $dateCarbon = Carbon::parse($date);
            for ($hour = 0; $hour < 24; $hour++) {
                $hourStart = $dateCarbon->copy()->setHour($hour)->startOfHour()->timestamp * 1000;
                $hourEnd = $dateCarbon->copy()->setHour($hour)->endOfHour()->timestamp * 1000;
                
                $hourLogs = $offLogs->filter(function($log) use ($hourStart, $hourEnd) {
                    return $log->timestamp >= $hourStart && $log->timestamp <= $hourEnd;
                });
                
                $hourEnergy = 0;
                $hourRuntime = 0;
                $hourWater = 0;
                $hourCost = 0;
                
                foreach ($hourLogs as $offLog) {
                    $unitPrice = 6.00;
                    $pumpingCapacity = 50;
                    
                    if ($offLog->user_id) {
                        $user = User::find($offLog->user_id);
                        if ($user) {
                            $unitPrice = $user->unit_price ?: 6.00;
                            $pumpingCapacity = $user->motor_pumping_capacity ?: 50;
                        }
                    }
                    
                    $onLog = MotorLog::where('device_id', $offLog->device_id)
                        ->where('motor_status', 'ON')
                        ->where('timestamp', '<', $offLog->timestamp)
                        ->orderBy('timestamp', 'desc')
                        ->first();
                    
                    if ($onLog) {
                        $powerKw = ($onLog->voltage * $onLog->current) / 1000;
                        $runtimeHours = $offLog->run_time / 3600;
                        $energy = $powerKw * $runtimeHours;
                        $water = ($offLog->run_time / 60) * $pumpingCapacity;
                        $cost = $energy * $unitPrice;
                        
                        $hourEnergy += $energy;
                        $hourRuntime += $offLog->run_time;
                        $hourWater += $water;
                        $hourCost += $cost;
                    }
                }
                
                $chartData[] = [
                    'label' => sprintf('%02d:00', $hour),
                    'energy' => round($hourEnergy, 3),
                    'runtime' => round($hourRuntime / 60, 0),
                    'water' => round($hourWater, 0),
                    'cost' => round($hourCost, 2)
                ];
            }
        } elseif ($reportType === 'monthly') {
            // Daily breakdown for monthly report
            $monthCarbon = Carbon::create($year, $month, 1);
            $daysInMonth = $monthCarbon->daysInMonth;
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dayCarbon = Carbon::create($year, $month, $day);
                $dayStart = $dayCarbon->startOfDay()->timestamp * 1000;
                $dayEnd = $dayCarbon->endOfDay()->timestamp * 1000;
                
                $dayLogs = $offLogs->filter(function($log) use ($dayStart, $dayEnd) {
                    return $log->timestamp >= $dayStart && $log->timestamp <= $dayEnd;
                });
                
                $dayEnergy = 0;
                $dayRuntime = 0;
                $dayWater = 0;
                $dayCost = 0;
                
                foreach ($dayLogs as $offLog) {
                    $unitPrice = 6.00;
                    $pumpingCapacity = 50;
                    
                    if ($offLog->user_id) {
                        $user = User::find($offLog->user_id);
                        if ($user) {
                            $unitPrice = $user->unit_price ?: 6.00;
                            $pumpingCapacity = $user->motor_pumping_capacity ?: 50;
                        }
                    }
                    
                    $onLog = MotorLog::where('device_id', $offLog->device_id)
                        ->where('motor_status', 'ON')
                        ->where('timestamp', '<', $offLog->timestamp)
                        ->orderBy('timestamp', 'desc')
                        ->first();
                    
                    if ($onLog) {
                        $powerKw = ($onLog->voltage * $onLog->current) / 1000;
                        $runtimeHours = $offLog->run_time / 3600;
                        $energy = $powerKw * $runtimeHours;
                        $water = ($offLog->run_time / 60) * $pumpingCapacity;
                        $cost = $energy * $unitPrice;
                        
                        $dayEnergy += $energy;
                        $dayRuntime += $offLog->run_time;
                        $dayWater += $water;
                        $dayCost += $cost;
                    }
                }
                
                $chartData[] = [
                    'label' => $dayCarbon->format('d M'),
                    'energy' => round($dayEnergy, 3),
                    'runtime' => round($dayRuntime / 60, 0),
                    'water' => round($dayWater, 0),
                    'cost' => round($dayCost, 2)
                ];
            }
        } elseif ($reportType === 'yearly') {
            // Monthly breakdown for yearly report
            for ($m = 1; $m <= 12; $m++) {
                $monthStart = Carbon::create($year, $m, 1)->startOfMonth()->timestamp * 1000;
                $monthEnd = Carbon::create($year, $m, 1)->endOfMonth()->timestamp * 1000;
                
                $monthLogs = $offLogs->filter(function($log) use ($monthStart, $monthEnd) {
                    return $log->timestamp >= $monthStart && $log->timestamp <= $monthEnd;
                });
                
                $monthEnergy = 0;
                $monthRuntime = 0;
                $monthWater = 0;
                $monthCost = 0;
                
                foreach ($monthLogs as $offLog) {
                    $unitPrice = 6.00;
                    $pumpingCapacity = 50;
                    
                    if ($offLog->user_id) {
                        $user = User::find($offLog->user_id);
                        if ($user) {
                            $unitPrice = $user->unit_price ?: 6.00;
                            $pumpingCapacity = $user->motor_pumping_capacity ?: 50;
                        }
                    }
                    
                    $onLog = MotorLog::where('device_id', $offLog->device_id)
                        ->where('motor_status', 'ON')
                        ->where('timestamp', '<', $offLog->timestamp)
                        ->orderBy('timestamp', 'desc')
                        ->first();
                    
                    if ($onLog) {
                        $powerKw = ($onLog->voltage * $onLog->current) / 1000;
                        $runtimeHours = $offLog->run_time / 3600;
                        $energy = $powerKw * $runtimeHours;
                        $water = ($offLog->run_time / 60) * $pumpingCapacity;
                        $cost = $energy * $unitPrice;
                        
                        $monthEnergy += $energy;
                        $monthRuntime += $offLog->run_time;
                        $monthWater += $water;
                        $monthCost += $cost;
                    }
                }
                
                $chartData[] = [
                    'label' => Carbon::create($year, $m, 1)->format('M'),
                    'energy' => round($monthEnergy, 2),
                    'runtime' => round($monthRuntime / 60, 0),
                    'water' => round($monthWater, 0),
                    'cost' => round($monthCost, 2)
                ];
            }
        } else { // custom
            // Daily breakdown for custom range
            $currentDate = Carbon::parse($startDate);
            $endDateCarbon = Carbon::parse($endDate);
            
            while ($currentDate <= $endDateCarbon) {
                $dayStart = $currentDate->startOfDay()->timestamp * 1000;
                $dayEnd = $currentDate->endOfDay()->timestamp * 1000;
                
                $dayLogs = $offLogs->filter(function($log) use ($dayStart, $dayEnd) {
                    return $log->timestamp >= $dayStart && $log->timestamp <= $dayEnd;
                });
                
                $dayEnergy = 0;
                $dayRuntime = 0;
                $dayWater = 0;
                $dayCost = 0;
                
                foreach ($dayLogs as $offLog) {
                    $unitPrice = 6.00;
                    $pumpingCapacity = 50;
                    
                    if ($offLog->user_id) {
                        $user = User::find($offLog->user_id);
                        if ($user) {
                            $unitPrice = $user->unit_price ?: 6.00;
                            $pumpingCapacity = $user->motor_pumping_capacity ?: 50;
                        }
                    }
                    
                    $onLog = MotorLog::where('device_id', $offLog->device_id)
                        ->where('motor_status', 'ON')
                        ->where('timestamp', '<', $offLog->timestamp)
                        ->orderBy('timestamp', 'desc')
                        ->first();
                    
                    if ($onLog) {
                        $powerKw = ($onLog->voltage * $onLog->current) / 1000;
                        $runtimeHours = $offLog->run_time / 3600;
                        $energy = $powerKw * $runtimeHours;
                        $water = ($offLog->run_time / 60) * $pumpingCapacity;
                        $cost = $energy * $unitPrice;
                        
                        $dayEnergy += $energy;
                        $dayRuntime += $offLog->run_time;
                        $dayWater += $water;
                        $dayCost += $cost;
                    }
                }
                
                $chartData[] = [
                    'label' => $currentDate->format('d M'),
                    'energy' => round($dayEnergy, 3),
                    'runtime' => round($dayRuntime / 60, 0),
                    'water' => round($dayWater, 0),
                    'cost' => round($dayCost, 2)
                ];
                
                $currentDate->addDay();
            }
        }

        // Calculate metrics
        $totalEnergy = 0;
        $totalRuntime = 0;
        $totalWater = 0;
        $totalCost = 0;
        $totalCycles = 0;

        foreach ($offLogs as $offLog) {
            // Get user's settings
            $unitPrice = 6.00;
            $pumpingCapacity = 50;
            
            if ($offLog->user_id) {
                $user = User::find($offLog->user_id);
                if ($user) {
                    $unitPrice = $user->unit_price ?: 6.00;
                    $pumpingCapacity = $user->motor_pumping_capacity ?: 50;
                }
            }

            $onLog = MotorLog::where('device_id', $offLog->device_id)
                ->where('motor_status', 'ON')
                ->where('timestamp', '<', $offLog->timestamp)
                ->orderBy('timestamp', 'desc')
                ->first();

            if ($onLog) {
                $powerKw = ($onLog->voltage * $onLog->current) / 1000;
                $runtimeHours = $offLog->run_time / 3600;
                $energy = $powerKw * $runtimeHours;
                $water = ($offLog->run_time / 60) * $pumpingCapacity;
                $cost = $energy * $unitPrice;

                $totalEnergy += $energy;
                $totalRuntime += $offLog->run_time;
                $totalWater += $water;
                $totalCost += $cost;
                $totalCycles++;
            }
        }

        // Get device-wise breakdown
        $deviceWiseBreakdown = [];
        if (!$deviceId) {
            $deviceIds = $offLogs->pluck('device_id')->unique();
            foreach ($deviceIds as $dId) {
                $deviceLogs = $offLogs->where('device_id', $dId);
                $device = Device::find($dId);
                
                if ($device) {
                    $deviceEnergy = 0;
                    $deviceRuntime = 0;
                    $deviceWater = 0;
                    $deviceCycles = 0;

                    $deviceCost = 0;
                    foreach ($deviceLogs as $offLog) {
                        $unitPrice = 6.00;
                        $pumpingCapacity = 50;
                        
                        if ($offLog->user_id) {
                            $user = User::find($offLog->user_id);
                            if ($user) {
                                $unitPrice = $user->unit_price ?: 6.00;
                                $pumpingCapacity = $user->motor_pumping_capacity ?: 50;
                            }
                        }

                        $onLog = MotorLog::where('device_id', $offLog->device_id)
                            ->where('motor_status', 'ON')
                            ->where('timestamp', '<', $offLog->timestamp)
                            ->orderBy('timestamp', 'desc')
                            ->first();

                        if ($onLog) {
                            $powerKw = ($onLog->voltage * $onLog->current) / 1000;
                            $runtimeHours = $offLog->run_time / 3600;
                            $energy = $powerKw * $runtimeHours;
                            $water = ($offLog->run_time / 60) * $pumpingCapacity;
                            $cost = $energy * $unitPrice;

                            $deviceEnergy += $energy;
                            $deviceRuntime += $offLog->run_time;
                            $deviceWater += $water;
                            $deviceCost += $cost;
                            $deviceCycles++;
                        }
                    }

                    $deviceWiseBreakdown[] = [
                        'deviceId' => $device->id,
                        'deviceName' => $device->device_name,
                        'smsNumber' => $device->sms_number,
                        'energy' => round($deviceEnergy, 3),
                        'runtime' => round($deviceRuntime / 60, 0),
                        'water' => round($deviceWater, 0),
                        'cost' => round($deviceCost, 2),
                        'cycles' => $deviceCycles
                    ];
                }
            }
        }

        // Get user-wise breakdown
        $userWiseBreakdown = [];
        if (!$userId && $userIds->count() > 0) {
            foreach ($userIds as $uId) {
                $user = User::find($uId);
                if ($user) {
                    $userLogs = $offLogs->where('user_id', $uId);
                    $userEnergy = 0;
                    $userRuntime = 0;
                    $userWater = 0;
                    $userCycles = 0;
                    $userUnitPrice = $user->unit_price ?: 6.00;
                    $userPumpingCapacity = $user->motor_pumping_capacity ?: 50;

                    foreach ($userLogs as $offLog) {
                        $onLog = MotorLog::where('device_id', $offLog->device_id)
                            ->where('motor_status', 'ON')
                            ->where('timestamp', '<', $offLog->timestamp)
                            ->orderBy('timestamp', 'desc')
                            ->first();

                        if ($onLog) {
                            $powerKw = ($onLog->voltage * $onLog->current) / 1000;
                            $runtimeHours = $offLog->run_time / 3600;
                            $userEnergy += $powerKw * $runtimeHours;
                            $userRuntime += $offLog->run_time;
                            $userWater += ($offLog->run_time / 60) * $userPumpingCapacity;
                            $userCycles++;
                        }
                    }

                    $userWiseBreakdown[] = [
                        'userId' => $user->id,
                        'userName' => $user->name,
                        'phoneNumber' => $user->phone_number,
                        'city' => $user->city,
                        'state' => $user->state,
                        'address' => $user->address,
                        'energy' => round($userEnergy, 3),
                        'runtime' => round($userRuntime / 60, 0),
                        'water' => round($userWater, 0),
                        'cost' => round($userEnergy * $userUnitPrice, 2),
                        'cycles' => $userCycles
                    ];
                }
            }
        }

        return [
            'totalEnergy' => round($totalEnergy, 2),
            'totalRuntime' => round($totalRuntime / 60, 0),
            'totalWater' => round($totalWater, 0),
            'totalCost' => round($totalCost, 2),
            'totalCycles' => $totalCycles,
            'deviceWiseBreakdown' => $deviceWiseBreakdown,
            'userWiseBreakdown' => $userWiseBreakdown,
            'chartData' => $chartData
        ];
    }
}
