<x-layouts.app :title="__('Motor Log Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Motor Log Details</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">View detailed motor log information</p>
            </div>
            <flux:button variant="outline" :href="route('motor-logs.index')" icon="arrow-left">
                Back to Logs
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Motor Log Information -->
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-6">Motor Log Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div>
                                <flux:label>Log ID</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100 font-mono">#{{ $motorLog->id }}</p>
                            </div>

                            <div>
                                <flux:label>Timestamp</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $motorLog->formatted_timestamp }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $motorLog->time_ago }}</p>
                            </div>

                            <div>
                                <flux:label>Phone Number</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $motorLog->phone_number }}</p>
                            </div>

                            <div>
                                <flux:label>Command</flux:label>
                                <flux:badge variant="info" class="font-mono">{{ $motorLog->command }}</flux:badge>
                            </div>
                        </div>

                        <!-- Motor Status -->
                        <div class="space-y-4">
                            <div>
                                <flux:label>Motor Status</flux:label>
                                @if($motorLog->motor_status == 'ON')
                                    <flux:badge variant="success" class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        ON
                                    </flux:badge>
                                @elseif($motorLog->motor_status == 'OFF')
                                    <flux:badge variant="danger" class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        OFF
                                    </flux:badge>
                                @else
                                    <flux:badge variant="info" class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        STATUS
                                    </flux:badge>
                                @endif
                            </div>

                            <div>
                                <flux:label>Sync Status</flux:label>
                                @if($motorLog->is_synced)
                                    <flux:badge variant="success" class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Synced
                                    </flux:badge>
                                @else
                                    <flux:badge variant="warning" class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        Unsynced
                                    </flux:badge>
                                @endif
                            </div>

                            <div>
                                <flux:label>Mode</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $motorLog->mode ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <flux:label>Clock</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $motorLog->clock ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <flux:label>Run Time</flux:label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                                    @if($motorLog->run_time && $motorLog->motor_status == 'OFF')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            {{ $motorLog->run_time }} seconds
                                        </span>
                                    @else
                                        <span class="text-zinc-400">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sensor Readings -->
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-6">Sensor Readings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Voltage -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Voltage</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                {{ $motorLog->voltage ? $motorLog->voltage . 'V' : 'N/A' }}
                            </p>
                        </div>

                        <!-- Current -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Current</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                {{ $motorLog->current ? $motorLog->current . 'A' : 'N/A' }}
                            </p>
                        </div>

                        <!-- Water Level -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Water Level</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                {{ $motorLog->water_level ? $motorLog->water_level . '%' : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-6">Customer Information</h3>
                    
                    @if($customer)
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                @if($customer->profile_photo)
                                    <img src="{{ $customer->profile_photo_url }}" alt="{{ $customer->name }}" class="w-12 h-12 rounded-full">
                                @else
                                    <div class="w-12 h-12 bg-zinc-300 dark:bg-zinc-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                            {{ substr($customer->name, 0, 2) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $customer->name }}</p>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $customer->email }}</p>
                                </div>
                            </div>

                            <div class="border-t dark:border-zinc-700 pt-4 space-y-3">
                                <div>
                                    <flux:label>Phone Number</flux:label>
                                    <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $customer->phone_number }}</p>
                                </div>

                                <div>
                                    <flux:label>Address</flux:label>
                                    <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                                        {{ $customer->address_line_1 }}<br>
                                        @if($customer->address_line_2)
                                            {{ $customer->address_line_2 }}<br>
                                        @endif
                                        {{ $customer->city }}, {{ $customer->state }}<br>
                                        {{ $customer->postal_code }}
                                        @if($customer->country && $customer->country !== 'India')
                                            , {{ $customer->country }}
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <flux:label>Member Since</flux:label>
                                    <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $customer->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="border-t dark:border-zinc-700 pt-4">
                                <flux:button variant="primary" :href="route('customers.show', $customer)" class="w-full">
                                    View Customer Profile
                                </flux:button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">No customer found</h3>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">No customer is associated with this phone number.</p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Actions</h3>
                    
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('motor-logs.destroy', $motorLog) }}" onsubmit="return confirm('Are you sure you want to delete this motor log? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="danger" class="w-full">
                                Delete Motor Log
                            </flux:button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>