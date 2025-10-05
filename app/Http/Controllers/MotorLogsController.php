<?php

namespace App\Http\Controllers;

use App\Models\MotorLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MotorLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = MotorLog::query();

        // Filter by phone number - prioritize dropdown selection over text search
        if ($request->filled('phone_filter')) {
            $query->where('phone_number', $request->phone_filter);
        } elseif ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('phone_number', 'like', "%{$searchTerm}%");
        }

        // Filter by motor status
        if ($request->filled('motor_status')) {
            $query->where('motor_status', $request->motor_status);
        }

        // Filter by command
        if ($request->filled('command')) {
            $query->where('command', $request->command);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay()->timestamp * 1000;
            $query->where('timestamp', '>=', $dateFrom);
        }

        if ($request->filled('date_to')) {
            $dateTo = Carbon::parse($request->date_to)->endOfDay()->timestamp * 1000;
            $query->where('timestamp', '<=', $dateTo);
        }

        // Filter by sync status
        if ($request->filled('is_synced')) {
            $query->where('is_synced', $request->is_synced === '1');
        }

        // Filter by customer (if phone number matches customer phone)
        if ($request->filled('customer_id')) {
            $customer = User::find($request->customer_id);
            if ($customer && $customer->phone_number) {
                $query->where('phone_number', $customer->phone_number);
            }
        }

        // Get statistics
        $stats = $this->getStats($query->clone());

        // Paginate results
        $motorLogs = $query->with('customer')
            ->orderBy('timestamp', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Get unique phone numbers from customers for filter dropdown
        $phoneNumbers = User::where('role', 'customer')
            ->whereNotNull('phone_number')
            ->orderBy('phone_number')
            ->pluck('phone_number')
            ->toArray();

        return view('motor-logs.index', compact('motorLogs', 'phoneNumbers', 'stats'));
    }

    public function show(MotorLog $motorLog)
    {
        // Try to find customer by phone number
        $customer = User::where('phone_number', $motorLog->phone_number)
            ->where('role', 'customer')
            ->first();

        return view('motor-logs.show', compact('motorLog', 'customer'));
    }

    public function destroy(MotorLog $motorLog)
    {
        $motorLog->delete();

        return redirect()->route('motor-logs.index')
            ->with('success', 'Motor log deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'log_ids' => 'required|array',
            'log_ids.*' => 'exists:motor_logs,id'
        ]);

        MotorLog::whereIn('id', $request->log_ids)->delete();

        return redirect()->route('motor-logs.index')
            ->with('success', count($request->log_ids) . ' motor logs deleted successfully.');
    }

    private function getStats($query)
    {
        $totalLogs = $query->count();
        $onLogs = $query->clone()->where('motor_status', 'ON')->count();
        $offLogs = $query->clone()->where('motor_status', 'OFF')->count();
        $statusLogs = $query->clone()->where('motor_status', 'STATUS')->count();

        // Get unique phone numbers
        $uniquePhones = $query->clone()->distinct('phone_number')->count('phone_number');

        // Get average voltage and current
        $avgVoltage = $query->clone()->whereNotNull('voltage')->avg('voltage');
        $avgCurrent = $query->clone()->whereNotNull('current')->avg('current');
        $avgWaterLevel = $query->clone()->whereNotNull('water_level')->avg('water_level');

        // Get sync statistics
        $syncedLogs = $query->clone()->where('is_synced', true)->count();
        $unsyncedLogs = $query->clone()->where('is_synced', false)->count();

        // Get recent activity (last 24 hours)
        $last24Hours = Carbon::now()->subDay()->timestamp * 1000;
        $recentActivity = $query->clone()->where('timestamp', '>=', $last24Hours)->count();

        return [
            'total_logs' => $totalLogs,
            'on_logs' => $onLogs,
            'off_logs' => $offLogs,
            'status_logs' => $statusLogs,
            'unique_phones' => $uniquePhones,
            'avg_voltage' => round($avgVoltage ?? 0, 2),
            'avg_current' => round($avgCurrent ?? 0, 2),
            'avg_water_level' => round($avgWaterLevel ?? 0, 2),
            'synced_logs' => $syncedLogs,
            'unsynced_logs' => $unsyncedLogs,
            'recent_activity' => $recentActivity,
        ];
    }
}