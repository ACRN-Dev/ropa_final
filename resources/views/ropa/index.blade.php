@extends('layouts.app')

@section('title', 'Record of Processing Activities')

@section('content')
<div class="container mx-auto p-4 sm:p-6">

   {{-- OVERALL PROGRESS BAR + SAVE BUTTON --}}
<div class="sticky top-0 z-50 bg-white shadow-md p-4 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
        <h2 class="text-xl font-bold text-orange-500 mb-2 sm:mb-0 flex items-center">
            <i data-feather="activity" class="w-5 h-5 mr-2 text-orange-500"></i> Overall Progress
        </h2>
        <div class="w-full sm:w-64 bg-gray-200 rounded-full h-4">
            <div class="bg-orange-500 h-4 rounded-full" id="overallProgress" style="width:0%"></div>
        </div>
        <span class="text-sm font-semibold text-gray-700 mt-1 block sm:mt-0" id="overallProgressLabel">0%</span>
    </div>

    <div>
        <button type="submit" form="progressForm" class="bg-orange-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 flex items-center transition">
            <i data-feather="save" class="w-5 h-5 mr-2"></i> Save Progress
        </button>
    </div>
</div>

{{-- Main Form --}}
    <form id="progressForm" action="" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- SECTION 1: Organisation Details --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-orange-500 flex items-center">
            <i data-feather="briefcase" class="w-5 h-5 mr-2"></i> Organisation Details
        </h2>
        <span class="text-sm font-semibold text-gray-600" id="orgProgressLabel">0%</span>
    </div>

    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
        <div class="bg-indigo-600 h-2 rounded-full" id="orgProgress" style="width: 0%"></div>
    </div>



<!-- SECTION 1: Organisation Details -->
<!-- SECTION 1: Organisation Details -->
<!-- SECTION 1: Organisation Details -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">


    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
        <div class="bg-indigo-600 h-2 rounded-full" id="orgProgress" style="width: 0%"></div>
    </div>

    <!-- Form Fields -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <!-- Organisation Name Dropdown -->
        <div>
            <label for="organisation_name"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Organisation Name
            </label>
            <select name="organisation_name" id="organisation_name"
                class="section-org p-2 border rounded-lg w-full focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option value="">Select Organisation</option>
                <option value="Mutala Trust">Mutala Trust</option>
                <option value="Charlse River Medical Group">Charlse River Medical Group</option>
                <option value="Infectious Diseases Research Lab">Infectious Diseases Research Lab</option>
                <option value="Clinresco">Clinresco</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- Custom Organisation Name (Visible only when "Other" is selected) -->
        <div id="otherOrgContainer" class="hidden">
            <label for="other_organisation_name"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Specify Organisation Name
            </label>
            <input type="text" name="other_organisation_name" id="other_organisation_name"
                placeholder="Enter organisation name"
                class="section-org p-2 border rounded-lg w-full focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <!-- Department Dropdown -->
        <div>
            <label for="department"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Department
            </label>
            <select name="department" id="department"
                class="section-org p-2 border rounded-lg w-full focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option value="">Select Department</option>
                <option value="IT">IT</option>
                <option value="HR">HR</option>
                <option value="Community Engagement">Community Engagement</option>
                <option value="Data & Biostatistics">Data & Biostatistics</option>
                <option value="Laboratory">Laboratory</option>
                <option value="Pharmacy">Pharmacy</option>
                <option value="Finance & Administration">Finance & Administration</option>
                <option value="Clinical Operations (ClinOps)">Clinical Operations (ClinOps)</option>
                <option value="Project Management">Project Management</option>
                <option value="Legal & Compliance">Legal & Compliance</option>
                <option value="Data Protection">Data Protection</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- Custom Department Name (Visible only when "Other" is selected) -->
        <div id="otherDeptContainer" class="hidden">
            <label for="other_department"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Specify Department Name
            </label>
            <input type="text" name="other_department" id="other_department"
                placeholder="Enter department name"
                class="section-org p-2 border rounded-lg w-full focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

    </div>
</div>


      {{-- SECTION 2: Processing Activity --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-orange-500 flex items-center">
            <i data-feather="cpu" class="w-5 h-5 mr-2"></i> Processing Activity Details 
        </h2>
        <span class="text-sm font-semibold text-gray-600" id="activityProgressLabel">0%</span>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
        <div class="bg-yellow-500 h-2 rounded-full" id="activityProgress" style="width: 0%"></div>
    </div>

    <!-- Process Form -->
    {{-- SECTION 2: Processing Activity --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
   

    <!-- Process Form -->
    <div id="processContainer">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4 process-row">
            <input type="text" name="processes[]" placeholder="Process Name" class="section-activity p-2 border rounded-lg w-full">

            <div class="dynamic-field">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Data Sources</label>
                <div class="data-sources-wrapper">
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="data_sources[0][]" placeholder="Data Source" class="section-activity p-2 border rounded-lg w-full">
                        <button type="button" class="bg-red-600 text-white px-2 rounded-lg remove-source">Remove</button>
                    </div>
                </div>
                <button type="button" class="bg-orange-500 text-white px-2 py-1 rounded-lg mt-1 add-source">Add Data Source</button>
            </div>

            <div class="dynamic-field">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Data Formats</label>
                <div class="data-formats-wrapper">
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="data_formats[0][]" placeholder="Data Format" class="section-activity p-2 border rounded-lg w-full">
                        <button type="button" class="bg-red-600 text-white px-2 rounded-lg remove-format">Remove</button>
                    </div>
                </div>
                <button type="button" class="bg-orange-500 text-white px-2 py-1 rounded-lg mt-1 add-format">Add Data Format</button>
            </div>

            <input type="text" name="information_nature" placeholder="Nature of Information" class="section-activity p-2 border rounded-lg w-full">

            <!-- Approximate Number of Records -->
            <div>
                <label for="records_count" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Approximate Number of Records</label>
                <input type="number" name="records_count[]" placeholder="Enter number of records" class="section-activity p-2 border rounded-lg w-full" min="0">
            </div>

            <!-- Data Volume -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Data Volume</label>
                <input type="text" name="data_volume[]" placeholder="Low / Medium / High" class="section-activity p-2 border rounded-lg w-full" readonly>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Add Data Source dynamically
    document.querySelectorAll('.add-source').forEach(btn => {
        btn.addEventListener('click', function(){
            const wrapper = this.previousElementSibling;
            const index = Array.from(document.querySelectorAll('.process-row')).indexOf(this.closest('.process-row'));
            const div = document.createElement('div');
            div.classList.add('flex','gap-2','mb-2');
            div.innerHTML = `<input type="text" name="data_sources[${index}][]" placeholder="Data Source" class="section-activity p-2 border rounded-lg w-full">
                             <button type="button" class="bg-red-600 text-white px-2 rounded-lg remove-source">-</button>`;
            wrapper.appendChild(div);
        });
    });

    // Add Data Format dynamically
    document.querySelectorAll('.add-format').forEach(btn => {
        btn.addEventListener('click', function(){
            const wrapper = this.previousElementSibling;
            const index = Array.from(document.querySelectorAll('.process-row')).indexOf(this.closest('.process-row'));
            const div = document.createElement('div');
            div.classList.add('flex','gap-2','mb-2');
            div.innerHTML = `<input type="text" name="data_formats[${index}][]" placeholder="Data Format" class="section-activity p-2 border rounded-lg w-full">
                             <button type="button" class="bg-red-600 text-white px-2 rounded-lg remove-format">-</button>`;
            wrapper.appendChild(div);
        });
    });

    // Remove dynamic field
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-source')){
            e.target.parentElement.remove();
        }
        if(e.target.classList.contains('remove-format')){
            e.target.parentElement.remove();
        }
    });

    // Update Data Volume based on number of records
    document.addEventListener('input', function(e){
        if(e.target.name === 'records_count[]'){
            const value = parseInt(e.target.value);
            let volume = '';
            if(isNaN(value)){
                volume = '';
            } else if(value < 25){
                volume = 'Low';
            } else if(value <= 100){
                volume = 'Medium';
            } else {
                volume = 'High';
            }
            e.target.closest('.process-row').querySelector('input[name="data_volume[]"]').value = volume;
        }
    });

});
</script>

{{-- SECTION 3: Data Sharing & Outsourcing --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-orange-500 flex items-center">
            <i data-feather="share-2" class="w-5 h-5 mr-2"></i> Data Sharing & Outsourcing
        </h2>
        <span class="text-sm font-semibold text-gray-600" id="shareProgressLabel">0%</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Information Shared --}}
        <select name="information_shared" id="information_shared"
            class="p-2 border rounded-lg w-full focus:ring-2 focus:ring-orange-500 hover:border-orange-500">
            <option value="">Information Shared?</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

    </div>

    {{-- CONDITIONAL SECTION --}}
    <div id="infoSharingSection" class="mt-6 hidden">

        <h3 class="text-lg font-semibold mb-3 text-orange-600">
            Information Sharing Details
        </h3>

        {{-- Local + Transborder Checkboxes --}}
        <div class="flex gap-6 mb-4">

            <label class="flex items-center space-x-2 text-gray-800 dark:text-gray-200">
                <input type="checkbox" id="local_check" name="sharing_local" value="1"
                       class="h-4 w-4 text-orange-500 focus:ring-orange-500">
                <span>Local Sharing</span>
            </label>

            <label class="flex items-center space-x-2 text-gray-800 dark:text-gray-200">
                <input type="checkbox" id="trans_check" name="sharing_transborder" value="1"
                       class="h-4 w-4 text-orange-500 focus:ring-orange-500">
                <span>Transborder Sharing</span>
            </label>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- MULTIPLE LOCAL ORGANIZATIONS --}}
            <div id="localContainer" class="col-span-1 sm:col-span-2 hidden">
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
                    Organizations Shared With (Local)
                </label>

                <div id="localRepeat">
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="local_organizations[]" 
                               class="p-2 border rounded-lg w-full focus:ring-2 focus:ring-orange-500 hover:border-orange-500"
                               placeholder="Enter organization">

                        <button type="button"
                                class="removeLocal bg-red-500 text-white px-3 py-2 rounded-md hidden hover:bg-red-600">
                            -
                        </button>
                    </div>
                </div>

                <button type="button" id="addLocal"
                        class="mt-2 bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">
                    + Add Organization
                </button>
            </div>

            {{-- MULTIPLE TRANSBORDER COUNTRIES --}}
            <div id="transContainer" class="col-span-1 sm:col-span-2 hidden">
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
                    Countries Shared With (Transborder)
                </label>

                <div id="transRepeat">
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="transborder_countries[]"
                               class="p-2 border rounded-lg w-full focus:ring-2 focus:ring-orange-500 hover:border-orange-500"
                               placeholder="Enter country">

                        <button type="button"
                                class="removeTrans bg-red-500 text-white px-3 py-2 rounded-md hidden hover:bg-red-600">
                            -
                        </button>
                    </div>
                </div>

                <button type="button" id="addTrans"
                        class="mt-2 bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">
                    + Add Country
                </button>
            </div>

            {{-- Description --}}
            <textarea name="sharing_description"
                id="sharing_description"
                placeholder="Describe how the information is shared..."
                class="p-2 border rounded-lg w-full col-span-1 sm:col-span-2 hidden focus:ring-2 focus:ring-orange-500 hover:border-orange-500"></textarea>

            {{-- Number of Days --}}
            <input type="number" name="sharing_days"
                id="sharing_days"
                placeholder="Estimated Number of Days Shared"
                class="p-2 border rounded-lg w-full hidden focus:ring-2 focus:ring-orange-500 hover:border-orange-500">

            {{-- Comments --}}
            <textarea name="sharing_comment"
                id="sharing_comment"
                placeholder="Comments..."
                class="p-2 border rounded-lg w-full col-span-1 sm:col-span-2 hidden focus:ring-2 focus:ring-orange-500 hover:border-orange-500"></textarea>

        </div>

    </div>
</div>


{{-- SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    let infoShared = document.getElementById('information_shared');
    let infoSection = document.getElementById('infoSharingSection');

    // Checkboxes
    let localCheck = document.getElementById('local_check');
    let transCheck = document.getElementById('trans_check');

    // Containers
    let localContainer = document.getElementById('localContainer');
    let transContainer = document.getElementById('transContainer');

    // Repeat containers
    let localRepeat = document.getElementById('localRepeat');
    let addLocal = document.getElementById('addLocal');

    let transRepeat = document.getElementById('transRepeat');
    let addTrans = document.getElementById('addTrans');

    // Other fields
    let desc = document.getElementById('sharing_description');
    let days = document.getElementById('sharing_days');
    let comment = document.getElementById('sharing_comment');

    const hideAll = () => {
        localContainer.classList.add('hidden');
        transContainer.classList.add('hidden');
        desc.classList.add('hidden');
        days.classList.add('hidden');
        comment.classList.add('hidden');
    };

    // Show main section only when yes
    infoShared.addEventListener('change', () => {
        if (infoShared.value === "1") {
            infoSection.classList.remove('hidden');
        } else {
            infoSection.classList.add('hidden');
            localCheck.checked = false;
            transCheck.checked = false;
            hideAll();
        }
    });

    // Checkbox logic (supports both checked)
    const updateVisibility = () => {
        hideAll();

        if (localCheck.checked) {
            localContainer.classList.remove('hidden');
        }

        if (transCheck.checked) {
            transContainer.classList.remove('hidden');
        }

        if (localCheck.checked || transCheck.checked) {
            desc.classList.remove('hidden');
            days.classList.remove('hidden');
            comment.classList.remove('hidden');
        }
    };

    localCheck.addEventListener('change', updateVisibility);
    transCheck.addEventListener('change', updateVisibility);

    // Add Local Organization
    addLocal.addEventListener('click', () => {
        let div = document.createElement('div');
        div.classList.add("flex", "gap-2", "mb-2");

        div.innerHTML = `
            <input type="text" name="local_organizations[]" 
                   class="p-2 border rounded-lg w-full"
                   placeholder="Enter organization">

            <button type="button"
                    class="removeLocal bg-red-500 text-white px-3 py-2 rounded-md">-</button>
        `;

        localRepeat.appendChild(div);
    });

    localRepeat.addEventListener('click', event => {
        if (event.target.classList.contains('removeLocal')) {
            event.target.parentElement.remove();
        }
    });

    // Add Transborder Country
    addTrans.addEventListener('click', () => {
        let div = document.createElement('div');
        div.classList.add("flex", "gap-2", "mb-2");

        div.innerHTML = `
            <input type="text" name="transborder_countries[]"
                   class="p-2 border rounded-lg w-full"
                   placeholder="Enter country">

            <button type="button"
                    class="removeTrans bg-red-500 text-white px-3 py-2 rounded-md">-</button>
        `;

        transRepeat.appendChild(div);
    });

    transRepeat.addEventListener('click', event => {
        if (event.target.classList.contains('removeTrans')) {
            event.target.parentElement.remove();
        }
    });

});
</script>

{{-- SECTION 4: Legal & Retention --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-orange-500 flex items-center">
            <i data-feather="file-text" class="w-5 h-5 mr-2"></i> Legal & Retention Period 
        </h2>
        <span class="text-sm font-semibold text-gray-600" id="legalProgressLabel">0%</span>
    </div>

    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
        <div class="bg-red-600 h-2 rounded-full" id="legalProgress" style="width: 0%"></div>
    </div>

    {{-- LAWFUL BASIS CHECKBOXES --}}
    <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 block">
        Lawful Basis for Processing
    </label>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4 text-sm">

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="lawful_basis[]" value="Consent" class="lawful-option section-legal">
            <span>Consent</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="lawful_basis[]" value="Contractual Obligation" class="lawful-option section-legal">
            <span>Contractual Obligation</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="lawful_basis[]" value="Legal Obligation" class="lawful-option section-legal">
            <span>Legal Obligation</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="lawful_basis[]" value="Vital Interest" class="lawful-option section-legal">
            <span>Vital Interest</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="lawful_basis[]" value="Public Interest" class="lawful-option section-legal">
            <span>Public Interest</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="lawful_basis[]" value="Scientific Research" class="lawful-option section-legal">
            <span>Scientific Research</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="lawful_basis[]" value="Legitimate Interest" class="lawful-option section-legal">
            <span>Legitimate Interest</span>
        </label>

        {{-- I DO NOT KNOW --}}
        <label class="flex items-center space-x-2 text-red-600 font-semibold">
            <input type="checkbox" id="lawful_idk" name="lawful_basis[]" value="I Do Not Know" class="section-legal">
            <span>I do not know</span>
        </label>
    </div>

    {{-- RETENTION PERIOD / PEOPLE WITH ACCESS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <input type="number" name="retention_period_years" placeholder="Retention Period (Years)"
            class="section-legal p-2 border rounded-lg w-full">

        <input type="number" name="access_estimate" placeholder="Estimated Number of People With Access"
            class="section-legal p-2 border rounded-lg w-full">
    </div>

    <textarea name="retention_rationale" placeholder="Retention Rationale" rows="2"
        class="section-legal p-2 border rounded-lg w-full"></textarea>
</div>

{{-- SCRIPT FOR CHECKBOX LOGIC --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const idkCheckbox = document.getElementById('lawful_idk');
    const otherCheckboxes = document.querySelectorAll('.lawful-option');

    // When "I do not know" is checked → uncheck others
    idkCheckbox.addEventListener('change', function () {
        if (this.checked) {
            otherCheckboxes.forEach(cb => cb.checked = false);
        }
    });

    // When any other checkbox is selected → uncheck "I do not know"
    otherCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (this.checked) {
                idkCheckbox.checked = false;
            }
        });
    });

});
</script>

{{-- SECTION 5: Access & Users --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-orange-500 flex items-center">
            <i data-feather="users" class="w-5 h-5 mr-2"></i> Access Control Management 
        </h2>
        <span class="text-sm font-semibold text-gray-600" id="accessProgressLabel">0%</span>
    </div>

    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
        <div class="bg-purple-600 h-2 rounded-full" id="accessProgress" style="width: 0%"></div>
    </div>

    <div class="grid grid-cols-1 gap-4">

        {{-- Access Controlled --}}
        <select name="access_control" id="access_control" class="section-access p-2 border rounded-lg w-full">
            <option value="">Access Controlled?</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        {{-- CONDITIONAL SECTION: Access Control Measures --}}
        <div id="accessMeasuresSection" class="hidden">

            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 block">
                Access Control Measures
            </label>

            <div id="accessMeasuresContainer" class="space-y-2">
                <div class="flex items-center space-x-2">
                    <input type="text" name="access_measures[]" placeholder="Enter Access Control Measure"
                        class="section-access p-2 border rounded-lg w-full">
                    <button type="button" class="removeMeasure px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 hidden">
                        Remove
                    </button>
                </div>
            </div>

            <button type="button" id="addAccessMeasure"
                class="mt-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                + Add More
            </button>
        </div>

    </div>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const accessControl = document.getElementById('access_control');
    const measuresSection = document.getElementById('accessMeasuresSection');
    const addButton = document.getElementById('addAccessMeasure');
    const measuresContainer = document.getElementById('accessMeasuresContainer');

    // Show/hide measures section based on selection
    accessControl.addEventListener('change', function () {
        if (this.value === "1") {
            measuresSection.classList.remove('hidden');
        } else {
            measuresSection.classList.add('hidden');
            // Remove all except the first input
            measuresContainer.innerHTML = `<div class="flex items-center space-x-2">
                <input type="text" name="access_measures[]" placeholder="Enter Access Control Measure" class="section-access p-2 border rounded-lg w-full">
                <button type="button" class="removeMeasure px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 hidden">Remove</button>
            </div>`;
        }
    });

    // Add new measure input dynamically
    addButton.addEventListener('click', function () {
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2';
        div.innerHTML = `
            <input type="text" name="access_measures[]" placeholder="Enter Access Control Measure" class="section-access p-2 border rounded-lg w-full">
            <button type="button" class="removeMeasure px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600">Remove</button>
        `;
        measuresContainer.appendChild(div);

        // Add event listener for the remove button
        div.querySelector('.removeMeasure').addEventListener('click', function () {
            div.remove();
        });
    });

    // Optional: Add remove listener to initial hidden remove button
    const initialRemove = measuresContainer.querySelector('.removeMeasure');
    initialRemove.addEventListener('click', function () {
        measuresContainer.removeChild(this.parentElement);
    });
});
</script>



     {{-- SECTION 7: SOPs & Supporting Documents --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-orange-500 flex items-center mb-4">
        <i data-feather="upload" class="w-5 h-5 mr-2"></i> SOPs & Supporting Documents
    </h2>

    {{-- SOPs Container --}}
    <div id="sopContainer" class="space-y-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sop-entry">
            <input type="text" name="sop_name[]" placeholder="SOP Name" class="section-sop p-2 border rounded-lg w-full">
            <input type="text" name="sop_version[]" placeholder="SOP Version" class="section-sop p-2 border rounded-lg w-full">
            <input type="text" name="sop_owner[]" placeholder="SOP Owner/Author" class="section-sop p-2 border rounded-lg w-full">
            <input type="date" name="sop_date[]" placeholder="Date of Issue" class="section-sop p-2 border rounded-lg w-full">
            <input type="file" name="sop_file[]" class="p-2 border rounded-lg w-full">
            <input type="file" name="supporting_documents[]" class="p-2 border rounded-lg w-full">
            <button type="button" class="removeSOP px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 self-start mt-2">
                Remove
            </button>
        </div>

    </div>

    <button type="button" id="addSOP" class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-indigo-700">
        + Add SOP
    </button>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addButton = document.getElementById('addSOP');
    const sopContainer = document.getElementById('sopContainer');

    // Function to create a new SOP entry
    const createSOPEntry = () => {
        const div = document.createElement('div');
        div.className = 'grid grid-cols-1 sm:grid-cols-2 gap-4 sop-entry';

        div.innerHTML = `
            <input type="text" name="sop_name[]" placeholder="SOP Name" class="section-sop p-2 border rounded-lg w-full">
            <input type="text" name="sop_version[]" placeholder="SOP Version" class="section-sop p-2 border rounded-lg w-full">
            <input type="text" name="sop_owner[]" placeholder="SOP Owner/Author" class="section-sop p-2 border rounded-lg w-full">
            <input type="date" name="sop_date[]" placeholder="Date of Issue" class="section-sop p-2 border rounded-lg w-full">
            <input type="file" name="sop_file[]" class="p-2 border rounded-lg w-full">
            <input type="file" name="supporting_documents[]" class="p-2 border rounded-lg w-full">
            <button type="button" class="removeSOP px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 self-start mt-2">
                Remove
            </button>
        `;

        // Add remove functionality
        div.querySelector('.removeSOP').addEventListener('click', function () {
            div.remove();
        });

        return div;
    };

    // Add SOP button click
    addButton.addEventListener('click', function () {
        sopContainer.appendChild(createSOPEntry());
    });

    // Remove functionality for initial SOP entry
    sopContainer.querySelectorAll('.removeSOP').forEach(btn => {
        btn.addEventListener('click', function () {
            btn.parentElement.remove();
        });
    });
});
</script>



        {{-- SECTION 8: Risk Reporting & Mitigation --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-orange-500 flex items-center">
                    <i data-feather="alert-triangle" class="w-5 h-5 mr-2"></i> Risk Reporting & Mitigation
                </h2>
                <span class="text-sm font-semibold text-gray-600" id="riskProgressLabel">0%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
                <div class="bg-red-700 h-2 rounded-full" id="riskProgress" style="width: 0%"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <textarea name="risk_report[]" placeholder="Risk Reporting" rows="4" class="section-risk p-2 border rounded-lg w-full"></textarea>
                <textarea name="risk_mitigation[]" placeholder="Risk Mitigation Strategy" rows="4" class="section-risk p-2 border rounded-lg w-full"></textarea>
            </div>
        </div>


<div class="flex justify-end mt-6">
    <button type="submit" form="progressForm" 
        class="bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 flex items-center">
        <i data-feather="check" class="w-5 h-5 mr-2"></i> Submit
    </button>
</div>


    </form>
</div>


{{-- Feather Icons --}}
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        feather.replace({ 'aria-hidden': 'true' });

        const orgSelect = document.getElementById('organisation_name');
        const deptSelect = document.getElementById('department');
        const otherOrgContainer = document.getElementById('otherOrgContainer');
        const otherDeptContainer = document.getElementById('otherDeptContainer');

        // Show/hide "Other Organisation" field
        orgSelect.addEventListener('change', () => {
            if (orgSelect.value === 'Other') {
                otherOrgContainer.classList.remove('hidden');
            } else {
                otherOrgContainer.classList.add('hidden');
                document.getElementById('other_organisation_name').value = '';
            }
        });

        // Show/hide "Other Department" field
        deptSelect.addEventListener('change', () => {
            if (deptSelect.value === 'Other') {
                otherDeptContainer.classList.remove('hidden');
            } else {
                otherDeptContainer.classList.add('hidden');
                document.getElementById('other_department').value = '';
            }
        });
    });

    // Progress tracker
    function updateSectionProgress(sectionClass, progressId, labelId) {
        const fields = document.querySelectorAll(`.${sectionClass}`);
        const filled = Array.from(fields).filter(f => f.value.trim() !== "").length;
        const percent = fields.length ? Math.round((filled / fields.length) * 100) : 0;
        if(document.getElementById(progressId)) {
            document.getElementById(progressId).style.width = percent + '%';
        }
        if(document.getElementById(labelId)) {
            document.getElementById(labelId).textContent = percent + '%';
        }
        return percent;
    }

    function updateOverallProgress() {
        const sections = [
            'section-org','section-activity','section-share','section-legal',
            'section-access','section-submit','section-risk'
        ];
        const total = sections.length;
        let sum = 0;
        sections.forEach(sec => sum += updateSectionProgress(sec, 'temp', 'temp'));
        const overall = Math.round(sum / total);
        document.getElementById('overallProgress').style.width = overall + '%';
        document.getElementById('overallProgressLabel').textContent = overall + '%';

        updateSectionProgress('section-risk', 'riskProgress', 'riskProgressLabel');
    }

    document.querySelectorAll('.section-org, .section-activity, .section-share, .section-legal, .section-access, .section-submit, .section-risk')
        .forEach(input => input.addEventListener('input', updateOverallProgress));

    updateOverallProgress();
</script>

@endsection
