@extends('layouts.admin')

@section('title', 'View ROPA Record')

@section('content')
<div class="container mx-auto py-6">

    <!-- Page Header with Back Button -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.ropa.index') }}" 
               class="inline-flex items-center bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i data-feather="arrow-left" class="w-4 h-4 mr-1"></i> Back
            </a>
            <h2 class="text-2xl font-bold text-indigo-700 flex items-center">
                <i data-feather="eye" class="w-6 h-6 mr-2"></i> View ROPA Record #{{ $ropa->id }}
            </h2>
        </div>
    </div>

    <p class="text-gray-600 dark:text-gray-400 mb-4">ROPA record details and risk weight settings.</p>

    <!-- ROPA Details -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-indigo-700 mb-4">ROPA Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="font-semibold text-gray-700">Organisation Name:</span>
                <p class="text-gray-800">{{ $ropa->organisation_name }}</p>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Department Name:</span>
                <p class="text-gray-800">{{ $ropa->department_name ?? $ropa->other_department }}</p>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Status:</span>
                <p class="text-gray-800">{{ ucfirst($ropa->status) }}</p>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Date Submitted:</span>
                <p class="text-gray-800">{{ $ropa->date_submitted?->format('d M Y') ?? '—' }}</p>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Processor:</span>
                <p class="text-gray-800">{{ $ropa->processor ?? '—' }}</p>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Country:</span>
                <p class="text-gray-800">{{ $ropa->country ?? '—' }}</p>
            </div>
            <div class="md:col-span-2">
                <span class="font-semibold text-gray-700">Lawful Basis:</span>
                <p class="text-gray-800">
                    {{ is_array($ropa->lawful_basis) ? implode(', ', $ropa->lawful_basis) : ($ropa->lawful_basis ?? '—') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Risk Weight Settings -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h3 class="text-lg font-semibold text-indigo-700 mb-4">Risk Weight Settings (%)</h3>

        @php
            $scoreableFields = [
                'status',
                'other_specify',
                'information_shared',
                'information_nature',
                'outsourced_processing',
                'processor',
                'transborder_processing',
                'country',
                'lawful_basis',
                'retention_period_years',
                'retention_rationale',
                'users_count',
                'access_control',
                'personal_data_category',
            ];
            $weights = $ropa->riskWeightSettings->pluck('weight', 'field_name');
        @endphp

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-left">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="py-2 px-4">Field</th>
                        <th class="py-2 px-4">Weight (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scoreableFields as $field)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-2 px-4 capitalize">{{ str_replace('_', ' ', $field) }}</td>
                            <td class="py-2 px-4">{{ $weights[$field] ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    feather.replace();
</script>
@endsection
