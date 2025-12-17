<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Deleted - Ampush Motor Controller | AMPUSHWORKS ENTERPRISES PRIVATE LIMITED</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 dark:bg-zinc-900 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Company/App Branding Header -->
        <div class="text-center border-b border-zinc-300 dark:border-zinc-700 pb-4">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-1">
                Ampush Motor Controller
            </h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                AMPUSHWORKS ENTERPRISES PRIVATE LIMITED
            </p>
        </div>

        <div>
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900/20">
                <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-zinc-900 dark:text-zinc-100">
                Account Successfully Deleted
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-600 dark:text-zinc-400">
                Your account and all associated data have been permanently deleted from our system.
            </p>
        </div>

        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                        Deletion Complete
                    </h3>
                    <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                        <p>All your personal information, devices, and notifications have been removed from our database.</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <a href="{{ route('login') }}" 
               class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Go to Login Page
            </a>
        </div>

        <div class="text-center">
            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                Thank you for using our service. If you have any questions, please contact support.
            </p>
        </div>

        <!-- Company Footer -->
        <div class="text-center border-t border-zinc-300 dark:border-zinc-700 pt-4 mt-6">
            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                <strong class="text-zinc-700 dark:text-zinc-300">Ampush Motor Controller</strong><br>
                Developed by <strong class="text-zinc-700 dark:text-zinc-300">AMPUSHWORKS ENTERPRISES PRIVATE LIMITED</strong><br>
                <a href="https://ampushworks.com" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline text-xs">https://ampushworks.com</a>
            </p>
        </div>
    </div>
</body>
</html>

