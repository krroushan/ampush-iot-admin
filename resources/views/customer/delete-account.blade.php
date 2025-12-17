<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Account - Ampush Motor Controller | AMPUSHWORKS ENTERPRISES PRIVATE LIMITED</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 dark:bg-zinc-900 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
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
            <h2 class="mt-6 text-center text-3xl font-extrabold text-zinc-900 dark:text-zinc-100">
                Delete Your Account
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-600 dark:text-zinc-400">
                This action cannot be undone. All your data will be permanently deleted.
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('customer.account.delete') }}">
            @csrf

            @if ($errors->any())
                <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                There were errors with your submission
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="phone_number" class="sr-only">Phone Number</label>
                    <input id="phone_number" name="phone_number" type="text" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-500 dark:placeholder-zinc-400 text-zinc-900 dark:text-zinc-100 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-white dark:bg-zinc-800" 
                           placeholder="Phone Number" value="{{ old('phone_number') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-500 dark:placeholder-zinc-400 text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-white dark:bg-zinc-800" 
                           placeholder="Password">
                </div>
                <div>
                    <label for="confirmation" class="sr-only">Type: DELETE MY ACCOUNT</label>
                    <input id="confirmation" name="confirmation" type="text" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-500 dark:placeholder-zinc-400 text-zinc-900 dark:text-zinc-100 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-white dark:bg-zinc-800" 
                           placeholder="Type: DELETE MY ACCOUNT" value="{{ old('confirmation') }}">
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                        Please type <strong>DELETE MY ACCOUNT</strong> to confirm
                    </p>
                </div>
            </div>

            <div class="rounded-md bg-yellow-50 dark:bg-yellow-900/20 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            Warning
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <p>Deleting your account will:</p>
                            <ul class="list-disc pl-5 mt-1 space-y-1">
                                <li>Permanently delete all your personal information</li>
                                <li>Unassign all devices from your account</li>
                                <li>Delete all your notifications</li>
                                <li>Revoke all your access tokens</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" 
                        style="background-color: #dc2626; color: white; width: 100%; padding: 12px 16px; border: none; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background-color 0.2s;"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-base font-semibold rounded-md text-white bg-red-600 hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-sm hover:shadow-md"
                        onmouseover="this.style.backgroundColor='#b91c1c'"
                        onmouseout="this.style.backgroundColor='#dc2626'">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px; margin-right: 8px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete My Account
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Cancel and go back to login
                </a>
            </div>
        </form>

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

