@extends('layouts.admin')

@section('title', 'Admin')

@section('content')

<!-- Top Navigation -->
<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 rounded-xl shadow-sm mb-6">
    <div class="container mx-auto px-4 flex justify-between items-center h-16">
        <div class="flex items-center space-x-3">
            <i data-feather="grid" class="w-6 h-6 text-orange-500"></i>
            <span class="font-bold text-xl text-gray-800 dark:text-gray-100">Admin Dashboard</span>
        </div>
        <div class="relative">
            <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                <i data-feather="user" class="w-6 h-6 text-gray-600 dark:text-gray-300"></i>
                <span class="text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                <i data-feather="chevron-down" class="w-4 h-4 text-gray-600 dark:text-gray-300"></i>
            </button>
            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-lg py-2 z-50">
                <a href="#" class="flex items-center space-x-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-orange-100 dark:hover:bg-gray-700 transition">
                    <i data-feather="user" class="w-4 h-4 text-orange-500"></i>
                    <span>Profile</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-2 w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-orange-100 dark:hover:bg-gray-700 transition">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border-l-4 border-orange-500 shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total ROPA Records</span>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ \App\Models\Ropa::count() }}</div>
            </div>
            <i data-feather="database" class="w-8 h-8 text-orange-500"></i>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border-l-4 border-yellow-500 shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pending Reviews</span>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ \App\Models\Ropa::where('status','Pending')->count() }}</div>
            </div>
            <i data-feather="clock" class="w-8 h-8 text-yellow-500"></i>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border-l-4 border-red-600 shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Overdue Reviews</span>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $overdueReviews ?? 0 }}</div>
            </div>
            <i data-feather="alert-triangle" class="w-8 h-8 text-red-600"></i>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border-l-4 border-green-600 shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tasks Completed</span>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $tasksCompleted ?? 0 }}</div>
            </div>
            <i data-feather="check-circle" class="w-8 h-8 text-green-600"></i>
        </div>
    </div>

    <!-- ── DEPARTMENT BREAKDOWN ── -->
    @php
        $departments = [
            'Data Protection'                  => ['icon' => 'shield',        'color' => 'indigo'],
            'IT'                               => ['icon' => 'monitor',       'color' => 'blue'],
            'HR'                               => ['icon' => 'users',         'color' => 'green'],
            'Community Engagement'             => ['icon' => 'heart',         'color' => 'pink'],
            'Data & Biostatisitcs'             => ['icon' => 'bar-chart-2',   'color' => 'purple'],
            'Laboratory'                       => ['icon' => 'activity',      'color' => 'teal'],
            'Pharmacy'                         => ['icon' => 'package',       'color' => 'cyan'],
            'Finance & Administration'         => ['icon' => 'dollar-sign',   'color' => 'yellow'],
            'Clinical Operations (ClinOps)'    => ['icon' => 'clipboard',     'color' => 'orange'],
            'Project Management'               => ['icon' => 'briefcase',     'color' => 'red'],
            'Legal & Compliance'               => ['icon' => 'book',          'color' => 'gray'],
        ];

        $colorMap = [
            'indigo' => ['bg' => 'bg-indigo-50',  'border' => 'border-indigo-500', 'icon' => 'text-indigo-500',  'badge' => 'bg-indigo-100 text-indigo-700',  'bar' => 'bg-indigo-500'],
            'blue'   => ['bg' => 'bg-blue-50',    'border' => 'border-blue-500',   'icon' => 'text-blue-500',    'badge' => 'bg-blue-100 text-blue-700',      'bar' => 'bg-blue-500'],
            'green'  => ['bg' => 'bg-green-50',   'border' => 'border-green-500',  'icon' => 'text-green-600',   'badge' => 'bg-green-100 text-green-700',    'bar' => 'bg-green-500'],
            'pink'   => ['bg' => 'bg-pink-50',    'border' => 'border-pink-500',   'icon' => 'text-pink-500',    'badge' => 'bg-pink-100 text-pink-700',      'bar' => 'bg-pink-500'],
            'purple' => ['bg' => 'bg-purple-50',  'border' => 'border-purple-500', 'icon' => 'text-purple-500',  'badge' => 'bg-purple-100 text-purple-700',  'bar' => 'bg-purple-500'],
            'teal'   => ['bg' => 'bg-teal-50',    'border' => 'border-teal-500',   'icon' => 'text-teal-600',    'badge' => 'bg-teal-100 text-teal-700',      'bar' => 'bg-teal-500'],
            'cyan'   => ['bg' => 'bg-cyan-50',    'border' => 'border-cyan-500',   'icon' => 'text-cyan-600',    'badge' => 'bg-cyan-100 text-cyan-700',      'bar' => 'bg-cyan-500'],
            'yellow' => ['bg' => 'bg-yellow-50',  'border' => 'border-yellow-500', 'icon' => 'text-yellow-600',  'badge' => 'bg-yellow-100 text-yellow-700',  'bar' => 'bg-yellow-500'],
            'orange' => ['bg' => 'bg-orange-50',  'border' => 'border-orange-500', 'icon' => 'text-orange-500',  'badge' => 'bg-orange-100 text-orange-700',  'bar' => 'bg-orange-500'],
            'red'    => ['bg' => 'bg-red-50',     'border' => 'border-red-500',    'icon' => 'text-red-500',     'badge' => 'bg-red-100 text-red-700',        'bar' => 'bg-red-500'],
            'gray'   => ['bg' => 'bg-gray-50',    'border' => 'border-gray-400',   'icon' => 'text-gray-500',    'badge' => 'bg-gray-100 text-gray-700',      'bar' => 'bg-gray-400'],
        ];

        $totalRopa = \App\Models\Ropa::count() ?: 1;

        $deptStats = [];
        foreach ($departments as $name => $meta) {
            $total    = \App\Models\Ropa::where('department', $name)->count();
            $pending  = \App\Models\Ropa::where('department', $name)->where('status', 'Pending')->count();
            $approved = \App\Models\Ropa::where('department', $name)->where('status', 'Approved')->count();
            $reviewed = \App\Models\Ropa::where('department', $name)->where('status', 'Reviewed')->count();
            $rejected = \App\Models\Ropa::where('department', $name)->where('status', 'Rejected')->count();
            $deptStats[$name] = compact('total','pending','approved','reviewed','rejected');
        }
    @endphp

    <div class="mb-8">
        <!-- Section header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">ROPA by Department</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Submission counts and status breakdown per department</p>
            </div>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ count($departments) }} departments</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($departments as $deptName => $meta)
                @php
                    $c    = $colorMap[$meta['color']];
                    $s    = $deptStats[$deptName];
                    $pct  = $totalRopa > 0 ? round(($s['total'] / $totalRopa) * 100) : 0;
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Coloured top stripe -->
                    <div class="h-1 w-full {{ $c['bar'] }}"></div>

                    <div class="p-4">
                        <!-- Icon + name -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="p-2 rounded-lg {{ $c['bg'] }}">
                                    <i data-feather="{{ $meta['icon'] }}" class="w-4 h-4 {{ $c['icon'] }}"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 leading-tight">{{ $deptName }}</span>
                            </div>
                            <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $s['total'] }}</span>
                        </div>

                        <!-- Progress bar -->
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 mb-3">
                            <div class="{{ $c['bar'] }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>

                        <!-- Status pills -->
                        <div class="flex flex-wrap gap-1.5">
                            @if($s['pending'] > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block"></span>
                                    {{ $s['pending'] }} Pending
                                </span>
                            @endif
                            @if($s['reviewed'] > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                                    {{ $s['reviewed'] }} Reviewed
                                </span>
                            @endif
                            @if($s['approved'] > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                    {{ $s['approved'] }} Approved
                                </span>
                            @endif
                            @if($s['rejected'] > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                    {{ $s['rejected'] }} Rejected
                                </span>
                            @endif
                            @if($s['total'] === 0)
                                <span class="text-xs text-gray-400 italic">No submissions yet</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- ── END DEPARTMENT BREAKDOWN ── -->

    <!-- All ROPA Submissions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i data-feather="list" class="w-6 h-6 mr-2"></i>
                All ROPA Submissions
            </h2>
            <div class="flex items-center gap-3">
                <select id="statusFilter" class="px-4 py-2 rounded-lg border-2 border-white bg-white text-gray-700 text-sm font-semibold focus:ring-2 focus:ring-orange-300 focus:outline-none">
                    <option value="all">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Reviewed">Reviewed</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
                <button onclick="exportTable()" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-orange-600 rounded-lg hover:bg-orange-50 transition-all font-semibold text-sm">
                    <i data-feather="download" class="w-4 h-4"></i>
                    Export
                </button>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
            <div class="relative">
                <input type="text" id="tableSearch"
                       placeholder="Search by organisation, department, user, or status..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-gray-200">
                <i data-feather="search" class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="ropaTable">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Organisation</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Department</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Submitted By</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date Submitted</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $allRopas = \App\Models\Ropa::with('user')->latest('created_at')->get();
                    @endphp

                    @forelse($allRopas as $ropa)
                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors duration-150" data-status="{{ $ropa->status ?? 'Pending' }}">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-bold text-gray-900 dark:text-gray-100">{{ $ropa->id }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                                        <i data-feather="briefcase" class="w-4 h-4 text-white"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $ropa->organisation_name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $dept = $ropa->department ?? $ropa->other_department ?? 'N/A';
                                    $deptMeta = $departments[$dept] ?? null;
                                    $deptC = $deptMeta ? $colorMap[$deptMeta['color']] : $colorMap['gray'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $deptC['badge'] }}">
                                    @if($deptMeta)
                                        <i data-feather="{{ $deptMeta['icon'] }}" class="w-3 h-3"></i>
                                    @endif
                                    {{ $dept }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-700">{{ strtoupper(substr($ropa->user->name ?? 'U', 0, 2)) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $ropa->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($ropa->user->email ?? '', 25) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $ropa->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ropa->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $status = $ropa->status ?? 'Pending';
                                    $statusColor = match($status) {
                                        'Reviewed', 'Approved' => 'bg-green-100 text-green-800 border-green-300',
                                        'Rejected'             => 'bg-red-100 text-red-800 border-red-300',
                                        'Pending'              => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        default                => 'bg-gray-100 text-gray-800 border-gray-300'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border-2 {{ $statusColor }}">{{ $status }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('ropa.show', $ropa->id) }}" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all" title="View">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('ropa.edit', $ropa->id) }}" class="p-2 text-orange-600 hover:text-orange-800 hover:bg-orange-50 rounded-lg transition-all" title="Edit">
                                        <i data-feather="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('ropa.destroy', $ropa->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this ROPA record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                            <i data-feather="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
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

    document.getElementById('userMenuButton').addEventListener('click', function () {
        document.getElementById('userDropdown').classList.toggle('hidden');
    });

    document.getElementById('tableSearch').addEventListener('input', function() {
        const filter = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#ropaTable tbody tr');
        let visible = 0;
        rows.forEach(row => {
            if (row.cells.length === 1) return;
            const text = row.textContent.toLowerCase();
            const show = filter === '' || text.includes(filter);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('visibleCount').textContent = visible;
    });

    document.getElementById('statusFilter').addEventListener('change', function() {
        const selected = this.value.toLowerCase();
        const rows = document.querySelectorAll('#ropaTable tbody tr');
        let visible = 0;
        rows.forEach(row => {
            if (row.cells.length === 1) return;
            const status = (row.getAttribute('data-status') || '').toLowerCase();
            const show = selected === 'all' || status === selected;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('visibleCount').textContent = visible;
        feather.replace();
    });

    function exportTable() {
        alert('Export functionality — integrate with your preferred export library (Excel, CSV, etc.)');
    }

    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>

<style>
    .overflow-x-auto::-webkit-scrollbar { height: 8px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f1f1f1; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #ea580c; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #c2410c; }
</style>

@endsection