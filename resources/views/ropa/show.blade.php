@extends('layouts.admin')

@section('title', 'ROPA Details')

@section('content')
@php
    $toList = function ($value) {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                $value = [$value];
            }
        }

        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($item) => is_string($item) ? trim($item) : $item,
            $value
        ), fn ($item) => $item !== null && $item !== ''));
    };

    $renderList = function ($value) use ($toList) {
        $items = $toList($value);
        if (!$items) {
            return '<span class="text-gray-400 text-sm">N/A</span>';
        }

        $html = '';
        foreach ($items as $item) {
            $html .= '<span class="inline-flex items-center px-2.5 py-1 rounded-md border border-gray-200 bg-gray-50 text-xs text-gray-700 mr-2 mb-2">'.e($item).'</span>';
        }
        return $html;
    };

    $statusColor = match($ropa->status ?? 'Pending') {
        'Reviewed', 'Approved' => 'bg-green-100 text-green-700 border-green-200',
        'Rejected' => 'bg-red-100 text-red-700 border-red-200',
        default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
    };

    $riskColor = match(strtolower((string) $ropa->risk_level)) {
        'critical' => 'bg-red-100 text-red-800 border-red-300',
        'high' => 'bg-orange-100 text-orange-800 border-orange-300',
        'medium' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'low' => 'bg-green-100 text-green-800 border-green-300',
        default => 'bg-gray-100 text-gray-700 border-gray-300',
    };

    $sections = [
        'Organisation' => [
            'Organisation Name' => $ropa->organisation_name,
            'Other Organisation' => $ropa->other_organisation_name,
            'Department' => $ropa->department,
            'Other Department' => $ropa->other_department,
        ],
        'Processing' => [
            'Processes' => $ropa->processes,
            'Information Nature' => $ropa->information_nature,
            'Data Sources' => $ropa->data_sources,
            'Data Formats' => $ropa->data_formats,
            'Personal Data Categories' => $ropa->personal_data_categories,
        ],
        'Volume and Retention' => [
            'Records Count' => $ropa->records_count,
            'Data Volume' => $ropa->data_volume,
            'Retention Period' => $ropa->retention_period_years,
            'Access Estimate' => $ropa->access_estimate,
        ],
        'Sharing and Security' => [
            'Information Shared' => $ropa->information_shared ? 'Yes' : 'No',
            'Sharing Type' => $ropa->sharing_type,
            'Local Organizations' => $ropa->local_organizations,
            'Transborder Countries' => $ropa->transborder_countries,
            'Sharing Comment' => $ropa->sharing_comment,
            'Access Control' => $ropa->access_control ? 'Yes' : 'No',
            'Access Measures' => $ropa->access_measures,
            'Technical Measures' => $ropa->technical_measures,
            'Organisational Measures' => $ropa->organisational_measures,
        ],
        'Lawful Basis and Risk' => [
            'Lawful Basis' => $ropa->lawful_basis,
            'Risk Report' => $ropa->risk_report,
        ],
    ];
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">ROPA Record</h1>
            <p class="text-sm text-gray-500 mt-1">Record #{{ $ropa->id }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm font-semibold">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                Dashboard
            </a>
            <a href="{{ route('ropa.edit', $ropa->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-700 text-sm font-semibold">
                <i data-feather="edit-2" class="w-4 h-4"></i>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</p>
            <div class="mt-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md border text-xs font-semibold {{ $statusColor }}">
                    {{ $ropa->status ?? 'Pending' }}
                </span>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Risk Level</p>
            <div class="mt-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md border text-xs font-semibold {{ $riskColor }}">
                    {{ $ropa->risk_level ? ucfirst($ropa->risk_level) : 'Not Set' }}
                </span>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Submitted By</p>
            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $ropa->user->name ?? 'Unknown' }}</p>
            <p class="text-xs text-gray-500">{{ $ropa->user->email ?? '' }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Dates</p>
            <p class="mt-2 text-sm text-gray-700">Submitted: {{ optional($ropa->date_submitted ?? $ropa->created_at)->format('d M Y') }}</p>
            <p class="text-sm text-gray-700">Updated: {{ $ropa->updated_at->format('d M Y') }}</p>
        </div>
    </div>

    @foreach($sections as $title => $fields)
        <section class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200">
                <h2 class="text-sm font-semibold text-gray-800">{{ $title }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px]">
                    <tbody class="divide-y divide-gray-100">
                    @foreach($fields as $label => $value)
                        <tr>
                            <td class="w-56 px-5 py-3 align-top bg-gray-50 border-r border-gray-100">
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ $label }}</p>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-800 align-top">
                                {!! is_array($value) || (is_string($value) && str_starts_with(trim($value), '['))
                                    ? $renderList($value)
                                    : (filled($value) ? e((string) $value) : '<span class="text-gray-400 text-sm">N/A</span>') !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach
</div>

<script>
    feather.replace();
</script>
@endsection
