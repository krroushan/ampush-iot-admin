<x-layouts.app :title="__('Device Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Device Details</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">View detailed device information and activity</p>
            </div>
            <div class="flex gap-2">
                <flux:button variant="primary" :href="route('devices.edit', $device)" icon="pencil">
                    Edit Device
                </flux:button>
                <form method="POST" action="{{ route('devices.destroy', $device) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this device? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <flux:button variant="danger" type="submit" icon="trash">
                        Delete Device
                    </flux:button>
                </form>
                <flux:button variant="outline" :href="route('devices.index')" icon="arrow-left">
                    Back to Devices
                </flux:button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Device Information -->
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-6">Device Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div>
                                <flux:label>Device ID</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100 font-mono">#{{ $device->id }}</p>
                            </div>

                            <div>
                                <flux:label>Device Name</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $device->device_name }}</p>
                            </div>

                            <div>
                                <flux:label>SMS Number</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100 font-mono">{{ $device->sms_number }}</p>
                            </div>
                        </div>

                        <!-- Status & Activity -->
                        <div class="space-y-4">
                            <div>
                                <flux:label>Status</flux:label>
                                <div class="mt-1">
                                    @if($device->is_active)
                                        <flux:badge variant="success" class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Active
                                        </flux:badge>
                                    @else
                                        <flux:badge variant="danger" class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Inactive
                                        </flux:badge>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <flux:label>Last Activity</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $device->last_activity }}</p>
                            </div>

                            <div>
                                <flux:label>Created At</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $device->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($device->description)
                        <div class="mt-6 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                            <flux:label>Description</flux:label>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $device->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Device Statistics -->
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-6">Device Statistics</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <!-- Total Logs -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Logs</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['total_logs']) }}</p>
                        </div>

                        <!-- Motor ON -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Motor ON</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['on_logs']) }}</p>
                        </div>

                        <!-- Motor OFF -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Motor OFF</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['off_logs']) }}</p>
                        </div>

                        <!-- Last 24h -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Last 24h</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['last_24h']) }}</p>
                        </div>

                        <!-- Avg Voltage -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Avg Voltage</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stats['avg_voltage'] }}V</p>
                        </div>

                        <!-- Avg Current -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Avg Current</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stats['avg_current'] }}A</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Motor Logs -->
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-6">Recent Motor Logs</h3>
                    
                    @if($device->motorLogs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="text-xs text-zinc-500 dark:text-zinc-400 border-b border-zinc-200 dark:border-zinc-700">
                                    <tr>
                                        <th class="pb-3 text-left">Timestamp</th>
                                        <th class="pb-3 text-left">Status</th>
                                        <th class="pb-3 text-left">Command</th>
                                        <th class="pb-3 text-right">Voltage</th>
                                        <th class="pb-3 text-right">Current</th>
                                        <th class="pb-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                                    @foreach($device->motorLogs->take(10) as $log)
                                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30">
                                            <td class="py-3">{{ $log->formatted_timestamp }}</td>
                                            <td class="py-3">
                                                <span class="inline-flex px-2 text-xs font-semibold rounded-full {{ $log->motor_status == 'ON' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $log->motor_status }}
                                                </span>
                                            </td>
                                            <td class="py-3 font-mono text-xs">{{ $log->command }}</td>
                                            <td class="py-3 text-right">{{ $log->voltage ? $log->voltage . 'V' : 'N/A' }}</td>
                                            <td class="py-3 text-right">{{ $log->current ? $log->current . 'A' : 'N/A' }}</td>
                                            <td class="py-3 text-right">
                                                <a href="{{ route('motor-logs.show', $log) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($device->motorLogs->count() > 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('motor-logs.index', ['search' => $device->sms_number]) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 text-sm">
                                    View all motor logs →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">No motor logs yet</h3>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">This device hasn't generated any motor logs yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-6">Customer Information</h3>
                    
                    @if($device->user)
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                @if($device->user->profile_photo)
                                    <img src="{{ $device->user->profile_photo_url }}" alt="{{ $device->user->name }}" class="w-12 h-12 rounded-full">
                                @else
                                    <div class="w-12 h-12 bg-zinc-300 dark:bg-zinc-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                            {{ $device->user->initials() }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $device->user->name }}</p>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $device->user->email }}</p>
                                </div>
                            </div>

                            <div class="border-t dark:border-zinc-700 pt-4 space-y-3">
                                <div>
                                    <flux:label>Phone Number</flux:label>
                                    <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $device->user->phone_number }}</p>
                                </div>

                                <div>
                                    <flux:label>Address</flux:label>
                                    <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                                        {{ $device->user->address_line_1 }}<br>
                                        @if($device->user->address_line_2)
                                            {{ $device->user->address_line_2 }}<br>
                                        @endif
                                        {{ $device->user->city }}, {{ $device->user->state }}<br>
                                        {{ $device->user->postal_code }}
                                    </p>
                                </div>
                            </div>

                            <div class="border-t dark:border-zinc-700 pt-4">
                                <flux:button variant="primary" :href="route('customers.show', $device->user)" class="w-full">
                                    View Customer Profile
                                </flux:button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Not assigned</h3>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">This device is not assigned to any customer yet.</p>
                            <div class="mt-4">
                                <flux:button variant="primary" :href="route('devices.edit', $device)" icon="plus" class="w-full">
                                    Assign Customer
                                </flux:button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

