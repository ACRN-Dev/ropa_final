@extends('layouts.admin')

@section('title', 'Edit ROPA Record')

@section('content')

<div class="w-full max-w-screen-xl mx-auto px-3 sm:px-4 lg:px-6 py-4 lg:py-6">

    {{-- ── PAGE HEADER ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-orange-600 leading-tight flex items-center gap-2">
                <i data-feather="edit-2" class="w-6 h-6 lg:w-7 lg:h-7"></i>
                Edit ROPA Record
            </h1>
            <p class="text-xs text-gray-500 mt-1">Record ID: <span class="font-semibold text-gray-700">#{{ $ropa->id }}</span></p>
        </div>
        <a href="{{ route('ropa.show', $ropa->id) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-xs font-semibold self-start sm:self-auto">
            <i data-feather="arrow-left" class="w-3.5 h-3.5"></i>
            Back to Record
        </a>
    </div>

    {{-- ── ALERTS ── --}}
    @if(session('success'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-5 text-sm">
            <i data-feather="check-circle" class="w-4 h-4 flex-shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5">
            <div class="flex items-center gap-2 font-semibold text-sm mb-2">
                <i data-feather="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                Please fix the following errors:
            </div>
            <ul class="list-disc list-inside text-sm space-y-0.5 ml-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ropa.update', $ropa->id) }}" id="editForm">
        @csrf
        @method('PATCH')

        {{-- ════════════════════════════════════════
             SECTION 1 — ORGANISATION
        ════════════════════════════════════════ --}}
        <div class="form-section bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 lg:px-6 py-3 flex items-center gap-2">
                <i data-feather="briefcase" class="w-4 h-4 text-white flex-shrink-0"></i>
                <h2 class="text-sm lg:text-base font-bold text-white">Organisation</h2>
            </div>
            <div class="p-4 lg:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Organisation Name --}}
                <div>
                    <label class="field-label">Organisation Name <span class="text-red-500">*</span></label>
                    <select name="organisation_name" id="organisation_name" class="field-input" required>
                        <option value="">Select Organisation</option>
                        @foreach(['Mutala Trust','Infectious Diseases Research Lab','Charles River Medical Group','Africa Clinical Research Network','Other'] as $org)
                            <option value="{{ $org }}" {{ old('organisation_name', $ropa->organisation_name) === $org ? 'selected' : '' }}>{{ $org }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Other Organisation --}}
                <div id="other_organisation_div" class="{{ old('organisation_name', $ropa->organisation_name) === 'Other' ? '' : 'hidden' }}">
                    <label class="field-label">Specify Other Organisation</label>
                    <input type="text" name="other_organisation_name" id="other_organisation"
                           value="{{ old('other_organisation_name', $ropa->other_organisation_name) }}"
                           placeholder="Enter organisation name"
                           class="field-input">
                </div>

                {{-- Department --}}
                <div>
                    <label class="field-label">Department <span class="text-red-500">*</span></label>
                    <select name="department" id="department_name" class="field-input" required>
                        <option value="">Select Department</option>
                        @foreach([
                            'Data Protection','IT','HR','Community Engagement',
                            'Data & Biostatisitcs','Laboratory','Pharmacy',
                            'Finance & Administration','Clinical Operations (ClinOps)',
                            'Project Management','Legal & Compliance','Other'
                        ] as $dept)
                            <option value="{{ $dept }}" {{ old('department', $ropa->department) === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Other Department --}}
                <div id="other_department_div" class="{{ old('department', $ropa->department) === 'Other' ? '' : 'hidden' }}">
                    <label class="field-label">Specify Other Department</label>
                    <input type="text" name="other_department" id="other_department"
                           value="{{ old('other_department', $ropa->other_department) }}"
                           placeholder="Enter department name"
                           class="field-input">
                </div>

                {{-- Status --}}
                <div>
                    <label class="field-label">Status</label>
                    <select name="status" class="field-input">
                        @foreach(['Pending','Reviewed','Approved','Rejected'] as $s)
                            <option value="{{ $s }}" {{ old('status', $ropa->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Date Submitted --}}
                <div>
                    <label class="field-label">Date Submitted</label>
                    <input type="date" name="date_submitted"
                           value="{{ old('date_submitted', $ropa->date_submitted ? \Carbon\Carbon::parse($ropa->date_submitted)->format('Y-m-d') : '') }}"
                           class="field-input">
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════
             SECTION 2 — PROCESSING DETAILS
        ════════════════════════════════════════ --}}
        <div class="form-section bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 lg:px-6 py-3 flex items-center gap-2">
                <i data-feather="settings" class="w-4 h-4 text-white flex-shrink-0"></i>
                <h2 class="text-sm lg:text-base font-bold text-white">Processing Details</h2>
            </div>
            <div class="p-4 lg:p-6 grid grid-cols-1 gap-4">

                {{-- Processes / Activities --}}
                <div>
                    <label class="field-label">Processing Activities / Processes</label>
                    <p class="text-xs text-gray-500 mb-1.5">Select all that apply</p>
                    @php
                        $selectedProcesses = old('processes', is_string($ropa->processes) ? json_decode($ropa->processes, true) ?? [] : ($ropa->processes ?? []));
                        $processOptions = [
                            'User Access and Identity Management','Data Collection','Data Storage',
                            'Data Sharing','Data Analysis','Data Archiving','Data Deletion',
                            'Consent Management','Monitoring & Surveillance','HR Processing',
                            'Financial Processing','Clinical Data Management','Laboratory Data Processing',
                            'Research Data Management','Communication & Outreach',
                        ];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($processOptions as $opt)
                            <label class="checkbox-label">
                                <input type="checkbox" name="processes[]" value="{{ $opt }}"
                                       {{ in_array($opt, (array)$selectedProcesses) ? 'checked' : '' }}
                                       class="checkbox-input">
                                <span>{{ $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Information Nature --}}
                <div>
                    <label class="field-label">Nature of Information</label>
                    <textarea name="information_nature" rows="3" class="field-input"
                              placeholder="Describe the nature of information processed...">{{ old('information_nature', is_array($ropa->information_nature) ? implode(', ', $ropa->information_nature) : $ropa->information_nature) }}</textarea>
                </div>

                {{-- Personal Data Categories --}}
                <div>
                    <label class="field-label">Categories of Personal Data</label>
                    @php
                        $selectedCategories = old('personal_data_categories', is_string($ropa->personal_data_categories) ? json_decode($ropa->personal_data_categories, true) ?? [] : ($ropa->personal_data_categories ?? []));
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mt-1">
                        @foreach(['Health Data','Financial Data','Employment Data','Contact Information','Genetic Data','Biometric Data','Criminal Record','Demographic Data','Other'] as $cat)
                            <label class="checkbox-label">
                                <input type="checkbox" name="personal_data_categories[]" value="{{ $cat }}"
                                       {{ in_array($cat, (array)$selectedCategories) ? 'checked' : '' }}
                                       class="checkbox-input">
                                <span>{{ $cat }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Other Personal Data Categories --}}
                <div>
                    <label class="field-label">Other Personal Data Categories</label>
                    <input type="text" name="other_personal_data_categories"
                           value="{{ old('other_personal_data_categories', $ropa->other_personal_data_categories) }}"
                           placeholder="Specify other categories..."
                           class="field-input">
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════
             SECTION 3 — DATA VOLUME & RETENTION
        ════════════════════════════════════════ --}}
        <div class="form-section bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-4 lg:px-6 py-3 flex items-center gap-2">
                <i data-feather="database" class="w-4 h-4 text-white flex-shrink-0"></i>
                <h2 class="text-sm lg:text-base font-bold text-white">Data Volume & Retention</h2>
            </div>
            <div class="p-4 lg:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="field-label">Estimated Records</label>
                    <input type="text" name="estimated_records"
                           value="{{ old('estimated_records', $ropa->estimated_records) }}"
                           placeholder="e.g. 500–1000"
                           class="field-input">
                </div>

                <div>
                    <label class="field-label">Data Volume</label>
                    <select name="data_volume" class="field-input">
                        <option value="">Select volume</option>
                        @foreach(['Small (< 100)','Medium (100–1,000)','Large (1,000–10,000)','Very Large (> 10,000)'] as $vol)
                            <option value="{{ $vol }}" {{ old('data_volume', $ropa->data_volume) === $vol ? 'selected' : '' }}>{{ $vol }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label">Retention Period (Years)</label>
                    <input type="number" name="retention_years" min="0" max="100"
                           value="{{ old('retention_years', $ropa->retention_years) }}"
                           placeholder="e.g. 5"
                           class="field-input">
                </div>

                <div class="md:col-span-2">
                    <label class="field-label">Retention Rationale</label>
                    <textarea name="retention_rationale" rows="3" class="field-input"
                              placeholder="Explain why data is retained for this period...">{{ old('retention_rationale', $ropa->retention_rationale) }}</textarea>
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════
             SECTION 4 — INFORMATION SHARING
        ════════════════════════════════════════ --}}
        <div class="form-section bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-4 lg:px-6 py-3 flex items-center gap-2">
                <i data-feather="share-2" class="w-4 h-4 text-white flex-shrink-0"></i>
                <h2 class="text-sm lg:text-base font-bold text-white">Information Sharing</h2>
            </div>
            <div class="p-4 lg:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Information Shared toggle --}}
                <div class="md:col-span-2">
                    <label class="toggle-label">
                        <input type="hidden" name="information_shared" value="0">
                        <input type="checkbox" name="information_shared" value="1" class="toggle-checkbox"
                               id="information_shared"
                               {{ old('information_shared', $ropa->information_shared) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                        <span class="text-sm font-semibold text-gray-700">Information is shared with third parties</span>
                    </label>
                </div>

                {{-- Local Sharing --}}
                <div class="toggle-dependent" id="sharing_fields" style="{{ old('information_shared', $ropa->information_shared) ? '' : 'display:none' }}">
                    <label class="toggle-label">
                        <input type="hidden" name="local_sharing" value="0">
                        <input type="checkbox" name="local_sharing" value="1" class="toggle-checkbox"
                               id="local_sharing"
                               {{ old('local_sharing', $ropa->local_sharing) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                        <span class="text-sm font-semibold text-gray-700">Local Sharing</span>
                    </label>
                </div>

                {{-- Transborder Sharing --}}
                <div class="toggle-dependent" style="{{ old('information_shared', $ropa->information_shared) ? '' : 'display:none' }}">
                    <label class="toggle-label">
                        <input type="hidden" name="transborder_sharing" value="0">
                        <input type="checkbox" name="transborder_sharing" value="1" class="toggle-checkbox"
                               id="transborder_sharing"
                               {{ old('transborder_sharing', $ropa->transborder_sharing) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                        <span class="text-sm font-semibold text-gray-700">Transborder Sharing</span>
                    </label>
                </div>

                {{-- Local Organisations --}}
                <div id="local_orgs_div" class="{{ old('local_sharing', $ropa->local_sharing) ? '' : 'hidden' }}">
                    <label class="field-label">Local Organisations</label>
                    <input type="text" name="local_organizations"
                           value="{{ old('local_organizations', $ropa->local_organizations) }}"
                           placeholder="Enter local organisations..."
                           class="field-input">
                </div>

                {{-- Transborder Countries --}}
                <div id="transborder_countries_div" class="{{ old('transborder_sharing', $ropa->transborder_sharing) ? '' : 'hidden' }}">
                    <label class="field-label">Transborder Countries</label>
                    <input type="text" name="transborder_countries"
                           value="{{ old('transborder_countries', $ropa->transborder_countries) }}"
                           placeholder="Enter countries..."
                           class="field-input">
                </div>

                {{-- Sharing Comment --}}
                <div class="md:col-span-2 toggle-dependent" style="{{ old('information_shared', $ropa->information_shared) ? '' : 'display:none' }}">
                    <label class="field-label">Sharing Comment</label>
                    <textarea name="sharing_comment" rows="2" class="field-input"
                              placeholder="Additional notes on data sharing...">{{ old('sharing_comment', $ropa->sharing_comment) }}</textarea>
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════
             SECTION 5 — SECURITY MEASURES
        ════════════════════════════════════════ --}}
        <div class="form-section bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 lg:px-6 py-3 flex items-center gap-2">
                <i data-feather="shield" class="w-4 h-4 text-white flex-shrink-0"></i>
                <h2 class="text-sm lg:text-base font-bold text-white">Security Measures</h2>
            </div>
            <div class="p-4 lg:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label class="toggle-label">
                        <input type="hidden" name="access_control" value="0">
                        <input type="checkbox" name="access_control" value="1" class="toggle-checkbox"
                               {{ old('access_control', $ropa->access_control) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                        <span class="text-sm font-semibold text-gray-700">Access Control measures are in place</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="field-label">Access Measures</label>
                    <textarea name="access_measures" rows="2" class="field-input"
                              placeholder="Describe access control measures...">{{ old('access_measures', is_array($ropa->access_measures) ? implode(', ', $ropa->access_measures) : $ropa->access_measures) }}</textarea>
                </div>

                <div>
                    <label class="field-label">Technical Measures</label>
                    <textarea name="technical_measures" rows="3" class="field-input"
                              placeholder="e.g. Encryption, Firewalls, TLS...">{{ old('technical_measures', is_array($ropa->technical_measures) ? implode(', ', $ropa->technical_measures) : $ropa->technical_measures) }}</textarea>
                </div>

                <div>
                    <label class="field-label">Organisational Measures</label>
                    <textarea name="organisational_measures" rows="3" class="field-input"
                              placeholder="e.g. Staff training, Data policies...">{{ old('organisational_measures', is_array($ropa->organisational_measures) ? implode(', ', $ropa->organisational_measures) : $ropa->organisational_measures) }}</textarea>
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════
             SECTION 6 — LAWFUL BASIS & RISK
        ════════════════════════════════════════ --}}
        <div class="form-section bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-4 lg:px-6 py-3 flex items-center gap-2">
                <i data-feather="alert-triangle" class="w-4 h-4 text-white flex-shrink-0"></i>
                <h2 class="text-sm lg:text-base font-bold text-white">Lawful Basis & Risk</h2>
            </div>
            <div class="p-4 lg:p-6 grid grid-cols-1 gap-4">

                {{-- Lawful Basis --}}
                <div>
                    <label class="field-label">Lawful Basis <span class="text-red-500">*</span></label>
                    @php
                        $selectedBasis = old('lawful_basis', is_string($ropa->lawful_basis) ? json_decode($ropa->lawful_basis, true) ?? [] : ($ropa->lawful_basis ?? []));
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-1">
                        @foreach([
                            'Consent','Contractual Obligation','Legal Obligation','Vital Interest',
                            'Public Interest','Legitimate Interest',
                            'Where The Data Subject Has Made The Information Public','Scientific Research'
                        ] as $basis)
                            <label class="checkbox-label">
                                <input type="checkbox" name="lawful_basis[]" value="{{ $basis }}"
                                       {{ in_array($basis, (array)$selectedBasis) ? 'checked' : '' }}
                                       class="checkbox-input">
                                <span>{{ $basis }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Risk Level --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label">Risk Level</label>
                        <select name="risk_level" class="field-input">
                            <option value="">Select risk level</option>
                            @foreach(['Low','Medium','High','Critical'] as $level)
                                <option value="{{ strtolower($level) }}"
                                    {{ old('risk_level', $ropa->risk_level) === strtolower($level) ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="field-label">Risk Report / Reference</label>
                        <input type="text" name="risk_report"
                               value="{{ old('risk_report', $ropa->risk_report) }}"
                               placeholder="e.g. DPIA-2024-01"
                               class="field-input">
                    </div>
                </div>

            </div>
        </div>

        {{-- ── FORM ACTIONS ── --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2 pb-6">
            <a href="{{ route('ropa.show', $ropa->id) }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700
                      border border-gray-300 rounded-xl hover:bg-gray-200 transition font-semibold text-sm">
                <i data-feather="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-orange-600 text-white
                           rounded-xl hover:bg-orange-700 active:scale-95 transition font-semibold text-sm shadow-md hover:shadow-lg">
                <i data-feather="save" class="w-4 h-4"></i>
                Save Changes
            </button>
        </div>

    </form>
</div>

<style>
    .field-label {
        @apply block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5;
    }
    .field-input {
        @apply w-full border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-sm
               text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400
               focus:border-orange-400 transition placeholder-gray-400;
    }
    .checkbox-label {
        @apply flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200
               bg-gray-50 hover:bg-orange-50 hover:border-orange-300 cursor-pointer
               transition text-sm text-gray-700 select-none;
    }
    .checkbox-input {
        @apply w-4 h-4 rounded accent-orange-600 flex-shrink-0;
    }

    /* Toggle switch */
    .toggle-label { @apply flex items-center gap-3 cursor-pointer select-none; }
    .toggle-checkbox { @apply sr-only; }
    .toggle-track {
        @apply relative inline-flex h-5 w-9 flex-shrink-0 rounded-full
               border-2 border-transparent bg-gray-300 transition-colors duration-200;
    }
    .toggle-track::after {
        content: '';
        @apply absolute left-0 top-0 h-4 w-4 rounded-full bg-white shadow
               transition-transform duration-200;
    }
    .toggle-checkbox:checked + .toggle-track { @apply bg-orange-500; }
    .toggle-checkbox:checked + .toggle-track::after { @apply translate-x-4; }

    /* Section entrance animation */
    .form-section {
        animation: slideUp 0.3s ease both;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .form-section:nth-child(1) { animation-delay: 0.04s; }
    .form-section:nth-child(2) { animation-delay: 0.08s; }
    .form-section:nth-child(3) { animation-delay: 0.12s; }
    .form-section:nth-child(4) { animation-delay: 0.16s; }
    .form-section:nth-child(5) { animation-delay: 0.20s; }
    .form-section:nth-child(6) { animation-delay: 0.24s; }
</style>

<script>
    feather.replace();

    // ── Helpers ──
    const show = el => el && el.classList.remove('hidden');
    const hide = el => el && el.classList.add('hidden');
    const showEl = el => el && (el.style.display = '');
    const hideEl = el => el && (el.style.display = 'none');

    // ── Organisation "Other" ──
    const orgSelect   = document.getElementById('organisation_name');
    const orgOtherDiv = document.getElementById('other_organisation_div');
    orgSelect.addEventListener('change', () => orgSelect.value === 'Other' ? show(orgOtherDiv) : hide(orgOtherDiv));

    // ── Department "Other" ──
    const deptSelect   = document.getElementById('department_name');
    const deptOtherDiv = document.getElementById('other_department_div');
    deptSelect.addEventListener('change', () => deptSelect.value === 'Other' ? show(deptOtherDiv) : hide(deptOtherDiv));

    // ── Information Shared cascade ──
    const infoShared   = document.getElementById('information_shared');
    const sharingDeps  = document.querySelectorAll('.toggle-dependent');
    function toggleSharingFields() {
        sharingDeps.forEach(el => infoShared.checked ? showEl(el) : hideEl(el));
        if (!infoShared.checked) {
            hide(document.getElementById('local_orgs_div'));
            hide(document.getElementById('transborder_countries_div'));
        }
    }
    infoShared.addEventListener('change', toggleSharingFields);

    // ── Local sharing → local orgs ──
    const localSharing   = document.getElementById('local_sharing');
    const localOrgsDiv   = document.getElementById('local_orgs_div');
    if (localSharing) {
        localSharing.addEventListener('change', () => localSharing.checked ? show(localOrgsDiv) : hide(localOrgsDiv));
    }

    // ── Transborder sharing → countries ──
    const transborderSharing     = document.getElementById('transborder_sharing');
    const transborderCountriesDiv = document.getElementById('transborder_countries_div');
    if (transborderSharing) {
        transborderSharing.addEventListener('change', () =>
            transborderSharing.checked ? show(transborderCountriesDiv) : hide(transborderCountriesDiv)
        );
    }

    // ── Unsaved-changes warning ──
    let formDirty = false;
    document.getElementById('editForm').addEventListener('change', () => formDirty = true);
    document.getElementById('editForm').addEventListener('submit', () => formDirty = false);
    window.addEventListener('beforeunload', e => {
        if (formDirty) { e.preventDefault(); e.returnValue = ''; }
    });
</script>

@endsection