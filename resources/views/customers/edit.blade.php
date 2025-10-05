<x-layouts.app :title="__('Edit Customer')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Edit Customer</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $customer->name }} (ID: #{{ $customer->id }})</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:button variant="outline" :href="route('customers.show', $customer)" icon="eye">
                    View Customer
                </flux:button>
                <flux:button variant="outline" :href="route('customers.index')" icon="arrow-left">
                    Back to Customers
                </flux:button>
            </div>
        </div>

        <!-- Customer Edit Form -->
        <div class="flex-1 overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
            <form method="POST" action="{{ route('customers.update', $customer) }}" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Personal Information -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Personal Information</h3>
                            
                            <!-- Name -->
                            <div class="mb-4">
                                <flux:input 
                                    name="name" 
                                    label="Full Name" 
                                    placeholder="Enter customer's full name"
                                    value="{{ old('name', $customer->name) }}"
                                    required
                                    autofocus
                                />
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <flux:input 
                                    name="email" 
                                    type="email"
                                    label="Email Address" 
                                    placeholder="customer@example.com"
                                    value="{{ old('email', $customer->email) }}"
                                    required
                                />
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-4">
                                <flux:input 
                                    name="phone_number" 
                                    label="Phone Number" 
                                    placeholder="+91 98765 43210"
                                    value="{{ old('phone_number', $customer->phone_number) }}"
                                    required
                                />
                                @error('phone_number')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Profile Photo -->
                            <div class="mb-4">
                                <label for="profile_photo" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Profile Photo
                                </label>
                                
                                <!-- Current Photo -->
                                @if($customer->profile_photo)
                                    <div class="mb-3" id="current-photo">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current photo:</p>
                                        <img src="{{ $customer->profile_photo_url }}" alt="Current profile photo" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                                    </div>
                                @endif
                                
                                <!-- Custom File Input -->
                                <div class="relative">
                                    <input 
                                        type="file" 
                                        name="profile_photo" 
                                        id="profile_photo"
                                        accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        onchange="previewImage(this)"
                                    />
                                    <div class="flex items-center justify-center w-full px-4 py-2 border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-lg bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-8 h-8 text-zinc-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                                <span class="font-medium text-blue-600 dark:text-blue-400">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-500">JPEG, PNG, JPG, GIF (Max 2MB)</p>
                                        </div>
                                    </div>
                                </div>
                                
                                @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                
                                <!-- New Image Preview -->
                                <div id="image-preview" class="mt-3 hidden">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">New photo preview:</p>
                                    <img id="preview-img" src="" alt="Preview" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Security -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Contact & Security</h3>
                            
                            <!-- Address -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Address Information</h4>
                                
                                <!-- Address Line 1 -->
                                <div class="mb-4">
                                    <flux:input 
                                        name="address_line_1" 
                                        id="address_line_1"
                                        label="Address Line 1" 
                                        placeholder="Start typing your address or business name..."
                                        value="{{ old('address_line_1', $customer->address_line_1) }}"
                                        required
                                    />
                                    @error('address_line_1')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        💡 Start typing your address or business name for suggestions
                                    </p>
                                </div>

                                <!-- Address Line 2 -->
                                <div class="mb-4">
                                    <flux:input 
                                        name="address_line_2" 
                                        label="Address Line 2 (Optional)" 
                                        placeholder="Apartment, suite, unit, building, floor, etc."
                                        value="{{ old('address_line_2', $customer->address_line_2) }}"
                                    />
                                    @error('address_line_2')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City, State, Postal Code -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">State</label>
                                        <select 
                                            name="state" 
                                            id="state" 
                                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white"
                                            required
                                        >
                                            <option value="">Select State</option>
                                        </select>
                                        @error('state')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">City</label>
                                        <select 
                                            name="city" 
                                            id="city" 
                                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white"
                                            required
                                            disabled
                                        >
                                            <option value="">Select City</option>
                                        </select>
                                        @error('city')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <flux:input 
                                            name="postal_code" 
                                            label="Postal Code" 
                                            placeholder="12345"
                                            value="{{ old('postal_code', $customer->postal_code) }}"
                                            required
                                        />
                                        @error('postal_code')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Country -->
                                <div class="mb-4">
                                    <flux:input 
                                        name="country" 
                                        label="Country" 
                                        placeholder="Country"
                                        value="{{ old('country', $customer->country) }}"
                                        required
                                    />
                                    @error('country')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password (Optional) -->
                            <div class="mb-4">
                                <flux:input 
                                    name="password" 
                                    type="password"
                                    label="New Password" 
                                    placeholder="Leave blank to keep current password"
                                />
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    Only fill this field if you want to change the password
                                </p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <flux:input 
                                    name="password_confirmation" 
                                    type="password"
                                    label="Confirm New Password" 
                                    placeholder="Confirm the new password"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="mt-8 p-4 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg">
                    <h4 class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">Account Information</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Customer ID</p>
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">#{{ $customer->id }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Email Status</p>
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                @if($customer->email_verified_at)
                                    <span class="text-green-600 dark:text-green-400">Verified</span>
                                @else
                                    <span class="text-yellow-600 dark:text-yellow-400">Pending</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Member Since</p>
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $customer->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex items-center justify-end gap-3">
                    <flux:button variant="outline" type="button" :href="route('customers.show', $customer)">
                        Cancel
                    </flux:button>
                    <flux:button variant="primary" type="submit" icon="check">
                        Update Customer
                    </flux:button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mapbox Geocoder Scripts -->
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/mapbox-geocoder.js') }}"></script>
    
    <script>
        // Load states and cities data
        let statesData = null;
        
        // Fetch states and cities data
        fetch('/data/india-states-cities.json')
            .then(response => response.json())
            .then(data => {
                statesData = data;
                populateStates();
                
                // Set current values or old values if form has errors
                const currentState = '{{ old('state', $customer->state) }}';
                const currentCity = '{{ old('city', $customer->city) }}';
                
                if (currentState) {
                    document.getElementById('state').value = currentState;
                    populateCities(currentState);
                    if (currentCity) {
                        setTimeout(() => {
                            document.getElementById('city').value = currentCity;
                        }, 100);
                    }
                }
            })
            .catch(error => {
                console.error('Error loading states data:', error);
            });

        function populateStates() {
            const stateSelect = document.getElementById('state');
            stateSelect.innerHTML = '<option value="">Select State</option>';
            
            if (statesData && statesData.states) {
                statesData.states.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.name;
                    option.textContent = state.name;
                    stateSelect.appendChild(option);
                });
            }
        }

        function populateCities(selectedState) {
            const citySelect = document.getElementById('city');
            citySelect.innerHTML = '<option value="">Select City</option>';
            
            if (selectedState && statesData && statesData.states) {
                const state = statesData.states.find(s => s.name === selectedState);
                if (state && state.cities) {
                    state.cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city;
                        option.textContent = city;
                        citySelect.appendChild(option);
                    });
                    citySelect.disabled = false;
                }
            } else {
                citySelect.disabled = true;
            }
        }

        // State change event listener
        document.getElementById('state').addEventListener('change', function() {
            populateCities(this.value);
        });

        // Image preview function
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const currentPhoto = document.getElementById('current-photo');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                    // Hide current photo when showing preview
                    if (currentPhoto) {
                        currentPhoto.style.display = 'none';
                    }
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
                // Show current photo again when no new file selected
                if (currentPhoto) {
                    currentPhoto.style.display = 'block';
                }
            }
        }
    </script>
</x-layouts.app>
