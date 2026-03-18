@extends('layouts.admin')

@section('title', 'Admin | System Logs')

@section('content')

<!-- ── PAGE HEADER ── -->
<div class="hidden md:flex items-center justify-between mb-5 lg:mb-6">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">System Logs</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor all user activity and system events.</p>
    </div>
    <a href="{{ route('activities.export') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
              text-gray-700 dark:text-gray-300 hover:bg-gray-50 font-semibold rounded-lg transition text-sm shadow-sm">
        <i data-feather="download" class="w-4 h-4"></i>
        Export Logs
    </a>
</div>
<div class="md:hidden mb-4 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">System Logs</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Monitor activity and events.</p>
    </div>
    <a href="{{ route('activities.export') }}"
       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300
              text-gray-600 font-semibold rounded-lg transition text-xs">
        <i data-feather="download" class="w-3.5 h-3.5"></i> Export
    </a>
</div>

<!-- ── STAT CARDS ── -->
@php
    $totalLogs   = $activities->total();
    $todayLogs   = \App\Models\UserActivity::whereDate('created_at', today())->count();
    $loginLogs   = \App\Models\UserActivity::where('action', 'login')->count();
    $deletedLogs = \App\Models\UserActivity::where('action', 'deleted')->count();
@endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 lg:gap-6 mb-6 lg:mb-8">
    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-orange-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Total Events</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($totalLogs) }}</p>
        </div>
        <i data-feather="activity" class="w-7 h-7 lg:w-8 lg:h-8 text-orange-500 flex-shrink-0"></i>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-blue-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Today</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($todayLogs) }}</p>
        </div>
        <i data-feather="calendar" class="w-7 h-7 lg:w-8 lg:h-8 text-blue-500 flex-shrink-0"></i>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-green-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Logins</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($loginLogs) }}</p>
        </div>
        <i data-feather="log-in" class="w-7 h-7 lg:w-8 lg:h-8 text-green-500 flex-shrink-0"></i>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-red-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Deletions</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($deletedLogs) }}</p>
        </div>
        <i data-feather="trash-2" class="w-7 h-7 lg:w-8 lg:h-8 text-red-500 flex-shrink-0"></i>
    </div>
</div>

<!-- ── MAIN TABLE CARD ── -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

    <!-- Top bar -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 lg:px-6 py-3 lg:py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="text-base lg:text-xl font-bold text-white flex items-center gap-2">
                <i data-feather="file-text" class="w-5 h-5 flex-shrink-0"></i>
                Activity Log
            </h2>
            <span class="text-orange-100 text-xs font-semibold">{{ number_format($activities->total()) }} total events</span>
        </div>
    </div>

    <!-- ── FILTER BAR ── -->
    <form method="GET" action="{{ route('activities.index') }}"
          class="px-4 lg:px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
        <div class="flex flex-col sm:flex-row gap-2 flex-wrap">
            <div class="relative flex-1 min-w-[200px]">
                <i data-feather="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search user, model, IP, description..."
                       class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                              focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                              dark:bg-gray-800 dark:text-gray-200 bg-white">
            </div>
            <select name="action"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                           focus:ring-2 focus:ring-orange-500 dark:bg-gray-800 dark:text-gray-200 bg-white">
                <option value="">All Actions</option>
                <option value="login"   {{ request('action')=='login'   ?'selected':'' }}>Login</option>
                <option value="logout"  {{ request('action')=='logout'  ?'selected':'' }}>Logout</option>
                <option value="created" {{ request('action')=='created' ?'selected':'' }}>Created</option>
                <option value="updated" {{ request('action')=='updated' ?'selected':'' }}>Updated</option>
                <option value="viewed"  {{ request('action')=='viewed'  ?'selected':'' }}>Viewed</option>
                <option value="deleted" {{ request('action')=='deleted' ?'selected':'' }}>Deleted</option>
            </select>
            <select name="date_range"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                           focus:ring-2 focus:ring-orange-500 dark:bg-gray-800 dark:text-gray-200 bg-white">
                <option value="">All Time</option>
                <option value="today"     {{ request('date_range')=='today'     ?'selected':'' }}>Today</option>
                <option value="yesterday" {{ request('date_range')=='yesterday' ?'selected':'' }}>Yesterday</option>
                <option value="7days"     {{ request('date_range')=='7days'     ?'selected':'' }}>Last 7 Days</option>
                <option value="30days"    {{ request('date_range')=='30days'    ?'selected':'' }}>Last 30 Days</option>
                <option value="90days"    {{ request('date_range')=='90days'    ?'selected':'' }}>Last 90 Days</option>
            </select>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-orange-500 hover:bg-orange-600
                           text-white font-semibold text-sm rounded-lg transition whitespace-nowrap">
                <i data-feather="filter" class="w-3.5 h-3.5"></i> Filter
            </button>
            @if(request()->hasAny(['search','action','date_range']))
                <a href="{{ route('activities.index') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800
                          border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300
                          hover:bg-gray-100 font-semibold text-sm rounded-lg transition whitespace-nowrap">
                    <i data-feather="x" class="w-3.5 h-3.5"></i> Clear
                </a>
            @endif
        </div>
        @if(request()->hasAny(['search','action','date_range']))
            <div class="flex flex-wrap gap-2 items-center mt-2.5">
                <span class="text-xs text-gray-500 font-semibold">Active:</span>
                @if(request('search'))
                    <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                        <i data-feather="search" class="w-3 h-3"></i> "{{ request('search') }}"
                    </span>
                @endif
                @if(request('action'))
                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 border border-green-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                        <i data-feather="zap" class="w-3 h-3"></i> {{ ucfirst(request('action')) }}
                    </span>
                @endif
                @if(request('date_range'))
                    <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-700 border border-purple-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                        <i data-feather="calendar" class="w-3 h-3"></i> {{ ucfirst(str_replace('days',' Days',request('date_range'))) }}
                    </span>
                @endif
            </div>
        @endif
    </form>

    @if($activities->count() > 0)

    <!-- ── DESKTOP TABLE ── -->
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-16">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">User</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">Model</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">IP Address</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($activities as $activity)
                @php
                    $actionStyle = match($activity->action) {
                        'created' => ['bg-green-100 text-green-700 border-green-200',   'check-circle'],
                        'updated' => ['bg-yellow-100 text-yellow-700 border-yellow-200','edit-2'],
                        'deleted' => ['bg-red-100 text-red-700 border-red-200',          'trash-2'],
                        'login'   => ['bg-blue-100 text-blue-700 border-blue-200',       'log-in'],
                        'logout'  => ['bg-gray-100 text-gray-600 border-gray-200',       'log-out'],
                        'viewed'  => ['bg-purple-100 text-purple-700 border-purple-200', 'eye'],
                        default   => ['bg-orange-100 text-orange-700 border-orange-200', 'zap'],
                    };
                    $hasChanges = !empty($activity->old_values) || !empty($activity->new_values);
                @endphp
                <tr class="hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-4 py-3 font-bold text-gray-400 text-xs">#{{ $activity->id }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold border {{ $actionStyle[0] }}">
                            <i data-feather="{{ $actionStyle[1] }}" class="w-3 h-3"></i>
                            {{ ucfirst($activity->action) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="flex-shrink-0 w-7 h-7 rounded-full bg-gradient-to-br from-orange-400 to-orange-600
                                        flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($activity->user?->name ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ $activity->user?->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-400">{{ $activity->user?->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 max-w-xs">
                        <span class="text-xs text-gray-700 dark:text-gray-300 line-clamp-2">
                            {{ $activity->description ?? $activity->description_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $activity->model_label }}</span>
                        @if($activity->model_id)
                            <span class="text-xs text-gray-400 ml-0.5">#{{ $activity->model_id }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="font-mono text-xs text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">
                            {{ $activity->ip_address ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ $activity->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $activity->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($hasChanges)
                            <button onclick="openDetail({{ $activity->id }})"
                                    class="p-1.5 text-orange-500 hover:bg-orange-50 rounded-lg transition-all" title="View Changes">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </button>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ── MOBILE CARD LIST ── -->
    <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-700">
        @foreach($activities as $activity)
        @php
            $actionStyle = match($activity->action) {
                'created' => ['bg-green-100 text-green-700 border-green-200',   'check-circle'],
                'updated' => ['bg-yellow-100 text-yellow-700 border-yellow-200','edit-2'],
                'deleted' => ['bg-red-100 text-red-700 border-red-200',          'trash-2'],
                'login'   => ['bg-blue-100 text-blue-700 border-blue-200',       'log-in'],
                'logout'  => ['bg-gray-100 text-gray-600 border-gray-200',       'log-out'],
                'viewed'  => ['bg-purple-100 text-purple-700 border-purple-200', 'eye'],
                default   => ['bg-orange-100 text-orange-700 border-orange-200', 'zap'],
            };
        @endphp
        <div class="p-4 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors">
            <div class="flex items-start justify-between gap-2 mb-2">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-br from-orange-400 to-orange-600
                                flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($activity->user?->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-sm text-gray-900 dark:text-gray-100 truncate">{{ $activity->user?->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-400">{{ $activity->created_at->format('M d, Y · h:i A') }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold border {{ $actionStyle[0] }} flex-shrink-0">
                    <i data-feather="{{ $actionStyle[1] }}" class="w-3 h-3"></i>
                    {{ ucfirst($activity->action) }}
                </span>
            </div>
            @if($activity->description)
                <p class="text-xs text-gray-600 dark:text-gray-300 mb-2 line-clamp-2">{{ $activity->description }}</p>
            @endif
            <div class="flex flex-wrap gap-1.5">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-600 border border-orange-200">
                    <i data-feather="layers" class="w-3 h-3"></i>
                    {{ $activity->model_label }}@if($activity->model_id) #{{ $activity->model_id }}@endif
                </span>
                @if($activity->ip_address)
                    <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $activity->ip_address }}</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- ── FOOTER ── -->
    <div class="px-4 lg:px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Showing <span class="font-bold text-gray-800 dark:text-gray-200">{{ $activities->firstItem() }}</span>–<span class="font-bold text-gray-800 dark:text-gray-200">{{ $activities->lastItem() }}</span>
                of <span class="font-bold text-gray-800 dark:text-gray-200">{{ number_format($activities->total()) }}</span> events
            </p>
            <div>{{ $activities->appends(request()->query())->links() }}</div>
        </div>
    </div>

    @else
    <div class="px-4 py-16 text-center">
        <i data-feather="file-text" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
        <p class="text-sm font-semibold text-gray-400 mb-1">No activity logs found</p>
        @if(request()->hasAny(['search','action','date_range']))
            <p class="text-xs text-gray-400 mb-4">Try adjusting your filters.</p>
            <a href="{{ route('activities.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-500 hover:bg-orange-600
                      text-white font-semibold text-sm rounded-lg transition">
                <i data-feather="x" class="w-3.5 h-3.5"></i> Clear Filters
            </a>
        @else
            <p class="text-xs text-gray-400">Activity will appear here once users start performing actions.</p>
        @endif
    </div>
    @endif
</div>

<!-- ── CHANGE DETAIL MODAL ── -->
<div id="detailModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl max-h-[80vh] flex flex-col">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-5 py-4 rounded-t-xl flex items-center justify-between flex-shrink-0">
            <h3 class="font-bold text-white flex items-center gap-2">
                <i data-feather="git-commit" class="w-4 h-4"></i>
                Change Detail
            </h3>
            <button onclick="closeDetail()" class="text-white/80 hover:text-white">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="overflow-y-auto p-5 flex-1" id="detailBody">
            <p class="text-sm text-gray-400">Loading...</p>
        </div>
    </div>
</div>

<!-- Embed activity change data as JSON -->
<script id="activityData" type="application/json">
{!! json_encode($activities->map(function($a) {
    return [
        'id'          => $a->id,
        'action'      => $a->action,
        'description' => $a->description ?? $a->description_label,
        'model'       => $a->model_label,
        'model_id'    => $a->model_id,
        'user'        => $a->user?->name ?? 'Unknown',
        'ip'          => $a->ip_address ?? 'N/A',
        'date'        => $a->created_at->format('M d, Y h:i A'),
        'old_values'  => $a->old_values,
        'new_values'  => $a->new_values,
    ];
})->values()) !!}
</script>

<style>
    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
</style>

<script>
    feather.replace();

    // Index activity data by id
    var ACTIVITY_DATA = {};
    try {
        JSON.parse(document.getElementById('activityData').textContent)
            .forEach(function(a) { ACTIVITY_DATA[a.id] = a; });
    } catch(e) { console.error('Activity data parse error', e); }

    function openDetail(id) {
        var a = ACTIVITY_DATA[id];
        if (!a) return;

        var html = '<div class="space-y-4">';

        // Meta grid
        html += '<div class="grid grid-cols-2 gap-3">';
        html += cell('Action',     badge(a.action));
        html += cell('User',       esc(a.user));
        html += cell('Model',      esc(a.model) + (a.model_id ? ' <span class="text-gray-400">#' + a.model_id + '</span>' : ''));
        html += cell('IP Address', '<span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">' + esc(a.ip) + '</span>');
        html += cell('Date',       esc(a.date));
        html += '</div>';

        // Description
        if (a.description) {
            html += '<div class="bg-gray-50 rounded-lg px-4 py-3">';
            html += '<p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Description</p>';
            html += '<p class="text-sm text-gray-700">' + esc(a.description) + '</p>';
            html += '</div>';
        }

        // Before / After diff table
        if (a.old_values || a.new_values) {
            var fields = Object.keys(Object.assign({}, a.old_values || {}, a.new_values || {}));
            html += '<div>';
            html += '<p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Field Changes</p>';
            html += '<div class="rounded-lg overflow-hidden border border-gray-200">';
            html += '<table class="w-full text-xs">';
            html += '<thead><tr class="bg-gray-50">';
            html += '<th class="px-3 py-2 text-left font-bold text-gray-500 uppercase">Field</th>';
            html += '<th class="px-3 py-2 text-left font-bold text-red-500 uppercase">Before</th>';
            html += '<th class="px-3 py-2 text-left font-bold text-green-600 uppercase">After</th>';
            html += '</tr></thead><tbody class="divide-y divide-gray-100">';

            if (fields.length === 0) {
                html += '<tr><td colspan="3" class="px-3 py-3 text-center text-gray-400">No field-level changes recorded</td></tr>';
            } else {
                fields.forEach(function(field) {
                    var oldVal = (a.old_values && a.old_values[field] !== undefined) ? String(a.old_values[field]) : '—';
                    var newVal = (a.new_values && a.new_values[field] !== undefined) ? String(a.new_values[field]) : '—';
                    var changed = oldVal !== newVal;
                    html += '<tr class="' + (changed ? 'bg-yellow-50' : '') + '">';
                    html += '<td class="px-3 py-2 font-semibold text-gray-600 capitalize">' + esc(field.replace(/_/g,' ')) + '</td>';
                    html += '<td class="px-3 py-2 text-red-600 ' + (changed ? 'line-through' : '') + '">' + esc(oldVal) + '</td>';
                    html += '<td class="px-3 py-2 text-green-700 font-semibold">' + esc(newVal) + '</td>';
                    html += '</tr>';
                });
            }

            html += '</tbody></table></div></div>';
        }

        html += '</div>';

        document.getElementById('detailBody').innerHTML = html;
        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModal').classList.add('flex');
        feather.replace();
    }

    function closeDetail() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModal').classList.remove('flex');
    }

    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) closeDetail();
    });

    // ── Helpers ──
    function cell(label, value) {
        return '<div class="bg-gray-50 rounded-lg px-3 py-2">'
             + '<p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">' + label + '</p>'
             + '<p class="text-sm text-gray-800">' + value + '</p>'
             + '</div>';
    }

    function badge(action) {
        var map = {
            created: 'bg-green-100 text-green-700',
            updated: 'bg-yellow-100 text-yellow-700',
            deleted: 'bg-red-100 text-red-700',
            login:   'bg-blue-100 text-blue-700',
            logout:  'bg-gray-100 text-gray-600',
            viewed:  'bg-purple-100 text-purple-700',
        };
        var cls = map[action] || 'bg-orange-100 text-orange-700';
        return '<span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold ' + cls + '">' + esc(action) + '</span>';
    }

    function esc(str) {
        if (str === null || str === undefined) return '—';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
</script>

@endsection