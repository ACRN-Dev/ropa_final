@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container mx-auto p-6">
    <!-- Page Header -->
    <h2 class="text-2xl font-bold mb-6 text-orange-500 flex items-center">
        <i data-feather="edit" class="w-6 h-6 mr-2 text-orange-500"></i> Edit User
    </h2>

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

    <!-- Edit Form -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $user->name) }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none"
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
                    value="{{ old('email', $user->email) }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none"
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
                    value="{{ old('department', $user->department) }}" 
                    placeholder="Enter department (optional)"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none"
                >
            </div>

            <!-- Job Title -->
            <div>
                <label for="job_title" class="block text-sm font-semibold text-gray-700 mb-2">Job Title</label>
                <input 
                    type="text" 
                    name="job_title" 
                    id="job_title" 
                    value="{{ old('job_title', $user->job_title) }}" 
                    placeholder="Enter job title (optional)"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none"
                >
            </div>

            <!-- User Type -->
            <div>
                <label for="user_type" class="block text-sm font-semibold text-gray-700 mb-2">User Type</label>
                <select 
                    name="user_type" 
                    id="user_type" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none"
                    required
                >
                    <option value="0" {{ old('user_type', $user->user_type) == 0 ? 'selected' : '' }}>User</option>
                    <option value="1" {{ old('user_type', $user->user_type) == 1 ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <!-- Active Status -->
            <div>
                <label for="active" class="block text-sm font-semibold text-gray-700 mb-2">Account Status</label>
                <select 
                    name="active" 
                    id="active" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none"
                    required
                >
                    <option value="1" {{ $user->active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$user->active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('admin.users.index') }}" class="flex items-center text-gray-600 hover:text-orange-500">
                    <i data-feather="arrow-left" class="w-4 h-4 mr-2 text-orange-500"></i> Back to Users
                </a>

                <button 
                    type="submit" 
                    class="flex items-center bg-orange-500 text-white px-5 py-2 rounded-lg hover:bg-orange-600 transition"
                >
                    <i data-feather="save" class="w-4 h-4 mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
