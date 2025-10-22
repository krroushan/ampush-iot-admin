<x-layouts.app :title="__('Edit Device')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Edit Device</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Update device information</p>
            </div>
            <flux:button variant="ghost" :href="route('devices.index')" icon="arrow-left">
                Back to Devices
            </flux:button>
        </div>

        <!-- Form -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
            <form method="POST" action="{{ route('devices.update', $device) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Device Name -->
                <div>
                    <label for="device_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Device Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="device_name" 
                        id="device_name" 
                        value="{{ old('device_name', $device->device_name) }}"
                        required
                        class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 @error('device_name') border-red-500 @enderror"
                        placeholder="e.g., Water Pump Controller #1"
                    >
                    @error('device_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SMS Number -->
                <div>
                    <label for="sms_number" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        SMS Number <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="sms_number" 
                        id="sms_number" 
                        value="{{ old('sms_number', $device->sms_number) }}"
                        required
                        class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 @error('sms_number') border-red-500 @enderror"
                        placeholder="e.g., +919876543210"
                    >
                    @error('sms_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                        The phone number used for SMS communication with the device
                    </p>
                </div>

                <!-- Assign to Customer -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Assign to Customer
                    </label>
                    <select 
                        name="user_id" 
                        id="user_id" 
                        class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror"
                    >
                        <option value="">-- Select Customer (Optional) --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('user_id', $device->user_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->phone_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Description
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4"
                        class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                        placeholder="Additional details about this device..."
                    >{{ old('description', $device->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Active -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        id="is_active" 
                        value="1"
                        {{ old('is_active', $device->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-zinc-300 rounded"
                    >
                    <label for="is_active" class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300">
                        Device is active
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button variant="ghost" :href="route('devices.index')" type="button">
                        Cancel
                    </flux:button>
                    <flux:button variant="primary" type="submit">
                        Update Device
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

