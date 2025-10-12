<x-layouts.app :title="__('Send Notification')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Send Push Notification</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Send notifications to your IoT customers</p>
            </div>
            <flux:button variant="outline" :href="route('notifications.index')" icon="arrow-left">
                Back to Notifications
            </flux:button>
        </div>

        <!-- Notification Form -->
        <div class="flex-1 overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
            <form id="notificationForm" method="POST" action="{{ route('notifications.store') }}" class="p-6 space-y-6">
                @csrf
                
                <!-- Title -->
                <div>
                    <flux:label for="title" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Notification Title *
                    </flux:label>
                    <flux:input 
                        id="title" 
                        name="title" 
                        required
                        maxlength="255"
                        placeholder="Enter notification title..."
                        class="w-full"
                    />
                </div>

                <!-- Message -->
                <div>
                    <flux:label for="body" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Message *
                    </flux:label>
                    <flux:textarea 
                        id="body" 
                        name="body" 
                        rows="4" 
                        required
                        maxlength="1000"
                        placeholder="Enter your message..."
                        class="w-full"
                    />
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Maximum 1000 characters</p>
                </div>

                <!-- Send To -->
                <div>
                    <flux:label for="send_to" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Send To *
                    </flux:label>
                    <flux:select 
                        id="send_to" 
                        name="send_to" 
                        required
                        class="w-full"
                    >
                        <option value="">Select recipient type</option>
                        <option value="all">All Customers</option>
                        <option value="specific">Specific Customer</option>
                        <option value="phone">By Phone Numbers</option>
                    </flux:select>
                </div>

                <!-- Customer Selection (Hidden by default) -->
                <div id="customerSelect" class="hidden">
                    <flux:label for="customer_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Select Customer
                    </flux:label>
                    <flux:select 
                        id="customer_id" 
                        name="customer_id" 
                        class="w-full"
                    >
                        <option value="">Select a customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone_number }})</option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Phone Numbers (Hidden by default) -->
                <div id="phoneSelect" class="hidden">
                    <flux:label for="phone_numbers" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Phone Numbers (one per line)
                    </flux:label>
                    <flux:textarea 
                        id="phone_numbers" 
                        name="phone_numbers" 
                        rows="3"
                        placeholder="+1234567890&#10;+0987654321"
                        class="w-full"
                    />
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Enter one phone number per line</p>
                </div>

                <!-- Additional Data (Optional) -->
                <div>
                    <flux:label for="data" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Additional Data (JSON - Optional)
                    </flux:label>
                    <flux:textarea 
                        id="data" 
                        name="data" 
                        rows="3"
                        placeholder='{"action": "open_device", "device_id": "123"}'
                        class="w-full"
                    />
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">JSON format for additional data</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button 
                        type="button" 
                        id="previewBtn"
                        variant="outline"
                        icon="eye"
                    >
                        Preview
                    </flux:button>
                    <flux:button 
                        type="submit" 
                        id="sendBtn"
                        variant="primary"
                        icon="paper-airplane"
                    >
                        <span id="sendBtnText">Send Notification</span>
                        <span id="sendBtnLoading" class="hidden">Sending...</span>
                    </flux:button>
                </div>
            </form>
        </div>

        <!-- Preview Modal -->
        <div id="previewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Notification Preview</h3>
                <div class="space-y-3">
                    <div>
                        <flux:label class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Title:</flux:label>
                        <p id="previewTitle" class="text-zinc-900 dark:text-zinc-100"></p>
                    </div>
                    <div>
                        <flux:label class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Message:</flux:label>
                        <p id="previewBody" class="text-zinc-900 dark:text-zinc-100"></p>
                    </div>
                    <div>
                        <flux:label class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Recipients:</flux:label>
                        <p id="previewRecipients" class="text-zinc-900 dark:text-zinc-100"></p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <flux:button 
                        type="button" 
                        id="closePreview"
                        variant="outline"
                    >
                        Close
                    </flux:button>
                    <flux:button 
                        type="button" 
                        id="sendFromPreview"
                        variant="primary"
                    >
                        Send Now
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div id="messageContainer" class="mt-6"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('notificationForm');
            const sendToSelect = document.getElementById('send_to');
            const customerSelect = document.getElementById('customerSelect');
            const phoneSelect = document.getElementById('phoneSelect');
            const customerIdSelect = document.getElementById('customer_id');
            const sendBtn = document.getElementById('sendBtn');
            const sendBtnText = document.getElementById('sendBtnText');
            const sendBtnLoading = document.getElementById('sendBtnLoading');
            const messageContainer = document.getElementById('messageContainer');
            const previewModal = document.getElementById('previewModal');

            // Show/hide customer selection based on send_to option
            sendToSelect.addEventListener('change', function() {
                if (this.value === 'specific') {
                    customerSelect.classList.remove('hidden');
                    phoneSelect.classList.add('hidden');
                } else if (this.value === 'phone') {
                    phoneSelect.classList.remove('hidden');
                    customerSelect.classList.add('hidden');
                } else {
                    customerSelect.classList.add('hidden');
                    phoneSelect.classList.add('hidden');
                }
            });

            // Preview functionality
            document.getElementById('previewBtn').addEventListener('click', function() {
                const title = document.getElementById('title').value;
                const body = document.getElementById('body').value;
                const sendTo = document.getElementById('send_to').value;
                
                if (!title || !body || !sendTo) {
                    showMessage('Please fill in all required fields', 'error');
                    return;
                }

                document.getElementById('previewTitle').textContent = title;
                document.getElementById('previewBody').textContent = body;
                
                let recipients = '';
                if (sendTo === 'all') {
                    recipients = 'All customers';
                } else if (sendTo === 'specific') {
                    const selectedCustomer = customerIdSelect.options[customerIdSelect.selectedIndex];
                    recipients = selectedCustomer.textContent || 'No customer selected';
                } else if (sendTo === 'phone') {
                    const phoneNumbers = document.getElementById('phone_numbers').value;
                    recipients = phoneNumbers ? phoneNumbers.split('\n').length + ' phone numbers' : 'No phone numbers entered';
                }
                
                document.getElementById('previewRecipients').textContent = recipients;
                previewModal.classList.remove('hidden');
            });

            // Close preview
            document.getElementById('closePreview').addEventListener('click', function() {
                previewModal.classList.add('hidden');
            });

            // Send from preview
            document.getElementById('sendFromPreview').addEventListener('click', function() {
                previewModal.classList.add('hidden');
                form.dispatchEvent(new Event('submit'));
            });

            // Form submission with validation
            form.addEventListener('submit', function(e) {
                const sendTo = document.getElementById('send_to').value;
                
                // Validate required fields
                if (sendTo === 'specific') {
                    const customerId = document.getElementById('customer_id').value;
                    if (!customerId) {
                        e.preventDefault();
                        showMessage('Please select a customer', 'error');
                        return;
                    }
                } else if (sendTo === 'phone') {
                    const phoneNumbers = document.getElementById('phone_numbers').value;
                    if (!phoneNumbers.trim()) {
                        e.preventDefault();
                        showMessage('Please enter phone numbers', 'error');
                        return;
                    }
                }

                // Show loading state
                sendBtn.disabled = true;
                sendBtnText.classList.add('hidden');
                sendBtnLoading.classList.remove('hidden');
                
                // Let the form submit naturally
            });

            // Show message function
            function showMessage(message, type) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `p-4 rounded-md mb-4 ${
                    type === 'success' 
                        ? 'bg-green-100 text-green-800 border border-green-200 dark:bg-green-900 dark:text-green-200' 
                        : 'bg-red-100 text-red-800 border border-red-200 dark:bg-red-900 dark:text-red-200'
                }`;
                messageDiv.textContent = message;
                
                messageContainer.innerHTML = '';
                messageContainer.appendChild(messageDiv);
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    messageDiv.remove();
                }, 5000);
            }
        });
    </script>
</x-layouts.app>