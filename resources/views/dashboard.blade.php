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

    <!-- Full Width ROPA Records Card -->
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

        <!-- ROPA TABLE -->
        <div>
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
                            <th class="px-4 py-3 text-left text-sm font-semibold">Processes</th>
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
                                <td class="px-4 py-3 max-w-xs">
                                    @php
                                        $processes = is_array($ropa->processes) ? $ropa->processes : [];
                                    @endphp
                                    @if (!empty($processes))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($processes as $process)
                                                <span class="inline-block bg-orange-100 text-orange-800 text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap">
                                                    {{ $process }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm italic">No processes</span>
                                    @endif
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
                                    <a href="{{ route('ropa.show', $ropa->id) }}"
                                       class="bg-orange-600 hover:bg-orange-700 text-white text-sm px-3 py-2 rounded-lg inline-flex items-center shadow">
                                        <i data-feather="eye" class="w-4 h-4 mr-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-600 dark:text-gray-400">
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



    </div><!-- end full-width card -->

</div><!-- end container -->

<!-- Feather Icons -->
<script src="https://unpkg.com/feather-icons"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    feather.replace();
});

// ── ROPA Selection ──────────────────────────────────────────
function toggleAllRopa(checkbox) {
    document.querySelectorAll('.ropa-checkbox').forEach(cb => cb.checked = checkbox.checked);
    updateRopaSelection();
}

function updateRopaSelection() {
    const checked = document.querySelectorAll('.ropa-checkbox:checked');
    const count = checked.length;
    const bulkActions = document.getElementById('ropa-bulk-actions');
    document.getElementById('ropa-selected-count').textContent = count;

    count > 0 ? bulkActions.classList.remove('hidden') : bulkActions.classList.add('hidden');

    const all = document.querySelectorAll('.ropa-checkbox');
    document.getElementById('select-all-ropa').checked = count === all.length && count > 0;
}

function clearRopaSelection() {
    document.querySelectorAll('.ropa-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('select-all-ropa').checked = false;
    updateRopaSelection();
}

function getSelectedRopaIds() {
    return Array.from(document.querySelectorAll('.ropa-checkbox:checked')).map(cb => cb.value);
}

function bulkShareRopa() {
    const ids = getSelectedRopaIds();
    if (!ids.length) { alert('Please select at least one ROPA record'); return; }
    alert(`Sharing ${ids.length} ROPA record(s)`);
}

function bulkDeleteRopa() {
    const ids = getSelectedRopaIds();
    if (!ids.length) { alert('Please select at least one ROPA record'); return; }
    if (confirm(`Are you sure you want to delete ${ids.length} ROPA record(s)?`)) {
        console.log('Deleting ROPA records:', ids);
        // window.location.href = `/ropa/bulk-delete?ids=${ids.join(',')}`;
    }
}


</script>

@endsection