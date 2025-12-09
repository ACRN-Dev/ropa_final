<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- REQUIRED TO PREVENT 419 ERRORS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- AlpineJS for collapsible menus -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-48 bg-orange-500 text-white p-4 flex flex-col items-center fixed top-0 left-0 h-full overflow-y-auto"
           x-data="{ adminMenuOpen: {{ request()->is('admin/tickets*') ? 'true' : 'false' }} }">

        <!-- Logo -->
        <div class="mb-6">
            <img src="{{ asset('logo.jpg') }}" alt="Logo"
                 class="w-32 h-20 rounded-lg shadow-md border-2 border-white object-cover">
        </div>

        <ul class="w-full">

            <!-- ========================= -->
            <!-- Dashboard -->
            <!-- ========================= -->
            <li class="mb-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-orange-400 
                   {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600' : '' }}">
                    <i data-feather="home" class="w-5 h-5 mr-2"></i> Dashboard
                </a>
            </li>

            <!-- ========================= -->
            <!-- Assessments -->
            <!-- ========================= -->
            <li class="mb-2">
                <a href="{{ route('admin.reviews.index') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-orange-400
                   {{ request()->routeIs('admin.reviews.index') ? 'bg-orange-600' : '' }}">
                    <i data-feather="file-text" class="w-5 h-5 mr-2"></i> Assessments
                </a>
            </li>

            <!-- ========================= -->
            <!-- Analytics -->
            <!-- ========================= -->
            <li class="mb-2">
                <a href="{{ route('admin.analytics') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-orange-400
                   {{ request()->routeIs('admin.analytics') ? 'bg-orange-600' : '' }}">
                    <i data-feather="bar-chart-2" class="w-5 h-5 mr-2"></i> Analytics
                </a>
            </li>

            <!-- ========================= -->
            <!-- ADMIN PANEL HEADER -->
            <!-- ========================= -->
            <div class="text-white/80 uppercase text-xs font-semibold px-3 mt-4 mb-2 flex items-center">
                <i data-feather="shield" class="w-4 h-4 mr-1"></i> Admin Panel
            </div>

            <!-- ========================= -->
            <!-- Collapsible Admin Menu -->
            <!-- ========================= -->
            <button @click="adminMenuOpen = !adminMenuOpen"
                    class="flex items-center justify-between w-full px-3 py-2 text-left hover:bg-orange-400 rounded">
                <div class="flex items-center">
                    <i data-feather="menu" class="w-4 h-4 mr-2"></i>
                    <span>Manage</span>
                </div>

                <i x-show="!adminMenuOpen" data-feather="chevron-down" class="w-4 h-4"></i>
                <i x-show="adminMenuOpen" data-feather="chevron-up" class="w-4 h-4"></i>
            </button>

            <!-- COLLAPSE CONTENT -->
            <ul x-show="adminMenuOpen" x-transition class="pl-6 space-y-1 mt-1">

                <!-- Manage Tickets -->
                <li>
                    <a href="{{ route('admin.tickets.index') }}"
                       class="flex items-center py-2 px-3 rounded hover:bg-orange-400
                       {{ request()->routeIs('admin.tickets.index') ? 'bg-orange-600' : '' }}">
                        <i data-feather="tag" class="w-4 h-4 mr-2"></i>
                        Manage Tickets
                    </a>
                </li>

                <!-- Manage Users -->
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center py-2 px-3 rounded hover:bg-orange-400
                       {{ request()->routeIs('admin.users.index') ? 'bg-orange-600' : '' }}">
                        <i data-feather="users" class="w-4 h-4 mr-2"></i>
                        Manage Users
                    </a>
                </li>

            </ul>

            <!-- ========================= -->
            <!-- Logs -->
            <!-- ========================= -->
            <li class="mb-2 mt-4">
                <a href="{{ route('activities.index') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-orange-400
                   {{ request()->routeIs('activities.index') ? 'bg-orange-600' : '' }}">
                    <i data-feather="clipboard" class="w-5 h-5 mr-2"></i> System Logs
                </a>
            </li>

            <!-- ========================= -->
            <!-- Logout -->
            <!-- ========================= -->
            <li class="mb-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center py-2 px-3 rounded hover:bg-orange-400 text-left">
                        <i data-feather="log-out" class="w-5 h-5 mr-2"></i> Sign Out
                    </button>
                </form>
            </li>

        </ul>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 ml-64">
        @yield('content')
    </main>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        feather.replace();
    });
</script>

</body>
</html>
