@extends('layouts.app')

@section('title', 'Add New ROPA Record')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-indigo-700 flex items-center">
        <i data-feather="plus-circle" class="w-6 h-6 mr-2"></i> Add New Record of Processing Activity
    </h2>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 flex items-center">
            <i data-feather="check-circle" class="w-5 h-5 mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li class="flex items-center">
                        <i data-feather="alert-circle" class="w-4 h-4 mr-1"></i> {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('ropa.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Organisation Name -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Organisation Name</label>
                    <select name="organisation_name" id="organisation_name" class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Select Organisation</option>
                        <option value="Mutala Trust">Mutala Trust</option>
                        <option value="Infectious Diseases Research Lab">Infectious Diseases Research Lab</option>
                        <option value="Charles River Medical Group">Charles River Medical Group</option>
                        <option value="Africa Clinical Research Network">Africa Clinical Research Network</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Specify Other Organisation -->
                <div id="other_organisation_div" style="display: none;">
                    <label class="block font-semibold text-gray-700 mb-1">Specify Other Organisation</label>
                    <input type="text" name="other_organisation" id="other_organisation"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter organisation name">
                </div>

                <!-- Department Name -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Department</label>
                    <select name="department_name" id="department_name"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Select Department</option>
                        <option value="Data Protection">Data Protection</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Human Resource">Human Resource</option>
                        <option value="Community Engagement">Community Engagement</option>
                        <option value="Data & Biostatics">Data & Biostatics</option>
                        <option value="Laboratory">Laboratory</option>
                        <option value="Pharmacy">Pharmacy</option>
                        <option value="Finance & Administration">Finance & Administration</option>
                        <option value="Clinical Operations">Clinical Operations</option>
                        <option value="Project Management">Project Management</option>
                        <option value="Legal & Compliance">Legal & Compliance</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Specify Other Department -->
                <div id="other_department_div" style="display: none;">
                    <label class="block font-semibold text-gray-700 mb-1">Specify Other Department</label>
                    <input type="text" name="other_department" id="other_department"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter department name">
                </div>

                

                <!-- Date Submitted -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Date Submitted</label>
                    <input type="date" name="date_submitted"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Information Shared -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="information_shared" value="1">
                    <label class="font-semibold text-gray-700">Information Shared</label>
                </div>

                <!-- Information Nature -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Information Nature</label>
                    <textarea name="information_nature" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                <!-- Outsourced Processing -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" id="outsourced_processing" name="outsourced_processing" value="1">
                    <label class="font-semibold text-gray-700">Outsourced Processing</label>
                </div>

                <!-- Processor (hidden until outsource checked) -->
                <div id="processor_div" style="display: none;">
                    <label class="block font-semibold text-gray-700 mb-1">Processor</label>
                    <input type="text" name="processor" id="processor"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter processor name">
                </div>

                <!-- Transborder Processing -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" id="transborder_processing" name="transborder_processing" value="1">
                    <label class="font-semibold text-gray-700">Transborder Processing</label>
                </div>

                <!-- Country -->
                <div id="country_div" style="display: none;">
                    <label class="block font-semibold text-gray-700 mb-1">Country</label>
                    <input type="text" name="country"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter country name">
                </div>

                <!-- Lawful Basis (multiple selection) -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Lawful Basis</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach([
                            'Consent',
                            'Contractual Obligation',
                            'Legal Obligation',
                            'Vital Interest',
                            'Public Interest',
                            'Legitimate Interest',
                            'Where The Data Subject Has Made The Information Public',
                            'Scientific Research'
                        ] as $option)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="lawful_basis[]" value="{{ $option }}" {{ in_array($option, old('lawful_basis', [])) ? 'checked' : '' }}>
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Retention Period -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Retention Period (Years)</label>
                    <input type="number" name="retention_period_years"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Retention Rationale -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Retention Rationale</label>
                    <textarea name="retention_rationale" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                <!-- Personal Data Categories -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Categories of Personal Data</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach(['Health Data', 'Financial Data', 'Employment Data', 'Contact Information', 'Genetic Data', 'Biometric Data', 'Criminal Record', 'Demographic Data', 'Other'] as $cat)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="personal_data_category[]" value="{{ $cat }}" {{ in_array($cat, old('personal_data_category', [])) ? 'checked' : '' }}>
                                <span>{{ $cat }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-end space-x-2">
                <a href="{{ route('ropa.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 flex items-center">
                    <i data-feather="arrow-left" class="w-4 h-4 mr-1"></i> Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 flex items-center">
                    <i data-feather="save" class="w-4 h-4 mr-1"></i> Save Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace();

    // Show/hide "Other" fields
    const toggleDisplay = (select, div) => {
        div.style.display = select.value === 'Other' ? 'block' : 'none';
    };

    const orgSelect = document.getElementById('organisation_name');
    const orgOtherDiv = document.getElementById('other_organisation_div');
    orgSelect.addEventListener('change', () => toggleDisplay(orgSelect, orgOtherDiv));
    toggleDisplay(orgSelect, orgOtherDiv);

    const deptSelect = document.getElementById('department_name');
    const deptOtherDiv = document.getElementById('other_department_div');
    deptSelect.addEventListener('change', () => toggleDisplay(deptSelect, deptOtherDiv));
    toggleDisplay(deptSelect, deptOtherDiv);

    // Toggle country field
    const transborder = document.getElementById('transborder_processing');
    const countryDiv = document.getElementById('country_div');
    transborder.addEventListener('change', () => {
        countryDiv.style.display = transborder.checked ? 'block' : 'none';
    });
    if (transborder.checked) countryDiv.style.display = 'block';

    // Toggle processor field
    const outsource = document.getElementById('outsourced_processing');
    const processorDiv = document.getElementById('processor_div');
    outsource.addEventListener('change', () => {
        processorDiv.style.display = outsource.checked ? 'block' : 'none';
    });
    if (outsource.checked) processorDiv.style.display = 'block';
</script>
@endsection
