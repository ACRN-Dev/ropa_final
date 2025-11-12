@extends('layouts.app')

@section('title', 'Edit ROPA Record')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-indigo-700 flex items-center">
        <i data-feather="edit" class="w-6 h-6 mr-2"></i> Edit Record of Processing Activity
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
        <form method="POST" action="{{ route('ropa.update', $ropa->id) }}">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Organisation -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Organisation Name</label>
                    <input type="text" name="organisation_name" value="{{ old('organisation_name', $ropa->organisation_name) }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Department -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Department Name</label>
                    <input type="text" name="department_name" value="{{ old('department_name', $ropa->department_name) }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Other Department -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Other Department</label>
                    <input type="text" name="other_department" value="{{ old('other_department', $ropa->other_department) }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Status -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Status</label>
                    <input type="text" name="status" value="{{ old('status', $ropa->status) }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Date Submitted -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Date Submitted</label>
                    <input type="date" name="date_submitted" value="{{ old('date_submitted', $ropa->date_submitted ? $ropa->date_submitted->format('Y-m-d') : '') }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Information Shared -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="information_shared" value="1" {{ old('information_shared', $ropa->information_shared) ? 'checked' : '' }} />
                    <label class="font-semibold text-gray-700">Information Shared</label>
                </div>

                <!-- Information Nature -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Information Nature</label>
                    <textarea name="information_nature" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('information_nature', $ropa->information_nature) }}</textarea>
                </div>

                <!-- Outsourced Processing -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="outsourced_processing" value="1" {{ old('outsourced_processing', $ropa->outsourced_processing) ? 'checked' : '' }} />
                    <label class="font-semibold text-gray-700">Outsourced Processing</label>
                </div>

                <!-- Processor -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Processor</label>
                    <input type="text" name="processor" value="{{ old('processor', $ropa->processor) }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Transborder Processing -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="transborder_processing" value="1" {{ old('transborder_processing', $ropa->transborder_processing) ? 'checked' : '' }} />
                    <label class="font-semibold text-gray-700">Transborder Processing</label>
                </div>

                <!-- Country -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', $ropa->country) }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Lawful Basis -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Lawful Basis</label>
                    <select name="lawful_basis[]" multiple
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($lawfulOptions as $option)
                            <option value="{{ $option }}" {{ in_array($option, old('lawful_basis', $ropa->lawful_basis ?? [])) ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Retention Period -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Retention Period (Years)</label>
                    <input type="number" name="retention_period_years" value="{{ old('retention_period_years', $ropa->retention_period_years) }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Retention Rationale -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Retention Rationale</label>
                    <textarea name="retention_rationale" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('retention_rationale', $ropa->retention_rationale) }}</textarea>
                </div>

                <!-- Users Count -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Users Count</label>
                    <input type="number" name="users_count" value="{{ old('users_count', $ropa->users_count) }}"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Access Control -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="access_control" value="1" {{ old('access_control', $ropa->access_control) ? 'checked' : '' }} />
                    <label class="font-semibold text-gray-700">Access Control</label>
                </div>

                <!-- Personal Data Category -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Categories of Personal Data</label>
                    <textarea name="personal_data_category[]" rows="3"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('personal_data_category') ? implode(', ', old('personal_data_category')) : implode(', ', $ropa->personal_data_category ?? []) }}</textarea>
                    <p class="text-gray-500 text-sm mt-1">Enter multiple categories separated by commas.</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-2">
                <a href="{{ route('ropa.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 flex items-center">
                    <i data-feather="arrow-left" class="w-4 h-4 mr-1"></i> Cancel
                </a>

                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 flex items-center">
                    <i data-feather="save" class="w-4 h-4 mr-1"></i> Update Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
