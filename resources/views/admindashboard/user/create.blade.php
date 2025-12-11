@extends('layouts.admin')

@section('title', 'Admin | Create User')

@section('content')
<div class="flex justify-center items-start mt-8">
    <!-- Landscape form container -->
    <div class="bg-white shadow-lg rounded-lg p-4 w-full max-w-4xl">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-orange-700 flex items-center">
                <i data-feather="user-plus" class="w-5 h-5 mr-2"></i> Add User
            </h2>
            <a href="{{ route('admin.users.index') }}" class="flex items-center text-gray-600 hover:text-indigo-700 text-sm">
                <i data-feather="arrow-left" class="w-4 h-4 mr-1"></i> Back
            </a>
        </div>

        <!-- Success / Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-2 rounded mb-3 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-2 gap-4 items-start">
            @csrf

            <!-- Full Name -->
            <div class="flex flex-col">
                <label for="name" class="text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Enter full name"
                       class="w-full px-2 py-1 border rounded focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
            </div>

            <!-- Email -->
            <div class="flex flex-col">
                <label for="email" class="text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter email"
                       class="w-full px-2 py-1 border rounded focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
            </div>

           <!-- Department -->
<div class="flex flex-col">
    <label for="department" class="text-sm font-semibold text-gray-700 mb-1">
        Department
    </label>

    <select name="department" id="department"
            class="w-full px-2 py-1 border rounded focus:ring-2 focus:ring-indigo-500 focus:outline-none">

        <option value="">Select User Department </option>

        <option value="Data Protection" 
            {{ old('department') == 'Data Protection' ? 'selected' : '' }}>
            Data Protection
        </option>

        <option value="IT" 
            {{ old('department') == 'IT' ? 'selected' : '' }}>
            IT
        </option>

        <option value="HR" 
            {{ old('department') == 'HR' ? 'selected' : '' }}>
            HR
        </option>

        <option value="Community Engagement" 
            {{ old('department') == 'Community Engagement' ? 'selected' : '' }}>
            Community Engagement
        </option>

        <option value="Data & Biostatisitcs" 
            {{ old('department') == 'Data & Biostatisitcs' ? 'selected' : '' }}>
            Data & Biostatisitcs
        </option>

        <option value="Laboratory" 
            {{ old('department') == 'Laboratory' ? 'selected' : '' }}>
            Laboratory
        </option>

        <option value="Pharmacy" 
            {{ old('department') == 'Pharmacy' ? 'selected' : '' }}>
            Pharmacy
        </option>

        <option value="Finance & Administration" 
            {{ old('department') == 'Finance & Administration' ? 'selected' : '' }}>
            Finance & Administration
        </option>

        <option value="Clinical Operations (ClinOps)" 
            {{ old('department') == 'Clinical Operations (ClinOps)' ? 'selected' : '' }}>
            Clinical Operations (ClinOps)
        </option>

        <option value="Project Management" 
            {{ old('department') == 'Project Management' ? 'selected' : '' }}>
            Project Management
        </option>

        <option value="Legal & Compliance" 
            {{ old('department') == 'Legal & Compliance' ? 'selected' : '' }}>
            Legal & Compliance
        </option>

    </select>
</div>

            <!-- Job Title -->
            <div class="flex flex-col">
                <label for="job_title" class="text-sm font-semibold text-gray-700 mb-1">Job Title</label>
                <input type="text" name="job_title" id="job_title" value="{{ old('job_title') }}" placeholder="Optional"
                       class="w-full px-2 py-1 border rounded focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <!-- User Type -->
            <div class="flex flex-col">
                <label for="user_type" class="text-sm font-semibold text-gray-700 mb-1">User Type</label>
                <select name="user_type" id="user_type"
                        class="w-full px-2 py-1 border rounded focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                    <option value="">-- Select --</option>
                    <option value="1" {{ old('user_type') == 1 ? 'selected' : '' }}>Admin</option>
                    <option value="0" {{ old('user_type') == 0 ? 'selected' : '' }}>User</option>
                </select>
            </div>

            <!-- Password -->
            <div class="flex flex-col">
                <label for="password" class="text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter password"
                       class="w-full px-2 py-1 border rounded focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
            </div>

            <!-- Confirm Password -->
            <div class="flex flex-col">
                <label for="password_confirmation" class="text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-enter password"
                       class="w-full px-2 py-1 border rounded focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
            </div>

            <!-- Buttons (full width) -->
            <div class="col-span-2 flex justify-end pt-2">
                <button type="submit" class="flex items-center bg-orange-500 text-white px-4 py-1 rounded hover:bg-orange-700 text-sm">
                    <i data-feather="user-check" class="w-4 h-4 mr-1"></i> Add User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
