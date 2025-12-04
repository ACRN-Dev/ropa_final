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
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    <div class="flex min-h-screen">
        <!-- Sidebar (FIXED) -->
        <aside class="w-64 bg-orange-600 text-white p-4 flex flex-col items-center fixed left-0 top-0 h-screen overflow-y-auto">

            <!-- Logo -->
            <div class="mb-6">
                <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-32 h-20 rounded-lg shadow-md border-2 border-white dark:border-gray-800 object-cover">
            </div>

            <ul class="w-full">
                <li class="mb-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-3 rounded hover:bg-orange-500 transition-colors duration-200">
                        <i data-feather="home" class="w-5 h-5 mr-2"></i> Dashboard
                    </a>
                </li>

                <li class="mb-2">
                    <a href="{{ route('ropa.index') }}" class="flex items-center py-2 px-3 rounded hover:bg-orange-500 transition-colors duration-200">
                        <i data-feather="file-text" class="w-5 h-5 mr-2"></i> Add Process
                    </a>
                </li>

                <li class="mb-2">
                    <a href="" class="flex items-center py-2 px-3 rounded hover:bg-orange-500 transition-colors duration-200">
                        <i data-feather="check-square" class="w-5 h-5 mr-2"></i> Reviews
                    </a>
                </li>

                <li class="mb-2">
                    <a href="{{ route('ticket.index') }}" class="flex items-center py-2 px-3 rounded hover:bg-orange-500 transition-colors duration-200 w-full text-left">
                        <i data-feather="tag" class="w-5 h-5 mr-2"></i> Add Ticket
                    </a>
                </li>

                <!-- Account Settings Button -->
                <li class="mb-2 w-full">
                    <a href="{{ route('profile.edit') }}" class="w-full flex items-center py-2 px-3 rounded hover:bg-orange-500 transition-colors duration-200 text-left">
                        <i data-feather="settings" class="w-5 h-5 mr-2"></i> Settings
                    </a>
                </li>

                <li class="mb-2 w-full">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center py-2 px-3 rounded hover:bg-orange-500 transition-colors duration-200 text-left">
                            <i data-feather="log-out" class="w-5 h-5 mr-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>


            
    <!-- Help Button at the Bottom -->
    <div class="mt-auto w-full">
        <a href="" class="w-full flex items-center justify-center py-3 px-3 mb-2 bg-orange-500 hover:bg-orange-1000 rounded-lg shadow">
            <i data-feather="help-circle" class="w-5 h-5 mr-2"></i> Help
        </a>
    </div>


</aside>

        <!-- Main Content (shifted right so sidebar does not overlap) -->
        <!-- ★ FIXED HERE: px-0 py-6 (no horizontal padding, vertical padding kept) -->
        <main class="flex-1 px-0 py-6 overflow-auto ml-64">
            @yield('content')
        </main>
    </div>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace({ 'aria-hidden': 'true' });
        });
    </script>
</body>
</html>
