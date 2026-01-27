@extends('layouts.app')

@section('title', 'User | Activity Logs')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-orange-600 mb-1">Activity Logs</h1>
        <p class="text-sm text-gray-600">Monitor all your recent activities and system events</p>
    </div>

    {{-- Search and Filter Bar --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 mb-6">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 p-4">
            <form method="GET" action="{{ route('activities.index') }}" class="flex flex-col lg:flex-row gap-3">
                {{-- Search Input --}}
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by user, model, IP address..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm shadow-sm">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Action Filter --}}
                <div class="flex-1 min-w-[200px]">
                    <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                    </select>
                </div>

                {{-- Date Range Filter --}}
                <div class="flex-1 min-w-[150px]">
                    <select name="date_range" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="7days" {{ request('date_range') == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30days" {{ request('date_range') == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90days" {{ request('date_range') == '90days' ? 'selected' : '' }}>Last 90 Days</option>
                    </select>
                </div>

                {{-- Filter Buttons --}}
                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-all text-sm font-semibold shadow-sm hover:shadow-md">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'action', 'date_range']))
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all text-sm font-semibold shadow-sm">
                        Clear
                    </a>
                    @endif
                </div>

                {{-- Active Filters Display --}}
                @if(request()->hasAny(['search', 'action', 'date_range']))
                <div class="mt-3 flex flex-wrap gap-2 items-center">
                    <span class="text-xs text-gray-600 font-semibold">Active Filters:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-2.5 py-1 rounded-full text-xs font-semibold">
                            Search: "{{ request('search') }}"
                        </span>
                    @endif
                    @if(request('action'))
                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-semibold">
                            Action: {{ ucfirst(request('action')) }}
                        </span>
                    @endif
                    @if(request('date_range'))
                        <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-800 px-2.5 py-1 rounded-full text-xs font-semibold">
                            Date: {{ ucfirst(str_replace('days', ' Days', request('date_range'))) }}
                        </span>
                    @endif
                </div>
                @endif
            </form>
        </div>
    </div>

    @if($activities->count() > 0)
        {{-- Activity Table --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-orange-600 via-orange-500 to-orange-600 text-white">
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Activity ID</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Action</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider min-w-[250px]">User</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Model</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">IP Address</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activities as $activity)
                            @php
                                $actionBadgeClass = match($activity->action) {
                                    'created' => 'bg-green-100 text-green-800 border-green-200',
                                    'updated' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'deleted' => 'bg-red-100 text-red-800 border-red-200',
                                    'login'   => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'logout'  => 'bg-gray-100 text-gray-800 border-gray-200',
                                    default   => 'bg-purple-100 text-purple-800 border-purple-200'
                                };

                                $modelName = $activity->model_label ?? 'General';
                            @endphp
                            <tr class="hover:bg-orange-50 transition-all duration-150">
                                <td class="px-4 py-3">{{ $activity->id }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $actionBadgeClass }}">
                                        {{ ucfirst($activity->action) }}
                                    </span>
                                </td>
                                {{-- User Column --}}
                                <td class="px-4 py-3 max-w-xs">
                                    <div class="line-clamp-2 leading-relaxed" title="{{ $activity->user?->name ?? 'Unknown User' }}">
                                        {{ $activity->user?->name ?? 'Unknown User' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $modelName }}
                                    @if($activity->model_id)
                                        <span class="text-gray-500">#{{ $activity->model_id }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-mono text-sm">
                                    {{ $activity->ip_address ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $activity->created_at->format('Y-m-d H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($activities->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-sm text-gray-600">
                            Showing <span class="font-bold text-gray-900">{{ $activities->firstItem() }}</span> to 
                            <span class="font-bold text-gray-900">{{ $activities->lastItem() }}</span> of 
                            <span class="font-bold text-gray-900">{{ $activities->total() }}</span> results
                        </div>
                        <div>
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-xl shadow-lg p-12 text-center border border-gray-200">
            <div class="max-w-lg mx-auto">
                <div class="bg-gradient-to-br from-orange-100 to-orange-200 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Activity Logs Yet</h3>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Your activity logs will appear here once you start performing actions in the system.
                </p>
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white px-8 py-4 rounded-xl hover:from-orange-700 hover:to-orange-800 transition-all font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Go to Dashboard
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
