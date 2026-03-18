@extends('layouts.admin')

@section('title', 'Admin | Edit User')

@section('content')

<!-- ── PAGE HEADER ── -->
<div class="hidden md:flex items-center justify-between mb-5 lg:mb-6">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">Edit User</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update account details, role, department and password.</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
              text-gray-700 dark:text-gray-300 hover:bg-gray-50 font-semibold rounded-lg transition text-sm shadow-sm">
        <i data-feather="arrow-left" class="w-4 h-4"></i>
        Back to Users
    </a>
</div>

<!-- Mobile header -->
<div class="md:hidden mb-4 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Edit User</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Update account details.</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
              text-gray-600 dark:text-gray-300 font-semibold rounded-lg transition text-xs">
        <i data-feather="arrow-left" class="w-3.5 h-3.5"></i>
        Back
    </a>
</div>

<!-- ── FLASH MESSAGES ── -->
@if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-5 text-sm">
        <i data-feather="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-5 text-sm">
        <i data-feather="alert-circle" class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5"></i>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- ── USER IDENTITY BANNER ── -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-4 lg:px-6 py-4 mb-4 flex items-center gap-4 shadow-sm">
    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-lg font-bold">
        {{ strtoupper(substr($user->name, 0, 2)) }}
    </div>
    <div class="min-w-0">
        <p class="font-bold text-gray-900 dark:text-gray-100 text-base">{{ $user->name }}</p>
        <p class="text-xs text-gray-400">{{ $user->email }} &middot; Member since {{ $user->created_at->format('M Y') }}</p>
    </div>
    <div class="ml-auto flex-shrink-0">
        @if($user->isActive())
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Active
            </span>
        @else
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Deactivated
            </span>
        @endif
    </div>
</div>

<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
@csrf
@method('PUT')

    <!-- ── SECTION 1: Account Details ── -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 lg:px-6 py-3">
            <h2 class="text-sm lg:text-base font-bold text-white flex items-center gap-2">
                <i data-feather="user" class="w-4 h-4 flex-shrink-0"></i>
                Account Details
            </h2>
        </div>

        <div class="p-4 lg:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">

            <!-- Full Name -->
            <div class="flex flex-col gap-1.5">
                <label for="name" class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $user->name) }}"
                       placeholder="e.g. Jane Doe"
                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                              focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                              dark:bg-gray-700 dark:text-gray-100 bg-white
                              @error('name') border-red-400 bg-red-50 @enderror"
                       required>
                @error('name')
                    <p class="text-xs text-red-500 flex items-center gap-1"><i data-feather="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $user->email) }}"
                       placeholder="e.g. jane@example.com"
                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                              focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                              dark:bg-gray-700 dark:text-gray-100 bg-white
                              @error('email') border-red-400 bg-red-50 @enderror"
                       required>
                @error('email')
                    <p class="text-xs text-red-500 flex items-center gap-1"><i data-feather="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Department -->
            <div class="flex flex-col gap-1.5">
                <label for="department" class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                    Department
                </label>
                <select name="department" id="department"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                               focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                               dark:bg-gray-700 dark:text-gray-100 bg-white
                               @error('department') border-red-400 bg-red-50 @enderror">
                    <option value="">— Select Department —</option>
                    @foreach([
                        'Data Protection',
                        'IT',
                        'HR',
                        'Community Engagement',
                        'Data & Biostatisitcs',
                        'Laboratory',
                        'Pharmacy',
                        'Finance & Administration',
                        'Clinical Operations (ClinOps)',
                        'Project Management',
                        'Legal & Compliance',
                    ] as $dept)
                        <option value="{{ $dept }}" {{ old('department', $user->department) == $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
                @error('department')
                    <p class="text-xs text-red-500 flex items-center gap-1"><i data-feather="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Job Title -->
            <div class="flex flex-col gap-1.5">
                <label for="job_title" class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                    Job Title <span class="text-gray-400 font-normal normal-case">(optional)</span>
                </label>
                <input type="text" name="job_title" id="job_title"
                       value="{{ old('job_title', $user->job_title) }}"
                       placeholder="e.g. Data Protection Officer"
                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                              focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                              dark:bg-gray-700 dark:text-gray-100 bg-white">
            </div>

            <!-- User Type -->
            <div class="flex flex-col gap-1.5">
                <label for="user_type" class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                    User Type <span class="text-red-500">*</span>
                </label>
                <select name="user_type" id="user_type"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                               focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                               dark:bg-gray-700 dark:text-gray-100 bg-white
                               @error('user_type') border-red-400 bg-red-50 @enderror"
                        required>
                    <option value="0" {{ old('user_type', $user->user_type) == 0 ? 'selected' : '' }}>User</option>
                    <option value="1" {{ old('user_type', $user->user_type) == 1 ? 'selected' : '' }}>Admin</option>
                </select>
                @error('user_type')
                    <p class="text-xs text-red-500 flex items-center gap-1"><i data-feather="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Account Status -->
            <div class="flex flex-col gap-1.5">
                <label for="active" class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                    Account Status <span class="text-red-500">*</span>
                </label>
                <select name="active" id="active"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                               focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                               dark:bg-gray-700 dark:text-gray-100 bg-white"
                        required>
                    <option value="1" {{ old('active', $user->active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('active', $user->active) == 0 ? 'selected' : '' }}>Deactivated</option>
                </select>
            </div>

        </div>
    </div>

    <!-- ── SECTION 2: Change Password ── -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">

        <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-4 lg:px-6 py-3 flex items-center justify-between">
            <h2 class="text-sm lg:text-base font-bold text-white flex items-center gap-2">
                <i data-feather="lock" class="w-4 h-4 flex-shrink-0"></i>
                Change Password
            </h2>
            <span class="text-xs text-gray-300 font-normal">Leave blank to keep current password</span>
        </div>

        <div class="p-4 lg:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">

            <!-- New Password -->
            <div class="flex flex-col gap-1.5">
                <label for="password" class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                    New Password
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                           placeholder="Enter new password"
                           class="w-full px-3 py-2 pr-10 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                  focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                  dark:bg-gray-700 dark:text-gray-100 bg-white
                                  @error('password') border-red-400 bg-red-50 @enderror">
                    <button type="button" onclick="togglePassword('password', 'eye-password')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i data-feather="eye" id="eye-password" class="w-4 h-4"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 flex items-center gap-1"><i data-feather="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm New Password -->
            <div class="flex flex-col gap-1.5">
                <label for="password_confirmation" class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                    Confirm New Password
                </label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Re-enter new password"
                           class="w-full px-3 py-2 pr-10 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                  focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                  dark:bg-gray-700 dark:text-gray-100 bg-white">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i data-feather="eye" id="eye-confirm" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ── FORM ACTIONS ── -->
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 hover:bg-gray-50 font-semibold rounded-lg transition text-sm">
            <i data-feather="x" class="w-4 h-4"></i>
            Cancel
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2 bg-orange-500 hover:bg-orange-600
                       text-white font-semibold rounded-lg transition text-sm shadow-sm">
            <i data-feather="save" class="w-4 h-4"></i>
            Save Changes
        </button>
    </div>

</form>

<script>
    feather.replace();

    function togglePassword(fieldId, iconId) {
        var field = document.getElementById(fieldId);
        var icon  = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.setAttribute('data-feather', 'eye-off');
        } else {
            field.type = 'password';
            icon.setAttribute('data-feather', 'eye');
        }
        feather.replace();
    }
</script>

@endsection