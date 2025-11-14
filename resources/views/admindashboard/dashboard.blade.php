@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Top Navigation -->
<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 rounded-xl shadow-sm mb-6">
    <div class="container mx-auto px-4 flex justify-between items-center h-16">
        
        <!-- Left: Logo / Brand -->
        <div class="flex items-center space-x-3">
            <i data-feather="grid" class="w-6 h-6 text-orange-500"></i>
            <span class="font-bold text-xl text-gray-800 dark:text-gray-100">Admin Dashboard</span>
        </div>

        <!-- Right: User Dropdown -->
        <div class="relative">
            <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                <i data-feather="user" class="w-6 h-6 text-gray-600 dark:text-gray-300"></i>
                <span class="text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                <i data-feather="chevron-down" class="w-4 h-4 text-gray-600 dark:text-gray-300"></i>
            </button>

            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-lg py-2 z-50">
                <a href="#" class="flex items-center space-x-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i data-feather="user" class="w-4 h-4 text-orange-500"></i>
                    <span>Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-2 w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i data-feather="log-out" class="w-4 h-4 text-red-600"></i>
                        <span>Logout</span>
                    </button>
                </form>

            </div>
        </div>
    </div>
</nav>

<!-- Main Dashboard -->
<div class="container mx-auto py-6">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">ROPA Admin Overview</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Monitor compliance, risk levels, and record updates.</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <!-- Total ROPA Records -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-center mb-2">
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total ROPA Records</span>
                <i data-feather="database" class="w-6 h-6 text-orange-500"></i>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                {{ \App\Models\Ropa::count() }}
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-center mb-2">
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">Pending Reviews</span>
                <i data-feather="clock" class="w-6 h-6 text-yellow-500"></i>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                {{ \App\Models\Ropa::where('status', 'Pending')->count() }}
            </div>
        </div>

        <!-- Overdue Reviews -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-center mb-2">
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">Overdue Reviews</span>
                <i data-feather="alert-triangle" class="w-6 h-6 text-red-600"></i>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $overdueReviews ?? 0 }}</div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-center mb-2">
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">Tasks Completed</span>
                <i data-feather="check-circle" class="w-6 h-6 text-green-600"></i>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $tasksCompleted ?? 0 }}</div>
        </div>
    </div>

    <!-- Risk & Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Risk Distribution -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <i data-feather="bar-chart-2" class="w-6 h-6 mr-2 text-orange-500"></i> 
                Risk Distribution
            </h2>

            <div class="space-y-4">
                @foreach(['High Risk', 'Medium Risk', 'Low Risk'] as $risk)
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">{{ $risk }}</span>
                        <span class="text-gray-700 dark:text-gray-300">
                            {{ ${str_replace(' ', '', strtolower($risk))} ?? 0 }}%
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <i data-feather="activity" class="w-6 h-6 mr-2 text-orange-500"></i> 
                Recent Submissions
            </h2>

            <div class="space-y-4">

                @php
                    $recentRopas = \App\Models\Ropa::with('user')
                        ->latest('date_submitted')
                        ->take(5)
                        ->get();
                @endphp

                @forelse($recentRopas as $ropa)
                    <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-semibold">{{ $ropa->organisation_name }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">
                                {{ $ropa->date_submitted ? $ropa->date_submitted->format('d M Y, h:i A') : '—' }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $ropa->department_name ?? $ropa->other_department ?? '—' }} • 
                            {{ $ropa->user->name ?? '—' }} — 
                            <span class="font-semibold">{{ $ropa->status ?? 'Pending' }}</span>
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-300">No recent activities.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    feather.replace();

    // Dropdown toggle
    document.getElementById('userMenuButton').addEventListener('click', function () {
        document.getElementById('userDropdown').classList.toggle('hidden');
    });
</script>
@endsection
