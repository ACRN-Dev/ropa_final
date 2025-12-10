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
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

<div class="flex min-h-screen">

    <!-- STATIC SIDEBAR -->
    <aside class="w-48 bg-orange-500 text-white p-4 flex flex-col items-center fixed top-0 left-0 h-full overflow-y-auto">

        <!-- Logo -->
        <div class="mb-6">
            <img src="{{ asset('logo.jpg') }}" alt="Logo"
                 class="w-32 h-20 rounded-lg shadow-md border-2 border-white object-cover">
        </div>

        <ul class="w-full">

            <!-- Dashboard -->
            <li class="mb-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-orange-400 
                   {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600' : '' }}">
                    <i data-feather="home" class="w-5 h-5 mr-2"></i> Dashboard
                </a>
            </li>

            <!-- Assessments -->
            <li class="mb-2">
                <a href="{{ route('admin.reviews.index') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-orange-400
                   {{ request()->routeIs('admin.reviews.index') ? 'bg-orange-600' : '' }}">
                    <i data-feather="file-text" class="w-5 h-5 mr-2"></i> Assessments
                </a>
            </li>

            <!-- Analytics -->
            <li class="mb-2">
                <a href="{{ route('admin.analytics') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-orange-400
                   {{ request()->routeIs('admin.analytics') ? 'bg-orange-600' : '' }}">
                    <i data-feather="bar-chart-2" class="w-5 h-5 mr-2"></i> Analytics
                </a>
            </li>

          

            <!-- Manage -->
            <li class="mb-2">
                <a href="{{ route('admin.tickets.index') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-orange-400
                   {{ request()->routeIs('admin.tickets.index') ? 'bg-orange-600' : '' }}">
                    <i data-feather="tag" class="w-4 h-4 mr-2"></i> Manage Tickets
                </a>
            </li>

            <li class="mb-2">
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center py-2 px-3 rounded hover:bg-orange-400
                   {{ request()->routeIs('admin.users.index') ? 'bg-orange-600' : '' }}">
                    <i data-feather="users" class="w-4 h-4 mr-2"></i> Manage Users
                </a>
            </li>

            <!-- Logout -->
            <li class="mb-2 mt-4">
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
    <main class="flex-1 p-6 ml-48">
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
