<x-layouts.app :title="__('Notifications')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Notifications</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Send and manage push notifications to your IoT customers</p>
            </div>
            <flux:button variant="primary" :href="route('notifications.send')" icon="plus">
                Send Notification
            </flux:button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            <!-- Total Notifications -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h5v14z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Sent</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['total_sent'] ?? 0) }}</p>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Success Rate</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['success_rate'] ?? 0, 1) }}%</p>
                </div>
            </div>

            <!-- Active Customers -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Active Customers</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['active_customers'] ?? 0) }}</p>
                </div>
            </div>

            <!-- Today's Notifications -->
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Today</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['today_sent'] ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Notifications Table -->
        <div class="flex-1 overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
            @if(isset($notifications) && $notifications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Title
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Message
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Recipients
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Sent At
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($notifications as $notification)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $notification->title }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-zinc-900 dark:text-zinc-100 max-w-xs truncate">
                                            {{ $notification->body }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                            @if($notification->user_id)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ $notification->user->name ?? 'Unknown Customer' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    All Customers ({{ $notification->sent_count }})
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($notification->sent)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Sent
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        @if($notification->sent_at)
                                            {{ $notification->sent_at->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-zinc-400">Not sent</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                        <button onclick="viewNotification({{ $notification->id }})" 
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            View
                                        </button>
                                        @if(!$notification->sent)
                                            <button onclick="sendNotification({{ $notification->id }})" 
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                Send
                                            </button>
                                        @endif
                                        <button onclick="deleteNotification({{ $notification->id }})" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                    {{ $notifications->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h5v14z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-2">No notifications sent yet</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Start by sending your first push notification to customers.</p>
                    <flux:button variant="primary" :href="route('notifications.send')" icon="plus">
                        Send First Notification
                    </flux:button>
                </div>
            @endif
        </div>
    </div>

    <script>
        function viewNotification(id) {
            // Implement view notification functionality
            alert('View notification ' + id + ' - Feature to be implemented');
        }

        function sendNotification(id) {
            // Implement send notification functionality
            if (confirm('Are you sure you want to send this notification?')) {
                alert('Sending notification ' + id + ' - Feature to be implemented');
            }
        }

        function deleteNotification(id) {
            // Implement delete notification functionality
            if (confirm('Are you sure you want to delete this notification?')) {
                alert('Deleting notification ' + id + ' - Feature to be implemented');
            }
        }
    </script>
</x-layouts.app>