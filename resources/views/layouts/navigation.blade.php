<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/5176.png') }}" class="block h-16 w-auto" alt="Rangers Logo">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (Auth::user()->role === 'super')

                        <!-- Content Management Dropdown -->
                        <div class="group mt-5">
                            <x-nav-link href="#" class="cursor-pointer">
                                {{ __('Manage Content & Orders') }}
                            </x-nav-link>
                            <div class="absolute hidden group-hover:block bg-white border rounded-md mt-1 shadow-lg">
                                <a href="{{ route('blogs.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    {{ __('Blogs') }}
                                </a>
                                <a href="{{ route('products.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    {{ __('Products') }}
                                </a>
                                <a href="{{ route('orders.product') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    {{ __('Product Orders') }}
                                </a>
                            </div>
                        </div>

                        <!-- Orders and Tickets Dropdown -->
                        <div class="group mt-5">
                            <x-nav-link href="#" class="cursor-pointer">
                                {{ __('Orders & Tickets') }}
                            </x-nav-link>
                            <div class="absolute hidden group-hover:block bg-white border rounded-md mt-1 shadow-lg">
                                <a href="{{ route('tickets.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    {{ __('Matchday Tickets') }}
                                </a>
                                <a href="{{ route('museum.tickets') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    {{ __('Museum Tickets') }}
                                </a>
                                <a href="{{ route('season.ticket') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    {{ __('Season Tickets') }}
                                </a>
                            </div>
                        </div>

                        <!-- Admins -->
                        <x-nav-link :href="route('admins.index')" :active="request()->routeIs('admins.index')">
                            {{ __('Admins') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('buy-ticket')" :active="request()->routeIs('buy-ticket')">
                            {{ __('Buy Tickets') }}
                        </x-nav-link>
                    @endif

                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }} ({{ strtoupper(Auth::user()->role) }})</div>

                            <div class="ms-1">
                                <i class="fa-solid fa-caret-down fa-beat-fade"></i>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Blogs') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Orders') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Tickets') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Admins') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
