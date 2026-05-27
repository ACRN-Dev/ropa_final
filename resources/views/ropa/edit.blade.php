@extends('layouts.admin')

@section('title', 'Edit ROPA')

@section('content')
@php
    $asList = function ($value) {
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

    $toLines = fn ($value) => implode("\n", $asList($value));
    $selectedStatus = old('status', $ropa->status ?? 'Pending');
    $selectedRisk = old('risk_level', $ropa->risk_level ?? '');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit ROPA Record</h1>
            <p class="text-sm text-gray-500 mt-1">Record #{{ $ropa->id }}</p>
        </div>
        <a href="{{ route('admin.ropa.show', $ropa->id) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm font-semibold">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            Back to View
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold mb-1">Please fix the following issues:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editRopaForm" method="POST" action="{{ route('ropa.update', $ropa->id) }}" class="space-y-5">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        <section class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-5 py-3 border-b border-gray-200">
                <h2 class="text-sm font-semibold text-gray-800">Core Details</h2>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Organisation Name</label>
                    <input name="organisation_name" type="text" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 text-sm"
                           value="{{ old('organisation_name', $ropa->organisation_name) }}">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Other Organisation</label>
                    <input name="other_organisation_name" type="text" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 text-sm"
                           value="{{ old('other_organisation_name', $ropa->other_organisation_name) }}">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Department</label>
                    <input name="department" type="text" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 text-sm"
                           value="{{ old('department', $ropa->department) }}">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Other Department</label>
                    <input name="other_department" type="text" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 text-sm"
                           value="{{ old('other_department', $ropa->other_department) }}">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 text-sm">
                        @foreach(['Pending', 'Reviewed'] as $status)
                            <option value="{{ $status }}" {{ $selectedStatus === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Risk Level</label>
                    <select name="risk_level" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 text-sm">
                        <option value="">Not Set</option>
                        @foreach(['low', 'medium', 'high', 'critical'] as $level)
                            <option value="{{ $level }}" {{ $selectedRisk === $level ? 'selected' : '' }}>{{ ucfirst($level) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-5 py-3 border-b border-gray-200">
                <h2 class="text-sm font-semibold text-gray-800">Structured Fields</h2>
                <p class="text-xs text-gray-500 mt-1">Use one value per line. The form converts these lines into structured list fields.</p>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $listFields = [
                        'processes' => 'Processes / Activities',
                        'data_sources' => 'Data Sources',
                        'data_formats' => 'Data Formats',
                        'personal_data_categories' => 'Personal Data Categories',
                        'lawful_basis' => 'Lawful Basis',
                        'local_organizations' => 'Local Organizations',
                        'transborder_countries' => 'Transborder Countries',
                        'access_measures' => 'Access Measures',
                        'technical_measures' => 'Technical Measures',
                        'organisational_measures' => 'Organisational Measures',
                        'risk_report' => 'Risk Report Notes',
                        'records_count' => 'Records Count',
                        'data_volume' => 'Data Volume',
                        'retention_period_years' => 'Retention Period',
                        'access_estimate' => 'Access Estimate',
                    ];
                @endphp
                @foreach($listFields as $field => $label)
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $label }}</label>
                        <textarea rows="4"
                                  data-array-field="{{ $field }}"
                                  class="array-source w-full rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 text-sm"
                                  placeholder="One value per line">{{ old($field.'_text', $toLines(old($field, $ropa->{$field}))) }}</textarea>
                    </div>
                @endforeach
            </div>
        </section>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        <section class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-5 py-3 border-b border-gray-200">
                <h2 class="text-sm font-semibold text-gray-800">Boolean and Notes</h2>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="hidden" name="information_shared" value="0">
                    <input type="checkbox" name="information_shared" value="1" class="rounded border-gray-300"
                           {{ old('information_shared', $ropa->information_shared) ? 'checked' : '' }}>
                    Information Shared
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="hidden" name="access_control" value="0">
                    <input type="checkbox" name="access_control" value="1" class="rounded border-gray-300"
                           {{ old('access_control', $ropa->access_control) ? 'checked' : '' }}>
                    Access Control in Place
                </label>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sharing Comment</label>
                    <textarea name="sharing_comment" rows="3" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 text-sm">{{ old('sharing_comment', $ropa->sharing_comment) }}</textarea>
                </div>
            </div>
        </section>
        <section class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-5 py-3 border-b border-gray-200">
                <h2 class="text-sm font-semibold text-gray-800">Save</h2>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-gray-600">Review your updates and save when ready.</p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.ropa.show', $ropa->id) }}"
                       class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-semibold text-center hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-orange-600 text-white text-sm font-semibold hover:bg-orange-700">
                        Save Changes
                    </button>
                </div>
            </div>
        </section>
        </div>

    </form>
</div>

<script>
    feather.replace();

    document.getElementById('editRopaForm').addEventListener('submit', function () {
        document.querySelectorAll('.generated-array').forEach(function (el) { el.remove(); });

        document.querySelectorAll('.array-source').forEach(function (source) {
            var field = source.dataset.arrayField;
            var lines = source.value
                .split(/\r?\n/)
                .map(function (line) { return line.trim(); })
                .filter(function (line) { return line.length > 0; });

            lines.forEach(function (line) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = field + '[]';
                input.value = line;
                input.className = 'generated-array';
                source.form.appendChild(input);
            });
        });
    });
</script>
@endsection
