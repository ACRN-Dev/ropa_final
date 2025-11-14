@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-md mb-6">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('logo.jpg') }}" alt="Logo" class="h-10 w-10 rounded-full shadow">
                <h1 class="text-xl font-bold text-orange-500"></h1>
            </div>

            <!-- User Info & Logout -->
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-orange-500">
                    <i data-feather="home" class="w-5 h-5"></i>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700 flex items-center">
                        <i data-feather="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <!-- Page Header -->
        <h2 class="text-2xl font-bold mb-6 text-orange-500 flex items-center">
            <i data-feather="settings" class="w-6 h-6 mr-2"></i> Account Management 
        </h2>

        <!-- Global Success / Error Messages -->
        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('status') }}</div>
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

        <!-- Two-column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Profile Information -->
            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-orange-500">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <i data-feather="user" class="w-5 h-5 mr-2 text-orange-500"></i> Profile Information
                </h3>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-semibold text-gray-700 mb-1">Department</label>
                        <input type="text" id="department" name="department" value="{{ old('department', auth()->user()->department) }}"
                            placeholder="Optional"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none @error('department') border-red-500 @enderror">
                        @error('department')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Job Title -->
                    <div>
                        <label for="job_title" class="block text-sm font-semibold text-gray-700 mb-1">Job Title</label>
                        <input type="text" id="job_title" name="job_title" value="{{ old('job_title', auth()->user()->job_title) }}"
                            placeholder="Optional"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none @error('job_title') border-red-500 @enderror">
                        @error('job_title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label for="active" class="block text-sm font-semibold text-gray-700 mb-1">Account Status</label>
                        <input type="text" id="active" value="{{ auth()->user()->active ? 'Active' : 'Inactive' }}"
                            class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed" readonly>
                    </div>

                    <!-- Save Changes -->
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="flex items-center bg-orange-500 text-white px-5 py-2 rounded-lg hover:bg-orange-600 transition">
                            <i data-feather="save" class="w-4 h-4 mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Change Form -->
            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-orange-500">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <i data-feather="lock" class="w-5 h-5 mr-2 text-orange-500"></i> Change Password
                </h3>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-1">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none @error('current_password') border-red-500 @enderror" required>
                        @error('current_password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">New Password</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none @error('password') border-red-500 @enderror" required>
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none @error('password_confirmation') border-red-500 @enderror" required>
                        @error('password_confirmation')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Update Password -->
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="flex items-center bg-orange-500 text-white px-5 py-2 rounded-lg hover:bg-orange-600 transition">
                            <i data-feather="refresh-ccw" class="w-4 h-4 mr-2"></i> Update Password
                        </button>
                    </div>
                </form>

                <!-- Two-Factor Authentication (2FA) -->
                <div class="mt-6">
                    <span class="text-orange-700 font-semibold">Two-Factor Authentication (2FA)</span>
                    <form method="POST" action="{{ route('2fa.toggle') }}" class="mt-2 relative">
                        @csrf
                        @method('PATCH')

                        <label for="2fa-toggle" class="flex items-center cursor-pointer relative">
                            <input type="checkbox" id="2fa-toggle" name="two_factor_enabled" class="sr-only peer"
                                onchange="this.form.submit()" {{ auth()->user()->two_factor_enabled ? 'checked' : '' }}>
                            <div class="w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-orange-500 transition-colors duration-300"></div>
                            <div class="absolute left-0 top-0 w-6 h-6 bg-white rounded-full shadow transform transition-transform duration-300
                                        peer-checked:translate-x-6"></div>
                            <span class="ml-16 text-sm font-medium text-gray-900">
                                {{ auth()->user()->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </label>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
