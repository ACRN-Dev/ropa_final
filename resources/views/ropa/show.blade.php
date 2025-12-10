@extends('layouts.app')

@section('title', 'View ROPA Record')

@section('content')
<div class="container mx-auto p-6">

    <!-- ROPA TABS -->
    <div class="flex flex-wrap gap-4 mb-8 border-b pb-2">
        @php
            $tabs = [
                'Details' => route('ropa.show', $ropa->id),
                'Review'  => route('ropa.review', $ropa->id),
            ];
        @endphp

        @foreach($tabs as $label => $link)
            <a href="{{ $link }}"
               class="px-4 py-2 font-semibold rounded-lg transition-colors
               {{ request()->url() === $link
                    ? 'bg-indigo-600 text-white'
                    : 'bg-gray-100 text-gray-700 hover:bg-indigo-100 hover:text-indigo-800'
               }}">
               {{ $label }}
            </a>
        @endforeach
    </div>

    <h1 class="text-3xl font-bold mb-6 text-orange-600">ROPA Record Details</h1>

    @php
        use Illuminate\Support\Str;

        function cleanValue($value) {
            if (is_string($value) && Str::startsWith($value, '[')) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) $value = $decoded;
            }

            if (is_array($value)) {
                $value = array_filter($value, fn($v) => $v !== null && $v !== '' && $v !== 'null');
                return count($value) ? $value : null;
            }

            return $value === null || $value === '' || $value === 'null' ? null : $value;
        }

        function yesNo($value) {
            return $value === null ? '—' : ($value ? 'Yes' : 'No');
        }

        function riskBadge($level) {
            return match($level) {
                'critical','Critical' => 'bg-red-600 text-white',
                'high','High'        => 'bg-orange-500 text-white',
                'medium','Medium'    => 'bg-yellow-400 text-black',
                'low','Low'          => 'bg-green-500 text-white',
                default              => 'bg-gray-300 text-black'
            };
        }

        function renderArray($value) {
            $arr = cleanValue($value);
            return $arr ? implode(', ', (array)$arr) : '—';
        }
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Organisation Info -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="font-semibold text-lg text-gray-800 mb-3">Organisation Information</h2>
            <p><span class="font-medium">Organisation Name:</span> {{ renderArray($ropa->organisation_name) }}</p>
            @if(cleanValue($ropa->other_organisation))
                <p><span class="font-medium">Other Organisation:</span> {{ renderArray($ropa->other_organisation) }}</p>
            @endif
            <p><span class="font-medium">Department:</span> {{ renderArray($ropa->department) }}</p>
            @if(cleanValue($ropa->other_department))
                <p><span class="font-medium">Other Department:</span> {{ renderArray($ropa->other_department) }}</p>
            @endif
        </div>

        <!-- Processing Details -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="font-semibold text-lg text-gray-800 mb-3">Processing Details</h2>
            <p><span class="font-medium">Processes:</span> {{ renderArray($ropa->processes) }}</p>
            <p><span class="font-medium">Data Sources:</span> {{ renderArray($ropa->data_sources) }}</p>
            @if(cleanValue($ropa->other_data_sources))
                <p><span class="font-medium">Other Data Sources:</span> {{ renderArray($ropa->other_data_sources) }}</p>
            @endif
            <p><span class="font-medium">Data Formats:</span> {{ renderArray($ropa->data_formats) }}</p>
            @if(cleanValue($ropa->other_data_formats))
                <p><span class="font-medium">Other Data Formats:</span> {{ renderArray($ropa->other_data_formats) }}</p>
            @endif
            <p><span class="font-medium">Nature of Information:</span> {{ renderArray($ropa->information_nature) }}</p>
            <p><span class="font-medium">Personal Data Categories:</span> {{ renderArray($ropa->personal_data_categories) }}</p>
            @if(cleanValue($ropa->other_personal_data_categories))
                <p><span class="font-medium">Other Personal Data Categories:</span> {{ renderArray($ropa->other_personal_data_categories) }}</p>
            @endif
        </div>

        <!-- Data & Retention -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="font-semibold text-lg text-gray-800 mb-3">Data Volume & Retention</h2>
            <p><span class="font-medium">Estimated Records:</span> {{ renderArray($ropa->estimated_records) }}</p>
            <p><span class="font-medium">Data Volume:</span> {{ renderArray($ropa->data_volume) }}</p>
            <p><span class="font-medium">Retention (Years):</span> {{ renderArray($ropa->retention_years) }}</p>
            @if(cleanValue($ropa->retention_rationale))
                <p><span class="font-medium">Retention Rationale:</span> {{ renderArray($ropa->retention_rationale) }}</p>
            @endif
        </div>

        <!-- Information Sharing -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="font-semibold text-lg text-gray-800 mb-3">Information Sharing</h2>
            <p><span class="font-medium">Information Shared:</span> {{ yesNo($ropa->information_shared) }}</p>
            <p><span class="font-medium">Local Sharing:</span> {{ yesNo($ropa->local_sharing) }}</p>
            <p><span class="font-medium">Transborder Sharing:</span> {{ yesNo($ropa->transborder_sharing) }}</p>
            @if(cleanValue($ropa->local_organizations))
                <p><span class="font-medium">Local Organizations:</span> {{ renderArray($ropa->local_organizations) }}</p>
            @endif
            @if(cleanValue($ropa->transborder_countries))
                <p><span class="font-medium">Transborder Countries:</span> {{ renderArray($ropa->transborder_countries) }}</p>
            @endif
            <p><span class="font-medium">Sharing Comment:</span> {{ renderArray($ropa->sharing_comment) }}</p>
        </div>

        <!-- Security Measures -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="font-semibold text-lg text-gray-800 mb-3">Security Measures</h2>
            <p><span class="font-medium">Access Control:</span> {{ yesNo($ropa->access_control) }}</p>
            <p><span class="font-medium">Access Measures:</span> {{ renderArray($ropa->access_measures) }}</p>
            <p><span class="font-medium">Technical Measures:</span> {{ renderArray($ropa->technical_measures) }}</p>
            <p><span class="font-medium">Organisational Measures:</span> {{ renderArray($ropa->organisational_measures) }}</p>
        </div>

        <!-- Lawful Basis & Risk -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="font-semibold text-lg text-gray-800 mb-3">Lawful Basis & Risk</h2>
            <p><span class="font-medium">Lawful Basis:</span> {{ renderArray($ropa->lawful_basis) }}</p>
            <p><span class="font-medium">Risk Report:</span> {{ renderArray($ropa->risk_report) }}</p>
            <p><span class="font-medium">Risk Level:</span>
                <span class="px-2 py-1 rounded-full {{ riskBadge($ropa->risk_level) }}">
                    {{ renderArray($ropa->risk_level) }}
                </span>
            </p>
        </div>

        <!-- Status & Timestamps -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="font-semibold text-lg text-gray-800 mb-3">Status & Timestamps</h2>
            <p>
                <span class="font-medium">Status:</span>
                <span class="px-2 py-1 rounded-full
                    {{ $ropa->status === 'Reviewed'
                        ? 'bg-green-500 text-white'
                        : 'bg-yellow-400 text-black'
                    }}">
                    {{ renderArray($ropa->status) }}
                </span>
            </p>
            <p><span class="font-medium">Created At:</span> {{ $ropa->created_at->format('d M Y, H:i') }}</p>
            <p><span class="font-medium">Updated At:</span> {{ $ropa->updated_at->format('d M Y, H:i') }}</p>
        </div>

    </div>
</div>
@endsection
