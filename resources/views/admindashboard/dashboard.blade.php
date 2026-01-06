@extends('layouts.admin')

@section('title', 'Admin | Dashboard')

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

            <!-- Dropdown -->
            <div id="userDropdown"
                 class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-lg py-2 z-50">
                
                <a href="#"
                   class="flex items-center space-x-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-orange-100 dark:hover:bg-gray-700 transition">
                    <i data-feather="user" class="w-4 h-4 text-orange-500"></i>
                    <span>Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center space-x-2 w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-orange-100 dark:hover:bg-gray-700 transition">
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
        <p class="text-gray-600 dark:text-gray-400 mt-2">Monitor compliance, risk levels, and recent activity.</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <!-- Total ROPA Records -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border-l-4 border-orange-500 shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center justify-between">
            <div>
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total ROPA Records</span>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Ropa::count() }}</div>
            </div>
            <i data-feather="database" class="w-8 h-8 text-orange-500"></i>
        </div>

        <!-- Pending Reviews -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border-l-4 border-yellow-500 shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center justify-between">
            <div>
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">Pending Reviews</span>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Ropa::where('status','Pending')->count() }}</div>
            </div>
            <i data-feather="clock" class="w-8 h-8 text-yellow-500"></i>
        </div>

        <!-- Overdue Reviews -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border-l-4 border-red-600 shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center justify-between">
            <div>
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">Overdue Reviews</span>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $overdueReviews ?? 0 }}</div>
            </div>
            <i data-feather="alert-triangle" class="w-8 h-8 text-red-600"></i>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border-l-4 border-green-600 shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center justify-between">
            <div>
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">Tasks Completed</span>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $tasksCompleted ?? 0 }}</div>
            </div>
            <i data-feather="check-circle" class="w-8 h-8 text-green-600"></i>
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

            @php
                $riskMap = [
                    'Critical Risk' => $criticalRisk ?? null,
                    'High Risk'     => $highRisk ?? null,
                    'Medium Risk'   => $mediumRisk ?? null,
                    'Low Risk'      => $lowRisk ?? null,
                ];

                $colors = [
                    'Critical Risk' => 'bg-purple-700',
                    'High Risk'     => 'bg-red-600',
                    'Medium Risk'   => 'bg-yellow-500',
                    'Low Risk'      => 'bg-green-600',
                ];
            @endphp

            <div class="space-y-6">
                @foreach($riskMap as $risk => $value)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex items-center gap-2">

                                @if($risk === 'Critical Risk')
                                    <i data-feather="alert-circle" class="w-4 h-4 text-purple-700"></i>
                                @elseif($risk === 'High Risk')
                                    <i data-feather="alert-octagon" class="w-4 h-4 text-red-600"></i>
                                @elseif($risk === 'Medium Risk')
                                    <i data-feather="alert-triangle" class="w-4 h-4 text-yellow-500"></i>
                                @else
                                    <i data-feather="check-circle" class="w-4 h-4 text-green-600"></i>
                                @endif

                                <span class="font-semibold">{{ $risk }}</span>
                            </div>

                            <span class="text-gray-700 dark:text-gray-300">
                                @if($value === null)
                                    <span class="text-gray-400 italic">No data</span>
                                @else
                                    {{ $value }}%
                                @endif
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            @if($value !== null)
                            <div class="h-3 {{ $colors[$risk] }} transition-all duration-500"
                                 style="width: {{ $value }}%">
                            </div>
                            @endif
                        </div>
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
                    <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg flex justify-between items-center">
                        <div>

                            <!-- Organisation -->
                            <span class="font-semibold text-green-600">
                                {{ $ropa->organisation_name }}
                            </span>

                            <p class="text-sm text-gray-600 dark:text-gray-300">

                                <!-- Department in orange-500 -->
                                <span class="text-orange-500">
                                    {{ $ropa->department ?? $ropa->other_department ?? '—' }}
                                </span>

                                • {{ $ropa->user->name ?? '—' }} —

                                <span class="font-semibold 
                                    @if(($ropa->status ?? 'Pending') == 'Pending') text-red-600 @endif">
                                    {{ $ropa->status ?? 'Pending' }}
                                </span>

                            </p>
                        </div>

                        <span class="text-sm text-gray-500 dark:text-gray-300">
                            {{ $ropa->date_submitted ? $ropa->date_submitted->format('d M Y, h:i A') : '—' }}
                        </span>
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
