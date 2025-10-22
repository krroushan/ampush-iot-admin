<x-layouts.app :title="__('Motor Logs')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Motor Logs</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Monitor and manage IoT motor logs</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            <!-- Total Logs -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Logs</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['total_logs']) }}</p>
                </div>
            </div>

            <!-- Motor ON -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Motor ON</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['on_logs']) }}</p>
                </div>
            </div>

            <!-- Motor OFF -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Motor OFF</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['off_logs']) }}</p>
                </div>
            </div>

            <!-- Unique Devices -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Unique Devices</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['unique_phones']) }}</p>
                </div>
            </div>

            <!-- Average Voltage -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Avg Voltage</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $stats['avg_voltage'] }}V</p>
                </div>
            </div>

            <!-- Average Current -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Avg Current</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $stats['avg_current'] }}A</p>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Last 24h</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['recent_activity']) }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
            <form method="GET" action="{{ route('motor-logs.index') }}" id="filterForm" class="space-y-4">
                <!-- First Row: Search and Status Filters -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <!-- Search by Customer Phone -->
                    <div>
                        <flux:label for="search">Customer Phone</flux:label>
                        <flux:input 
                            type="text" 
                            name="phone_number" 
                            id="phone_number" 
                            value="{{ request('phone_number') }}" 
                            placeholder="Enter customer phone number"
                            oninput="realTimeFilter()"
                        />
                    </div>

                    <!-- Select Phone Number -->
                    <div>
                        <flux:label for="phone_filter">Select Phone</flux:label>
                        <flux:select name="customer_id" id="customer_id" onchange="realTimeFilter()">
                            <option value="">All Customers</option>
                            @foreach($phoneNumbers as $phoneNumber)
                                @php
                                    $customer = \App\Models\User::where('phone_number', $phoneNumber)->where('role', 'customer')->first();
                                @endphp
                                @if($customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $phoneNumber }})
                                    </option>
                                @endif
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Command -->
                    <div class="flex-1 min-w-[150px]">
                        <flux:label for="command">Command</flux:label>
                        <flux:select name="command" id="command" onchange="realTimeFilter()">
                            <option value="">All Commands</option>
                            <option value="MOTORON" {{ request('command') == 'MOTORON' ? 'selected' : '' }}>MOTORON</option>
                            <option value="MOTOROFF" {{ request('command') == 'MOTOROFF' ? 'selected' : '' }}>MOTOROFF</option>
                            <option value="STATUS" {{ request('command') == 'STATUS' ? 'selected' : '' }}>STATUS</option>
                        </flux:select>
                    </div>

                    <!-- Device Filter -->
                    <div class="flex-1 min-w-[150px]">
                        <flux:label for="device_id">Device</flux:label>
                        <flux:select name="device_id" id="device_id" onchange="realTimeFilter()">
                            <option value="">All Devices</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->device_name }} ({{ $device->sms_number }})
                                </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 sm:col-span-2 lg:col-span-1">
                        <flux:button variant="outline" onclick="clearFilters()" type="button">
                            Clear
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                            Filter
                        </flux:button>
                    </div>
                </div>

                <!-- Second Row: Date Filters -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <!-- Date From -->
                    <div class="flex-1 min-w-[150px]">
                        <flux:label for="date_from">Date From</flux:label>
                        <flux:input
                            type="date"
                            name="date_from"
                            id="date_from"
                            value="{{ request('date_from') }}"
                            onchange="realTimeFilter()"
                        />
                    </div>

                    <!-- Date To -->
                    <div class="flex-1 min-w-[150px]">
                        <flux:label for="date_to">Date To</flux:label>
                        <flux:input
                            type="date"
                            name="date_to"
                            id="date_to"
                            value="{{ request('date_to') }}"
                            onchange="realTimeFilter()"
                        />
                    </div>
                </div>
            </form>
        </div>

        <!-- Motor Logs Table -->
        <div class="flex-1 overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
            <div class="p-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Motor Logs</h3>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                        <flux:button variant="outline" onclick="selectAll()" class="w-full sm:w-auto">
                            Select All
                        </flux:button>
                        <flux:button variant="danger" onclick="bulkDelete()" class="w-full sm:w-auto">
                            Delete Selected
                        </flux:button>
                    </div>
                </div>

                @if($motorLogs->count() > 0)
                    <div class="overflow-x-auto" id="logsTable">
                        <table class="w-full min-w-[800px]">
                            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll(this)">
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">ID</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Timestamp</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Phone</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Device</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Customer</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Motor Status</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Command</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Voltage</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Current</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Water Level</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Run Time</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($motorLogs as $log)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="log_ids[]" value="{{ $log->id }}" class="log-checkbox">
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            {{ $log->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            <div>{{ $log->formatted_timestamp }}</div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $log->time_ago }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            {{ $log->phone_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($log->device)
                                                <div class="font-medium">{{ $log->device->device_name }}</div>
                                                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $log->device->sms_number }}</div>
                                            @else
                                                <span class="text-zinc-400">No device</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($log->user)
                                                <div class="font-medium">{{ $log->user->name }}</div>
                                                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $log->user->email }}</div>
                                            @else
                                                <span class="text-zinc-400">No customer</span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($log->motor_status == 'ON')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    ON
                                                </span>
                                            @elseif($log->motor_status == 'OFF')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    OFF
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    STATUS
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($log->command == 'MOTORON')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    MOTORON
                                                </span>
                                            @elseif($log->command == 'MOTOROFF')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    MOTOROFF
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    STATUS
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($log->voltage)
                                                @if($log->voltage >= 220)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $log->voltage }}V
                                                    </span>
                                                @elseif($log->voltage >= 200)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        {{ $log->voltage }}V
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        {{ $log->voltage }}V
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-zinc-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($log->current)
                                                @if($log->current >= 2)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                        {{ $log->current }}A
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $log->current }}A
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-zinc-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($log->water_level)
                                                @if($log->water_level >= 80)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $log->water_level }}%
                                                    </span>
                                                @elseif($log->water_level >= 50)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        {{ $log->water_level }}%
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        {{ $log->water_level }}%
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-zinc-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($log->run_time && $log->motor_status == 'OFF')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    {{ $log->run_time }}s
                                                </span>
                                            @else
                                                <span class="text-zinc-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <flux:button variant="ghost" size="sm" :href="route('motor-logs.show', $log)">
                                                    View
                                                </flux:button>
                                                <form method="POST" action="{{ route('motor-logs.destroy', $log) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this log?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <flux:button type="submit" variant="ghost" size="sm" class="text-red-600 hover:text-red-900">
                                                        Delete
                                                    </flux:button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4" id="paginationContainer">
                        {{ $motorLogs->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">No motor logs found</h3>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Try adjusting your filters or check back later.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bulk Delete Form -->
    <form id="bulkDeleteForm" method="POST" action="{{ route('motor-logs.bulk-delete') }}" style="display: none;">
        @csrf
        <div id="bulkDeleteInputs"></div>
    </form>

    <script>
        function toggleAll(checkbox) {
            const checkboxes = document.querySelectorAll('.log-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('.log-checkbox');
            checkboxes.forEach(cb => cb.checked = true);
            document.getElementById('selectAllCheckbox').checked = true;
        }

        function bulkDelete() {
            const checkedBoxes = document.querySelectorAll('.log-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Please select at least one log to delete.');
                return;
            }

            if (confirm(`Are you sure you want to delete ${checkedBoxes.length} motor log(s)?`)) {
                const form = document.getElementById('bulkDeleteForm');
                const inputs = document.getElementById('bulkDeleteInputs');
                
                // Clear previous inputs
                inputs.innerHTML = '';
                
                // Add checked IDs
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'log_ids[]';
                    input.value = cb.value;
                    inputs.appendChild(input);
                });
                
                form.submit();
            }
        }

        // Real-time filtering - simple and fast
        let filterTimeout;
        
        function realTimeFilter() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                // Just submit the form - let Laravel handle the filtering
                document.getElementById('filterForm').submit();
            }, 500); // 500ms delay for typing
        }
        
        // Clear filters
        function clearFilters() {
            console.log('Clearing filters...');
            // Redirect to clean URL without any parameters
            window.location.href = '{{ route("motor-logs.index") }}';
        }
    </script>
</x-layouts.app>