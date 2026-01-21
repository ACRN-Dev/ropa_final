@extends('layouts.app')

@section('title', 'Risk | Register')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-orange-600 mb-2">ACRN Risk Register</h1>
            <p class="text-gray-600">Monitor and manage organizational risks</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('risk-register.download-template') }}" 
               class="inline-flex items-center gap-2 bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors shadow-md hover:shadow-lg font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download Template
            </a>
            <button type="button" 
                    onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Upload Sheet
            </button>
            <a href="{{ route('risk-register.create') }}" 
               class="inline-flex items-center gap-2 bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors shadow-md hover:shadow-lg font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Risk
            </a>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="font-semibold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Error Alert --}}
    @if(session('error'))
        <div class="alert alert-error bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="font-semibold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Risks --}}
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Total Risks</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalRisks ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- High Risk --}}
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">High Risk</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $highRisks ?? 0 }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Medium Risk --}}
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Medium Risk</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $mediumRisks ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Low Risk --}}
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Low Risk</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $lowRisks ?? 0 }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form action="{{ route('risk-register.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                        Search Risks
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="search"
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by risk title, description..." 
                               class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Risk Level Filter --}}
                <div>
                    <label for="risk_level" class="block text-sm font-semibold text-gray-700 mb-2">
                        Risk Level
                    </label>
                    <select name="risk_level" 
                            id="risk_level"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Levels</option>
                        <option value="low" {{ request('risk_level') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('risk_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('risk_level') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ request('risk_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                    </label>
                    <select name="status" 
                            id="status"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="mitigated" {{ request('status') == 'mitigated' ? 'selected' : '' }}>Mitigated</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 flex-wrap">
                <button type="submit" 
                        class="inline-flex items-center gap-2 bg-orange-600 text-white px-6 py-2.5 rounded-lg hover:bg-orange-700 transition-colors font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Apply Filters
                </button>
                <a href="{{ route('risk-register.index') }}" 
                   class="inline-flex items-center gap-2 border-2 border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Bulk Actions Bar --}}
    <div id="bulkActionsBar" class="hidden bg-orange-50 border-l-4 border-orange-500 p-4 mb-6 rounded-lg shadow-md">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-700">
                    <span id="selectedCount">0</span> risk(s) selected
                </span>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <button type="button" 
                        onclick="exportSelected()"
                        class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export Selected (CSV)
                </button>
                <button type="button" 
                        onclick="exportSelectedPDF()"
                        class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Export PDF
                </button>
                <button type="button" 
                        onclick="deleteSelected()"
                        class="inline-flex items-center gap-2 bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800 transition-colors font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Selected
                </button>
                <button type="button" 
                        onclick="clearSelection()"
                        class="inline-flex items-center gap-2 border-2 border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                    Clear
                </button>
            </div>
        </div>
    </div>

    {{-- Risks Table --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if(isset($risks) && $risks->count() > 0)
            {{-- Pagination Info --}}
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-3 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Showing <span class="font-semibold">{{ $risks->firstItem() }}</span> to 
                    <span class="font-semibold">{{ $risks->lastItem() }}</span> of 
                    <span class="font-semibold">{{ $risks->total() }}</span> risks
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="risksTable">
                    <thead class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">
                                <input type="checkbox" id="selectAll" class="rounded" onchange="toggleSelectAll(this)">
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Risk ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Risk Title</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Department</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Risk Level</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Owner</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Last Updated</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($risks as $risk)
                            <tr class="hover:bg-gray-50 transition-colors risk-row" data-id="{{ $risk->id }}">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="risk-checkbox rounded" value="{{ $risk->id }}" onchange="updateBulkActionsBar()">
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $risk->risk_id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $risk->title }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ Str::limit($risk->description, 60) }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $risk->department }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $levelColors = [
                                            'low' => 'bg-green-100 text-green-800 border-green-200',
                                            'medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'high' => 'bg-orange-100 text-orange-800 border-orange-200',
                                            'critical' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $levelColors[$risk->risk_level] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        <span class="w-2 h-2 rounded-full {{ $risk->risk_level == 'critical' ? 'bg-red-600' : ($risk->risk_level == 'high' ? 'bg-orange-600' : ($risk->risk_level == 'medium' ? 'bg-yellow-600' : 'bg-green-600')) }}"></span>
                                        {{ ucfirst($risk->risk_level) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'open' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-purple-100 text-purple-800',
                                            'mitigated' => 'bg-green-100 text-green-800',
                                            'closed' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$risk->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $risk->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $risk->owner->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $risk->updated_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- View --}}
                                        <a href="{{ route('risk-register.show', ['risk_register' => $risk->id]) }}"
                                           class="text-blue-600 hover:text-blue-800 transition-colors"
                                           title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('risk-register.edit', ['risk_register' => $risk->id]) }}"
                                           class="text-orange-600 hover:text-orange-800 transition-colors" 
                                           title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('risk-register.destroy', ['risk_register' => $risk->id]) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this risk?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 transition-colors" 
                                                    title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Advanced Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200 bg-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    {{-- Pagination Links --}}
                    <div class="flex flex-wrap gap-1">
                        {{-- Previous Page Link --}}
                        @if ($risks->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                ← Previous
                            </span>
                        @else
                            <a href="{{ $risks->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                ← Previous
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($risks->getUrlRange(1, $risks->lastPage()) as $page => $url)
                            @if ($page == $risks->currentPage())
                                <span class="px-3 py-2 text-sm font-semibold text-white bg-orange-600 rounded-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($risks->hasMorePages())
                            <a href="{{ $risks->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Next →
                            </a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                Next →
                            </span>
                        @endif
                    </div>

                    {{-- Items Per Page Selector --}}
                    <div class="flex items-center gap-2">
                        <label for="perPage" class="text-sm font-medium text-gray-700">Items per page:</label>
                        <select id="perPage" onchange="changeItemsPerPage(this.value)" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Risks Found</h3>
                <p class="text-gray-500 mb-6">Get started by adding your first risk to the register.</p>
                <a href="{{ route('risk-register.create') }}" 
                   class="inline-flex items-center gap-2 bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add First Risk
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Upload Modal --}}
<div id="uploadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Upload Risk Sheet</h2>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')" 
                    class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('risk-register.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <label for="riskFile" class="block text-sm font-semibold text-gray-700">
                    Select Excel File <span class="text-red-500">*</span>
                </label>
                <div class="border-2 border-dashed border-orange-300 rounded-lg p-6 text-center hover:border-orange-500 transition-colors cursor-pointer"
                     onclick="document.getElementById('riskFile').click()">
                    <svg class="w-12 h-12 text-orange-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Click to upload or drag and drop</p>
                    <p class="text-xs text-gray-500">Excel files (CSV, XLS, XLSX)</p>
                </div>
                <input type="file" 
                       id="riskFile"
                       name="file"
                       accept=".csv,.xlsx,.xls"
                       class="hidden"
                       required>
                <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> File should contain columns: title, description, risk_category, likelihood, impact, department, etc.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-orange-600 text-white px-4 py-2.5 rounded-lg hover:bg-orange-700 transition-colors font-semibold">
                    Upload
                </button>
                <button type="button" 
                        onclick="document.getElementById('uploadModal').classList.add('hidden')"
                        class="flex-1 border-2 border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// File upload handler
document.getElementById('riskFile')?.addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || '';
    document.getElementById('fileName').textContent = fileName ? `Selected: ${fileName}` : '';
});

// Drag and drop
const dropZone = document.querySelector('[onclick="document.getElementById(\'riskFile\').click()"]');
if (dropZone) {
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-orange-500', 'bg-orange-50');
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-orange-500', 'bg-orange-50');
    });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-orange-500', 'bg-orange-50');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('riskFile').files = files;
            document.getElementById('fileName').textContent = `Selected: ${files[0].name}`;
        }
    });
}

// Change items per page
function changeItemsPerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    window.location = url.toString();
}

// Bulk selection functions
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.risk-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateBulkActionsBar();
}

function updateBulkActionsBar() {
    const selectedCheckboxes = document.querySelectorAll('.risk-checkbox:checked');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedCheckboxes.length > 0) {
        bulkActionsBar.classList.remove('hidden');
        selectedCount.textContent = selectedCheckboxes.length;
    } else {
        bulkActionsBar.classList.add('hidden');
        document.getElementById('selectAll').checked = false;
    }
}

function clearSelection() {
    document.querySelectorAll('.risk-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActionsBar();
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.risk-checkbox:checked')).map(cb => cb.value);
}

function exportSelected() {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('Please select at least one risk to export');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("risk-register.export-csv") }}';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function exportSelectedPDF() {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('Please select at least one risk to export');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("risk-register.export-pdf") }}';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function deleteSelected() {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('Please select at least one risk to delete');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected risk(s)?`)) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("risk-register.bulk-delete") }}';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease-out, max-height 0.5s ease-out, margin 0.5s ease-out";
            alert.style.opacity = 0;
            alert.style.maxHeight = 0;
            alert.style.marginBottom = 0;
            setTimeout(() => alert.remove(), 500);
        }, 10000);
    });
});
</script>
@endsection