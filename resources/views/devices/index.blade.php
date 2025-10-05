<x-layouts.app :title="__('Devices')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Devices</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Manage IoT devices and their assignments</p>
            </div>
            <flux:button variant="primary" :href="route('devices.create')" icon="plus">
                Add Device
            </flux:button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            <!-- Total Devices -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Devices</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['total_devices']) }}</p>
                </div>
            </div>

            <!-- Active Devices -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Active</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['active_devices']) }}</p>
                </div>
            </div>

            <!-- Inactive Devices -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Inactive</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['inactive_devices']) }}</p>
                </div>
            </div>

            <!-- Assigned Devices -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Assigned</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['assigned_devices']) }}</p>
                </div>
            </div>

            <!-- Unassigned Devices -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Unassigned</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['unassigned_devices']) }}</p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
            <form method="GET" action="{{ route('devices.index') }}" id="filterForm" class="space-y-4">
                <!-- First Row: Search and Status/Assignment -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                            Search
                        </label>
                        <input 
                            type="text" 
                            name="search" 
                            id="search" 
                            value="{{ request('search') }}"
                            placeholder="Device name, SMS number, or customer..." 
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                            Status
                        </label>
                        <select 
                            name="status" 
                            id="status" 
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Assignment Filter -->
                    <div>
                        <label for="assignment" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                            Assignment
                        </label>
                        <select 
                            name="assignment" 
                            id="assignment" 
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">All Devices</option>
                            <option value="assigned" {{ request('assignment') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="unassigned" {{ request('assignment') === 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                        </select>
                    </div>
                </div>

                <!-- Second Row: Customer Filter and Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Customer Filter -->
                    <div class="lg:col-span-2">
                        <label for="customer_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                            Customer
                        </label>
                        <select 
                            name="customer_id" 
                            id="customer_id" 
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->phone_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="lg:col-span-2 flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Filters
                        </button>
                        <a href="{{ route('devices.index') }}" class="flex-1 px-4 py-2 bg-zinc-200 dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 rounded-lg hover:bg-zinc-300 dark:hover:bg-zinc-600 transition-colors text-center">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Devices Table -->
        <div class="flex-1 overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
            @if($devices->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Device Info
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    SMS Number
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Assigned To
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider hidden lg:table-cell">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider hidden xl:table-cell">
                                    Last Activity
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider hidden xl:table-cell">
                                    Created At
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($devices as $device)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    {{ $device->device_name }}
                                                </div>
                                                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    ID: #{{ $device->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-mono text-zinc-900 dark:text-zinc-100">
                                            {{ $device->sms_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($device->user)
                                            <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                                {{ $device->user->name }}
                                            </div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $device->user->phone_number }}
                                            </div>
                                        @else
                                            <span class="inline-flex rounded-full bg-orange-100 px-2 text-xs font-semibold leading-5 text-orange-800">
                                                Unassigned
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $device->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $device->status_text }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400 hidden xl:table-cell">
                                        {{ $device->last_activity }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400 hidden xl:table-cell">
                                        {{ $device->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('devices.show', $device) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            View
                                        </a>
                                        <a href="{{ route('devices.edit', $device) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('devices.destroy', $device) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this device?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                    {{ $devices->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="h-12 w-12 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-zinc-500 dark:text-zinc-400 text-lg mb-2">No devices found</p>
                    <p class="text-zinc-400 dark:text-zinc-500 text-sm mb-4">Get started by adding your first device</p>
                    <flux:button variant="primary" :href="route('devices.create')" icon="plus">
                        Add Device
                    </flux:button>
                </div>
            @endif
        </div>
    </div>

    <!-- Real-time filtering script -->
    <script>
        let debounceTimer;
        
        function debounce(func, delay) {
            return function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(func, delay);
            };
        }

        // Real-time filtering for search
        document.getElementById('search').addEventListener('input', debounce(function() {
            document.getElementById('filterForm').submit();
        }, 500));

        // Immediate filtering for dropdowns
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('assignment').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('customer_id').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
</x-layouts.app>

