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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">


<!-- Risk Distribution Card -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-bold mb-4 flex items-center text-orange-500">
        <i data-feather="bar-chart-2" class="w-6 h-6 mr-2"></i>
        Risk Distribution
    </h2>

    @if (($critical + $high + $medium + $low) === 0)
        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
            <i data-feather="info" class="w-6 h-6 mx-auto mb-2"></i>
            <p class="font-semibold">No risk data available.</p>
        </div>
    @else
        <div class="space-y-4">

            <!-- Critical -->
            <div class="flex justify-between items-center">
                <span class="flex items-center gap-2 font-semibold text-red-700">
                    <i data-feather="alert-triangle" class="w-4 h-4"></i>
                    Critical Risk
                </span>
                <span class="font-bold text-red-700">{{ $critical }}</span>
            </div>

            <!-- High -->
            <div class="flex justify-between items-center">
                <span class="flex items-center gap-2 font-semibold text-red-600">
                    <i data-feather="alert-circle" class="w-4 h-4"></i>
                    High Risk
                </span>
                <span class="font-bold text-red-600">{{ $high }}</span>
            </div>

            <!-- Medium -->
            <div class="flex justify-between items-center">
                <span class="flex items-center gap-2 font-semibold text-yellow-600">
                    <i data-feather="alert-octagon" class="w-4 h-4"></i>
                    Medium Risk
                </span>
                <span class="font-bold text-yellow-600">{{ $medium }}</span>
            </div>

            <!-- Low -->
            <div class="flex justify-between items-center">
                <span class="flex items-center gap-2 font-semibold text-green-600">
                    <i data-feather="check-circle" class="w-4 h-4"></i>
                    Low Risk
                </span>
                <span class="font-bold text-green-600">{{ $low }}</span>
            </div>

        </div>
        @endif
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

    <!-- All ROPA Submissions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i data-feather="list" class="w-6 h-6 mr-2"></i>
                All ROPA Submissions
            </h2>
            <div class="flex items-center gap-3">
                <!-- Filter Dropdown -->
                <select id="statusFilter" 
                        class="px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-700 text-sm font-semibold focus:ring-2 focus:ring-orange-300 focus:outline-none">
                    <option value="all">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Reviewed">Reviewed</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
                
                <!-- Export Button -->
                <button onclick="exportTable()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-orange-600 rounded-lg hover:bg-orange-50 transition-all font-semibold text-sm">
                    <i data-feather="download" class="w-4 h-4"></i>
                    Export
                </button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
            <div class="relative">
                <input type="text" 
                       id="tableSearch"
                       placeholder="Search by organisation, department, user, or status..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-gray-200">
                <i data-feather="search" class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"></i>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="ropaTable">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Organisation
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Department
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Submitted By
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Date Submitted
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Risk Level
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $allRopas = \App\Models\Ropa::with('user')
                            ->latest('created_at')
                            ->get();
                    @endphp

                    @forelse($allRopas as $ropa)
                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors duration-150" data-status="{{ $ropa->status ?? 'Pending' }}">
                            <!-- ID -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-bold text-gray-900 dark:text-gray-100">#{{ $ropa->id }}</span>
                            </td>

                            <!-- Organisation -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                                        <i data-feather="briefcase" class="w-4 h-4 text-white"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $ropa->organisation_name ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Department -->
                            <td class="px-4 py-3">
                                @php
                                    $dept = $ropa->department ?? $ropa->other_department ?? 'N/A';
                                    $deptColor = match(true) {
                                        str_contains(strtolower($dept), 'software') || str_contains(strtolower($dept), 'developer') => 'bg-purple-100 text-purple-800 border-purple-200',
                                        str_contains(strtolower($dept), 'hr') || str_contains(strtolower($dept), 'human') => 'bg-green-100 text-green-800 border-green-200',
                                        str_contains(strtolower($dept), 'finance') || str_contains(strtolower($dept), 'accounting') => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        str_contains(strtolower($dept), 'marketing') || str_contains(strtolower($dept), 'sales') => 'bg-pink-100 text-pink-800 border-pink-200',
                                        default => 'bg-blue-100 text-blue-800 border-blue-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $deptColor }}">
                                    {{ $dept }}
                                </span>
                            </td>

                            <!-- Submitted By -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-700">
                                            {{ strtoupper(substr($ropa->user->name ?? 'U', 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $ropa->user->name ?? 'Unknown' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($ropa->user->email ?? '', 25) }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Date Submitted -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $ropa->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $ropa->created_at->format('h:i A') }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3">
                                @php
                                    $status = $ropa->status ?? 'Pending';
                                    $statusColor = match($status) {
                                        'Reviewed', 'Approved' => 'bg-green-100 text-green-800 border-green-300',
                                        'Rejected' => 'bg-red-100 text-red-800 border-red-300',
                                        'Pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        default => 'bg-gray-100 text-gray-800 border-gray-300'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border-2 {{ $statusColor }}">
                                    {{ $status }}
                                </span>
                            </td>

                            <!-- Risk Level -->
                            <!-- Risk Level -->
<td class="px-4 py-3">
    @php
        $risk = strtolower($ropa->risk_level ?? 'n/a');

        $riskColor = match ($risk) {
            'critical' => 'bg-purple-100 text-purple-800 border-purple-300',
            'high'     => 'bg-red-100 text-red-800 border-red-300',
            'medium'   => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'low'      => 'bg-green-100 text-green-800 border-green-300',
            default    => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    @endphp

    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $riskColor }}">
        {{ ucfirst($risk) }}
    </span>
</td>


                            <!-- Actions -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('ropa.show', $ropa->id) }}" 
                                       class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all"
                                       title="View Details">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                    </a>
                                    
                                    <a href="{{ route('ropa.edit', $ropa->id) }}" 
                                       class="p-2 text-orange-600 hover:text-orange-800 hover:bg-orange-50 rounded-lg transition-all"
                                       title="Edit">
                                        <i data-feather="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    
                                    <form action="{{ route('ropa.destroy', $ropa->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this ROPA record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all"
                                                title="Delete">
                                            <i data-feather="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i data-feather="inbox" class="w-16 h-16 text-gray-400"></i>
                                    <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">No ROPA records found</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">ROPA submissions will appear here</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer with Pagination Info -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing <span class="font-bold text-gray-900 dark:text-gray-100" id="visibleCount">{{ $allRopas->count() }}</span> of 
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $allRopas->count() }}</span> records
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-semibold">Total: {{ \App\Models\Ropa::count() }}</span> | 
                    <span class="font-semibold text-yellow-600">Pending: {{ \App\Models\Ropa::where('status', 'Pending')->count() }}</span> | 
                    <span class="font-semibold text-green-600">Reviewed: {{ \App\Models\Ropa::where('status', 'Reviewed')->count() }}</span>
                </div>
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

    // Table Search Functionality
    document.getElementById('tableSearch').addEventListener('input', function() {
        const filter = this.value.toLowerCase().trim();
        const table = document.getElementById('ropaTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');
        
        let visibleCount = 0;
        
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length === 1) continue; // Skip empty state row
            
            let found = false;
            
            // Search in relevant columns
            for (let j = 1; j < cells.length - 1; j++) {
                const cellText = cells[j].textContent || cells[j].innerText;
                if (cellText.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
            
            if (found || filter === '') {
                rows[i].style.display = '';
                visibleCount++;
            } else {
                rows[i].style.display = 'none';
            }
        }
        
        // Update visible count
        document.getElementById('visibleCount').textContent = visibleCount;
    });

    // Status Filter Functionality
    document.getElementById('statusFilter').addEventListener('change', function() {
        const selectedStatus = this.value.toLowerCase();
        const table = document.getElementById('ropaTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');
        
        let visibleCount = 0;
        
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            if (row.cells.length === 1) continue; // Skip empty state row
            
            const rowStatus = row.getAttribute('data-status').toLowerCase();
            
            if (selectedStatus === 'all' || rowStatus === selectedStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
        
        // Update visible count
        document.getElementById('visibleCount').textContent = visibleCount;
        
        // Re-initialize feather icons
        feather.replace();
    });

    // Export Table Function
    function exportTable() {
        alert('Export functionality - Integrate with your preferred export library (e.g., Excel, CSV)');
        // You can implement actual export functionality here
        // Example: Use libraries like SheetJS, jsPDF, or server-side export
    }

    // Initialize feather icons after page load
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>

<style>
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #ea580c;
        border-radius: 10px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #c2410c;
    }
</style>

@endsection