@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="container mx-auto p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-indigo-700 flex items-center">
            <i data-feather="user-plus" class="w-6 h-6 mr-2"></i> Create User
        </h2>
        <a href="{{ route('admin.users.index') }}" class="flex items-center text-gray-600 hover:text-indigo-700">
            <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Back to Users
        </a>
    </div>

    <!-- Success / Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Create Form -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    placeholder="Enter full name"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" 
                    required
                >
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    placeholder="Enter email address"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" 
                    required
                >
            </div>

            <!-- Department -->
            <div>
                <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                <input 
                    type="text" 
                    name="department" 
                    id="department" 
                    value="{{ old('department') }}" 
                    placeholder="Enter department (optional)"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                >
            </div>

            <!-- Job Title -->
            <div>
                <label for="job_title" class="block text-sm font-semibold text-gray-700 mb-2">Job Title</label>
                <input 
                    type="text" 
                    name="job_title" 
                    id="job_title" 
                    value="{{ old('job_title') }}" 
                    placeholder="Enter job title (optional)"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                >
            </div>

            <!-- User Type -->
            <div>
                <label for="user_type" class="block text-sm font-semibold text-gray-700 mb-2">User Type</label>
                <select 
                    name="user_type" 
                    id="user_type" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" 
                    required
                >
                    <option value="">-- Select User Type --</option>
                    <option value="1" {{ old('user_type') == 1 ? 'selected' : '' }}>Admin</option>
                    <option value="0" {{ old('user_type') == 0 ? 'selected' : '' }}>User</option>
                </select>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    placeholder="Enter password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" 
                    required
                >
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    placeholder="Re-enter password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" 
                    required
                >
            </div>

            <!-- Buttons -->
            <div class="flex justify-end items-center pt-4">
                <button 
                    type="submit" 
                    class="flex items-center bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition"
                >
                    <i data-feather="user-check" class="w-4 h-4 mr-2"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
