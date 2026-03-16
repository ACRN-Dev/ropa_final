@extends('layouts.admin')

@section('title', 'Admin')

@section('content')

<!-- ── PAGE HEADER (desktop only — mobile header is in the layout) ── -->
<div class="hidden md:flex items-center justify-between mb-5 lg:mb-6">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">ROPA Admin Overview</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor compliance, risk levels, and recent activity.</p>
    </div>
    <!-- User menu (desktop) -->
    <div class="relative">
        <button id="userMenuButton"
                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none">
            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 max-w-[140px] truncate">{{ Auth::user()->name }}</span>
            <i data-feather="chevron-down" class="w-4 h-4 text-gray-500"></i>
        </button>
        <div id="userDropdown"
             class="hidden absolute right-0 mt-2 w-44 bg-white dark:bg-gray-800 shadow-xl rounded-xl py-2 z-50 border border-gray-100 dark:border-gray-700">
            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-gray-700 transition">
                <i data-feather="user" class="w-4 h-4 text-orange-500"></i> Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-gray-700 transition">
                    <i data-feather="log-out" class="w-4 h-4 text-red-500"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Mobile page title -->
<div class="md:hidden mb-4">
    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">ROPA Overview</h1>
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Monitor compliance and recent activity.</p>
</div>

<!-- ── STAT CARDS ── -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 lg:gap-6 mb-6 lg:mb-8">

    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-orange-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Total Records</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ \App\Models\Ropa::count() }}</p>
        </div>
        <i data-feather="database" class="w-7 h-7 lg:w-8 lg:h-8 text-orange-500 flex-shrink-0"></i>
    </div>

    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-yellow-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Pending</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ \App\Models\Ropa::where('status','Pending')->count() }}</p>
        </div>
        <i data-feather="clock" class="w-7 h-7 lg:w-8 lg:h-8 text-yellow-500 flex-shrink-0"></i>
    </div>

    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-red-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Overdue</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $overdueReviews ?? 0 }}</p>
        </div>
        <i data-feather="alert-triangle" class="w-7 h-7 lg:w-8 lg:h-8 text-red-500 flex-shrink-0"></i>
    </div>

    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-green-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Completed</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $tasksCompleted ?? 0 }}</p>
        </div>
        <i data-feather="check-circle" class="w-7 h-7 lg:w-8 lg:h-8 text-green-500 flex-shrink-0"></i>
    </div>

</div>

<!-- ── DEPARTMENT BREAKDOWN ── -->
@php
    $departments = [
        'Data Protection'               => ['icon' => 'shield',       'color' => 'indigo'],
        'IT'                            => ['icon' => 'monitor',      'color' => 'blue'],
        'HR'                            => ['icon' => 'users',        'color' => 'green'],
        'Community Engagement'          => ['icon' => 'heart',        'color' => 'pink'],
        'Data & Biostatisitcs'          => ['icon' => 'bar-chart-2',  'color' => 'purple'],
        'Laboratory'                    => ['icon' => 'activity',     'color' => 'teal'],
        'Pharmacy'                      => ['icon' => 'package',      'color' => 'cyan'],
        'Finance & Administration'      => ['icon' => 'dollar-sign',  'color' => 'yellow'],
        'Clinical Operations (ClinOps)' => ['icon' => 'clipboard',    'color' => 'orange'],
        'Project Management'            => ['icon' => 'briefcase',    'color' => 'red'],
        'Legal & Compliance'            => ['icon' => 'book',         'color' => 'gray'],
    ];

    $colorMap = [
        'indigo' => ['bg'=>'bg-indigo-50', 'icon'=>'text-indigo-500', 'badge'=>'bg-indigo-100 text-indigo-700', 'bar'=>'bg-indigo-500'],
        'blue'   => ['bg'=>'bg-blue-50',   'icon'=>'text-blue-500',   'badge'=>'bg-blue-100 text-blue-700',     'bar'=>'bg-blue-500'],
        'green'  => ['bg'=>'bg-green-50',  'icon'=>'text-green-600',  'badge'=>'bg-green-100 text-green-700',   'bar'=>'bg-green-500'],
        'pink'   => ['bg'=>'bg-pink-50',   'icon'=>'text-pink-500',   'badge'=>'bg-pink-100 text-pink-700',     'bar'=>'bg-pink-500'],
        'purple' => ['bg'=>'bg-purple-50', 'icon'=>'text-purple-500', 'badge'=>'bg-purple-100 text-purple-700', 'bar'=>'bg-purple-500'],
        'teal'   => ['bg'=>'bg-teal-50',   'icon'=>'text-teal-600',   'badge'=>'bg-teal-100 text-teal-700',     'bar'=>'bg-teal-500'],
        'cyan'   => ['bg'=>'bg-cyan-50',   'icon'=>'text-cyan-600',   'badge'=>'bg-cyan-100 text-cyan-700',     'bar'=>'bg-cyan-500'],
        'yellow' => ['bg'=>'bg-yellow-50', 'icon'=>'text-yellow-600', 'badge'=>'bg-yellow-100 text-yellow-700', 'bar'=>'bg-yellow-500'],
        'orange' => ['bg'=>'bg-orange-50', 'icon'=>'text-orange-500', 'badge'=>'bg-orange-100 text-orange-700', 'bar'=>'bg-orange-500'],
        'red'    => ['bg'=>'bg-red-50',    'icon'=>'text-red-500',    'badge'=>'bg-red-100 text-red-700',       'bar'=>'bg-red-500'],
        'gray'   => ['bg'=>'bg-gray-50',   'icon'=>'text-gray-500',   'badge'=>'bg-gray-100 text-gray-700',     'bar'=>'bg-gray-400'],
    ];

    $totalRopa = \App\Models\Ropa::count() ?: 1;
    $deptStats = [];
    foreach ($departments as $name => $meta) {
        $deptStats[$name] = [
            'total'    => \App\Models\Ropa::where('department', $name)->count(),
            'pending'  => \App\Models\Ropa::where('department', $name)->where('status', 'Pending')->count(),
            'approved' => \App\Models\Ropa::where('department', $name)->where('status', 'Approved')->count(),
            'reviewed' => \App\Models\Ropa::where('department', $name)->where('status', 'Reviewed')->count(),
            'rejected' => \App\Models\Ropa::where('department', $name)->where('status', 'Rejected')->count(),
        ];
    }
@endphp

<div class="mb-6 lg:mb-8">
    <div class="flex items-center justify-between mb-3 lg:mb-4">
        <div>
            <h2 class="text-base lg:text-xl font-bold text-gray-900 dark:text-gray-100">ROPA by Department</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 hidden sm:block">Submission counts and status breakdown per department</p>
        </div>
        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ count($departments) }} depts</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-4">
        @foreach($departments as $deptName => $meta)
            @php
                $c   = $colorMap[$meta['color']];
                $s   = $deptStats[$deptName];
                $pct = round(($s['total'] / $totalRopa) * 100);
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-1 w-full {{ $c['bar'] }}"></div>
                <div class="p-3 lg:p-4">
                    <div class="flex items-start justify-between mb-2 lg:mb-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="p-1.5 rounded-lg {{ $c['bg'] }} flex-shrink-0">
                                <i data-feather="{{ $meta['icon'] }}" class="w-3.5 h-3.5 {{ $c['icon'] }}"></i>
                            </div>
                            <span class="text-xs lg:text-sm font-semibold text-gray-800 dark:text-gray-100 leading-tight">{{ $deptName }}</span>
                        </div>
                        <span class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100 flex-shrink-0 ml-1">{{ $s['total'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 mb-2 lg:mb-3">
                        <div class="{{ $c['bar'] }} h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        @if($s['pending'])
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block"></span>{{ $s['pending'] }} Pending
                            </span>
                        @endif
                        @if($s['reviewed'])
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>{{ $s['reviewed'] }} Reviewed
                            </span>
                        @endif
                        @if($s['approved'])
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>{{ $s['approved'] }} Approved
                            </span>
                        @endif
                        @if($s['rejected'])
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>{{ $s['rejected'] }} Rejected
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

<!-- ── ROPA TABLE ── -->
@php $allRopas = \App\Models\Ropa::with('user')->latest('created_at')->get(); @endphp

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

    <!-- Table top bar -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 lg:px-6 py-3 lg:py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="text-base lg:text-xl font-bold text-white flex items-center gap-2">
                <i data-feather="list" class="w-5 h-5 flex-shrink-0"></i>
                All ROPA Submissions
            </h2>
            <div class="flex items-center gap-2">
                <select id="statusFilter"
                        class="flex-1 sm:flex-none px-3 py-1.5 rounded-lg border-2 border-white bg-white
                               text-gray-700 text-xs lg:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-orange-300">
                    <option value="all">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Reviewed">Reviewed</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
                <button onclick="exportTable()"
                        class="inline-flex items-center gap-1.5 px-3 lg:px-4 py-1.5 bg-white text-orange-600
                               rounded-lg hover:bg-orange-50 font-semibold text-xs lg:text-sm whitespace-nowrap transition">
                    <i data-feather="download" class="w-3.5 h-3.5 lg:w-4 lg:h-4"></i>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Department tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto dept-tab-scroll">
        <nav id="deptTabs" class="flex min-w-max px-3 lg:px-6 gap-0.5 pt-2 lg:pt-3">
            <button data-dept="all"
                    class="dept-tab active-tab whitespace-nowrap px-3 lg:px-4 py-2 text-xs lg:text-sm
                           font-semibold rounded-t-lg border-b-2 border-orange-500 text-orange-600
                           bg-orange-50 dark:bg-gray-700 transition-all">
                All
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                    {{ $allRopas->count() }}
                </span>
            </button>
            @foreach($departments as $deptName => $meta)
                @php
                    $tabCount = $allRopas->filter(fn($r) => ($r->department ?? $r->other_department ?? '') === $deptName)->count();
                    $tc = $colorMap[$meta['color']];
                @endphp
                <button data-dept="{{ $deptName }}"
                        class="dept-tab whitespace-nowrap px-3 lg:px-4 py-2 text-xs lg:text-sm
                               font-semibold rounded-t-lg border-b-2 border-transparent
                               text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300
                               transition-all">
                    <i data-feather="{{ $meta['icon'] }}" class="w-3 h-3 inline-block mr-1 {{ $tc['icon'] }}"></i>
                    {{ $deptName }}
                    @if($tabCount > 0)
                        <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs font-bold {{ $tc['badge'] }}">{{ $tabCount }}</span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Search -->
    <div class="px-3 lg:px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
        <div class="relative">
            <i data-feather="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input type="text" id="tableSearch"
                   placeholder="Search by organisation, department, user, or status..."
                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                          focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                          dark:bg-gray-800 dark:text-gray-200 bg-white">
        </div>
    </div>

    <!-- ── DESKTOP TABLE (sm and up) ── -->
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm" id="ropaTable">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Organisation</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">Department</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">Submitted By</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($allRopas as $ropa)
                    @php $rowDept = $ropa->department ?? $ropa->other_department ?? ''; @endphp
                    <tr class="ropa-row hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors"
                        data-status="{{ $ropa->status ?? 'Pending' }}"
                        data-dept="{{ $rowDept }}">
                        <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-900 dark:text-gray-100 text-xs">
                            #{{ $ropa->id }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-shrink-0 h-7 w-7 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                                    <i data-feather="briefcase" class="w-3.5 h-3.5 text-white"></i>
                                </div>
                                <span class="font-semibold text-gray-900 dark:text-gray-100 text-xs lg:text-sm">{{ $ropa->organisation_name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            @php
                                $dept     = $rowDept ?: 'N/A';
                                $deptMeta = $departments[$dept] ?? null;
                                $deptC    = $deptMeta ? $colorMap[$deptMeta['color']] : $colorMap['gray'];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $deptC['badge'] }}">
                                @if($deptMeta)<i data-feather="{{ $deptMeta['icon'] }}" class="w-3 h-3"></i>@endif
                                {{ $dept }}
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <div class="flex items-center gap-2">
                                <div class="flex-shrink-0 h-7 w-7 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ strtoupper(substr($ropa->user->name ?? 'U', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ $ropa->user->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-400">{{ Str::limit($ropa->user->email ?? '', 22) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap hidden lg:table-cell">
                            <div class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ $ropa->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $ropa->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $status = $ropa->status ?? 'Pending';
                                $sc = match($status) {
                                    'Reviewed', 'Approved' => 'bg-green-100 text-green-700 border-green-200',
                                    'Rejected'             => 'bg-red-100 text-red-700 border-red-200',
                                    default                => 'bg-yellow-100 text-yellow-700 border-yellow-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border {{ $sc }}">{{ $status }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('ropa.show', $ropa->id) }}"
                                   class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="View">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('ropa.edit', $ropa->id) }}"
                                   class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-all" title="Edit">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <i data-feather="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-sm font-semibold text-gray-400">No ROPA records found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ── MOBILE CARD LIST (xs only) ── -->
    <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-700" id="mobileCardList">
        @forelse($allRopas as $ropa)
            @php
                $rowDept  = $ropa->department ?? $ropa->other_department ?? '';
                $status   = $ropa->status ?? 'Pending';
                $deptMeta = $departments[$rowDept] ?? null;
                $deptC    = $deptMeta ? $colorMap[$deptMeta['color']] : $colorMap['gray'];
                $sc = match($status) {
                    'Reviewed', 'Approved' => 'bg-green-100 text-green-700 border-green-200',
                    'Rejected'             => 'bg-red-100 text-red-700 border-red-200',
                    default                => 'bg-yellow-100 text-yellow-700 border-yellow-200'
                };
            @endphp
            <div class="mobile-card p-4 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors"
                 data-status="{{ $status }}" data-dept="{{ $rowDept }}">
                <div class="flex items-start justify-between gap-2 mb-2.5">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="flex-shrink-0 h-9 w-9 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                            <i data-feather="briefcase" class="w-4 h-4 text-white"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-sm text-gray-900 dark:text-gray-100 truncate">{{ $ropa->organisation_name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">#{{ $ropa->id }} · {{ $ropa->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border {{ $sc }} flex-shrink-0">{{ $status }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-1.5 mb-3">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $deptC['badge'] }}">
                        @if($deptMeta)<i data-feather="{{ $deptMeta['icon'] }}" class="w-3 h-3"></i>@endif
                        {{ $rowDept ?: 'N/A' }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $ropa->user->name ?? 'Unknown' }}</span>
                </div>
                <div class="flex gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('ropa.show', $ropa->id) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-semibold
                              text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all">
                        <i data-feather="eye" class="w-3.5 h-3.5"></i> View
                    </a>
                    <a href="{{ route('ropa.edit', $ropa->id) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-semibold
                              text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-lg transition-all">
                        <i data-feather="edit-2" class="w-3.5 h-3.5"></i> Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="py-16 text-center">
                <i data-feather="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm font-semibold text-gray-400">No ROPA records found</p>
            </div>
        @endforelse
    </div>

    <!-- Footer -->
    <div class="px-4 lg:px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Showing <span class="font-bold text-gray-800 dark:text-gray-200" id="visibleCount">{{ $allRopas->count() }}</span>
                of <span class="font-bold text-gray-800 dark:text-gray-200">{{ $allRopas->count() }}</span> records
            </p>
            <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-xs">
                <span class="font-semibold text-gray-600 dark:text-gray-300">Total: {{ \App\Models\Ropa::count() }}</span>
                <span class="font-semibold text-yellow-600">Pending: {{ \App\Models\Ropa::where('status','Pending')->count() }}</span>
                <span class="font-semibold text-green-600">Reviewed: {{ \App\Models\Ropa::where('status','Reviewed')->count() }}</span>
            </div>
        </div>
    </div>
</div>

<script>
    // ── User dropdown (desktop) ──
    const userBtn = document.getElementById('userMenuButton');
    const userDd  = document.getElementById('userDropdown');
    if (userBtn) {
        userBtn.addEventListener('click', e => { e.stopPropagation(); userDd.classList.toggle('hidden'); });
        document.addEventListener('click', () => userDd.classList.add('hidden'));
    }

    // ── Filter state ──
    let activeDept = 'all', activeStatus = 'all', activeSearch = '';

    function applyFilters() {
        let visible = 0;
        const all = [
            ...document.querySelectorAll('#ropaTable tbody tr.ropa-row'),
            ...document.querySelectorAll('.mobile-card')
        ];
        all.forEach(el => {
            const dept   = (el.dataset.dept   || '').toLowerCase();
            const status = (el.dataset.status || '').toLowerCase();
            const text   = el.textContent.toLowerCase();
            const show =
                (activeDept   === 'all' || dept   === activeDept.toLowerCase()) &&
                (activeStatus === 'all' || status === activeStatus.toLowerCase()) &&
                (activeSearch === ''   || text.includes(activeSearch));
            el.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('visibleCount').textContent = visible / 2 | 0; // each record has table row + card
    }

    // Count only the visible rows (not duplicated by mobile cards)
    function applyFiltersFixed() {
        let visible = 0;
        const isMobile = window.innerWidth < 640;

        document.querySelectorAll('#ropaTable tbody tr.ropa-row').forEach(el => {
            const show = matchesFilters(el);
            el.style.display = show ? '' : 'none';
            if (show && !isMobile) visible++;
        });
        document.querySelectorAll('.mobile-card').forEach(el => {
            const show = matchesFilters(el);
            el.style.display = show ? '' : 'none';
            if (show && isMobile) visible++;
        });
        if (!isMobile) {
            // recount from table rows
            visible = [...document.querySelectorAll('#ropaTable tbody tr.ropa-row')]
                .filter(r => r.style.display !== 'none').length;
        } else {
            visible = [...document.querySelectorAll('.mobile-card')]
                .filter(r => r.style.display !== 'none').length;
        }
        document.getElementById('visibleCount').textContent = visible;
    }

    function matchesFilters(el) {
        const dept   = (el.dataset.dept   || '').toLowerCase();
        const status = (el.dataset.status || '').toLowerCase();
        const text   = el.textContent.toLowerCase();
        return (activeDept   === 'all' || dept   === activeDept.toLowerCase())
            && (activeStatus === 'all' || status === activeStatus.toLowerCase())
            && (activeSearch === ''    || text.includes(activeSearch));
    }

    document.getElementById('tableSearch').addEventListener('input', function () {
        activeSearch = this.value.toLowerCase().trim();
        applyFiltersFixed();
    });

    document.getElementById('statusFilter').addEventListener('change', function () {
        activeStatus = this.value;
        applyFiltersFixed();
        feather.replace();
    });

    document.querySelectorAll('.dept-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.dept-tab').forEach(t => {
                t.classList.remove('active-tab','border-orange-500','text-orange-600','bg-orange-50','dark:bg-gray-700');
                t.classList.add('border-transparent','text-gray-500','dark:text-gray-400');
            });
            this.classList.add('active-tab','border-orange-500','text-orange-600','bg-orange-50','dark:bg-gray-700');
            this.classList.remove('border-transparent','text-gray-500','dark:text-gray-400');
            this.scrollIntoView({ behavior:'smooth', block:'nearest', inline:'center' });
            activeDept = this.dataset.dept;
            applyFiltersFixed();
            feather.replace();
        });
    });

    function exportTable() {
        alert('Export functionality — integrate with your preferred export library (Excel, CSV, etc.)');
    }

    document.addEventListener('DOMContentLoaded', () => feather.replace());
</script>

<style>
    /* Tab scrollbar */
    .dept-tab-scroll::-webkit-scrollbar { height: 3px; }
    .dept-tab-scroll::-webkit-scrollbar-track { background: transparent; }
    .dept-tab-scroll::-webkit-scrollbar-thumb { background: #fdba74; border-radius: 4px; }

    /* Table scrollbar */
    .overflow-x-auto::-webkit-scrollbar { height: 5px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f9fafb; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #ea580c; border-radius: 6px; }

    .dept-tab { transition: color .15s, border-color .15s, background-color .15s; }

    /* Prevent any child from busting the layout width */
    * { box-sizing: border-box; }
</style>

@endsection