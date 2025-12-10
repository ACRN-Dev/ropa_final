@extends('layouts.admin')

@section('title', 'Admin | User Management')

@section('content')
<div class="mx-auto p-4 sm:p-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h2 class="text-2xl font-bold text-orange-500 flex items-center">
            <i data-feather="users" class="w-6 h-6 mr-2"></i> User Management
        </h2>

        <a href="{{ route('admin.users.create') }}"
           class="mt-3 sm:mt-0 inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            <i data-feather="user-plus" class="w-4 h-4 mr-2"></i> Create User
        </a>
    </div>

    <!-- Success / Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm sm:text-base">
            {{ session('error') }}
        </div>
    @endif

    @php
        // Load departments dynamically
        $departments = \App\Models\User::whereNotNull('department')
                        ->distinct()
                        ->pluck('department');

        // Get filtered users
        $users = \App\Models\User::filter(request()->only(['search','status','user_type','department']))
                    ->paginate(10);
    @endphp

    <!-- Filters -->
    <form method="GET" class="mb-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email..."
               class="flex-1 px-4 py-2 border rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-orange-500 focus:outline-none">

        <select name="status" class="px-4 py-2 border rounded-lg text-sm sm:text-base">
            <option value="">All Status</option>
            <option value="active" @selected(request('status')=='active')>Active</option>
            <option value="deactivated" @selected(request('status')=='deactivated')>Deactivated</option>
        </select>

        <select name="user_type" class="px-4 py-2 border rounded-lg text-sm sm:text-base">
            <option value="">All Types</option>
            <option value="1" @selected(request('user_type')=='1')>Admin</option>
            <option value="0" @selected(request('user_type')=='0')>User</option>
        </select>

        <select name="department" class="px-4 py-2 border rounded-lg text-sm sm:text-base">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept }}" @selected(request('department')==$dept)>{{ $dept }}</option>
            @endforeach
        </select>

        <button type="submit"
                class="flex items-center justify-center bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition text-sm sm:text-base">
            <i data-feather="search" class="w-4 h-4 mr-1"></i> Filter
        </button>
    </form>

    <!-- Users Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full table-auto border-collapse text-sm sm:text-base">
            <thead class="bg-orange-500 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Name</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Type</th>
                    <th class="py-3 px-4 text-left">Department</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-semibold">{{ $user->name }}</td>
                        <td class="py-3 px-4 truncate max-w-[150px]">{{ $user->email }}</td>
                        <td class="py-3 px-4">{{ $user->isAdmin() ? 'Admin' : 'User' }}</td>
                        <td class="py-3 px-4">{{ $user->department ?? '—' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs sm:text-sm rounded-full {{ $user->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->isActive() ? 'Active' : 'Deactivated' }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-3 px-4">
                            <div class="flex justify-center space-x-2 sm:space-x-3">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-orange-500 hover:text-orange-600" title="View">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>

                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-orange-400 hover:text-orange-500" title="Edit">
                                    <i data-feather="edit" class="w-4 h-4"></i>
                                </a>

                                <!-- Toggle Status -->
                                <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to {{ $user->isActive() ? 'deactivate' : 'activate' }} this account?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $user->isActive() ? 'text-gray-600' : 'text-green-600' }} hover:text-gray-900">
                                        <i data-feather="{{ $user->isActive() ? 'slash' : 'check' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>

                                <!-- 2FA Toggle -->
                                <form action="{{ route('2fa.toggle') }}" method="POST" onsubmit="return confirm('Toggle 2FA for this user?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="{{ $user->two_factor_enabled ? 'text-blue-600' : 'text-gray-500' }} hover:text-blue-800">
                                        <i data-feather="{{ $user->two_factor_enabled ? 'lock' : 'unlock' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-4 text-center text-gray-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
