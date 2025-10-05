<?php

namespace App\Http\Controllers;

use App\Models\MotorLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Customer Statistics
        $totalCustomers = User::where('role', 'customer')->count();
        $activeCustomers = User::where('role', 'customer')
            ->whereHas('motorLogs')
            ->count();
        
        // Motor Log Statistics
        $totalLogs = MotorLog::count();
        $todayLogs = MotorLog::whereDate('created_at', Carbon::today())->count();
        $thisWeekLogs = MotorLog::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        
        // Motor Status Statistics
        $motorOnLogs = MotorLog::where('motor_status', 'ON')->count();
        $motorOffLogs = MotorLog::where('motor_status', 'OFF')->count();
        $motorStatusLogs = MotorLog::where('motor_status', 'STATUS')->count();
        
        // Voltage Statistics
        $avgVoltage = MotorLog::whereNotNull('voltage')->avg('voltage');
        $maxVoltage = MotorLog::whereNotNull('voltage')->max('voltage');
        $minVoltage = MotorLog::whereNotNull('voltage')->min('voltage');
        $totalVoltageReadings = MotorLog::whereNotNull('voltage')->count();
        
        // Current Statistics
        $avgCurrent = MotorLog::whereNotNull('current')->avg('current');
        $maxCurrent = MotorLog::whereNotNull('current')->max('current');
        $minCurrent = MotorLog::whereNotNull('current')->min('current');
        $totalCurrentReadings = MotorLog::whereNotNull('current')->count();
        
        // Water Level Statistics
        $avgWaterLevel = MotorLog::whereNotNull('water_level')->avg('water_level');
        $maxWaterLevel = MotorLog::whereNotNull('water_level')->max('water_level');
        $minWaterLevel = MotorLog::whereNotNull('water_level')->min('water_level');
        $totalWaterLevelReadings = MotorLog::whereNotNull('water_level')->count();
        
        // Device Statistics
        $uniqueDevices = MotorLog::distinct('phone_number')->count('phone_number');
        $syncedLogs = MotorLog::where('is_synced', true)->count();
        $unsyncedLogs = MotorLog::where('is_synced', false)->count();
        
        // Recent Activity (Last 24 hours)
        $last24Hours = Carbon::now()->subDay();
        $recentActivity = MotorLog::where('created_at', '>=', $last24Hours)->count();
        
        // Top Active Customers (by log count)
        $topActiveCustomers = User::where('role', 'customer')
            ->withCount('motorLogs')
            ->orderBy('motor_logs_count', 'desc')
            ->take(5)
            ->get();
        
        // Recent Motor Logs
        $recentLogs = MotorLog::with('customer')
            ->orderBy('timestamp', 'desc')
            ->take(10)
            ->get();
        
        // Daily Activity (Last 7 days)
        $dailyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = MotorLog::whereDate('created_at', $date)->count();
            $dailyActivity[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }
        
        return view('dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'totalLogs',
            'todayLogs',
            'thisWeekLogs',
            'motorOnLogs',
            'motorOffLogs',
            'motorStatusLogs',
            'avgVoltage',
            'maxVoltage',
            'minVoltage',
            'totalVoltageReadings',
            'avgCurrent',
            'maxCurrent',
            'minCurrent',
            'totalCurrentReadings',
            'avgWaterLevel',
            'maxWaterLevel',
            'minWaterLevel',
            'totalWaterLevelReadings',
            'uniqueDevices',
            'syncedLogs',
            'unsyncedLogs',
            'recentActivity',
            'topActiveCustomers',
            'recentLogs',
            'dailyActivity'
        ));
    }
}