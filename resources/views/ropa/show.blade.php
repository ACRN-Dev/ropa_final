@extends('layouts.admin')

@section('title', 'User | View ROPA Record')

@section('content')

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
        return ($value === null || $value === '' || $value === 'null') ? null : $value;
    }

    function renderBadges($value) {
        $clean = cleanValue($value);
        if (!$clean) return '<span class="text-gray-400 italic text-sm">—</span>';
        $items = is_array($clean) ? $clean : [$clean];
        $out = '';
        foreach ($items as $item) {
            $out .= '<span class="inline-block px-2 py-0.5 bg-orange-50 text-orange-700 border border-orange-200 rounded text-xs font-medium mr-1 mb-1">' . e($item) . '</span>';
        }
        return '<div class="flex flex-wrap">' . $out . '</div>';
    }

    function yesNoBadge($value) {
        if ($value === null || $value === '') return '<span class="text-gray-400 italic text-sm">—</span>';
        return $value
            ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded text-xs font-semibold"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Yes</span>'
            : '<span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-700 border border-red-200 rounded text-xs font-semibold"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>No</span>';
    }

    function riskBadge($level) {
        $classes = match(strtolower($level ?? '')) {
            'critical' => 'bg-red-100 text-red-800 border-red-300',
            'high'     => 'bg-orange-100 text-orange-800 border-orange-300',
            'medium'   => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'low'      => 'bg-green-100 text-green-800 border-green-300',
            default    => 'bg-gray-100 text-gray-700 border-gray-300',
        };
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded border text-xs font-bold ' . $classes . '">' . e(ucfirst($level ?? 'N/A')) . '</span>';
    }

    $statusColor = match($ropa->status ?? 'Pending') {
        'Approved', 'Reviewed' => 'bg-green-100 text-green-800 border-green-300',
        'Rejected'             => 'bg-red-100 text-red-800 border-red-300',
        default                => 'bg-yellow-100 text-yellow-800 border-yellow-300',
    };

    // Sections: [ label, icon-path, color, rows[] ]
    // Each row: [ 'label', 'value_html' ]
    $sections = [
        [
            'title' => 'Organisation',
            'color' => 'orange',
            'icon'  => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
            'rows'  => array_filter([
                ['Organisation Name',       renderBadges($ropa->organisation_name)],
                cleanValue($ropa->other_organisation_name) ? ['Other Organisation', renderBadges($ropa->other_organisation_name)] : null,
                ['Department',              renderBadges($ropa->department)],
                cleanValue($ropa->other_department) ? ['Other Department',  renderBadges($ropa->other_department)] : null,
            ]),
        ],
        [
            'title' => 'Processing Details',
            'color' => 'blue',
            'icon'  => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'rows'  => array_filter([
                ['Processes / Activities',   renderBadges($ropa->processes)],
                ['Data Sources',             renderBadges($ropa->data_sources)],
                cleanValue($ropa->other_data_sources) ? ['Other Data Sources', renderBadges($ropa->other_data_sources)] : null,
                ['Data Formats',             renderBadges($ropa->data_formats)],
                cleanValue($ropa->other_data_formats) ? ['Other Data Formats', renderBadges($ropa->other_data_formats)] : null,
                cleanValue($ropa->information_nature) ? ['Nature of Information', renderBadges($ropa->information_nature)] : null,
                ['Personal Data Categories', renderBadges($ropa->personal_data_categories)],
                cleanValue($ropa->other_personal_data_categories) ? ['Other Personal Data Categories', renderBadges($ropa->other_personal_data_categories)] : null,
            ]),
        ],
        [
            'title' => 'Data Volume & Retention',
            'color' => 'purple',
            'icon'  => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4',
            'rows'  => array_filter([
                cleanValue($ropa->estimated_records) ? ['Estimated Records', renderBadges($ropa->estimated_records)] : null,
                ['Data Volume',             renderBadges($ropa->data_volume)],
                ['Retention Period (Yrs)',  renderBadges($ropa->retention_years)],
                cleanValue($ropa->retention_rationale) ? ['Retention Rationale', renderBadges($ropa->retention_rationale)] : null,
            ]),
        ],
        [
            'title' => 'Information Sharing',
            'color' => 'indigo',
            'icon'  => 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z',
            'rows'  => array_filter([
                ['Information Shared',    yesNoBadge($ropa->information_shared)],
                ['Local Sharing',         yesNoBadge($ropa->local_sharing)],
                ['Transborder Sharing',   yesNoBadge($ropa->transborder_sharing)],
                cleanValue($ropa->local_organizations) ? ['Local Organisations', renderBadges($ropa->local_organizations)] : null,
                cleanValue($ropa->transborder_countries) ? ['Transborder Countries', renderBadges($ropa->transborder_countries)] : null,
                cleanValue($ropa->sharing_comment) ? ['Sharing Comment', renderBadges($ropa->sharing_comment)] : null,
            ]),
        ],
        [
            'title' => 'Security Measures',
            'color' => 'green',
            'icon'  => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'rows'  => array_filter([
                ['Access Control',         yesNoBadge($ropa->access_control)],
                cleanValue($ropa->access_measures) ? ['Access Measures', renderBadges($ropa->access_measures)] : null,
                cleanValue($ropa->technical_measures) ? ['Technical Measures', renderBadges($ropa->technical_measures)] : null,
                cleanValue($ropa->organisational_measures) ? ['Organisational Measures', renderBadges($ropa->organisational_measures)] : null,
            ]),
        ],
        [
            'title' => 'Lawful Basis & Risk',
            'color' => 'red',
            'icon'  => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'rows'  => array_filter([
                ['Lawful Basis',  renderBadges($ropa->lawful_basis)],
                cleanValue($ropa->risk_report) ? ['Risk Report', renderBadges($ropa->risk_report)] : null,
                cleanValue($ropa->risk_level) ? ['Risk Level', riskBadge($ropa->risk_level)] : null,
            ]),
        ],
    ];

    $colorMap = [
        'orange' => ['header' => 'from-orange-500 to-orange-600', 'label' => 'bg-orange-50 text-orange-700 border-r border-orange-100', 'stripe' => 'bg-orange-50/40'],
        'blue'   => ['header' => 'from-blue-500 to-blue-600',     'label' => 'bg-blue-50 text-blue-700 border-r border-blue-100',     'stripe' => 'bg-blue-50/40'],
        'purple' => ['header' => 'from-purple-500 to-purple-600', 'label' => 'bg-purple-50 text-purple-700 border-r border-purple-100', 'stripe' => 'bg-purple-50/40'],
        'indigo' => ['header' => 'from-indigo-500 to-indigo-600', 'label' => 'bg-indigo-50 text-indigo-700 border-r border-indigo-100', 'stripe' => 'bg-indigo-50/40'],
        'green'  => ['header' => 'from-green-500 to-green-600',   'label' => 'bg-green-50 text-green-700 border-r border-green-100',   'stripe' => 'bg-green-50/40'],
        'red'    => ['header' => 'from-red-500 to-red-600',       'label' => 'bg-red-50 text-red-700 border-r border-red-100',         'stripe' => 'bg-red-50/40'],
    ];
@endphp

<div class="w-full max-w-screen-xl mx-auto px-3 sm:px-4 lg:px-6 py-4 lg:py-6">

    {{-- ── PAGE HEADER ── --}}
    <div class="mb-5">
        {{-- Top row: title + actions --}}
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-orange-600 leading-tight">ROPA Record Details</h1>
                <p class="text-xs text-gray-500 mt-1">Record ID: <span class="font-semibold text-gray-700">#{{ $ropa->id }}</span></p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Status badge --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold border-2 {{ $statusColor }}">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ $ropa->status ?? 'Pending' }}
                </span>
                {{-- Back --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-xs font-semibold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
                {{-- Edit --}}<a href="{{ route('ropa.edit', $ropa->id) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition text-xs font-semibold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                {{-- Print --}}
                <button onclick="window.print()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition text-xs font-semibold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
            </div>
        </div>

        {{-- Tabs --}}
        @php
            $tabs = [
                'Details' => route('ropa.show', $ropa->id),
            ];
        @endphp
        <div class="flex gap-2 border-b border-gray-200">
            @foreach($tabs as $label => $link)
                <a href="{{ $link }}"
                   class="px-4 py-2 text-sm font-semibold rounded-t-lg border-b-2 transition-all
                   {{ request()->url() === $link
                        ? 'border-orange-500 text-orange-600 bg-orange-50'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-3.5 h-3.5 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($label === 'Details')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        @endif
                    </svg>
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ── METADATA BAR ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-3 flex items-center gap-3">
            <div class="h-10 w-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-bold text-white">{{ strtoupper(substr($ropa->user->name ?? 'U', 0, 2)) }}</span>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Submitted By</p>
                <p class="text-sm font-bold text-gray-900 truncate">{{ $ropa->user->name ?? 'Unknown' }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $ropa->user->email ?? '' }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-3 flex items-center gap-3">
            <div class="h-10 w-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Created</p>
                <p class="text-sm font-bold text-gray-900">{{ $ropa->created_at->format('d M Y') }}</p>
                <p class="text-xs text-gray-500">{{ $ropa->created_at->format('h:i A') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-3 flex items-center gap-3">
            <div class="h-10 w-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Last Updated</p>
                <p class="text-sm font-bold text-gray-900">{{ $ropa->updated_at->format('d M Y') }}</p>
                <p class="text-xs text-gray-500">{{ $ropa->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    {{-- ── SECTION TABLES ── --}}
    <div class="space-y-5">
        @foreach($sections as $section)
            @php $c = $colorMap[$section['color']]; @endphp
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- Section header --}}
                <div class="bg-gradient-to-r {{ $c['header'] }} px-4 py-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section['icon'] }}"/>
                    </svg>
                    <h2 class="text-sm lg:text-base font-bold text-white">{{ $section['title'] }}</h2>
                </div>

                {{-- Scrollable table wrapper --}}
                <div class="overflow-x-auto section-scroll">
                    <table class="w-full min-w-[400px]">
                        <tbody class="divide-y divide-gray-100">
                            @foreach($section['rows'] as $i => $row)
                                @if($row)
                                <tr class="{{ $i % 2 === 0 ? 'bg-white' : $c['stripe'] }}">
                                    {{-- Label cell --}}
                                    <td class="px-4 py-3 w-44 sm:w-52 align-top {{ $c['label'] }}">
                                        <span class="text-xs font-semibold uppercase tracking-wide whitespace-nowrap">
                                            {{ $row[0] }}
                                        </span>
                                    </td>
                                    {{-- Value cell --}}
                                    <td class="px-4 py-3 text-sm text-gray-800 align-top">
                                        {!! $row[1] !!}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

</div>

<style>
    /* Section scrollbar */
    .section-scroll::-webkit-scrollbar { height: 5px; }
    .section-scroll::-webkit-scrollbar-track { background: #f9fafb; }
    .section-scroll::-webkit-scrollbar-thumb { background: #ea580c; border-radius: 4px; }
    .section-scroll::-webkit-scrollbar-thumb:hover { background: #c2410c; }

    @media print {
        button, a[href*="edit"], a[href*="back"] { display: none !important; }
        .shadow-sm, .shadow-md { box-shadow: none !important; }
        body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        .section-scroll { overflow: visible !important; }
        table { min-width: unset !important; }
    }
</style>

@endsection