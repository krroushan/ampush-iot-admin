<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Dashboard</flux:heading>
                <flux:subheading>IoT Motor Control System Overview</flux:subheading>
            </div>
            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                Last updated: {{ now()->format('M d, Y H:i') }}
            </div>
        </div>

        <!-- All Statistics Cards - Responsive Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            <!-- Total Customers -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Customers</p>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($totalCustomers) }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $activeCustomers }} active</p>
                    </div>
                    <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/20">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Motor Logs -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Motor Logs</p>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($totalLogs) }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $todayLogs }} today</p>
                    </div>
                    <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/20">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Unique Devices -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Unique Devices</p>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($uniqueDevices) }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $syncedLogs }} synced</p>
                    </div>
                    <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900/20">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Recent Activity</p>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($recentActivity) }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Last 24 hours</p>
                    </div>
                    <div class="rounded-lg bg-orange-100 p-3 dark:bg-orange-900/20">
                        <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Motor Status -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Motor Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-green-500"></div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">ON</span>
                        </div>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($motorOnLogs) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-red-500"></div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">OFF</span>
                        </div>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($motorOffLogs) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">STATUS</span>
                        </div>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($motorStatusLogs) }}</span>
                    </div>
                </div>
            </div>

            <!-- Voltage Statistics -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Voltage (V)</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Average</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($avgVoltage, 2) }}V</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Maximum</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($maxVoltage, 2) }}V</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Minimum</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($minVoltage, 2) }}V</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Readings</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($totalVoltageReadings) }}</span>
                    </div>
                </div>
            </div>

            <!-- Current Statistics -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Current (A)</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Average</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($avgCurrent, 2) }}A</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Maximum</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($maxCurrent, 2) }}A</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Minimum</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($minCurrent, 2) }}A</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Readings</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($totalCurrentReadings) }}</span>
                    </div>
                </div>
            </div>

            <!-- Water Level Statistics -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Water Level (%)</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Average</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($avgWaterLevel, 1) }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Maximum</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($maxWaterLevel, 1) }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Minimum</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($minWaterLevel, 1) }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Readings</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($totalWaterLevelReadings) }}</span>
                    </div>
                </div>
            </div>

            <!-- Sync Status -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Sync Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-green-500"></div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">Synced</span>
                        </div>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($syncedLogs) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-yellow-500"></div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">Unsynced</span>
                        </div>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($unsyncedLogs) }}</span>
                    </div>
                </div>
            </div>

            <!-- Weekly Activity -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">This Week</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Total Logs</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($thisWeekLogs) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Daily Average</span>
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($thisWeekLogs / 7, 1) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row: Top Customers and Recent Activity -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Top Active Customers -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Top Active Customers</h3>
                    <flux:button variant="outline" size="sm" :href="route('customers.index')">
                        View All
                    </flux:button>
                </div>
                <div class="space-y-3">
                    @forelse($topActiveCustomers as $customer)
                        <div class="flex items-center justify-between rounded-lg border border-zinc-100 p-3 dark:border-zinc-700">
                            <div class="flex items-center gap-3">
                                <img src="{{ $customer->profile_photo_url }}" alt="{{ $customer->name }}" class="h-8 w-8 rounded-full">
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $customer->name }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $customer->phone_number }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($customer->motor_logs_count) }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">logs</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">No customer data available</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Motor Logs -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Recent Motor Logs</h3>
                    <flux:button variant="outline" size="sm" :href="route('motor-logs.index')">
                        View All
                    </flux:button>
                </div>
                <div class="space-y-3">
                    @forelse($recentLogs as $log)
                        <div class="flex items-center justify-between rounded-lg border border-zinc-100 p-3 dark:border-zinc-700">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $log->motor_status === 'ON' ? 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400' : ($log->motor_status === 'OFF' ? 'bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400' : 'bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400') }}">
                                    <span class="text-xs font-semibold">{{ $log->motor_status }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $log->phone_number }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $log->command }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $log->created_at->diffForHumans() }}</p>
                                @if($log->voltage)
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ number_format($log->voltage, 1) }}V</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">No recent motor logs available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
