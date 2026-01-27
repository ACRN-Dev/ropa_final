@extends('layouts.app')

@section('title', 'User | View ROPA Record')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-orange-600 mb-1">ROPA Record Details</h1>
                <p class="text-sm text-gray-600">Record ID: #{{ $ropa->id }}</p>
            </div>
            
            {{-- Status Badge --}}
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-lg text-sm font-bold shadow-sm
                    {{ $ropa->status === 'Reviewed'
                        ? 'bg-green-100 text-green-800 border-2 border-green-300'
                        : 'bg-yellow-100 text-yellow-800 border-2 border-yellow-300'
                    }}">
                    <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ $ropa->status ?? 'Pending' }}
                </span>
                
                {{-- Back Button --}}
                <a href="{{ route('ropa.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all text-sm font-semibold shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- ROPA TABS --}}
    <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 pb-3">
        @php
            $tabs = [
                'Details' => route('ropa.show', $ropa->id),
                'Review'  => route('ropa.review', $ropa->id),
            ];
        @endphp

        @foreach($tabs as $label => $link)
            <a href="{{ $link }}"
               class="px-5 py-2.5 font-semibold rounded-lg transition-all text-sm
               {{ request()->url() === $link
                    ? 'bg-orange-600 text-white shadow-md'
                    : 'bg-gray-100 text-gray-700 hover:bg-orange-50 hover:text-orange-800'
               }}">
               <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            if ($value === null) return '<span class="text-gray-400">—</span>';
            return $value 
                ? '<span class="inline-flex items-center gap-1 text-green-700 font-semibold"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Yes</span>' 
                : '<span class="inline-flex items-center gap-1 text-red-700 font-semibold"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>No</span>';
        }

        function riskBadge($level) {
            return match(strtolower($level ?? '')) {
                'critical' => 'bg-red-100 text-red-800 border-red-300',
                'high'     => 'bg-orange-100 text-orange-800 border-orange-300',
                'medium'   => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                'low'      => 'bg-green-100 text-green-800 border-green-300',
                default    => 'bg-gray-100 text-gray-800 border-gray-300'
            };
        }

        function renderArray($value) {
            $arr = cleanValue($value);
            if (!$arr) return '<span class="text-gray-400">—</span>';
            
            $items = is_array($arr) ? $arr : [$arr];
            $badges = array_map(function($item) {
                return '<span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md text-sm border border-blue-200">' . e($item) . '</span>';
            }, $items);
            
            return implode(' ', $badges);
        }

        function renderField($label, $value, $icon = null) {
            $iconSvg = '';
            if ($icon) {
                $iconSvg = '<svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">' . $icon . '</svg>';
            }
            
            return '
                <div class="flex gap-3 py-2 border-b border-gray-100 last:border-0">
                    ' . $iconSvg . '
                    <div class="flex-1">
                        <dt class="text-sm font-semibold text-gray-700 mb-1">' . $label . '</dt>
                        <dd class="text-sm text-gray-900">' . $value . '</dd>
                    </div>
                </div>
            ';
        }
    @endphp

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Organisation Info --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Organisation Information
                </h2>
            </div>
            <div class="p-6 space-y-3">
                {!! renderField('Organisation Name', renderArray($ropa->organisation_name)) !!}
                @if(cleanValue($ropa->other_organisation))
                    {!! renderField('Other Organisation', renderArray($ropa->other_organisation)) !!}
                @endif
                {!! renderField('Department', renderArray($ropa->department)) !!}
                @if(cleanValue($ropa->other_department))
                    {!! renderField('Other Department', renderArray($ropa->other_department)) !!}
                @endif
            </div>
        </div>

        {{-- Processing Details --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Processing Details
                </h2>
            </div>
            <div class="p-6 space-y-3">
                {!! renderField('Processes', renderArray($ropa->processes)) !!}
                {!! renderField('Data Sources', renderArray($ropa->data_sources)) !!}
                @if(cleanValue($ropa->other_data_sources))
                    {!! renderField('Other Data Sources', renderArray($ropa->other_data_sources)) !!}
                @endif
                {!! renderField('Data Formats', renderArray($ropa->data_formats)) !!}
                @if(cleanValue($ropa->other_data_formats))
                    {!! renderField('Other Data Formats', renderArray($ropa->other_data_formats)) !!}
                @endif
                @if(cleanValue($ropa->information_nature))
                    {!! renderField('Nature of Information', renderArray($ropa->information_nature)) !!}
                @endif
                {!! renderField('Personal Data Categories', renderArray($ropa->personal_data_categories)) !!}
                @if(cleanValue($ropa->other_personal_data_categories))
                    {!! renderField('Other Personal Data Categories', renderArray($ropa->other_personal_data_categories)) !!}
                @endif
            </div>
        </div>

        {{-- Data Volume & Retention --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                    </svg>
                    Data Volume & Retention
                </h2>
            </div>
            <div class="p-6 space-y-3">
                @if(cleanValue($ropa->estimated_records))
                    {!! renderField('Estimated Records', renderArray($ropa->estimated_records)) !!}
                @endif
                {!! renderField('Data Volume', renderArray($ropa->data_volume)) !!}
                {!! renderField('Retention Period (Years)', renderArray($ropa->retention_years)) !!}
                @if(cleanValue($ropa->retention_rationale))
                    {!! renderField('Retention Rationale', renderArray($ropa->retention_rationale)) !!}
                @endif
            </div>
        </div>

        {{-- Information Sharing --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Information Sharing
                </h2>
            </div>
            <div class="p-6 space-y-3">
                {!! renderField('Information Shared', yesNo($ropa->information_shared)) !!}
                {!! renderField('Local Sharing', yesNo($ropa->local_sharing)) !!}
                {!! renderField('Transborder Sharing', yesNo($ropa->transborder_sharing)) !!}
                @if(cleanValue($ropa->local_organizations))
                    {!! renderField('Local Organizations', renderArray($ropa->local_organizations)) !!}
                @endif
                @if(cleanValue($ropa->transborder_countries))
                    {!! renderField('Transborder Countries', renderArray($ropa->transborder_countries)) !!}
                @endif
                @if(cleanValue($ropa->sharing_comment))
                    {!! renderField('Sharing Comment', renderArray($ropa->sharing_comment)) !!}
                @endif
            </div>
        </div>

        {{-- Security Measures --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Security Measures
                </h2>
            </div>
            <div class="p-6 space-y-3">
                {!! renderField('Access Control', yesNo($ropa->access_control)) !!}
                @if(cleanValue($ropa->access_measures))
                    {!! renderField('Access Measures', renderArray($ropa->access_measures)) !!}
                @endif
                @if(cleanValue($ropa->technical_measures))
                    {!! renderField('Technical Measures', renderArray($ropa->technical_measures)) !!}
                @endif
                @if(cleanValue($ropa->organisational_measures))
                    {!! renderField('Organisational Measures', renderArray($ropa->organisational_measures)) !!}
                @endif
            </div>
        </div>

        {{-- Lawful Basis & Risk --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Lawful Basis & Risk
                </h2>
            </div>
            <div class="p-6 space-y-3">
                {!! renderField('Lawful Basis', renderArray($ropa->lawful_basis)) !!}
                @if(cleanValue($ropa->risk_report))
                    {!! renderField('Risk Report', renderArray($ropa->risk_report)) !!}
                @endif
                @if(cleanValue($ropa->risk_level))
                    <div class="flex gap-3 py-2">
                        <div class="flex-1">
                            <dt class="text-sm font-semibold text-gray-700 mb-2">Risk Level</dt>
                            <dd>
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold border-2 {{ riskBadge($ropa->risk_level) }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ucfirst($ropa->risk_level ?? 'N/A') }}
                                </span>
                            </dd>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Metadata Section --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Created By --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-md">
                    <span class="text-lg font-bold text-white">
                        {{ strtoupper(substr($ropa->user->name ?? 'U', 0, 2)) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Created By</p>
                    <p class="text-sm font-bold text-gray-900">{{ $ropa->user->name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-500">{{ $ropa->user->email ?? '' }}</p>
                </div>
            </div>
        </div>

        {{-- Created Date --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Created At</p>
                    <p class="text-sm font-bold text-gray-900">{{ $ropa->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $ropa->created_at->format('h:i A') }}</p>
                </div>
            </div>
        </div>

        {{-- Last Updated --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Last Updated</p>
                    <p class="text-sm font-bold text-gray-900">{{ $ropa->updated_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $ropa->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="mt-6 flex justify-end gap-3">
        <a href="{{ route('ropa.edit', $ropa->id) }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-all font-semibold shadow-md hover:shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Record
        </a>
        
        <button onclick="window.print()" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all font-semibold shadow-md hover:shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print
        </button>
    </div>
</div>

<style>
@media print {
    button, a[href*="edit"], a[href*="back"] {
        display: none !important;
    }
    
    .shadow-lg, .shadow-md {
        box-shadow: none !important;
    }
    
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}
</style>

@endsection