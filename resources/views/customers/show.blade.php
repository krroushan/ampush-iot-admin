<x-layouts.app :title="__('Customer Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $customer->name }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Customer ID: #{{ $customer->id }}</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:button variant="outline" :href="route('customers.edit', $customer)" icon="pencil">
                    Edit Customer
                </flux:button>
                <flux:button variant="outline" :href="route('customers.index')" icon="arrow-left">
                    Back to Customers
                </flux:button>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Info Card -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-6">
                            @if($customer->profile_photo)
                                <img src="{{ $customer->profile_photo_url }}" alt="{{ $customer->name }}" class="h-16 w-16 rounded-full object-cover">
                            @else
                                <div class="h-16 w-16 rounded-full bg-zinc-200 dark:bg-zinc-600 flex items-center justify-center">
                                    <span class="text-2xl font-medium text-zinc-700 dark:text-zinc-300">
                                        {{ $customer->initials() }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $customer->name }}</h2>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Customer since {{ $customer->created_at->format('F Y') }}</p>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-3">Contact Information</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <svg class="h-5 w-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm text-zinc-900 dark:text-zinc-100">{{ $customer->email }}</p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Email Address</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="h-5 w-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm text-zinc-900 dark:text-zinc-100">{{ $customer->phone_number }}</p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Phone Number</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <svg class="h-5 w-5 text-zinc-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <div>
                                            <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                                @if($customer->address_line_1)
                                                    <p>{{ $customer->address_line_1 }}</p>
                                                    @if($customer->address_line_2)
                                                        <p>{{ $customer->address_line_2 }}</p>
                                                    @endif
                                                    <p>{{ $customer->city }}, {{ $customer->state }} {{ $customer->postal_code }}</p>
                                                    @if($customer->country && $customer->country !== 'India')
                                                        <p>{{ $customer->country }}</p>
                                                    @endif
                                                @else
                                                    {{ $customer->address }}
                                                @endif
                                            </div>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Address</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div>
                                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-3">Account Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-zinc-900 dark:text-zinc-100">Customer</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Role</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($customer->email_verified_at)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                                    Pending
                                                </span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Email Status</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-zinc-900 dark:text-zinc-100">{{ $customer->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Member Since</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-zinc-900 dark:text-zinc-100">{{ $customer->updated_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Last Updated</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <flux:button variant="outline" class="w-full justify-start" icon="pencil">
                                Edit Customer
                            </flux:button>
                            <flux:button variant="outline" class="w-full justify-start" icon="device-phone-mobile">
                                View Devices
                            </flux:button>
                            <flux:button variant="outline" class="w-full justify-start" icon="envelope">
                                Send Email
                            </flux:button>
                            <flux:button variant="outline" class="w-full justify-start" icon="bell">
                                View Alerts
                            </flux:button>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Statistics</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">Total Devices</span>
                                <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $totalDevices }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">Active Devices</span>
                                <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $activeDevices }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">Total Alerts</span>
                                <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">0</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">Last Activity</span>
                                <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $lastActivity ? \Carbon\Carbon::parse($lastActivity)->diffForHumans() : 'Never' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device Management Section -->
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">IoT Devices</h3>
                    <flux:modal.trigger name="assign-device-modal">
                        <flux:button variant="primary" size="sm" icon="plus">
                            Assign Device
                        </flux:button>
                    </flux:modal.trigger>
                </div>
                
                @if($customer->devices->isEmpty())
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-zinc-400 mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">No devices assigned</h4>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                            This customer doesn't have any IoT devices assigned yet.
                        </p>
                        <flux:modal.trigger name="assign-device-modal">
                            <flux:button variant="primary" icon="plus">
                                Assign First Device
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Device
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        SMS Number
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Last Activity
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($customer->devices as $device)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    {{ $device->device_name }}
                                                </div>
                                                @if($device->description)
                                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                                        {{ $device->description }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            {{ $device->sms_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($device->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $device->last_activity_at ? $device->last_activity_at->diffForHumans() : 'Never' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <flux:button variant="ghost" size="sm" icon="eye" :href="route('devices.show', $device)">
                                                View
                                            </flux:button>
                                            <flux:button variant="ghost" size="sm" icon="pencil" :href="route('devices.edit', $device)">
                                                Edit
                                            </flux:button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Assign Device Modal -->
    <flux:modal name="assign-device-modal" class="max-w-md">
        <form action="{{ route('customers.assign-device', $customer) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Assign Device</flux:heading>
                    <flux:subheading>Select an available device to assign to this customer</flux:subheading>
                </div>

                <flux:select name="device_id" label="Select Device" placeholder="Choose a device..." required>
                    @php
                        $availableDevices = \App\Models\Device::whereNull('user_id')
                            ->orWhere('user_id', $customer->id)
                            ->get();
                    @endphp
                    
                    @forelse($availableDevices as $device)
                        <option value="{{ $device->id }}" {{ $device->user_id == $customer->id ? 'selected' : '' }}>
                            {{ $device->device_name }} ({{ $device->sms_number }})
                            @if($device->user_id == $customer->id)
                                - Already Assigned
                            @endif
                        </option>
                    @empty
                        <option value="" disabled>No available devices</option>
                    @endforelse
                </flux:select>

                <div class="flex gap-2">
                    <flux:button type="submit" variant="primary">Assign Device</flux:button>
                    <flux:modal.close>
                        <flux:button type="button" variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </form>
    </flux:modal>
</x-layouts.app>
