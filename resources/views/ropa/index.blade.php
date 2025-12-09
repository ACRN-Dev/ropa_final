@extends('layouts.app')

@section('title', 'Create ROPA Record')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold text-orange-600 mb-6">Create ROPA Record</h1>

  {{-- Success --}}
    @if(session('success'))
        <div class="alert bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
        <div class="alert bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('ropa.store') }}" method="POST" class="space-y-10">
        @csrf




<!-- ORGANISATION & DEPARTMENT -->
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-bold mb-4 text-gray-700">Organisation Information</h2>

    <div class="grid md:grid-cols-2 gap-6">

        <!-- Organisation Name -->
        <div>
            <label class="block font-semibold mb-1">Organisation Name</label>
            <select name="organisation_name" id="organisation_name" class="w-full border rounded-lg p-2">
                <option value="">-- Select Organisation --</option>
                @foreach(['Mutala Trust','Infectious Diseases Research Lab','Clinresco','Other'] as $org)
                    <option value="{{ $org }}" {{ old('organisation_name') == $org ? 'selected' : '' }}>
                        {{ $org }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Other Organisation Input -->
        <div id="other_organisation_wrapper" class="{{ old('organisation_name') == 'Other' ? '' : 'hidden' }}">
            <label class="block font-semibold mb-1">Specify Other Organisation(s)</label>
            <div id="other_organisation_container" class="space-y-2">
                <input type="text" name="other_organisation_name[]" class="w-full border rounded-lg p-2" placeholder="Enter organisation name">
            </div>
            <button type="button" id="add_other_organisation" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700">
                Add More
            </button>
        </div>

        <!-- Department Input -->
      <!-- Department (Read-Only Input) -->
<div>
    <label class="block font-semibold mb-1">Department</label>
    <input type="text" 
           name="department" 
           value="{{ old('department', \App\Models\User::find(auth()->id())->department ?? '') }}" 
           class="w-full border rounded-lg p-2 bg-gray-100 cursor-not-allowed" 
           readonly>
</div>


        <!-- Other Department Input -->
        <div id="other_department_wrapper" class="{{ old('department') == 'Other' ? '' : 'hidden' }}">
            <label class="block font-semibold mb-1">Specify Other Department(s)</label>
            <div id="other_department_container" class="space-y-2">
                <input type="text" name="other_department[]" class="w-full border rounded-lg p-2" placeholder="Enter department name">
            </div>
            <button type="button" id="add_other_department" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700">
                Add More
            </button>
        </div>

    </div>
</div>



        <!-- PROCESSING INFORMATION -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-bold mb-4 text-gray-700">Processing Information</h2>
            <div class="grid gap-6">

                <!-- Processes -->
                <div>
                    <label class="block font-semibold mb-1">Processes</label>
                    <input type="text" name="processes[]" class="w-full border rounded-lg p-2" placeholder="e.g. Data collection, analysis">
                </div>

                <!-- Data Sources -->
                <div>
                    <label class="block font-semibold mb-1">Data Sources</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['Employees','Participants','Other','I do not know'] as $source)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="data_sources[]" value="{{ $source }}" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" {{ (is_array(old('data_sources')) && in_array($source, old('data_sources'))) ? 'checked' : '' }}>
                                <span>{{ $source }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div id="data_sources_other_container" class="space-y-2 mt-2 hidden">
                        <input type="text" name="data_sources_other[]" class="w-full border rounded-lg p-2" placeholder="Specify other data source">
                    </div>
                    <button type="button" id="add_data_sources_other" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700 hidden">Add More</button>
                </div>

                <!-- Data Formats -->
                <div>
                    <label class="block font-semibold mb-1">Data Formats</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['CSV','JSON','XML','PDF','DOCX','EXCEL','Other','I do not know'] as $format)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="data_formats[]" value="{{ $format }}" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" {{ (is_array(old('data_formats')) && in_array($format, old('data_formats'))) ? 'checked' : '' }}>
                                <span>{{ $format }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div id="data_formats_other_container" class="space-y-2 mt-2 hidden">
                        <input type="text" name="data_formats_other[]" class="w-full border rounded-lg p-2" placeholder="Specify other data format">
                    </div>
                    <button type="button" id="add_data_formats_other" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700 hidden">Add More</button>
                </div>

                <!-- Personal Data Categories -->
                <div>
                    <label class="block font-semibold mb-1">Personal Data Categories</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['Name','Address','Email','Phone','ID Number','Financial','Health','Other','I do not know'] as $category)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="personal_data_categories[]" value="{{ $category }}" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" {{ (is_array(old('personal_data_categories')) && in_array($category, old('personal_data_categories'))) ? 'checked' : '' }}>
                                <span>{{ $category }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div id="personal_data_categories_other_container" class="space-y-2 mt-2 hidden">
                        <input type="text" name="personal_data_categories_other[]" class="w-full border rounded-lg p-2" placeholder="Specify other category">
                    </div>
                    <button type="button" id="add_personal_data_categories_other" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700 hidden">Add More</button>
                </div>

             <!-- Additional Details -->
@foreach(['Data Volume'=>'data_volume[]','Retention Period (Years)'=>'retention_period_years[]','Retention Rationale'=>'retention_rationale[]'] as $label => $name)
    <div>
        <label class="block font-semibold mb-1">{{ $label }}</label>
        @if($label == 'Data Volume')
            <select name="{{ $name }}" class="w-full border rounded-lg p-2">
                <option value="">-- Select Data Volume --</option>
                <option value="1-100" {{ old('data_volume')[0] ?? '' == '1-100' ? 'selected' : '' }}>1-100 (Low)</option>
                <option value="101-200" {{ old('data_volume')[0] ?? '' == '101-200' ? 'selected' : '' }}>101-200 (Medium)</option>
                <option value="201-500" {{ old('data_volume')[0] ?? '' == '201-500' ? 'selected' : '' }}>201-500 (High)</option>
                <option value="500+" {{ old('data_volume')[0] ?? '' == '500+' ? 'selected' : '' }}>500+ (Very High)</option>
            </select>
        @else
            <input type="text" name="{{ $name }}" class="w-full border rounded-lg p-2">
        @endif
    </div>
@endforeach


            </div>
        </div>

        <!-- INFORMATION SHARING -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-bold mb-4 text-gray-700">Information Sharing</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block font-semibold mb-1">Information Shared?</label>
                    <select name="information_shared" id="information_shared" class="w-full border rounded-lg p-2">
                        <option value="">-- Select --</option>
                        <option value="1" {{ old('information_shared')=='1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('information_shared')=='0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Sharing Type</label>
                    <div class="flex flex-wrap gap-4 mt-1">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="sharing_type[]" value="local" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" {{ (is_array(old('sharing_type')) && in_array('local', old('sharing_type'))) ? 'checked' : '' }}>
                            <span>Local Sharing</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="sharing_type[]" value="transborder" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" {{ (is_array(old('sharing_type')) && in_array('transborder', old('sharing_type'))) ? 'checked' : '' }}>
                            <span>Transborder Sharing</span>
                        </label>
                    </div>
                </div>
            </div>

            <div id="sharing_details" class="mt-4 hidden">
                <div id="local_sharing_container" class="mb-4 hidden">
                    <label class="block font-semibold mb-1">Local Organisations</label>
                    <div id="local_fields_container" class="space-y-2">
                        <input type="text" name="local_organizations[]" class="w-full border rounded-lg p-2" placeholder="Enter local organisation">
                    </div>
                    <button type="button" id="add_local_field" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700">Add More</button>
                </div>

                <div id="transborder_sharing_container" class="mb-4 hidden">
                    <label class="block font-semibold mb-1">Transborder Countries</label>
                    <div id="transborder_fields_container" class="space-y-2">
                        <input type="text" name="transborder_countries[]" class="w-full border rounded-lg p-2" placeholder="Enter country">
                    </div>
                    <button type="button" id="add_transborder_field" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700">Add More</button>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Sharing Comment</label>
                    <textarea name="sharing_comment" class="w-full border rounded-lg p-3">{{ old('sharing_comment') }}</textarea>
                </div>
            </div>
        </div>

        <!-- ACCESS CONTROL -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <label class="block font-semibold mb-2">Access Control Implemented</label>
            <select name="access_control" id="access_control_select" class="w-full border rounded-lg p-2">
                <option value="">-- Select --</option>
                <option value="1" {{ old('access_control') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('access_control') == '0' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <!-- ACCESS & SECURITY MEASURES -->
        <div id="access_security_measures_section" class="bg-white p-6 rounded-xl shadow-md hidden">
            <h2 class="text-xl font-bold mb-4 text-gray-700">Access & Security Measures</h2>
            
            <!-- TECHNICAL MEASURES -->
            <div>
                <label class="block font-semibold mb-2">Technical Measures</label>
                <div class="flex flex-wrap gap-4">
                    @foreach([
                        'Encryption at rest & in transit','RBAC','MFA','Automatic audit logs',
                        'Segmented network architecture','Firewalls and VPN-restricted admin access',
                        'Regular vulnerability scanning and patching','High-availability and fail-safe mechanisms','Other','I do not know'
                    ] as $tech)
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="technical_measures[]" value="{{ $tech }}" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" {{ (is_array(old('technical_measures')) && in_array($tech, old('technical_measures'))) ? 'checked' : '' }}>
                            <span>{{ $tech }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="technical_measures_other_container" class="space-y-2 mt-2 hidden">
                    <input type="text" name="technical_measures_other[]" class="w-full border rounded-lg p-2" placeholder="Specify other technical measure">
                </div>
                <button type="button" id="add_technical_measures_other" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700 hidden">Add More</button>
            </div>

            <!-- ORGANISATIONAL MEASURES -->
            <div class="mt-4">
                <label class="block font-semibold mb-2">Organisational Measures</label>
                <div class="flex flex-wrap gap-4">
                    @foreach([
                        'Biometric privacy notice','Internal privacy data policy','Administrator access governance','Conduct DPIAs',
                        'Legitimate Interest Assessment (LIA)','Retention and disposal schedule','Incident response and breach reporting procedures',
                        'Employee onboarding and privacy training','Vendor due diligence and contractual safeguards','Periodic internal audits','Other','I do not know'
                    ] as $org)
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="organisational_measures[]" value="{{ $org }}" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" {{ (is_array(old('organisational_measures')) && in_array($org, old('organisational_measures'))) ? 'checked' : '' }}>
                            <span>{{ $org }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="organisational_measures_other_container" class="space-y-2 mt-2 hidden">
                    <input type="text" name="organisational_measures_other[]" class="w-full border rounded-lg p-2" placeholder="Specify other organisational measure">
                </div>
                <button type="button" id="add_organisational_measures_other" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700 hidden">Add More</button>
            </div>
        </div>

        <!-- LAWFUL BASIS & RISK -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-bold mb-4 text-gray-700">Lawful Basis & Risk</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-1">Lawful Basis</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['Consent','Public Interest','Legitimate Interest','Contractual Obligation','Legal Obligation','Vital Interest','Scientific Research','Other','I do not know'] as $basis)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="lawful_basis[]" value="{{ $basis }}" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" {{ (is_array(old('lawful_basis')) && in_array($basis, old('lawful_basis'))) ? 'checked' : '' }}>
                                <span>{{ $basis }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div id="lawful_basis_other_container" class="space-y-2 mt-2 hidden">
                        <input type="text" name="lawful_basis_other[]" class="w-full border rounded-lg p-2" placeholder="Specify other lawful basis">
                    </div>
                    <button type="button" id="add_lawful_basis_other" class="mt-2 text-white bg-orange-600 px-3 py-1 rounded hover:bg-orange-700 hidden">Add More</button>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Risk Report</label>
                    <textarea name="risk_report" class="w-full border rounded-lg p-2" rows="4">{{ old('risk_report') }}</textarea>
                </div>
            </div>
        </div>

        <!-- SUBMIT BUTTON -->
        <div class="flex justify-end">
            <button type="submit" class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
                Submit ROPA
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease-out, max-height 0.5s ease-out";
            alert.style.opacity = 0;
            alert.style.maxHeight = 0;
            setTimeout(() => alert.remove(), 500); // remove from DOM after fade
        }, 50000); // 10 seconds
    });
});
</script>

@include('ropajs.ropa-form-scripts') <!-- JS for dynamic fields -->

@endsection
