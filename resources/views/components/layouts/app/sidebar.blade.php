<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('customers.index')" :current="request()->routeIs('customers.*')" wire:navigate>{{ __('Customers') }}</flux:navlist.item>
                    <flux:navlist.item icon="device-phone-mobile" :href="route('devices.index')" :current="request()->routeIs('devices.*')" wire:navigate>{{ __('Devices') }}</flux:navlist.item>
                    <flux:navlist.item icon="chart-bar" :href="route('motor-logs.index')" :current="request()->routeIs('motor-logs.*')" wire:navigate>{{ __('Motor Logs') }}</flux:navlist.item>
                    <flux:navlist.item icon="bell" :href="route('notifications.index')" :current="request()->routeIs('notifications.*')" wire:navigate>{{ __('Notifications') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        
        <!-- Theme persistence system -->
        <script>
            // Theme management system
            window.themeManager = {
                savedTheme: localStorage.getItem('theme') || 'dark',
                
                applyTheme: function(theme) {
                    const html = document.documentElement;
                    console.log('Applying theme:', theme);
                    
                    if (theme === 'dark') {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                    
                    this.savedTheme = theme;
                    localStorage.setItem('theme', theme);
                },
                
                toggleTheme: function() {
                    const newTheme = this.savedTheme === 'dark' ? 'light' : 'dark';
                    this.applyTheme(newTheme);
                    console.log('Toggled to:', newTheme);
                }
            };
            
            // Ensure theme persists after Flux loads
            (function() {
                const html = document.documentElement;
                const savedTheme = localStorage.getItem('theme');
                
                // Force apply saved theme after Flux might have changed it
                if (savedTheme) {
                    console.log('Re-applying saved theme after Flux:', savedTheme);
                    window.themeManager.applyTheme(savedTheme);
                }
                
                // Watch for changes to the html class and restore our theme if needed
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            const currentClasses = mutation.target.className;
                            const savedTheme = localStorage.getItem('theme');
                            
                            console.log('Class changed to:', currentClasses, 'Saved theme:', savedTheme);
                            
                            // If Flux changed the theme and it doesn't match our saved theme, restore it
                            if (savedTheme === 'light' && currentClasses.includes('dark')) {
                                console.log('Flux forced dark mode, restoring light mode');
                                window.themeManager.applyTheme('light');
                            } else if (savedTheme === 'dark' && !currentClasses.includes('dark')) {
                                console.log('Flux removed dark mode, restoring dark mode');
                                window.themeManager.applyTheme('dark');
                            }
                        }
                    });
                });
                
                observer.observe(html, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            })();
        </script>
    </body>
</html>
