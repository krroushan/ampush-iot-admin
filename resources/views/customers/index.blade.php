<x-layouts.app :title="__('Customers')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Customers</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Manage your IoT device customers</p>
            </div>
            <flux:button variant="primary" :href="route('customers.create')" icon="plus">
                Add Customer
            </flux:button>
        </div>

        <!-- Search and Filters -->
        <div class="flex items-center gap-4">
            <form method="GET" action="{{ route('customers.search') }}" class="flex-1">
                <flux:input 
                    name="search" 
                    placeholder="Search customers by name, email, or phone..." 
                    value="{{ request('search') }}"
                    icon="magnifying-glass"
                    class="w-full"
                />
            </form>
            <flux:button variant="outline" icon="funnel">
                Filters
            </flux:button>
        </div>

        <!-- Customers Table -->
        <div class="flex-1 overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
            @if($customers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Address
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Joined
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($customers as $customer)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($customer->profile_photo)
                                                    <img src="{{ $customer->profile_photo_url }}" alt="{{ $customer->name }}" class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-zinc-200 dark:bg-zinc-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                                            {{ $customer->initials() }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    {{ $customer->name }}
                                                </div>
                                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                                    ID: #{{ $customer->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-zinc-900 dark:text-zinc-100">{{ $customer->email }}</div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $customer->phone_number }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-zinc-900 dark:text-zinc-100 max-w-xs">
                                            @if($customer->address_line_1)
                                                <div class="truncate">{{ $customer->address_line_1 }}</div>
                                                @if($customer->address_line_2)
                                                    <div class="truncate text-zinc-500 dark:text-zinc-400">{{ $customer->address_line_2 }}</div>
                                                @endif
                                                <div class="truncate text-zinc-500 dark:text-zinc-400">{{ $customer->city }}, {{ $customer->state }}</div>
                                            @else
                                                <div class="truncate">{{ $customer->address }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $customer->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <flux:button variant="ghost" size="sm" :href="route('customers.show', $customer)" icon="eye">
                                                View
                                            </flux:button>
                                            <flux:button variant="ghost" size="sm" :href="route('customers.edit', $customer)" icon="pencil">
                                                Edit
                                            </flux:button>
                                            <flux:button 
                                                variant="ghost" 
                                                size="sm" 
                                                icon="trash" 
                                                class="text-red-600 hover:text-red-700"
                                                onclick="confirmDelete('{{ $customer->id }}', '{{ $customer->name }}')"
                                            >
                                                Delete
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                    {{ $customers->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="text-center">
                        <div class="mx-auto h-12 w-12 text-zinc-400">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">No customers found</h3>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            Get started by adding your first customer.
                        </p>
                        <div class="mt-6">
                            <flux:button variant="primary" :href="route('customers.create')" icon="plus">
                                Add Customer
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Delete Customer</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">This action cannot be undone.</p>
                </div>
            </div>
            <p class="text-sm text-zinc-700 dark:text-zinc-300 mb-6">
                Are you sure you want to delete <span id="customerName" class="font-medium"></span>? 
                This will permanently remove the customer and all associated data.
            </p>
            <div class="flex justify-end gap-3">
                <flux:button variant="outline" onclick="closeDeleteModal()">Cancel</flux:button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <flux:button variant="danger" type="submit">Delete Customer</flux:button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(customerId, customerName) {
            document.getElementById('customerName').textContent = customerName;
            document.getElementById('deleteForm').action = `/customers/${customerId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</x-layouts.app>
