@extends('layouts.admin')

@section('title', 'Risk Analytics Overview')

@section('content')
<div class="container mx-auto p-4 md:p-6">

    {{-- PAGE TITLE --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 space-y-2 md:space-y-0">
        <h1 class="text-2xl font-bold text-orange-600 flex items-center">
            <i data-feather="bar-chart-2" class="w-6 h-6 mr-2"></i>
            Risk Analytics Overview
        </h1>

        {{-- FILTERS --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
            <select class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto">
                <option>All Departments</option>
                <option>HR</option>
                <option>Finance</option>
                <option>Operations</option>
            </select>

            <button class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded flex items-center w-full sm:w-auto justify-center">
                <i data-feather="search" class="w-4 h-4 mr-1"></i> Filter
            </button>
        </div>
    </div>

    {{-- TABS NAVIGATION --}}
    <div class="mb-6 border-b">
        <nav class="flex flex-wrap space-x-2">
            <button class="tab-link px-4 py-2 bg-orange-50 text-orange-600 rounded-t-lg font-medium" data-tab="risk">Risk Overview</button>
            <button class="tab-link px-4 py-2 bg-gray-100 text-gray-600 rounded-t-lg font-medium" data-tab="user">User Analytics</button>
        </nav>
    </div>

    {{-- TABS CONTENT --}}
    <div>
        {{-- Risk Overview Tab --}}
        <div id="tab-content-risk" class="tab-content">

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white shadow rounded p-4 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">High Risk</p>
                        <p class="text-3xl font-bold text-red-500">{{ $highRisk ?? 0 }}%</p>
                    </div>
                    <i data-feather="alert-triangle" class="text-red-500 w-10 h-10"></i>
                </div>

                <div class="bg-white shadow rounded p-4 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Medium Risk</p>
                        <p class="text-3xl font-bold text-yellow-500">{{ $mediumRisk ?? 0 }}%</p>
                    </div>
                    <i data-feather="alert-circle" class="text-yellow-500 w-10 h-10"></i>
                </div>

                <div class="bg-white shadow rounded p-4 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Low Risk</p>
                        <p class="text-3xl font-bold text-green-500">{{ $lowRisk ?? 0 }}%</p>
                    </div>
                    <i data-feather="check-circle" class="text-green-500 w-10 h-10"></i>
                </div>

                <div class="bg-white shadow rounded p-4 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Records</p>
                        <p class="text-3xl font-bold text-orange-600">{{ \App\Models\Ropa::count() }}</p>
                    </div>
                    <i data-feather="database" class="text-orange-600 w-10 h-10"></i>
                </div>
            </div>

            {{-- DONUT CHART + SUMMARY --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 bg-white shadow rounded p-6">

                <div>
                    <canvas id="riskChart" class="w-full h-64 md:h-80"></canvas>
                    <div class="mt-4 space-y-1">
                        <p class="flex items-center"><span class="w-3 h-3 bg-red-800 rounded-full mr-2"></span> Critical Risk</p>
                        <p class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span> High Risk</p>
                        <p class="flex items-center"><span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span> Medium Risk</p>
                        <p class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> Low Risk</p>
                    </div>
                </div>

                <div class="grid gap-4">
                    <div class="p-4 border-l-4 border-orange-400 bg-orange-50 rounded">
                        <p class="text-gray-600">Total Records:</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Ropa::count() }}</p>
                    </div>

                    <div class="p-4 border-l-4 border-green-400 bg-green-50 rounded">
                        <p class="text-gray-600">Reviewed Records:</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Ropa::where('status', \App\Models\Ropa::STATUS_REVIEWED)->count() }}</p>
                    </div>

                    <div class="p-4 border-l-4 border-yellow-400 bg-yellow-50 rounded">
                        <p class="text-gray-600">Pending Records:</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Ropa::where('status', \App\Models\Ropa::STATUS_PENDING)->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- TABLE SECTION --}}
            <div class="bg-white shadow rounded p-6 mb-6 overflow-x-auto">
                <h2 class="text-xl font-semibold mb-4">Submitted ROPA Records</h2>

                {{-- Filters --}}
                <div class="flex flex-col md:flex-row items-start md:items-center mb-4 space-y-2 md:space-y-0 md:space-x-3">
                    <input type="text" placeholder="Search..." class="border rounded px-3 py-2 w-full md:w-1/3">
                    <select class="border rounded px-3 py-2">
                        <option value="">All Status</option>
                        <option value="{{ \App\Models\Ropa::STATUS_REVIEWED }}">Approved</option>
                        <option value="{{ \App\Models\Ropa::STATUS_PENDING }}">Pending</option>
                    </select>

                    <select class="border rounded px-3 py-2">
                        <option value="">All Departments</option>
                        @foreach(\App\Models\Ropa::distinct('department')->pluck('department') as $department)
                            <option value="{{ $department }}">{{ $department }}</option>
                        @endforeach
                    </select>

                    <div class="flex space-x-2">
                        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Export CSV</button>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Export PDF</button>
                    </div>
                </div>

                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-3">Organisation</th>
                            <th class="p-3">Department</th>
                            <th class="p-3">Submitted By</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Risk Score</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ropas as $ropa)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $ropa->organisation_name }}</td>
                                <td class="p-3">{{ $ropa->department }}</td>
                                <td class="p-3">{{ $ropa->user->name ?? 'N/A' }}</td>
                                <td class="p-3">{{ $ropa->created_at->format('d M Y') }}</td>
                                <td class="p-3">
                                    @if($ropa->isReviewed())
                                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded">Approved</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded">Pending</span>
                                    @endif
                                </td>
                                <td class="p-3">{{ $ropa->calculateRiskScore() }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-3 text-center text-gray-500">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- User Analytics Tab --}}
        <div id="tab-content-user" class="tab-content hidden">
            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-semibold mb-4">User Analytics</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <h3 class="text-lg font-semibold mb-2">User Type Distribution</h3>
                        <canvas id="userTypeChart" class="w-full h-64 md:h-80"></canvas>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">Users per Department</h3>
                        <canvas id="departmentChart" class="w-full h-64 md:h-80"></canvas>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">Active vs Inactive Users</h3>
                        <canvas id="activeChart" class="w-full h-64 md:h-80"></canvas>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">Two Factor Authentication</h3>
                        <canvas id="twoFactorChart" class="w-full h-64 md:h-80"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- FEATHER ICONS --}}
<script>
    feather.replace();
</script>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> <!-- Plugin for numbers -->

<script>
    // TAB SWITCHING
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', () => {
            const tab = link.dataset.tab;
            tabContents.forEach(c => c.classList.add('hidden'));
            document.getElementById('tab-content-' + tab).classList.remove('hidden');
            tabLinks.forEach(l => l.classList.remove('bg-orange-50', 'text-orange-600'));
            tabLinks.forEach(l => l.classList.add('bg-gray-100', 'text-gray-600'));
            link.classList.add('bg-orange-50', 'text-orange-600');
            link.classList.remove('bg-gray-100', 'text-gray-600');
        });
    });

    // RISK CHART
    const ctxRisk = document.getElementById('riskChart').getContext('2d');
    new Chart(ctxRisk, {
        type: 'doughnut',
        data: {
            labels: ['Critical Risk', 'High Risk', 'Medium Risk', 'Low Risk'],
            datasets: [{
                data: [{{ $criticalRisk ?? 0 }}, {{ $highRisk ?? 0 }}, {{ $mediumRisk ?? 0 }}, {{ $lowRisk ?? 0 }}],
                backgroundColor: ['#7f1d1d','#ef4444','#facc15','#22c55e'],
            }],
        },
        options: { 
            cutout: '60%',
            plugins: {
                datalabels: {
                    color: '#fff',
                    formatter: (value) => value + '%',
                    font: { weight: 'bold', size: 14 }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // USER ANALYTICS CHARTS
    const users = @json(\App\Models\User::all());

    // User Type
    const adminCount = users.filter(u => u.user_type === 1).length;
    const normalCount = users.filter(u => u.user_type === 0).length;
    new Chart(document.getElementById('userTypeChart').getContext('2d'), {
        type: 'pie',
        data: { labels: ['Admin', 'User'], datasets: [{ data: [adminCount, normalCount], backgroundColor: ['#ef4444','#22c55e'] }] },
        options: { plugins: { datalabels: { color: '#fff', formatter: v => v, font: { weight: 'bold', size: 14 } } } },
        plugins: [ChartDataLabels]
    });

    // Department Distribution
    const deptCounts = {};
    users.forEach(u => { const d = u.department || 'Unassigned'; deptCounts[d] = (deptCounts[d] || 0) + 1; });
    new Chart(document.getElementById('departmentChart').getContext('2d'), {
        type: 'bar',
        data: { labels: Object.keys(deptCounts), datasets: [{ label: 'Users', data: Object.values(deptCounts), backgroundColor: '#3b82f6' }] },
        options: { scales: { y: { beginAtZero: true } }, plugins: { datalabels: { anchor:'end', align:'end', color:'#000', font:{weight:'bold'} } } },
        plugins: [ChartDataLabels]
    });

    // Active vs Inactive
    const activeCount = users.filter(u => u.active).length;
    const inactiveCount = users.filter(u => !u.active).length;
    new Chart(document.getElementById('activeChart').getContext('2d'), {
        type: 'doughnut',
        data: { labels: ['Active', 'Inactive'], datasets: [{ data: [activeCount, inactiveCount], backgroundColor: ['#10b981','#ef4444'] }] },
        options: { plugins: { datalabels: { color:'#fff', formatter:v=>v, font:{weight:'bold', size:14} } } },
        plugins: [ChartDataLabels]
    });

    // 2FA Enabled
    const twoFactorEnabled = users.filter(u => u.two_factor_enabled).length;
    const twoFactorDisabled = users.filter(u => !u.two_factor_enabled).length;
    new Chart(document.getElementById('twoFactorChart').getContext('2d'), {
        type: 'doughnut',
        data: { labels: ['2FA Enabled', '2FA Disabled'], datasets: [{ data: [twoFactorEnabled, twoFactorDisabled], backgroundColor: ['#6366f1','#facc15'] }] },
        options: { plugins: { datalabels: { color:'#fff', formatter:v=>v, font:{weight:'bold', size:14} } } },
        plugins: [ChartDataLabels]
    });
</script>
@endsection
