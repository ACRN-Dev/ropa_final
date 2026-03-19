@extends('layouts.app')

@section('title', 'Add New ROPA Record')

@section('content')

<style>
    .section-card {
        border-left: 4px solid #ea580c;
        transition: box-shadow 0.2s ease;
    }
    .section-card:hover {
        box-shadow: 0 4px 24px 0 rgba(234,88,12,0.08);
    }
    .step-badge {
        background: #ea580c;
        color: #fff;
        border-radius: 9999px;
        width: 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        flex-shrink: 0;
    }
    .field-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
        font-size: 0.875rem;
        display: block;
    }
    .field-input {
        width: 100%;
        border: 1px solid #d1d5db;
        background: #f9fafb;
        border-radius: 0.5rem;
        padding: 0.55rem 0.85rem;
        font-size: 0.95rem;
        transition: border-color 0.15s, box-shadow 0.15s;
        outline: none;
    }
    .field-input:focus {
        border-color: #ea580c;
        box-shadow: 0 0 0 3px rgba(234,88,12,0.12);
        background: #fff;
    }
    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.6rem;
        border-radius: 0.4rem;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background 0.12s;
    }
    .checkbox-group label:hover {
        background: #fff7ed;
    }
    .checkbox-group input[type="checkbox"] {
        accent-color: #ea580c;
        width: 1rem;
        height: 1rem;
    }
    .toggle-checkbox {
        accent-color: #ea580c;
        width: 1.1rem;
        height: 1.1rem;
    }
</style>

<div class="container mx-auto py-8 px-4 max-w-4xl">

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-orange-600 flex items-center justify-center shadow">
                <i data-feather="plus-circle" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">New ROPA Record</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Record of Processing Activity</p>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <i data-feather="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center gap-2 mb-1 font-semibold">
                <i data-feather="alert-circle" class="w-4 h-4 text-red-600"></i> Please fix the following:
            </div>
            <ul class="list-disc list-inside text-sm space-y-0.5 pl-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ropa.store') }}">
        @csrf

        <!-- ── Section 1: Organisation Details ─────────────────── -->
        <div class="section-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center gap-3 mb-5">
                <span class="step-badge">1</span>
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Organisation Details</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Organisation Name -->
                <div>
                    <label class="field-label">Organisation Name <span class="text-red-500">*</span></label>
                    <select name="organisation_name" id="organisation_name" class="field-input" required>
                        <option value="">Select Organisation</option>
                        @foreach(['Mutala Trust', 'Infectious Diseases Research Lab', 'Charles River Medical Group', 'Africa Clinical Research Network', 'Other'] as $org)
                            <option value="{{ $org }}" {{ old('organisation_name') == $org ? 'selected' : '' }}>{{ $org }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Other Organisation -->
                <div id="other_organisation_div" class="hidden">
                    <label class="field-label">Specify Other Organisation</label>
                    <input type="text" name="other_organisation" id="other_organisation"
                        class="field-input" placeholder="Enter organisation name"
                        value="{{ old('other_organisation') }}">
                </div>

                <!-- Department -->
                <div>
                    <label class="field-label">Department <span class="text-red-500">*</span></label>
                    <select name="department_name" id="department_name" class="field-input" required>
                        <option value="">Select Department</option>
                        @foreach([
                            'Data Protection', 'Information Technology', 'Human Resource',
                            'Community Engagement', 'Data & Biostatics', 'Laboratory',
                            'Pharmacy', 'Finance & Administration', 'Clinical Operations',
                            'Project Management', 'Legal & Compliance', 'Other'
                        ] as $dept)
                            <option value="{{ $dept }}" {{ old('department_name') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Other Department -->
                <div id="other_department_div" class="hidden">
                    <label class="field-label">Specify Other Department</label>
                    <input type="text" name="other_department" id="other_department"
                        class="field-input" placeholder="Enter department name"
                        value="{{ old('other_department') }}">
                </div>

                <!-- Date Submitted -->
                <div>
                    <label class="field-label">Date Submitted</label>
                    <input type="date" name="date_submitted" class="field-input"
                        value="{{ old('date_submitted') }}">
                </div>
            </div>
        </div>

        <!-- ── Section 2: Processing Details ───────────────────── -->
        <div class="section-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center gap-3 mb-5">
                <span class="step-badge">2</span>
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Processing Activity Details</h2>
            </div>

            <div class="grid grid-cols-1 gap-5">
                <!-- Information Nature -->
                <div>
                    <label class="field-label">Information Nature</label>
                    <textarea name="information_nature" rows="3" class="field-input"
                        placeholder="Describe the nature of the information being processed...">{{ old('information_nature') }}</textarea>
                </div>

                <!-- Personal Data Categories -->
                <div>
                    <label class="field-label">Categories of Personal Data</label>
                    <div class="checkbox-group grid grid-cols-2 md:grid-cols-3 gap-1 mt-1">
                        @foreach(['Health Data', 'Financial Data', 'Employment Data', 'Contact Information', 'Genetic Data', 'Biometric Data', 'Criminal Record', 'Demographic Data', 'Other'] as $cat)
                            <label>
                                <input type="checkbox" name="personal_data_category[]" value="{{ $cat }}"
                                    {{ in_array($cat, old('personal_data_category', [])) ? 'checked' : '' }}>
                                <span>{{ $cat }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Section 3: Data Sharing ─────────────────────────── -->
        <div class="section-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center gap-3 mb-5">
                <span class="step-badge">3</span>
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Data Sharing</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Information Shared toggle -->
                <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <input type="checkbox" name="information_shared" value="1" class="toggle-checkbox"
                        {{ old('information_shared') ? 'checked' : '' }}>
                    <div>
                        <span class="font-semibold text-gray-700 dark:text-gray-200 text-sm">Information Shared</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Is this data shared with third parties?</p>
                    </div>
                </div>

                <!-- Outsourced Processing toggle -->
                <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <input type="checkbox" id="outsourced_processing" name="outsourced_processing" value="1"
                        class="toggle-checkbox" {{ old('outsourced_processing') ? 'checked' : '' }}>
                    <div>
                        <span class="font-semibold text-gray-700 dark:text-gray-200 text-sm">Outsourced Processing</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Is processing done by a third-party processor?</p>
                    </div>
                </div>

                <!-- Processor name (conditional) -->
                <div id="processor_div" class="hidden md:col-span-2">
                    <label class="field-label">Processor Name</label>
                    <input type="text" name="processor" class="field-input"
                        placeholder="Enter processor name" value="{{ old('processor') }}">
                </div>

                <!-- Transborder Processing toggle -->
                <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <input type="checkbox" id="transborder_processing" name="transborder_processing" value="1"
                        class="toggle-checkbox" {{ old('transborder_processing') ? 'checked' : '' }}>
                    <div>
                        <span class="font-semibold text-gray-700 dark:text-gray-200 text-sm">Transborder Processing</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Is data transferred across borders?</p>
                    </div>
                </div>

                <!-- Country (conditional) -->
                <div id="country_div" class="hidden">
                    <label class="field-label">Country</label>
                    <input type="text" name="country" class="field-input"
                        placeholder="Enter country name" value="{{ old('country') }}">
                </div>
            </div>
        </div>

        <!-- ── Section 4: Lawful Basis ─────────────────────────── -->
        <div class="section-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center gap-3 mb-5">
                <span class="step-badge">4</span>
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Lawful Basis</h2>
            </div>

            <div class="checkbox-group grid grid-cols-1 md:grid-cols-2 gap-1">
                @foreach([
                    'Consent',
                    'Contractual Obligation',
                    'Legal Obligation',
                    'Vital Interest',
                    'Public Interest',
                    'Legitimate Interest',
                    'Where The Data Subject Has Made The Information Public',
                    'Scientific Research'
                ] as $basis)
                    <label>
                        <input type="checkbox" name="lawful_basis[]" value="{{ $basis }}"
                            {{ in_array($basis, old('lawful_basis', [])) ? 'checked' : '' }}>
                        <span>{{ $basis }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- ── Section 5: Retention ────────────────────────────── -->
        <div class="section-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center gap-3 mb-5">
                <span class="step-badge">5</span>
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Retention Policy</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="field-label">Retention Period (Years)</label>
                    <input type="number" name="retention_period_years" min="0"
                        class="field-input" placeholder="e.g. 5"
                        value="{{ old('retention_period_years') }}">
                </div>

                <div class="md:col-span-2">
                    <label class="field-label">Retention Rationale</label>
                    <textarea name="retention_rationale" rows="3" class="field-input"
                        placeholder="Explain why data is retained for this period...">{{ old('retention_rationale') }}</textarea>
                </div>
            </div>
        </div>

        <!-- ── Action Buttons ──────────────────────────────────── -->
        <div class="flex items-center justify-between pt-2 pb-8">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 text-sm font-semibold shadow-sm transition">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Dashboard
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-semibold shadow transition">
                <i data-feather="save" class="w-4 h-4"></i> Save Record
            </button>
        </div>

    </form>
</div>

<script src="https://unpkg.com/feather-icons"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    feather.replace();

    // Organisation Other
    const orgSelect = document.getElementById('organisation_name');
    const orgOtherDiv = document.getElementById('other_organisation_div');
    const toggleOrg = () => orgOtherDiv.classList.toggle('hidden', orgSelect.value !== 'Other');
    orgSelect.addEventListener('change', toggleOrg);
    toggleOrg();

    // Department Other
    const deptSelect = document.getElementById('department_name');
    const deptOtherDiv = document.getElementById('other_department_div');
    const toggleDept = () => deptOtherDiv.classList.toggle('hidden', deptSelect.value !== 'Other');
    deptSelect.addEventListener('change', toggleDept);
    toggleDept();

    // Outsourced Processor
    const outsource = document.getElementById('outsourced_processing');
    const processorDiv = document.getElementById('processor_div');
    const toggleProcessor = () => processorDiv.classList.toggle('hidden', !outsource.checked);
    outsource.addEventListener('change', toggleProcessor);
    toggleProcessor();

    // Transborder Country
    const transborder = document.getElementById('transborder_processing');
    const countryDiv = document.getElementById('country_div');
    const toggleCountry = () => countryDiv.classList.toggle('hidden', !transborder.checked);
    transborder.addEventListener('change', toggleCountry);
    toggleCountry();
});
</script>

@endsection