<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { sidebar: '#071a32' }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

<div class="flex min-h-screen">

    <!-- ── OVERLAY (mobile only) ── -->
    <div id="sidebarOverlay"
         class="fixed inset-0 bg-black/50 z-30 hidden md:hidden"
         onclick="closeSidebar()"></div>

    <!-- ── SIDEBAR ── -->
    <!-- On mobile: hidden off-screen left, slides in via JS -->
    <!-- On desktop (md+): always visible, static in flow -->
    <aside id="sidebar"
           class="fixed top-0 left-0 h-full w-56 bg-sidebar text-white flex flex-col items-center
                  z-40 overflow-y-auto
                  -translate-x-full md:translate-x-0
                  transition-transform duration-300 ease-in-out">

        <!-- Logo -->
        <div class="py-5 px-4 w-full flex justify-center">
            <img src="{{ asset('logo.jpg') }}" alt="Logo"
                 class="w-28 h-18 rounded-lg shadow-md border-2 border-white object-cover">
        </div>

        <!-- Close button — mobile only -->
        <button onclick="closeSidebar()"
                class="absolute top-3 right-3 md:hidden text-white/70 hover:text-white p-1">
            <i data-feather="x" class="w-5 h-5"></i>
        </button>

        <nav class="w-full px-3 pb-6 flex-1 flex flex-col">
            <ul class="w-full space-y-1">

                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center py-2.5 px-3 rounded-lg text-sm font-medium
                              hover:bg-white/10 transition-colors
                              {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white' : 'text-white/80' }}">
                        <i data-feather="home" class="w-4 h-4 mr-3 flex-shrink-0"></i>
                        Home
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.analytics') }}"
                       class="flex items-center py-2.5 px-3 rounded-lg text-sm font-medium
                              hover:bg-white/10 transition-colors
                              {{ request()->routeIs('admin.analytics') ? 'bg-white/15 text-white' : 'text-white/80' }}">
                        <i data-feather="bar-chart-2" class="w-4 h-4 mr-3 flex-shrink-0"></i>
                        Analytics
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center py-2.5 px-3 rounded-lg text-sm font-medium
                              hover:bg-white/10 transition-colors
                              {{ request()->routeIs('admin.users.index') ? 'bg-white/15 text-white' : 'text-white/80' }}">
                        <i data-feather="users" class="w-4 h-4 mr-3 flex-shrink-0"></i>
                        Manage Users
                    </a>
                </li>

            </ul>

            <!-- Logout pinned to bottom -->
            <div class="mt-auto pt-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center py-2.5 px-3 rounded-lg text-sm font-medium
                                   text-white/80 hover:bg-white/10 transition-colors text-left">
                        <i data-feather="log-out" class="w-4 h-4 mr-3 flex-shrink-0"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- ── MAIN CONTENT ── -->
    <!-- ml-0 on mobile (sidebar hidden), ml-56 on desktop -->
    <div class="flex-1 flex flex-col min-w-0 md:ml-56">

        <!-- ── MOBILE TOP BAR ── -->
        <header class="md:hidden sticky top-0 z-20 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between px-4 h-14">
            <!-- Hamburger -->
            <button onclick="openSidebar()" class="text-gray-600 dark:text-gray-300 p-1 -ml-1">
                <i data-feather="menu" class="w-6 h-6"></i>
            </button>
            <span class="font-bold text-base text-gray-800 dark:text-gray-100">Admin Dashboard</span>
            <!-- User avatar placeholder -->
            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
        </header>

        <main class="flex-1 p-3 sm:p-4 lg:p-6 overflow-x-hidden">
            @yield('content')
        </main>
    </div>

</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', () => {
        feather.replace();
    });
</script>

</body>
</html>