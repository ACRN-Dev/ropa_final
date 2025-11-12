@extends('layouts.admin')

@section('title', 'Risk Analytics')

@section('content')
<div class="container mx-auto p-4 sm:p-6">
    <!-- Page Header & Filter -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
        <h2 class="text-3xl font-bold text-indigo-700 flex items-center mb-4 sm:mb-0">
            <i data-feather="bar-chart-2" class="w-6 h-6 mr-2"></i> Risk Analytics Overview
        </h2>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.analytics') }}" class="flex flex-col sm:flex-row items-center gap-2">
            <select name="department" class="px-3 py-2 border rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-indigo-500">
                <option value="">All Departments</option>
                <option value="HR" @if(request('department')=='HR') selected @endif>HR</option>
                <option value="Finance" @if(request('department')=='Finance') selected @endif>Finance</option>
                <option value="IT" @if(request('department')=='IT') selected @endif>IT</option>
            </select>

            <input type="month" name="month" value="{{ request('month') }}" class="px-3 py-2 border rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-indigo-500" />

            <button type="submit" class="flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm sm:text-base">
                <i data-feather="filter" class="w-4 h-4 mr-1"></i> Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
        @php
            $cards = [
                ['title'=>'High Risk', 'value'=>$highRisk ?? 25, 'icon'=>'alert-triangle', 'color'=>'red'],
                ['title'=>'Medium Risk', 'value'=>$mediumRisk ?? 40, 'icon'=>'alert-circle', 'color'=>'yellow'],
                ['title'=>'Low Risk', 'value'=>$lowRisk ?? 35, 'icon'=>'check-circle', 'color'=>'green'],
                ['title'=>'Total Records', 'value'=>$totalRecords ?? 200, 'icon'=>'database', 'color'=>'indigo'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="bg-white shadow-lg rounded-xl p-5 flex items-center space-x-4 border-l-4 border-{{ $card['color'] }}-600 hover:shadow-xl transition">
                <div class="p-3 bg-{{ $card['color'] }}-100 rounded-full">
                    <i data-feather="{{ $card['icon'] }}" class="w-6 h-6 text-{{ $card['color'] }}-600"></i>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">{{ $card['title'] }}</h3>
                    <p class="text-xl font-bold text-gray-800">{{ $card['value'] }}{{ $card['title'] !== 'Total Records' ? '%' : '' }}</p>
                </div>
            </div>
        @endforeach
    </div>
<!-- Risk Distribution Chart -->
<div class="bg-white shadow-lg rounded-xl p-6 mb-6">
    <h3 class="text-lg font-bold mb-4 text-gray-700 flex items-center">
        <i data-feather="pie-chart" class="w-5 h-5 mr-2 text-orange-500"></i>
        Africa Clinical Research Network Record Of All Processing Risk Distribution
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        <!-- Chart -->
        <div class="w-full max-w-md mx-auto">
            <canvas id="riskChart" class="h-64"></canvas>
        </div>

        <!-- Summary Stats -->
        <div class="flex flex-col space-y-3 text-gray-700">
            <div class="flex justify-between items-center bg-orange-50 border-l-4 border-blue-500 rounded-lg p-3">
                <span class="font-semibold">Total Records:</span>
                <span class="text-gray-800 font-bold">{{ $totalRecords }}</span>
            </div>

            <div class="flex justify-between items-center bg-green-50 border-l-4 border-green-500 rounded-lg p-3">
                <span class="font-semibold">Reviewed Records:</span>
                <span class="text-gray-800 font-bold">{{ $reviewedCount }}</span>
            </div>

            <div class="flex justify-between items-center bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-3">
                <span class="font-semibold">Pending Records:</span>
                <span class="text-gray-800 font-bold">{{ $pendingCount }}</span>
            </div>
        </div>
    </div>
</div>


    <!-- ROPA Records Table -->
    <div class="bg-white shadow-lg rounded-xl p-6 mb-6">
        <h3 class="text-lg font-bold mb-4 text-gray-700 flex items-center">
            <i data-feather="file-text" class="w-5 h-5 mr-2 text-indigo-600"></i> Submitted ROPA Records
        </h3>

        <!-- Top Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-2">
            <div class="flex gap-2 items-center">
                <input type="text" id="searchBox" placeholder="Search..." class="px-3 py-2 border rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-indigo-500">
                
                <select id="statusFilter" class="px-3 py-2 border rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="Approved">Approved</option>
                    <option value="Pending">Pending</option>
                </select>

                <select id="departmentFilter" class="px-3 py-2 border rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Departments</option>
                    <option value="HR">HR</option>
                    <option value="Finance">Finance</option>
                    <option value="IT">IT</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button id="exportCsv" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm sm:text-base">
                    <i data-feather="file-text" class="w-4 h-4 mr-1"></i> Export CSV
                </button>
                <button id="exportPdf" class="flex items-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm sm:text-base">
                    <i data-feather="file-text" class="w-4 h-4 mr-1"></i> Export PDF
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="ropasTable" class="min-w-full table-auto border-collapse text-sm sm:text-base">
                <thead class="bg-indigo-700 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Organisation</th>
                        <th class="py-3 px-4 text-left">Department</th>
                        <th class="py-3 px-4 text-left">Submitted By</th>
                        <th class="py-3 px-4 text-left">Date Submitted</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-center">Risk Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ropas as $ropa)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $ropa->organisation_name }}</td>
                            <td class="py-3 px-4">{{ $ropa->department_name ?? $ropa->other_department ?? '—' }}</td>
                            <td class="py-3 px-4">{{ $ropa->user->name ?? '—' }}</td>
                            <td class="py-3 px-4">{{ $ropa->date_submitted ? $ropa->date_submitted->format('d M Y') : '—' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs sm:text-sm rounded-full {{ $ropa->status === 'Approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $ropa->status ?? 'Pending' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">{{ $ropa->calculateRiskScore() }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">No ROPA records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js & DataTables -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwind.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwind.min.css" />

<script>
    // Risk Chart with Data Labels and Legend
    const ctx = document.getElementById('riskChart').getContext('2d');
    const riskChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['High Risk', 'Medium Risk', 'Low Risk'],
            datasets: [{
                label: 'Risk Levels',
                data: [{{ $highRisk ?? 25 }}, {{ $mediumRisk ?? 40 }}, {{ $lowRisk ?? 35 }}],
                backgroundColor: [
                    'rgba(220, 38, 38, 0.7)', // High Risk
                    'rgba(234, 179, 8, 0.7)', // Medium Risk
                    'rgba(22, 163, 74, 0.7)'  // Low Risk
                ],
                borderColor: [
                    'rgba(220, 38, 38, 1)',
                    'rgba(234, 179, 8, 1)',
                    'rgba(22, 163, 74, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 14 },
                    formatter: (value, ctx) => value + '%'
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: { size: 14, weight: 'bold' }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + '%';
                        }
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // DataTables Initialization
    $(document).ready(function() {
        var table = $('#ropasTable').DataTable({
            responsive: true,
            pageLength: 10,
            ordering: true,
            columnDefs: [{ orderable: false, targets: 4 }] // Disable sorting on Status
        });

        // Global search box
        $('#searchBox').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Department filter
        $('#departmentFilter').on('change', function() {
            table.column(1).search(this.value).draw();
        });

        // Status filter
        $('#statusFilter').on('change', function() {
            table.column(4).search(this.value).draw();
        });
    });

    feather.replace();
</script>
@endsection
