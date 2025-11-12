@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Top Navigation -->
<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
    <div class="container mx-auto px-4 flex justify-between items-center h-16">
        <!-- Left: Logo / Brand -->
        <div class="flex items-center space-x-3">
            <i data-feather="layers" class="w-6 h-6 text-indigo-600"></i>
            <span class="font-bold text-xl text-gray-800 dark:text-gray-100">Dashboard</span>
        </div>

        <!-- Right: Notification + User Dropdown -->
        <div class="flex items-center space-x-6">
            <!-- Notification -->
            <div class="relative">
                <button id="notificationButton" class="relative focus:outline-none">
                    <i data-feather="bell" class="w-6 h-6 text-gray-600 dark:text-gray-300"></i>
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5">3</span>
                </button>
                <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 shadow-lg rounded-lg py-2 z-50">
                    <p class="px-4 py-2 font-semibold border-b dark:border-gray-700 text-gray-700 dark:text-gray-300">Notifications</p>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">New ROPA submitted</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">Your ROPA approved</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">Reminder: Update pending records</a>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative">
                <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                    <i data-feather="user" class="w-6 h-6 text-gray-600 dark:text-gray-300"></i>
                    <span class="text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                    <i data-feather="chevron-down" class="w-4 h-4 text-gray-600 dark:text-gray-300"></i>
                </button>
                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-lg py-2 z-50">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <i data-feather="user" class="w-4 h-4 text-indigo-500"></i>
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i data-feather="log-out" class="w-4 h-4 text-red-500"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Main Dashboard -->
<div class="container mx-auto py-6">

    <!-- Title -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold">ROPA Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Overview of data processing activities and compliance status</p>
    </div>

    <!-- 4 Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        @php
            $userRopaCount = \App\Models\Ropa::where('user_id', Auth::id())->count();
            $pendingRopaCount = \App\Models\Ropa::where('user_id', Auth::id())->where('status', \App\Models\Ropa::STATUS_PENDING)->count();
            $reviewedRopaCount = \App\Models\Ropa::where('user_id', Auth::id())->where('status', \App\Models\Ropa::STATUS_REVIEWED)->count();
        @endphp

        <!-- Total ROPA Records -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition flex items-center space-x-4">
            <i data-feather="folder" class="w-10 h-10 text-indigo-600"></i>
            <div>
                <div class="text-lg font-semibold">Total ROPA Records</div>
                <div class="mt-2 text-3xl font-bold">{{ $userRopaCount }}</div>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition flex items-center space-x-4">
            <i data-feather="clock" class="w-10 h-10 text-yellow-500"></i>
            <div>
                <div class="text-lg font-semibold">Pending Reviews</div>
                <div class="mt-2 text-3xl font-bold">{{ $pendingRopaCount }}</div>
            </div>
        </div>

        <!-- Overdue Reviews -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition flex items-center space-x-4">
            <i data-feather="alert-circle" class="w-10 h-10 text-red-500"></i>
            <div>
                <div class="text-lg font-semibold">Overdue Reviews</div>
                <div class="mt-2 text-3xl font-bold">0</div>
            </div>
        </div>

        <!-- Tasks Completed -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition flex items-center space-x-4">
            <i data-feather="check-circle" class="w-10 h-10 text-green-600"></i>
            <div>
                <div class="text-lg font-semibold">Tasks Completed</div>
                <div class="mt-2 text-3xl font-bold">{{ $reviewedRopaCount }}</div>
            </div>
        </div>
    </div>

    <!-- Two Cards Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Risk Distribution Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-4 flex items-center text-gray-900 dark:text-gray-100">
                <i data-feather="bar-chart-2" class="w-6 h-6 mr-2 text-indigo-600"></i> Risk Distribution
            </h2>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                        <span class="font-semibold text-red-600">High Risk</span>
                    </div>
                    <div><span class="font-bold text-red-600">0</span> (0%)</div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                        <span class="font-semibold text-yellow-600">Medium Risk</span>
                    </div>
                    <div><span class="font-bold text-yellow-600">1</span> (8%)</div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        <span class="font-semibold text-green-600">Low Risk</span>
                    </div>
                    <div><span class="font-bold text-green-600">2</span> (17%)</div>
                </div>
            </div>
        </div>

        <!-- Recent ROPA Submissions -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <i data-feather="activity" class="w-6 h-6 mr-2 text-indigo-600"></i> Recent ROPA Submissions
            </h2>

            <div class="space-y-4">
                @php
                    $recentRopas = \App\Models\Ropa::where('user_id', Auth::id())
                        ->orderBy('date_submitted', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @forelse ($recentRopas as $ropa)
                    <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <div class="flex justify-between mb-1">
                            <span class="font-semibold">{{ $ropa->organisation_name ?? 'Unnamed Submission' }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300 flex items-center gap-1">
                                <i data-feather="clock" class="w-4 h-4 text-indigo-500"></i>
                                {{ $ropa->date_submitted 
                                    ? $ropa->date_submitted->format('d/m/Y • h:i A') 
                                    : 'N/A' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $ropa->department_name ?? 'Unknown Dept' }} • 
                            {{ $ropa->user->name ?? 'N/A' }} — 
                            <span class="font-semibold {{ $ropa->status === 'reviewed' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($ropa->status ?? 'Pending') }}
                            </span>
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                        No recent ROPA submissions found.
                    </p>
                @endforelse
            </div>
        </div>

    </div>
</div>

<!-- Feather Icons -->
<script src="https://unpkg.com/feather-icons"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();

    document.getElementById('userMenuButton').addEventListener('click', () => {
        document.getElementById('userDropdown').classList.toggle('hidden');
    });

    document.getElementById('notificationButton').addEventListener('click', () => {
        document.getElementById('notificationDropdown').classList.toggle('hidden');
    });
});
</script>

@endsection
