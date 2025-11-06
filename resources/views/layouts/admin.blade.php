<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

<div class="flex min-h-screen">
    <aside class="w-64 bg-indigo-900 text-gray-200 p-4 flex flex-col items-center">
        <div class="mb-6">
            <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-24 h-24 rounded-full shadow-lg">
        </div>

        <ul class="w-full">
            <li class="mb-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
                    <i data-feather="home" class="w-5 h-5 mr-2"></i> Dashboard
                </a>
            </li>

            <li class="mb-2">
              <a href="{{ route('admin.ropa.index') }}" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
                <i data-feather="file-text" class="w-5 h-5 mr-2"></i>  Assessments
            </a>
            </li>


<li class="mb-2">
    <a href="{{ route('reviews.index') }}" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
        <i data-feather="message-square" class="w-5 h-5 mr-2"></i> Reviews
    </a>
</li>




            <li class="mb-2">
                <a href="#" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
                    <i data-feather="bar-chart-2" class="w-5 h-5 mr-2"></i> Reports
                </a>
            </li>

 <li class="mb-2">
    <a href="{{ route('risk_scores.index') }}" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
        <i data-feather="shield" class="w-5 h-5 mr-2"></i> Scoring Management
    </a>
</li>




<li class="mb-2">
    <a href="{{ route('admin.users.index') }}" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
        <i data-feather="users" class="w-5 h-5 mr-2"></i>Manage Users
    </a>
</li>


<li class="mb-2">
    <a href="" class="flex items-center py-2 px-3 rounded hover:bg-indigo-600">
        <i data-feather="clipboard" class="w-5 h-5 mr-2"></i> System Logs
    </a>
</li>


<li class="mb-2">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center py-2 px-3 rounded hover:bg-indigo-600 text-left">
            <i data-feather="log-out" class="w-5 h-5 mr-2"></i> Sign Out
        </button>
    </form>
</li>


        </ul>
    </aside>

    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>

<script> feather.replace() </script>
</body>
</html>
