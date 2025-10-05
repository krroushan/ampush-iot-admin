<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <div class="space-y-6">
            <!-- Custom Theme Toggle -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Theme</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Choose your preferred theme</p>
                    </div>
                    
                    <!-- Theme Toggle Button -->
                    <button 
                        id="theme-toggle" 
                        type="button" 
                        class="inline-flex items-center justify-center rounded-lg border border-zinc-200 bg-white p-3 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-200 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600 dark:hover:text-zinc-300 dark:focus:ring-zinc-700 transition-colors"
                        aria-label="Toggle theme"
                    >
                        <!-- Sun icon (show when dark mode) -->
                        <svg id="sun-icon" class="h-6 w-6 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                        </svg>
                        <!-- Moon icon (show when light mode) -->
                        <svg id="moon-icon" class="hidden h-6 w-6 dark:block" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Current Theme Display -->
                <div class="mt-4">
                    <div class="flex items-center space-x-2 text-sm text-zinc-600 dark:text-zinc-400">
                        <span>Current theme:</span>
                        <span id="current-theme-display" class="font-medium text-zinc-900 dark:text-zinc-100">Dark</span>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Theme Toggle Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const themeToggle = document.getElementById('theme-toggle');
                const currentThemeDisplay = document.getElementById('current-theme-display');
                
                if (!themeToggle) {
                    console.error('Theme toggle button not found');
                    return;
                }
                
                // Update current theme display
                function updateThemeDisplay() {
                    const currentTheme = localStorage.getItem('theme') || 'dark';
                    if (currentThemeDisplay) {
                        currentThemeDisplay.textContent = currentTheme === 'dark' ? 'Dark' : 'Light';
                    }
                }
                
                // Initial theme display update
                updateThemeDisplay();
                
                // Theme toggle event listener
                themeToggle.addEventListener('click', function() {
                    if (window.themeManager) {
                        window.themeManager.toggleTheme();
                        updateThemeDisplay();
                    }
                });
                
                console.log('Settings theme toggle ready');
            });
        </script>
    </x-settings.layout>
</section>
