<x-layouts.app :title="__('Motor Reports')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Motor Reports</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Explore motor consumption reports with advanced filters</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
            <form method="GET" action="{{ route('admin.reports.index') }}" id="reports-filter-form" class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <!-- Report Type -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Report Type</label>
                        <select name="report_type" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                            <option value="daily" {{ $reportType === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="monthly" {{ $reportType === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ $reportType === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="custom" {{ $reportType === 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>

                    <!-- User Filter -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">User</label>
                        <select name="user_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->phone_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Device Filter -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Device</label>
                        <select name="device_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100" {{ $userId && $devices->count() == 0 ? 'disabled' : '' }}>
                            <option value="">All Devices</option>
                            @if($userId && $devices->count() == 0)
                                <option value="" disabled>No devices assigned to this user</option>
                            @else
                                @foreach($devices as $device)
                                    <option value="{{ $device->id }}" {{ $deviceId == $device->id ? 'selected' : '' }}>
                                        {{ $device->device_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @if($userId && $devices->count() == 0)
                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">This user has no devices assigned</p>
                        @endif
                    </div>

                    <!-- City Filter -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">City</label>
                        <select name="city" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                            <option value="">All Cities</option>
                            @foreach($cities as $cityName)
                                <option value="{{ $cityName }}" {{ $city === $cityName ? 'selected' : '' }}>{{ $cityName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- State Filter -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">State</label>
                        <select name="state" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                            <option value="">All States</option>
                            @foreach($states as $stateName)
                                <option value="{{ $stateName }}" {{ $state === $stateName ? 'selected' : '' }}>{{ $stateName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Filters (conditional based on report type) -->
                    @if($reportType === 'daily')
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Date</label>
                            <input type="date" name="date" value="{{ $date }}" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                        </div>
                    @endif

                    @if($reportType === 'monthly')
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Month</label>
                            <select name="month" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ Carbon\Carbon::create(null, $m, 1)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Year</label>
                            <input type="number" name="year" value="{{ $year }}" min="2020" max="2100" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                        </div>
                    @endif

                    @if($reportType === 'yearly')
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Year</label>
                            <input type="number" name="year" value="{{ $year }}" min="2020" max="2100" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                        </div>
                    @endif

                    @if($reportType === 'custom')
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Start Date</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">End Date</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                        </div>
                    @endif
                </div>

                <div class="flex gap-2">
                    <button type="submit" name="generate" value="1" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Generate Report
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 dark:hover:bg-zinc-600">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <script>
            (function() {
                'use strict';
                
                const form = document.getElementById('reports-filter-form') || document.querySelector('form[method="GET"]');
                if (!form) {
                    console.error('Reports form not found!');
                    return;
                }
                
                console.log('Form found:', form.id || 'form found');
                console.log('Form action:', form.action);
                
                const submitButton = form.querySelector('button[type="submit"]');
                let submitTimeout = null;
                
                // Create hidden generate input if not exists
                function ensureGenerateInput() {
                    let generateInput = form.querySelector('input[name="generate"]');
                    if (!generateInput) {
                        generateInput = document.createElement('input');
                        generateInput.type = 'hidden';
                        generateInput.name = 'generate';
                        generateInput.value = '1';
                        form.appendChild(generateInput);
                    }
                    return generateInput;
                }
                
                // Submit form function
                function submitForm() {
                    ensureGenerateInput();
                    
                    if (submitButton) {
                        submitButton.disabled = true;
                        const originalText = submitButton.innerHTML;
                        submitButton.innerHTML = 'Loading...';
                    }
                    
                    console.log('Submitting form with filters...');
                    console.log('Form action:', form.action);
                    console.log('Form method:', form.method);
                    
                    // Force form submission
                    form.submit();
                }
                
                // Add event listeners when DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }
                
                function init() {
                    // Auto-submit on select change
                    const selects = form.querySelectorAll('select');
                    console.log('Found', selects.length, 'select elements');
                    selects.forEach(function(select) {
                        console.log('Adding listener to:', select.name);
                        select.addEventListener('change', function(e) {
                            console.log('Select changed:', select.name, '=', select.value);
                            e.preventDefault();
                            submitForm();
                            return false;
                        });
                        // Also add input event as backup
                        select.addEventListener('input', function(e) {
                            console.log('Select input:', select.name, '=', select.value);
                            submitForm();
                        });
                    });
                    
                    // Auto-submit on date change
                    const dateInputs = form.querySelectorAll('input[type="date"]');
                    dateInputs.forEach(function(input) {
                        input.addEventListener('change', function() {
                            console.log('Date changed:', input.name, '=', input.value);
                            submitForm();
                        });
                    });
                    
                    // Auto-submit on number change (with debounce)
                    const numberInputs = form.querySelectorAll('input[type="number"]');
                    numberInputs.forEach(function(input) {
                        input.addEventListener('change', function() {
                            console.log('Number changed:', input.name, '=', input.value);
                            if (submitTimeout) clearTimeout(submitTimeout);
                            submitTimeout = setTimeout(submitForm, 300);
                        });
                    });
                    
                    console.log('Reports form auto-submit initialized');
                    console.log('Selects:', selects.length, 'Date inputs:', dateInputs.length, 'Number inputs:', numberInputs.length);
                }
            })();
        </script>

        <!-- Charts Section -->
        @php
            $hasChartData = false;
            $hasNonZeroData = false;
            $energySum = 0;
            $costSum = 0;
            $waterSum = 0;
            $runtimeSum = 0;
            
            if (isset($reportData) && $reportData && isset($reportData['chartData']) && count($reportData['chartData']) > 0) {
                $hasChartData = true;
                // Calculate sums for each metric
                $energySum = array_sum(array_column($reportData['chartData'], 'energy'));
                $costSum = array_sum(array_column($reportData['chartData'], 'cost'));
                $waterSum = array_sum(array_column($reportData['chartData'], 'water'));
                $runtimeSum = array_sum(array_column($reportData['chartData'], 'runtime'));
                
                // Check if there's any non-zero data in the charts
                if ($energySum > 0 || $costSum > 0 || $waterSum > 0 || $runtimeSum > 0) {
                    $hasNonZeroData = true;
                }
            }
            
            // Count how many charts have data
            $chartCount = 0;
            if ($energySum > 0) $chartCount++;
            if ($costSum > 0) $chartCount++;
            if ($waterSum > 0) $chartCount++;
            if ($runtimeSum > 0) $chartCount++;
        @endphp

        @if($hasChartData && $hasNonZeroData && $chartCount > 0)
            <div class="grid gap-4 lg:grid-cols-2">
                <!-- Energy Consumption Chart -->
                @if($energySum > 0)
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                        <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Energy Consumption</h3>
                        <canvas id="energyChart" height="300"></canvas>
                    </div>
                @endif

                <!-- Cost Chart -->
                @if($costSum > 0)
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                        <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Electricity Cost</h3>
                        <canvas id="costChart" height="300"></canvas>
                    </div>
                @endif

                <!-- Water Pumped Chart -->
                @if($waterSum > 0)
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                        <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Water Pumped</h3>
                        <canvas id="waterChart" height="300"></canvas>
                    </div>
                @endif

                <!-- Runtime Chart -->
                @if($runtimeSum > 0)
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                        <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Runtime</h3>
                        <canvas id="runtimeChart" height="300"></canvas>
                    </div>
                @endif
            </div>
        @endif

        <!-- Summary Cards -->
        @if(isset($reportData) && $reportData)
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Energy</p>
                            <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($reportData['totalEnergy'], 2) }} kWh</p>
                        </div>
                        <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Electricity Bill</p>
                            <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">₹{{ number_format($reportData['totalCost'], 2) }}</p>
                        </div>
                        <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Runtime</p>
                            <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($reportData['totalRuntime']) }} min</p>
                        </div>
                        <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900">
                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Water Pumped</p>
                            <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($reportData['totalWater']) }} L</p>
                        </div>
                        <div class="rounded-lg bg-cyan-100 p-3 dark:bg-cyan-900">
                            <svg class="h-6 w-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Motor Cycles</p>
                            <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($reportData['totalCycles']) }}</p>
                        </div>
                        <div class="rounded-lg bg-orange-100 p-3 dark:bg-orange-900">
                            <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device-Wise Breakdown -->
            @if(count($reportData['deviceWiseBreakdown']) > 0 && !$deviceId)
                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Device-Wise Breakdown</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Device</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Energy (kWh)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Runtime (min)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Water (L)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Cost (₹)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Cycles</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($reportData['deviceWiseBreakdown'] as $device)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $device['deviceName'] }}</div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $device['smsNumber'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ number_format($device['energy'], 3) }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ number_format($device['runtime']) }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ number_format($device['water']) }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">₹{{ number_format($device['cost'], 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ $device['cycles'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- User-Wise Breakdown -->
            @if(count($reportData['userWiseBreakdown']) > 0 && !$userId)
                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">User-Wise Breakdown</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Energy (kWh)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Runtime (min)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Water (L)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Cost (₹)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Cycles</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($reportData['userWiseBreakdown'] as $user)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user['userName'] }}</div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user['phoneNumber'] }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-zinc-900 dark:text-zinc-100">{{ $user['city'] }}, {{ $user['state'] }}</div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ Str::limit($user['address'], 30) }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ number_format($user['energy'], 3) }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ number_format($user['runtime']) }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ number_format($user['water']) }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">₹{{ number_format($user['cost'], 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ $user['cycles'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @else
            <div class="rounded-xl border border-zinc-200 bg-white p-12 text-center dark:border-zinc-700 dark:bg-zinc-800">
                <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="mt-4 text-lg font-medium text-zinc-900 dark:text-zinc-100">No Report Data</p>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Select filters above to generate report. Filters will apply automatically when changed.</p>
            </div>
        @endif
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    @if($hasChartData && $hasNonZeroData)
        <script>
            // Chart data from PHP
            const chartData = {!! json_encode($reportData['chartData']) !!};
            const labels = chartData.map(item => item.label);
            const energyData = chartData.map(item => item.energy);
            const costData = chartData.map(item => item.cost);
            const waterData = chartData.map(item => item.water);
            const runtimeData = chartData.map(item => item.runtime);

            // Chart configuration
            const chartConfig = {
                type: 'line',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            };

            // Energy Chart
            const energyCtx = document.getElementById('energyChart');
            if (energyCtx) {
                const energySum = energyData.reduce((a, b) => a + b, 0);
                if (energySum > 0) {
                    new Chart(energyCtx, {
                    ...chartConfig,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Energy (kWh)',
                            data: energyData,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        ...chartConfig.options,
                        plugins: {
                            ...chartConfig.plugins,
                            title: {
                                display: false
                            }
                        }
                    }
                });
                }
            }

            // Cost Chart
            const costCtx = document.getElementById('costChart');
            if (costCtx) {
                const costSum = costData.reduce((a, b) => a + b, 0);
                if (costSum > 0) {
                    new Chart(costCtx, {
                    ...chartConfig,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Cost (₹)',
                            data: costData,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        ...chartConfig.options,
                        plugins: {
                            ...chartConfig.plugins,
                            title: {
                                display: false
                            }
                        }
                    }
                });
                }
            }

            // Water Chart
            const waterCtx = document.getElementById('waterChart');
            if (waterCtx) {
                const waterSum = waterData.reduce((a, b) => a + b, 0);
                if (waterSum > 0) {
                    new Chart(waterCtx, {
                    ...chartConfig,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Water (Liters)',
                            data: waterData,
                            borderColor: 'rgb(6, 182, 212)',
                            backgroundColor: 'rgba(6, 182, 212, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        ...chartConfig.options,
                        plugins: {
                            ...chartConfig.plugins,
                            title: {
                                display: false
                            }
                        }
                    }
                });
                }
            }

            // Runtime Chart
            const runtimeCtx = document.getElementById('runtimeChart');
            if (runtimeCtx) {
                const runtimeSum = runtimeData.reduce((a, b) => a + b, 0);
                if (runtimeSum > 0) {
                    new Chart(runtimeCtx, {
                    ...chartConfig,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Runtime (minutes)',
                            data: runtimeData,
                            borderColor: 'rgb(168, 85, 247)',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        ...chartConfig.options,
                        plugins: {
                            ...chartConfig.plugins,
                            title: {
                                display: false
                            }
                        }
                    }
                });
                }
            }
        </script>
    @endif
</x-layouts.app>

