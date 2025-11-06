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
                <!-- Controller / Department -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Controller / Department</label>
                    <input type="text" name="controller_department" value="{{ old('controller_department') }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Processing Activity Name -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Processing Activity Name</label>
                    <input type="text" name="processing_activity_name" value="{{ old('processing_activity_name') }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Purpose of Processing -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Purpose of Processing</label>
                    <textarea name="purpose_of_processing" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('purpose_of_processing') }}</textarea>
                </div>

                <!-- Categories of Data Subjects -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Categories of Data Subjects</label>
                    <textarea name="categories_of_data_subjects" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('categories_of_data_subjects') }}</textarea>
                </div>

                <!-- Categories of Personal Data -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Categories of Personal Data</label>
                    <textarea name="categories_of_personal_data" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('categories_of_personal_data') }}</textarea>
                </div>

                <!-- Recipients of Data -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Recipients of Data</label>
                    <textarea name="recipients_of_data" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('recipients_of_data') }}</textarea>
                </div>

                <!-- Transfer to Third Countries -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Transfer to Third Countries</label>
                    <input type="text" name="transfer_to_third_countries" value="{{ old('transfer_to_third_countries') }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Retention Period -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Retention Period</label>
                    <input type="text" name="retention_period" value="{{ old('retention_period') }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Security Measures -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Security Measures</label>
                    <textarea name="security_measures" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('security_measures') }}</textarea>
                </div>

                <!-- Data Protection Officer -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Data Protection Officer</label>
                    <input type="text" name="data_protection_officer" value="{{ old('data_protection_officer') }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Date of Entry -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Date of Entry</label>
                    <input type="date" name="date_of_entry" value="{{ old('date_of_entry') }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
            </div>

            <!-- Actions -->
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
</script>
@endsection
