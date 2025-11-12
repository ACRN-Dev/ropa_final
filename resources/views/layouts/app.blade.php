<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title', 'WhatsApp Dashboard')</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.png') }}">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-indigo-700 text-white p-4 flex flex-col items-center">
            <!-- Logo -->
            <div class="mb-6">
                <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-24 h-24 rounded-full shadow-lg">
            </div>

            <ul class="w-full">
                <li class="mb-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
                        <i data-feather="home" class="w-5 h-5 mr-2"></i> Dashboard
                    </a>
                </li>

                <li class="mb-2">
                    <a href="{{ route('ropa.index') }}" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
                        <i data-feather="file-text" class="w-5 h-5 mr-2"></i> Submitted
                    </a>
                </li>

                <li class="mb-2">
                    <a href="#" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600 hover:text-white">
                        <i data-feather="alert-circle" class="w-5 h-5 mr-2"></i> Risk Assessment
                    </a>
                </li>

                <li class="mb-2">
                    <a href="#" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
                        <i data-feather="check-square" class="w-5 h-5 mr-2"></i> Reviews
                    </a>
                </li>

                <!-- Account Settings Button -->
<li class="mb-2 w-full">
         <a href="{{ route('profile.edit') }}" class="w-full flex items-center py-2 px-3 rounded hover:bg-indigo-600 text-left">
        <i data-feather="settings" class="w-5 h-5 mr-2"></i> Settings
    </a>
</li>

                <li class="mb-2 w-full">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center py-2 px-3 rounded hover:bg-indigo-600 text-left">
                            <i data-feather="log-out" class="w-5 h-5 mr-2"></i> Logout
                        </button>
                    </form>
                </li>


            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
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
