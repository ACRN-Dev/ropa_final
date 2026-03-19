@extends('layouts.app')

@section('title', 'Add New ROPA Record')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-orange-600 mb-1">ROPA Management</h1>
        <p class="text-sm text-gray-600">Register of Processing Activities</p>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="font-semibold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- Error Alert --}}
    @if(session('error'))
        <div class="alert alert-error bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="font-semibold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-validation bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-6 shadow-sm" role="alert">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold mb-2">Please fix the following errors:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- CREATE FORM --}}
    <form action="{{ route('ropa.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-md p-8 space-y-8" novalidate>
        @csrf

        <!-- ORGANISATION & DEPARTMENT -->
        <div class="space-y-6">
            <div class="flex items-center gap-2 pb-2 border-b border-gray-200">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Organisation Information</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Organisation Name -->
                <div>
                    <label for="organisation_name" class="block font-semibold mb-2 text-gray-700">Organisation Name</label>
                    <select name="organisation_name" id="organisation_name"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                        <option value="">-- Select Organisation --</option>
                        @foreach(['Africa Clinical Research','Mutala Trust','Infectious Diseases Research Lab','Clinresco', 'Charles River Medical Group'] as $org)
                            <option value="{{ $org }}" {{ old('organisation_name') == $org ? 'selected' : '' }}>{{ $org }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Organisation Status -->
                <div>
                    <label for="organisation_status" class="block font-semibold mb-2 text-gray-700">Organisation Status</label>
                    <input type="text" id="organisation_status" name="organisation_status"
                           value="{{ old('organisation_status', 'Controller') }}"
                           class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-50 text-gray-700"
                           readonly aria-readonly="true">
                </div>

                <!-- Other Organisation Input -->
                <div id="other_organisation_wrapper" class="{{ old('organisation_name') == 'Other' ? '' : 'hidden' }}">
                    <label class="block font-semibold mb-2 text-gray-700">Specify Other Organisation(s)</label>
                    <div id="other_organisation_container" class="space-y-2">
                        <input type="text" name="other_organisation_name[]"
                               class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Enter organisation name">
                    </div>
                    <button type="button" id="add_other_organisation"
                            class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                        Add More
                    </button>
                </div>

                <!-- Department (Read-Only) -->
                <div>
                    <label for="department" class="block font-semibold mb-2 text-gray-700">Department</label>
                    <input type="text" id="department" name="department"
                           value="{{ old('department', \App\Models\User::find(auth()->id())->department ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-50 cursor-not-allowed text-gray-600"
                           readonly aria-readonly="true">
                </div>

                <!-- Other Department Input -->
                <div id="other_department_wrapper" class="{{ old('department') == 'Other' ? '' : 'hidden' }}">
                    <label class="block font-semibold mb-2 text-gray-700">Specify Other Department(s)</label>
                    <div id="other_department_container" class="space-y-2">
                        <input type="text" name="other_department[]"
                               class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Enter department name">
                    </div>
                    <button type="button" id="add_other_department"
                            class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                        Add More
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Protection Officer -->
        <div>
            <label for="data_protection_officer" class="block font-semibold mb-2 text-gray-700">Data Protection Officer</label>
            <input type="text" id="data_protection_officer" name="data_protection_officer"
                   value="{{ old('data_protection_officer', 'Tatenda Magetsi') }}"
                   class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                   placeholder="Enter Data Protection Officer name">
        </div>

        <!-- PROCESSING INFORMATION -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <div class="flex items-center gap-2 pb-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Processing Information</h2>
            </div>

            <!-- Process Name -->
            <div>
                <label for="processes" class="block font-semibold mb-2 text-gray-700">Process Name</label>
                <input type="text" id="processes" name="processes[]"
                       class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="e.g. Data collection, analysis"
                       value="{{ old('processes.0') }}">
            </div>

            <!-- Outsourced Processing -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="outsourced_processing" class="block font-semibold mb-2 text-gray-700">Outsourced Processing?</label>
                    <select id="outsourced_processing" name="outsourced_processing"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                        <option value="">-- Select --</option>
                        <option value="1" {{ old('outsourced_processing') === '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('outsourced_processing') === '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div id="outsourced_processing_description_wrapper" class="hidden">
                    <label for="outsourced_processing_description" class="block font-semibold mb-2 text-gray-700">Brief Description (if Yes)</label>
                    <input type="text" id="outsourced_processing_description" name="outsourced_processing_description"
                           value="{{ old('outsourced_processing_description') }}"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                           placeholder="e.g. Data entry outsourced to vendor X for digitization"/>
                </div>
            </div>

            <!-- Description of Processing Activity -->
            <div>
                <label for="processing_activity_description" class="block font-semibold mb-2 text-gray-700">Description of Processing Activity</label>
                <textarea id="processing_activity_description" name="processing_activity_description" rows="4"
                          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                          placeholder="Briefly describe what this processing activity does, why it exists, and how data is handled.">{{ old('processing_activity_description') }}</textarea>
            </div>

            <!-- Data Sources -->
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Data Sources</label>
                <div class="flex flex-wrap gap-4">
                    @foreach(['Employees','Participants','Other','I do not know'] as $source)
                        <label class="inline-flex items-center gap-2 cursor-pointer hover:text-orange-600 transition-colors">
                            <input type="checkbox" name="data_sources[]" value="{{ $source }}"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   {{ (is_array(old('data_sources')) && in_array($source, old('data_sources'))) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $source }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="data_sources_other_container" class="space-y-2 mt-3 hidden">
                    <input type="text" name="data_sources_other[]"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           placeholder="Specify other data source">
                </div>
                <button type="button" id="add_data_sources_other"
                        class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium hidden">
                    Add More
                </button>
            </div>

            <!-- Data Formats -->
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Data Formats</label>
                <div class="flex flex-wrap gap-4">
                    @foreach(['CSV','JSON','XML','PDF','DOCX','EXCEL','Other','I do not know'] as $format)
                        <label class="inline-flex items-center gap-2 cursor-pointer hover:text-orange-600 transition-colors">
                            <input type="checkbox" name="data_formats[]" value="{{ $format }}"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   {{ (is_array(old('data_formats')) && in_array($format, old('data_formats'))) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $format }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="data_formats_other_container" class="space-y-2 mt-3 hidden">
                    <input type="text" name="data_formats_other[]"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           placeholder="Specify other data format">
                </div>
                <button type="button" id="add_data_formats_other"
                        class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium hidden">
                    Add More
                </button>
            </div>

            <!-- Personal Data Categories -->
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Personal Data Categories</label>
                <div class="flex flex-wrap gap-4">
                    @foreach(['Name','Address','Email','Phone','ID Number','Financial','Health','Other','I do not know'] as $category)
                        <label class="inline-flex items-center gap-2 cursor-pointer hover:text-orange-600 transition-colors">
                            <input type="checkbox" name="personal_data_categories[]" value="{{ $category }}"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   {{ (is_array(old('personal_data_categories')) && in_array($category, old('personal_data_categories'))) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $category }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="personal_data_categories_other_container" class="space-y-2 mt-3 hidden">
                    <input type="text" name="personal_data_categories_other[]"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           placeholder="Specify other category">
                </div>
                <button type="button" id="add_personal_data_categories_other"
                        class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium hidden">
                    Add More
                </button>
            </div>

            <!-- Data Volume -->
            <div>
                <label for="data_volume_0" class="block font-semibold mb-2 text-gray-700">Data Volume</label>
                <select name="data_volume[]" id="data_volume_0"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                    <option value="">-- Select Data Volume --</option>
                    <option value="1-100"   {{ (old('data_volume')[0] ?? '') == '1-100'   ? 'selected' : '' }}>1-100 (Low)</option>
                    <option value="101-200" {{ (old('data_volume')[0] ?? '') == '101-200' ? 'selected' : '' }}>101-200 (Medium)</option>
                    <option value="201-500" {{ (old('data_volume')[0] ?? '') == '201-500' ? 'selected' : '' }}>201-500 (High)</option>
                    <option value="500+"    {{ (old('data_volume')[0] ?? '') == '500+'    ? 'selected' : '' }}>500+ (Very High)</option>
                </select>
            </div>

            <!-- Retention Period -->
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Retention Period (Years)</label>
                <input type="text" name="retention_period_years[]"
                       value="{{ old('retention_period_years.0') }}"
                       class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="Enter retention period in years">
            </div>

            <!-- Retention Rationale -->
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Retention Rationale</label>
                <input type="text" name="retention_rationale[]"
                       value="{{ old('retention_rationale.0') }}"
                       class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="Enter retention rationale">
            </div>
        </div>

        <!-- INFORMATION SHARING -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <div class="flex items-center gap-2 pb-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Information Sharing</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label for="information_shared" class="block font-semibold mb-2 text-gray-700">Information Shared?</label>
                    <select name="information_shared" id="information_shared"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                        <option value="">-- Select --</option>
                        <option value="1" {{ old('information_shared')=='1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('information_shared')=='0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2 text-gray-700">Sharing Type</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer hover:text-orange-600 transition-colors">
                            <input type="checkbox" name="sharing_type[]" value="local"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   {{ (is_array(old('sharing_type')) && in_array('local', old('sharing_type'))) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Local Sharing</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer hover:text-orange-600 transition-colors">
                            <input type="checkbox" name="sharing_type[]" value="transborder"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   {{ (is_array(old('sharing_type')) && in_array('transborder', old('sharing_type'))) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Transborder Sharing</span>
                        </label>
                    </div>
                </div>
            </div>

            <div id="sharing_details" class="mt-6 space-y-4 hidden">
                <div id="local_sharing_container" class="hidden">
                    <label class="block font-semibold mb-2 text-gray-700">Local Organisations</label>
                    <div id="local_fields_container" class="space-y-2">
                        <input type="text" name="local_organizations[]"
                               class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Enter local organisation">
                    </div>
                    <button type="button" id="add_local_field"
                            class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                        Add More
                    </button>
                </div>

                <div id="transborder_sharing_container" class="hidden">
                    <label class="block font-semibold mb-2 text-gray-700">Transborder Countries</label>
                    <div id="transborder_fields_container" class="space-y-2">
                        <input type="text" name="transborder_countries[]"
                               class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Enter country">
                    </div>
                    <button type="button" id="add_transborder_field"
                            class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                        Add More
                    </button>
                </div>

                <div>
                    <label for="sharing_comment" class="block font-semibold mb-2 text-gray-700">Sharing Comment</label>
                    <textarea name="sharing_comment" id="sharing_comment" rows="4"
                              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('sharing_comment') }}</textarea>
                </div>
            </div>
        </div>

        <!-- ACCESS CONTROL -->
        <div class="space-y-4 pt-4 border-t border-gray-200">
            <div class="flex items-center gap-2 pb-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <label for="access_control_select" class="block font-semibold text-gray-700">Access Control Implemented</label>
            </div>
            <select name="access_control" id="access_control_select"
                    class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                <option value="">-- Select --</option>
                <option value="1" {{ old('access_control') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('access_control') == '0' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <!-- ACCESS & SECURITY MEASURES -->
        <div id="access_security_measures_section" class="space-y-6 pt-4 border-t border-gray-200 hidden">
            <div class="flex items-center gap-2 pb-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Access & Security Measures</h2>
            </div>

            <!-- Technical Measures -->
            <div>
                <label class="block font-semibold mb-3 text-gray-700">Technical Measures</label>
                <div class="flex flex-wrap gap-4">
                    @foreach([
                        'Encryption at rest & in transit','RBAC','MFA','Automatic audit logs',
                        'Segmented network architecture','Firewalls and VPN-restricted admin access',
                        'Regular vulnerability scanning and patching','High-availability and fail-safe mechanisms','Other','I do not know'
                    ] as $tech)
                        <label class="inline-flex items-center gap-2 cursor-pointer hover:text-orange-600 transition-colors">
                            <input type="checkbox" name="technical_measures[]" value="{{ $tech }}"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   {{ (is_array(old('technical_measures')) && in_array($tech, old('technical_measures'))) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $tech }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="technical_measures_other_container" class="space-y-2 mt-3 hidden">
                    <input type="text" name="technical_measures_other[]"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           placeholder="Specify other technical measure">
                </div>
                <button type="button" id="add_technical_measures_other"
                        class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium hidden">
                    Add More
                </button>
            </div>

            <!-- Organisational Measures -->
            <div class="mt-6">
                <label class="block font-semibold mb-3 text-gray-700">Organisational Measures</label>
                <div class="flex flex-wrap gap-4">
                    @foreach([
                        'Biometric privacy notice','Internal privacy data policy','Administrator access governance','Conduct DPIAs',
                        'Legitimate Interest Assessment (LIA)','Retention and disposal schedule','Incident response and breach reporting procedures',
                        'Employee onboarding and privacy training','Vendor due diligence and contractual safeguards','Periodic internal audits','Other','I do not know'
                    ] as $org)
                        <label class="inline-flex items-center gap-2 cursor-pointer hover:text-orange-600 transition-colors">
                            <input type="checkbox" name="organisational_measures[]" value="{{ $org }}"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   {{ (is_array(old('organisational_measures')) && in_array($org, old('organisational_measures'))) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $org }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="organisational_measures_other_container" class="space-y-2 mt-3 hidden">
                    <input type="text" name="organisational_measures_other[]"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           placeholder="Specify other organisational measure">
                </div>
                <button type="button" id="add_organisational_measures_other"
                        class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium hidden">
                    Add More
                </button>
            </div>
        </div>

        <!-- LAWFUL BASIS & RISK -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <div class="flex items-center gap-2 pb-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Lawful Basis & Risk</h2>
            </div>

            <!-- Lawful Basis -->
            <div class="mb-6">
                <label class="block font-semibold mb-3 text-gray-700">Lawful Basis</label>
                <div class="flex flex-wrap gap-4">
                    @foreach([
                        'Consent','Public Interest','Legitimate Interest','Contractual Obligation',
                        'Legal Obligation','Vital Interest','Scientific Research','Other','I do not know'
                    ] as $basis)
                        <label class="inline-flex items-center gap-2 cursor-pointer hover:text-orange-600 transition-colors">
                            <input type="checkbox" name="lawful_basis[]" value="{{ $basis }}"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-2 focus:ring-orange-500"
                                   {{ (is_array(old('lawful_basis')) && in_array($basis, old('lawful_basis'))) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $basis }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="lawful_basis_other_container" class="space-y-2 mt-3 hidden">
                    <input type="text" name="lawful_basis_other[]"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           placeholder="Specify other lawful basis">
                </div>
                <button type="button" id="add_lawful_basis_other"
                        class="mt-2 text-white bg-orange-600 px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium hidden">
                    Add More
                </button>
            </div>

            <!-- Risk Report -->
            <div>
                <label for="risk_report" class="block font-semibold mb-2 text-gray-700">Risk Report</label>
                <textarea name="risk_report" id="risk_report" rows="5"
                          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                          placeholder="Describe any identified risks, mitigation measures, and residual risk">{{ old('risk_report') }}</textarea>
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="flex justify-end gap-4 pt-4">
            <button type="reset"
                    class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                Reset Form
            </button>
            <button type="submit"
                    class="bg-orange-600 text-white px-8 py-3 rounded-lg hover:bg-orange-700 transition-colors font-semibold shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Submit ROPA
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Auto-dismiss alerts after 8s
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            alert.style.opacity = 0;
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }, 8000);
    });

    // Outsourced processing toggle
    const outsourcedSelect = document.getElementById('outsourced_processing');
    const outsourcedWrapper = document.getElementById('outsourced_processing_description_wrapper');
    function toggleOutsourced() {
        outsourcedWrapper.classList.toggle('hidden', outsourcedSelect.value !== '1');
        if (outsourcedSelect.value !== '1') {
            document.getElementById('outsourced_processing_description').value = '';
        }
    }
    if (outsourcedSelect) { toggleOutsourced(); outsourcedSelect.addEventListener('change', toggleOutsourced); }

    // Access control toggle
    const accessControlSelect = document.getElementById('access_control_select');
    const accessSecuritySection = document.getElementById('access_security_measures_section');
    function toggleAccessSecurity() {
        accessSecuritySection.classList.toggle('hidden', accessControlSelect.value !== '1');
    }
    if (accessControlSelect) { toggleAccessSecurity(); accessControlSelect.addEventListener('change', toggleAccessSecurity); }

});
</script>

<style>
@media print {
    .no-print, button { display: none !important; }
    body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
    .shadow-lg, .shadow-md { box-shadow: none !important; }
}
</style>

@include('ropajs.ropa-form-scripts')

@endsection