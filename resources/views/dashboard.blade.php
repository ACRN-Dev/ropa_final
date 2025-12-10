@extends('layouts.app')

@section('title', 'User | Dashboard')

@section('content')

<!-- Top Navigation -->
<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
    <div class="container mx-auto px-4 flex justify-between items-center h-16">
        <!-- Left: Logo / Brand -->
        <div class="flex items-center space-x-3">
            <i data-feather="layers" class="w-6 h-6 text-orange-500"></i>
            <span class="font-bold text-xl text-gray-800 dark:text-gray-100">Dashboard</span>
        </div>


        <!-- Right: Notification + User Dropdown -->
        <div class="flex items-center space-x-6">
            <!-- Notification -->
            <div class="relative">
               
            </div>


            
            <!-- User Dropdown -->
            <div class="relative">
                <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                    <i data-feather="user" class="w-6 h-6 text-gray-600 dark:text-gray-300"></i>
                    <span class="text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                    <i data-feather="chevron-down" class="w-4 h-4 text-gray-600 dark:text-gray-300"></i>
                </button>
                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-lg py-2 z-50">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <i data-feather="user" class="w-4 h-4 text-orange-500"></i>
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i data-feather="log-out" class="w-4 h-4 text-red-500"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>



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
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition">
    <h2 class="text-xl font-bold mb-4 flex items-center text-orange-500">
        <i data-feather="bar-chart-2" class="w-6 h-6 mr-2 text-orange-500"></i>
        Risk Distribution
    </h2>

    @if ($reviews->count() === 0)
        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
            <i data-feather="info" class="w-6 h-6 mx-auto mb-2"></i>
            <p class="font-semibold">No risk data available.</p>
            <p class="text-sm">Once reviews are submitted, risk distribution will appear here.</p>
        </div>

    @else
        <div class="space-y-4">

            <!-- Critical Risk -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <div class="flex items-center gap-2">
                        <i data-feather="alert-triangle" class="w-4 h-4 text-red-700"></i>
                        <span class="font-semibold text-red-700">Critical Risk</span>
                    </div>
                    <div>
                        <span class="font-bold text-red-700">
                            {{ $reviews->filter(fn($r) => $r->total_score <= 50)->count() }}
                        </span>
                        ({{ $criticalRisk }}%)
                    </div>
                </div>
                <div class="w-full bg-red-100 h-2 rounded-full">
                    <div class="bg-red-700 h-2 rounded-full" style="width: {{ $criticalRisk }}%"></div>
                </div>
            </div>

            <!-- High Risk -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <div class="flex items-center gap-2">
                        <i data-feather="alert-circle" class="w-4 h-4 text-red-600"></i>
                        <span class="font-semibold text-red-600">High Risk</span>
                    </div>
                    <div>
                        <span class="font-bold text-red-600">
                            {{ $reviews->filter(fn($r) => $r->total_score > 50 && $r->total_score <= 100)->count() }}
                        </span>
                        ({{ $highRisk }}%)
                    </div>
                </div>
                <div class="w-full bg-red-100 h-2 rounded-full">
                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $highRisk }}%"></div>
                </div>
            </div>

            <!-- Medium Risk -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <div class="flex items-center gap-2">
                        <i data-feather="alert-octagon" class="w-4 h-4 text-yellow-600"></i>
                        <span class="font-semibold text-yellow-600">Medium Risk</span>
                    </div>
                    <div>
                        <span class="font-bold text-yellow-600">
                            {{ $reviews->filter(fn($r) => $r->total_score > 100 && $r->total_score <= 160)->count() }}
                        </span>
                        ({{ $mediumRisk }}%)
                    </div>
                </div>
                <div class="w-full bg-yellow-100 h-2 rounded-full">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $mediumRisk }}%"></div>
                </div>
            </div>

            <!-- Low Risk -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <div class="flex items-center gap-2">
                        <i data-feather="check-circle" class="w-4 h-4 text-green-600"></i>
                        <span class="font-semibold text-green-600">Low Risk</span>
                    </div>
                    <div>
                        <span class="font-bold text-green-600">
                            {{ $reviews->filter(fn($r) => $r->total_score > 160)->count() }}
                        </span>
                        ({{ $lowRisk }}%)
                    </div>
                </div>
                <div class="w-full bg-green-100 h-2 rounded-full">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $lowRisk }}%"></div>
                </div>
            </div>

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

    <!-- TABLE WRAPPER WITH SCROLL -->
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-200 dark:border-gray-700 rounded-lg">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
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
                        <td colspan="5" class="px-4 py-6 text-center text-gray-600 dark:text-gray-400">
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
