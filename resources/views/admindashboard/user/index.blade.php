@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="container mx-auto p-4 sm:p-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h2 class="text-2xl font-bold text-orange-500 flex items-center">
            <i data-feather="users" class="w-6 h-6 mr-2"></i> User Management
        </h2>

        <!-- Create User Button -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.users.create') }}"
               class="mt-3 sm:mt-0 inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                <i data-feather="user-plus" class="w-4 h-4 mr-2"></i> Create User
            </a>
        </div>
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

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
        <!-- Total Users -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center space-x-4 border-l-4 border-orange-500">
            <div class="p-2 sm:p-3 bg-orange-100 rounded-full">
                <i data-feather="users" class="w-5 h-5 sm:w-6 sm:h-6 text-orange-500"></i>
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-500 uppercase">Total Users</h3>
                <p class="text-lg sm:text-xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
            </div>
        </div>

        <!-- Total Admins -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center space-x-4 border-l-4 border-green-600">
            <div class="p-2 sm:p-3 bg-green-100 rounded-full">
                <i data-feather="shield" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600"></i>
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-500 uppercase">Total Admins</h3>
                <p class="text-lg sm:text-xl font-bold text-gray-800">{{ \App\Models\User::where('user_type', 1)->count() }}</p>
            </div>
        </div>

        <!-- Departments -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center space-x-4 border-l-4 border-yellow-500">
            <div class="p-2 sm:p-3 bg-yellow-100 rounded-full">
                <i data-feather="briefcase" class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-500"></i>
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-500 uppercase">Departments</h3>
                <p class="text-lg sm:text-xl font-bold text-gray-800">
                    {{ \App\Models\User::whereNotNull('department')->distinct('department')->count('department') }}
                </p>
            </div>
        </div>

        <!-- Active Accounts -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center space-x-4 border-l-4 border-red-500">
            <div class="p-2 sm:p-3 bg-red-100 rounded-full">
                <i data-feather="activity" class="w-5 h-5 sm:w-6 sm:h-6 text-red-500"></i>
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-500 uppercase">Active Accounts</h3>
                <p class="text-lg sm:text-xl font-bold text-gray-800">
                    {{ \App\Models\User::where('active', 1)->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search by name, email, or department..." 
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none text-sm sm:text-base"
        >
        <button 
            type="submit" 
            class="flex items-center justify-center bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition text-sm sm:text-base"
        >
            <i data-feather="search" class="w-4 h-4 mr-1"></i> Search
        </button>
    </form>

    <!-- Users Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full table-auto border-collapse text-sm sm:text-base">
            <thead class="bg-orange-500 text-white">
                <tr>
                    <th class="py-3 px-4 text-left whitespace-nowrap">Name</th>
                    <th class="py-3 px-4 text-left whitespace-nowrap">Email</th>
                    <th class="py-3 px-4 text-left">Type</th>
                    <th class="py-3 px-4 text-left">Department</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Created</th>
                    <th class="py-3 px-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-semibold">{{ $user->name }}</td>
                        <td class="py-3 px-4 truncate max-w-[150px]">{{ $user->email }}</td>
                        <td class="py-3 px-4">
                            <span class="{{ $user->user_type == 1 ? 'text-green-600 font-semibold' : 'text-gray-700' }}">
                                {{ $user->user_type == 1 ? 'Admin' : 'User' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">{{ $user->department ?? '—' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs sm:text-sm rounded-full {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->active ? 'Active' : 'Deactivated' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex justify-center space-x-2 sm:space-x-3">
                                <!-- View -->
                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-orange-500 hover:text-orange-600" title="View">
                                    <i data-feather="eye"></i>
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-orange-400 hover:text-orange-500" title="Edit">
                                    <i data-feather="edit"></i>
                                </a>

                                <!-- Toggle Status -->
                                <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to {{ $user->active ? 'deactivate' : 'activate' }} this account?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $user->active ? 'text-gray-600' : 'text-green-600' }} hover:text-gray-900" 
                                            title="{{ $user->active ? 'Deactivate' : 'Activate' }}">
                                        <i data-feather="{{ $user->active ? 'slash' : 'check' }}"></i>
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="#" method="POST" onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500">No users found.</td>
                    </tr>
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
