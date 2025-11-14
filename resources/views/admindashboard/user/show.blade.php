@extends('layouts.admin')

@section('title', 'View User')

@section('content')
<div class="container mx-auto p-6">
    <!-- Page Header -->
    <h2 class="text-2xl font-bold mb-6 text-orange-500 flex items-center">
        <i data-feather="eye" class="w-6 h-6 mr-2 text-orange-500"></i> View User
    </h2>

    <!-- User Details Card -->
    <div class="bg-white shadow-lg rounded-lg p-6 space-y-4">
        <!-- Name -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Full Name</h3>
            <p class="text-gray-800">{{ $user->name }}</p>
        </div>

        <!-- Email -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Email Address</h3>
            <p class="text-gray-800">{{ $user->email }}</p>
        </div>

        <!-- Department -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Department</h3>
            <p class="text-gray-800">{{ $user->department ?? '—' }}</p>
        </div>

        <!-- Job Title -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Job Title</h3>
            <p class="text-gray-800">{{ $user->job_title ?? '—' }}</p>
        </div>

        <!-- User Type -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">User Type</h3>
            @if($user->user_type == 1)
                <span class="text-orange-600 font-semibold">Admin</span>
            @else
                <span class="text-gray-700">User</span>
            @endif
        </div>

        <!-- Account Status -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Account Status</h3>
            @if($user->active)
                <span class="px-2 py-1 text-sm bg-green-100 text-green-800 rounded-full">Active</span>
            @else
                <span class="px-2 py-1 text-sm bg-red-100 text-red-800 rounded-full">Inactive</span>
            @endif
        </div>

        <!-- Created At -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Account Created</h3>
            <p class="text-gray-800">{{ $user->created_at->format('d M Y, H:i') }}</p>
        </div>

        <!-- Back Button -->
        <div class="pt-4">
            <a href="{{ route('admin.users.index') }}" class="flex items-center text-gray-600 hover:text-orange-500">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2 text-orange-500"></i> Back to Users
            </a>
        </div>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
