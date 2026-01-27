@extends('layouts.app')

@section('title', 'User | Dashboard')

@section('content')

<!-- Main Dashboard -->
<div class="container mx-auto py-6">

    <!-- Title -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-orange-500">ROPA Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Overview of data processing activities and compliance status</p>
    </div>

    <!-- 4 Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
     @php
    $userId = Auth::id();

    // Total records for this user
    $userRopaCount = \App\Models\Ropa::where('user_id', $userId)->count();

    // Pending ROPA
    $pendingRopaCount = \App\Models\Ropa::where('user_id', $userId)
        ->where('status', \App\Models\Ropa::STATUS_PENDING)
        ->count();

    // Reviewed ROPA
    $reviewedRopaCount = \App\Models\Ropa::where('user_id', $userId)
        ->where('status', \App\Models\Ropa::STATUS_REVIEWED)
        ->count();

    // Overdue = pending + created more than 1 day ago
    $overdueReviews = \App\Models\Ropa::where('user_id', $userId)
        ->where('status', \App\Models\Ropa::STATUS_PENDING)
        ->where('created_at', '<=', now()->subDay())
        ->count();
@endphp



        <!-- Total ROPA Records -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition flex items-center space-x-4 border-l-4 border-orange-500">
            <i data-feather="folder" class="w-10 h-10 text-orange-500"></i>
            <div>
                <div class="text-lg font-semibold">Total ROPA Records</div>
                <div class="mt-2 text-3xl font-bold">{{ $userRopaCount }}</div>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition flex items-center space-x-4 border-l-4 border-yellow-500">
            <i data-feather="clock" class="w-10 h-10 text-yellow-500"></i>
            <div>
                <div class="text-lg font-semibold">Pending Reviews</div>
                <div class="mt-2 text-3xl font-bold">{{ $pendingRopaCount }}</div>
            </div>
        </div>

        <!-- Overdue Reviews -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition flex items-center space-x-4 border-l-4 border-red-500">
    <i data-feather="alert-circle" class="w-10 h-10 text-red-500"></i>
    <div>
        <div class="text-lg font-semibold">Overdue Reviews</div>
        <div class="mt-2 text-3xl font-bold">{{ $overdueReviews }}</div>
    </div>
</div>


        <!-- Tasks Completed -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition flex items-center space-x-4 border-l-4 border-green-500">
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

        <!-- Risk Level Determination Explanation -->
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                <i data-feather="info" class="w-4 h-4 mr-2"></i>
                Risk Level Determination
            </h3>

            <ul class="space-y-2 text-sm">
                <li class="flex justify-between">
                    <span class="font-semibold text-red-700">Critical</span>
                    <span class="text-gray-600 dark:text-gray-400">Score 20–25</span>
                </li>
                <li class="flex justify-between">
                    <span class="font-semibold text-red-600">High</span>
                    <span class="text-gray-600 dark:text-gray-400">Score 12–19</span>
                </li>
                <li class="flex justify-between">
                    <span class="font-semibold text-yellow-600">Medium</span>
                    <span class="text-gray-600 dark:text-gray-400">Score 6–11</span>
                </li>
                <li class="flex justify-between">
                    <span class="font-semibold text-green-600">Low</span>
                    <span class="text-gray-600 dark:text-gray-400">Score 1–5</span>
                </li>
            </ul>
        </div>
    @endif
</div>


<!-- Recent ROPA Submissions -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition">
    <h2 class="text-xl font-bold mb-4 flex items-center text-orange-500">
        <i data-feather="activity" class="w-6 h-6 mr-2 text-orange-500"></i>
        Recent ROPA Submissions
    </h2>

    @php
        use App\Models\Ropa;

    $recentRopas = Ropa::where('user_id', Auth::id())
    ->where('archived', false)
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

    @endphp

    <div class="space-y-4">
        @forelse ($recentRopas as $ropa)
            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg border-l-4 border-orange-500">
                
                <!-- TOP: Org Name + Timestamp -->
                <div class="flex justify-between mb-1">
                    <span class="font-semibold">
                        {{ $ropa->organisation_name 
                            ?? $ropa->other_organisation_name 
                            ?? 'Unnamed Submission' }}
                    </span>

                    <span class="text-sm text-gray-500 dark:text-gray-300 flex items-center gap-1">
                        <i data-feather="clock" class="w-4 h-4 text-orange-500"></i>
                        {{ $ropa->created_at ? $ropa->created_at->format('d/m/Y • h:i A') : 'N/A' }}
                    </span>
                </div>

                <!-- BOTTOM: Department • User • Status -->
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $ropa->department ?? $ropa->other_department ?? 'Unknown Dept' }}
                    • {{ $ropa->user->name ?? 'N/A' }} —
                    
                    <span class="font-semibold 
                        {{ $ropa->status === Ropa::STATUS_REVIEWED ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $ropa->status }}
                    </span>
                </p>
            </div>
        @empty
 <p class="text-sm text-gray-700 dark:text-gray-200 text-center flex items-center justify-center space-x-3">
    <!-- Larger Info Circle Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-9-3a1 1 0 112 0v2a1 1 0 11-2 0V7zm1 4a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd"/>
    </svg>
    <span>No recent ROPA submissions found.</span>
</p>

        @endforelse
    </div>
</div>
</div>


 
<div class="mt-10 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md w-full">

    <h2 class="text-xl font-bold mb-4 flex items-center text-orange-500">
        <i data-feather="" class="w-6 h-6 mr-2 text-orange-500"></i>
        All Submitted ROPA Records
    </h2>

    <!-- SEARCH & FILTER FORM -->
    <form method="GET" class="mb-4 flex flex-col sm:flex-row sm:items-center sm:space-x-4 gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search organisation or department..." 
               class="px-4 py-2 border rounded-lg w-full sm:w-1/3">

        <select name="status" class="px-4 py-2 border rounded-lg w-full sm:w-1/6">
            <option value="">All Status</option>
            <option value="{{ \App\Models\Ropa::STATUS_PENDING }}" {{ request('status') == \App\Models\Ropa::STATUS_PENDING ? 'selected' : '' }}>Pending</option>
            <option value="{{ \App\Models\Ropa::STATUS_REVIEWED }}" {{ request('status') == \App\Models\Ropa::STATUS_REVIEWED ? 'selected' : '' }}>Reviewed</option>
        </select>

        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
            Filter
        </button>
    </form>

    @php
        $allRopas = \App\Models\Ropa::where('user_id', Auth::id())
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('organisation_name', 'like', "%{$search}%")
                      ->orWhere('other_organisation_name', 'like', "%{$search}%")
                      ->orWhere('department', 'like', "%{$search}%")
                      ->orWhere('other_department', 'like', "%{$search}%");
                });
            })
            ->when(request('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->appends(request()->query());
    @endphp


<!-- TABS -->
<div class="border-b border-gray-200 dark:border-gray-700 mb-6">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <button onclick="switchTab('ropa')" 
                id="tab-ropa"
                class="tab-button border-b-2 border-orange-600 text-orange-600 whitespace-nowrap py-4 px-1 font-semibold text-sm">
            <div class="flex items-center gap-2">
                <i data-feather="file-text" class="w-5 h-5"></i>
                ROPA Records
            </div>
        </button>
        <button onclick="switchTab('risks')" 
                id="tab-risks"
                class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 font-semibold text-sm">
            <div class="flex items-center gap-2">
                <i data-feather="alert-triangle" class="w-5 h-5"></i>
                Enterprise Risks
            </div>
        </button>
    </nav>
</div>

<!-- ROPA TABLE -->
<div id="content-ropa" class="tab-content">
    <!-- Bulk Actions Bar for ROPA -->
    <div id="ropa-bulk-actions" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-blue-800">
                    <span id="ropa-selected-count">0</span> item(s) selected
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="bulkShareRopa()" 
                        class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg flex items-center shadow">
                    <i data-feather="share-2" class="w-4 h-4 mr-1"></i> Share Selected
                </button>
                <button onclick="bulkDeleteRopa()" 
                        class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded-lg flex items-center shadow">
                    <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete Selected
                </button>
                <button onclick="clearRopaSelection()" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm px-4 py-2 rounded-lg">
                    Clear Selection
                </button>
            </div>
        </div>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-200 dark:border-gray-700 rounded-lg">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" 
                               id="select-all-ropa"
                               onchange="toggleAllRopa(this)"
                               class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500">
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Organisation</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Department</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Created</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($allRopas as $ropa)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3">
                            <input type="checkbox" 
                                   class="ropa-checkbox w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   value="{{ $ropa->id }}"
                                   onchange="updateRopaSelection()">
                        </td>
                        <td class="px-4 py-3 truncate max-w-xs">
                            {{ $ropa->organisation_name ?? $ropa->other_organisation_name ?? 'Unnamed' }}
                        </td>
                        <td class="px-4 py-3 truncate max-w-xs">
                            {{ $ropa->department ?? $ropa->other_department ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 font-semibold">
                            @if ($ropa->status === \App\Models\Ropa::STATUS_REVIEWED)
                                <span class="text-green-600">Reviewed</span>
                            @else
                                <span class="text-yellow-600">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            {{ $ropa->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('ropa.show', $ropa->id) }}"
                                   class="bg-orange-600 hover:bg-orange-700 text-white text-sm px-3 py-2 rounded-lg flex items-center shadow">
                                    <i data-feather="eye" class="w-4 h-4 mr-1"></i> View
                                </a>
                                
                                <button onclick="openShareModal({{ $ropa->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-2 rounded-lg flex items-center shadow">
                                    <i data-feather="share-2" class="w-4 h-4 mr-1"></i> Share
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                   <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-600 dark:text-gray-400">
                            <div class="flex items-center justify-center space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-9-3a1 1 0 112 0v2a1 1 0 11-2 0V7zm1 4a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd"/>
                                </svg>
                                <span>No ROPA records found.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6 px-4">
        {{ $allRopas->links() }}
    </div>
</div>

<!-- RISKS TABLE -->
<div id="content-risks" class="tab-content hidden">
    <!-- Bulk Actions Bar for Risks -->
    <div id="risks-bulk-actions" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-blue-800">
                    <span id="risks-selected-count">0</span> item(s) selected
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="bulkUpdateRiskStatus()" 
                        class="bg-purple-600 hover:bg-purple-700 text-white text-sm px-4 py-2 rounded-lg flex items-center shadow">
                    <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Update Status
                </button>
                <button onclick="bulkExportRisks()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg flex items-center shadow">
                    <i data-feather="download" class="w-4 h-4 mr-1"></i> Export Selected
                </button>
                <button onclick="bulkDeleteRisks()" 
                        class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded-lg flex items-center shadow">
                    <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete Selected
                </button>
                <button onclick="clearRisksSelection()" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm px-4 py-2 rounded-lg">
                    Clear Selection
                </button>
            </div>
        </div>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-200 dark:border-gray-700 rounded-lg">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" 
                               id="select-all-risks"
                               onchange="toggleAllRisks(this)"
                               class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500">
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Risk ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Title</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Department</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Risk Level</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Source</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($allRisks as $risk)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3">
                            <input type="checkbox" 
                                   class="risk-checkbox w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   value="{{ $risk->id }}"
                                   onchange="updateRisksSelection()">
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            {{ $risk->risk_id }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $risk->title }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ Str::limit($risk->description, 60) }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $risk->department }}
                        </td>
                        <td class="px-4 py-3">
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
                        <td class="px-4 py-3">
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
                        <td class="px-4 py-3">
                            @if($risk->source_type === 'ROPA')
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                    <i data-feather="file-text" class="w-3 h-3 mr-1"></i>
                                    ROPA
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">{{ $risk->source_type ?? 'Manual' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('risk-register.show', $risk->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-2 rounded-lg flex items-center shadow">
                                    <i data-feather="eye" class="w-4 h-4 mr-1"></i> View
                                </a>
                                <a href="{{ route('risk-register.edit', $risk->id) }}"
                                   class="bg-orange-600 hover:bg-orange-700 text-white text-sm px-3 py-2 rounded-lg flex items-center shadow">
                                    <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-600 dark:text-gray-400">
                            <div class="flex items-center justify-center space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-9-3a1 1 0 112 0v2a1 1 0 11-2 0V7zm1 4a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd"/>
                                </svg>
                                <span>No enterprise risks found.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6 px-4">
        {{ $allRisks->links() }}
    </div>
</div>

<script>
// Tab switching function
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-orange-600', 'text-orange-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-orange-600', 'text-orange-600');
    
    feather.replace();
}

// ROPA Selection Functions
function toggleAllRopa(checkbox) {
    const checkboxes = document.querySelectorAll('.ropa-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateRopaSelection();
}

function updateRopaSelection() {
    const checkboxes = document.querySelectorAll('.ropa-checkbox:checked');
    const count = checkboxes.length;
    const bulkActions = document.getElementById('ropa-bulk-actions');
    const countDisplay = document.getElementById('ropa-selected-count');
    
    countDisplay.textContent = count;
    
    if (count > 0) {
        bulkActions.classList.remove('hidden');
    } else {
        bulkActions.classList.add('hidden');
    }
    
    // Update "select all" checkbox state
    const allCheckboxes = document.querySelectorAll('.ropa-checkbox');
    const selectAllCheckbox = document.getElementById('select-all-ropa');
    selectAllCheckbox.checked = count === allCheckboxes.length && count > 0;
}

function clearRopaSelection() {
    document.querySelectorAll('.ropa-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('select-all-ropa').checked = false;
    updateRopaSelection();
}

function getSelectedRopaIds() {
    const checkboxes = document.querySelectorAll('.ropa-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function bulkShareRopa() {
    const ids = getSelectedRopaIds();
    if (ids.length === 0) {
        alert('Please select at least one ROPA record');
        return;
    }
    // Implement your bulk share logic here
    console.log('Sharing ROPA records:', ids);
    alert(`Sharing ${ids.length} ROPA record(s)`);
}

function bulkDeleteRopa() {
    const ids = getSelectedRopaIds();
    if (ids.length === 0) {
        alert('Please select at least one ROPA record');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${ids.length} ROPA record(s)?`)) {
        // Implement your bulk delete logic here
        console.log('Deleting ROPA records:', ids);
        // Example: Submit form or make AJAX request
        // window.location.href = `/ropa/bulk-delete?ids=${ids.join(',')}`;
    }
}

// Risks Selection Functions
function toggleAllRisks(checkbox) {
    const checkboxes = document.querySelectorAll('.risk-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateRisksSelection();
}

function updateRisksSelection() {
    const checkboxes = document.querySelectorAll('.risk-checkbox:checked');
    const count = checkboxes.length;
    const bulkActions = document.getElementById('risks-bulk-actions');
    const countDisplay = document.getElementById('risks-selected-count');
    
    countDisplay.textContent = count;
    
    if (count > 0) {
        bulkActions.classList.remove('hidden');
    } else {
        bulkActions.classList.add('hidden');
    }
    
    const allCheckboxes = document.querySelectorAll('.risk-checkbox');
    const selectAllCheckbox = document.getElementById('select-all-risks');
    selectAllCheckbox.checked = count === allCheckboxes.length && count > 0;
}

function clearRisksSelection() {
    document.querySelectorAll('.risk-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('select-all-risks').checked = false;
    updateRisksSelection();
}

function getSelectedRiskIds() {
    const checkboxes = document.querySelectorAll('.risk-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function bulkUpdateRiskStatus() {
    const ids = getSelectedRiskIds();
    if (ids.length === 0) {
        alert('Please select at least one risk');
        return;
    }
    // Implement status update modal or logic here
    console.log('Updating risk status for:', ids);
    alert(`Update status for ${ids.length} risk(s)`);
}

function bulkExportRisks() {
    const ids = getSelectedRiskIds();
    if (ids.length === 0) {
        alert('Please select at least one risk');
        return;
    }
    // Implement export logic here
    console.log('Exporting risks:', ids);
    window.location.href = `/risk-register/export?ids=${ids.join(',')}`;
}

function bulkDeleteRisks() {
    const ids = getSelectedRiskIds();
    if (ids.length === 0) {
        alert('Please select at least one risk');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${ids.length} risk(s)?`)) {
        // Implement your bulk delete logic here
        console.log('Deleting risks:', ids);
        // Example: window.location.href = `/risk-register/bulk-delete?ids=${ids.join(',')}`;
    }
}

// Initialize feather icons on page load
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});
</script>



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

<div id="shareModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 w-full max-w-3xl p-10 rounded-2xl shadow-2xl">
        <h2 class="text-3xl font-bold mb-8 text-orange-600 text-center">Share ROPA Record</h2>

        <form id="shareForm" method="POST">
            @csrf
            <label class="block mb-2 font-semibold text-lg">Recipient Email</label>
            <input type="email" name="email" class="w-full px-5 py-3 mb-6 border rounded-lg dark:bg-gray-700 dark:text-white" placeholder="example@domain.com" required>

            <label class="block mb-2 font-semibold text-lg">CC (optional)</label>
            <input type="text" name="cc" class="w-full px-5 py-3 mb-6 border rounded-lg dark:bg-gray-700 dark:text-white" placeholder="email1@example.com, email2@example.com">

            <label class="block mb-2 font-semibold text-lg">Email Subject</label>
            <input type="text" name="subject" class="w-full px-5 py-3 mb-6 border rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Enter email subject" required>

            <label class="block mb-2 font-semibold text-lg">Format</label>
            <select name="format" class="w-full px-5 py-3 mb-6 border rounded-lg dark:bg-gray-700 dark:text-white">
                <option value="pdf">PDF</option>
                <!-- <option value="excel">Excel</option> -->
            </select>

            <div class="flex justify-end space-x-4 pt-6">
                <button type="button" onclick="closeShareModal()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">Send</button>
            </div>
        </form>
    </div>
</div>

<script>
function openShareModal(ropaId) {
    const modal = document.getElementById('shareModal');
    modal.classList.remove('hidden');
    document.getElementById('shareForm').action = "/ropa/" + ropaId + "/send-email"; // POST route
}

function closeShareModal() {
    document.getElementById('shareModal').classList.add('hidden');
}
</script>



@endsection
