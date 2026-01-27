<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title', 'ROPA')</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.png') }}">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Sidebar Color -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: '#071a32'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-48 bg-sidebar text-white p-4 flex flex-col items-center fixed left-0 top-0 h-screen overflow-y-auto z-30">

        <!-- Logo -->
        <div class="mb-6">
            <img src="{{ asset('logo.jpg') }}" alt="Logo"
                class="w-32 h-20 rounded-lg shadow-md border-2 border-white dark:border-gray-800 object-cover">
        </div>

        <ul class="w-full">

            <li class="mb-2">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-sidebar/80 transition-colors duration-200">
                    <i data-feather="home" class="w-5 h-5 mr-2"></i> Dashboard
                </a>
            </li>

            <li class="mb-2">
                <a href="{{ route('ropa.index') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-sidebar/80 transition-colors duration-200">
                    <i data-feather="file-text" class="w-5 h-5 mr-2"></i> Add Process 
                </a>
            </li>
<!-- 
            <li class="mb-2">
                <a href="{{ route('ticket.index') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-sidebar/80 transition-colors duration-200 w-full text-left">
                    <i data-feather="tag" class="w-5 h-5 mr-2"></i> Add Ticket
                </a>
            </li> -->

            <li class="mb-2">
                <a href="{{ route('risk-register.index') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-sidebar/80 transition-colors duration-200 w-full text-left">
                    <i data-feather="alert-triangle" class="w-5 h-5 mr-2"></i> Risk Register
                </a>
            </li>

            
<li class="mb-2">
    <a href="{{ route('activities.index') }}"
       class="flex items-center py-2 px-3 rounded hover:bg-sidebar/80 transition-colors duration-200 w-full text-left">
        <i data-feather="activity" class="w-5 h-5 mr-2"></i>
        My Logs
    </a>
</li>



            <!-- Settings -->
            <li class="mb-2 w-full">
                <a href="{{ route('profile.edit') }}"
                   class="w-full flex items-center py-2 px-3 rounded hover:bg-sidebar/80 transition-colors duration-200 text-left">
                    <i data-feather="settings" class="w-5 h-5 mr-2"></i> Settings
                </a>
            </li>

            <!-- Logout -->
            <li class="mb-2 w-full">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center py-2 px-3 rounded hover:bg-sidebar/80 transition-colors duration-200 text-left">
                        <i data-feather="log-out" class="w-5 h-5 mr-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>

        <!-- Help button at bottom -->
        <div class="mt-auto w-full">
            <a href="{{ route('help') }}"
               class="w-full flex items-center justify-center py-3 px-3 mb-2 bg-sidebar hover:bg-sidebar/90 rounded-lg shadow">
                <i data-feather="help-circle" class="w-5 h-5 mr-2"></i> Help
            </a>
        </div>

    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 ml-48 flex flex-col">
        
        <!-- Top Navigation Bar -->
        <nav class="bg-white dark:bg-gray-800 shadow-md fixed top-0 right-0 left-48 z-20">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    
                    <!-- Page Title / Breadcrumb -->
                    <div class="flex items-center gap-3">
                        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>

                    <!-- Right Side Items -->
                    <div class="flex items-center gap-4">
                        
                        <!-- Search Bar (Optional) -->
                        <div class="hidden md:block">
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Search..." 
                                       class="w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                                <i data-feather="search" class="w-4 h-4 absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative">
                                <i data-feather="bell" class="w-5 h-5 text-gray-600 dark:text-gray-300"></i>
                                <!-- Notification badge -->
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-orange-600 flex items-center justify-center text-white font-semibold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ auth()->user()->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ auth()->user()->department ?? 'No Department' }}
                                    </p>
                                </div>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-600 dark:text-gray-300"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2"
                                 style="display: none;">
                                
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i data-feather="user" class="w-4 h-4"></i>
                                    Profile
                                </a>
                                
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i data-feather="settings" class="w-4 h-4"></i>
                                    Settings
                                </a>

                                <hr class="my-2 border-gray-200 dark:border-gray-700">
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 text-left">
                                        <i data-feather="log-out" class="w-4 h-4"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 px-0 py-6 overflow-auto mt-20">
            @yield('content')
        </main>
    </div>

</div>

<!-- Alpine.js for dropdown -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Feather Icons -->
<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace({ 'aria-hidden': 'true' });
        
        // Re-replace feather icons when Alpine initializes
        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => feather.replace({ 'aria-hidden': 'true' }), 100);
        });
    });
</script>

</body>
</html>