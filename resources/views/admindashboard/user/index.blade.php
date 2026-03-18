@extends('layouts.admin')

@section('title', 'Admin | User Management')

@section('content')

@php
    $departments = \App\Models\User::whereNotNull('department')->distinct()->pluck('department');
    $users = \App\Models\User::filter(request()->only(['search','status','user_type','department']))->paginate(10);
    $totalUsers  = \App\Models\User::count();
    $adminUsers  = \App\Models\User::where('user_type', 1)->count();
    $allUsers    = \App\Models\User::all();
    $activeUsers = $allUsers->filter(fn($u) => $u->isActive())->count();
    $deactivated = $allUsers->filter(fn($u) => !$u->isActive())->count();
@endphp

<!-- ── PAGE HEADER ── -->
<div class="hidden md:flex items-center justify-between mb-5 lg:mb-6">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">User Management</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage accounts, roles, departments and access control.</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition text-sm shadow-sm">
        <i data-feather="user-plus" class="w-4 h-4"></i>
        Create User
    </a>
</div>

<!-- Mobile header -->
<div class="md:hidden mb-4 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">User Management</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage accounts and access.</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition text-xs">
        <i data-feather="user-plus" class="w-3.5 h-3.5"></i>
        New
    </a>
</div>

<!-- ── FLASH MESSAGES ── -->
@if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-5 text-sm">
        <i data-feather="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0"></i>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-5 text-sm">
        <i data-feather="alert-circle" class="w-4 h-4 text-red-600 flex-shrink-0"></i>
        {{ session('error') }}
    </div>
@endif

<!-- ── STAT CARDS ── -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 lg:gap-6 mb-6 lg:mb-8">
    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-orange-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Total Users</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $totalUsers }}</p>
        </div>
        <i data-feather="users" class="w-7 h-7 lg:w-8 lg:h-8 text-orange-500 flex-shrink-0"></i>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-green-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Active</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $activeUsers }}</p>
        </div>
        <i data-feather="user-check" class="w-7 h-7 lg:w-8 lg:h-8 text-green-500 flex-shrink-0"></i>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-blue-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Admins</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $adminUsers }}</p>
        </div>
        <i data-feather="shield" class="w-7 h-7 lg:w-8 lg:h-8 text-blue-500 flex-shrink-0"></i>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-xl border-l-4 border-red-500 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Deactivated</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $deactivated }}</p>
        </div>
        <i data-feather="user-x" class="w-7 h-7 lg:w-8 lg:h-8 text-red-500 flex-shrink-0"></i>
    </div>
</div>

<!-- ── MAIN TABLE CARD ── -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

    <!-- Top bar -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 lg:px-6 py-3 lg:py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="text-base lg:text-xl font-bold text-white flex items-center gap-2">
                <i data-feather="list" class="w-5 h-5 flex-shrink-0"></i>
                All Users
            </h2>
            <span class="text-orange-100 text-xs font-semibold">{{ $users->total() }} total</span>
        </div>
    </div>

    <!-- ── FILTER BAR ── -->
    <form method="GET" class="px-4 lg:px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
        <div class="flex flex-col sm:flex-row gap-2 flex-wrap">

            <!-- Search -->
            <div class="relative flex-1 min-w-[180px]">
                <i data-feather="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search name or email..."
                       class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                              focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                              dark:bg-gray-800 dark:text-gray-200 bg-white">
            </div>

            <!-- Status -->
            <select name="status"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                           focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                           dark:bg-gray-800 dark:text-gray-200 bg-white">
                <option value="">All Status</option>
                <option value="active"      @selected(request('status')=='active')>Active</option>
                <option value="deactivated" @selected(request('status')=='deactivated')>Deactivated</option>
            </select>

            <!-- Type -->
            <select name="user_type"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                           focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                           dark:bg-gray-800 dark:text-gray-200 bg-white">
                <option value="">All Types</option>
                <option value="1" @selected(request('user_type')=='1')>Admin</option>
                <option value="0" @selected(request('user_type')=='0')>User</option>
            </select>

            <!-- Department -->
            <select name="department"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                           focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                           dark:bg-gray-800 dark:text-gray-200 bg-white">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" @selected(request('department')==$dept)>{{ $dept }}</option>
                @endforeach
            </select>

            <!-- Submit -->
            <button type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-orange-500 hover:bg-orange-600
                           text-white font-semibold text-sm rounded-lg transition whitespace-nowrap">
                <i data-feather="filter" class="w-3.5 h-3.5"></i>
                Filter
            </button>

            <!-- Clear (only if filters active) -->
            @if(request()->hasAny(['search','status','user_type','department']))
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800
                          border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300
                          hover:bg-gray-100 font-semibold text-sm rounded-lg transition whitespace-nowrap">
                    <i data-feather="x" class="w-3.5 h-3.5"></i>
                    Clear
                </a>
            @endif
        </div>
    </form>

    <!-- ── DESKTOP TABLE ── -->
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">User</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">Department</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">2FA</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors">

                        <!-- User -->
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $user->name }}</span>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
                        </td>

                        <!-- Type -->
                        <td class="px-4 py-3">
                            @if($user->isAdmin())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                    <i data-feather="shield" class="w-3 h-3"></i> Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                    <i data-feather="user" class="w-3 h-3"></i> User
                                </span>
                            @endif
                        </td>

                        <!-- Department -->
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="text-xs text-gray-600 dark:text-gray-300">{{ $user->department ?? '—' }}</span>
                        </td>

                        <!-- 2FA -->
                        <td class="px-4 py-3 hidden lg:table-cell">
                            @if($user->two_factor_enabled)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200">
                                    <i data-feather="lock" class="w-3 h-3"></i> On
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-200">
                                    <i data-feather="unlock" class="w-3 h-3"></i> Off
                                </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-3">
                            @if($user->isActive())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Deactivated
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-3" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-1">

                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="View">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>

                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="p-1.5 text-orange-500 hover:bg-orange-50 rounded-lg transition-all" title="Edit">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                </a>

                                <!-- Toggle Status -->
                                <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST"
                                      onsubmit="return confirm('{{ $user->isActive() ? 'Deactivate' : 'Activate' }} {{ addslashes($user->name) }}?')">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg transition-all {{ $user->isActive() ? 'text-gray-500 hover:bg-gray-100' : 'text-green-600 hover:bg-green-50' }}"
                                            title="{{ $user->isActive() ? 'Deactivate' : 'Activate' }}">
                                        <i data-feather="{{ $user->isActive() ? 'slash' : 'check-circle' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>

                                <!-- 2FA Toggle -->
                                <form action="{{ route('2fa.toggle') }}" method="POST"
                                      onsubmit="return confirm('Toggle 2FA for {{ addslashes($user->name) }}?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit"
                                            class="p-1.5 rounded-lg transition-all {{ $user->two_factor_enabled ? 'text-blue-600 hover:bg-blue-50' : 'text-gray-400 hover:bg-gray-100' }}"
                                            title="{{ $user->two_factor_enabled ? 'Disable 2FA' : 'Enable 2FA' }}">
                                        <i data-feather="{{ $user->two_factor_enabled ? 'lock' : 'unlock' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <i data-feather="users" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-sm font-semibold text-gray-400">No users found</p>
                            @if(request()->hasAny(['search','status','user_type','department']))
                                <a href="{{ route('admin.users.index') }}" class="text-xs text-orange-500 hover:underline mt-1 inline-block">Clear filters</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ── MOBILE CARD LIST ── -->
    <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($users as $user)
            <div class="p-4 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors">
                <div class="flex items-start justify-between gap-2 mb-2.5">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-sm text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                    @if($user->isActive())
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200 flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200 flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Deactivated
                        </span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-1.5 mb-3">
                    @if($user->isAdmin())
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                            <i data-feather="shield" class="w-3 h-3"></i> Admin
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                            <i data-feather="user" class="w-3 h-3"></i> User
                        </span>
                    @endif
                    @if($user->department)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-600 border border-orange-200">
                            <i data-feather="briefcase" class="w-3 h-3"></i> {{ $user->department }}
                        </span>
                    @endif
                    @if($user->two_factor_enabled)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-500 border border-blue-200">
                            <i data-feather="lock" class="w-3 h-3"></i> 2FA
                        </span>
                    @endif
                </div>

                <div class="flex gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('admin.users.show', $user->id) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all">
                        <i data-feather="eye" class="w-3.5 h-3.5"></i> View
                    </a>
                    <a href="{{ route('admin.users.edit', $user->id) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-semibold text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-lg transition-all">
                        <i data-feather="edit-2" class="w-3.5 h-3.5"></i> Edit
                    </a>
                    <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST"
                          onsubmit="return confirm('{{ $user->isActive() ? 'Deactivate' : 'Activate' }} {{ addslashes($user->name) }}?')" class="flex-1">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-semibold rounded-lg transition-all
                                       {{ $user->isActive() ? 'text-gray-600 bg-gray-100 hover:bg-gray-200' : 'text-green-600 bg-green-50 hover:bg-green-100' }}">
                            <i data-feather="{{ $user->isActive() ? 'slash' : 'check-circle' }}" class="w-3.5 h-3.5"></i>
                            {{ $user->isActive() ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-16 text-center">
                <i data-feather="users" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm font-semibold text-gray-400">No users found</p>
            </div>
        @endforelse
    </div>

    <!-- ── FOOTER / PAGINATION ── -->
    <div class="px-4 lg:px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Showing <span class="font-bold text-gray-800 dark:text-gray-200">{{ $users->firstItem() ?? 0 }}</span>–<span class="font-bold text-gray-800 dark:text-gray-200">{{ $users->lastItem() ?? 0 }}</span>
                of <span class="font-bold text-gray-800 dark:text-gray-200">{{ $users->total() }}</span> users
            </p>
            <div class="pagination-wrapper">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    /* Pagination styling to match orange theme */
    .pagination-wrapper nav span[aria-current="page"] span,
    .pagination-wrapper nav button.active {
        background-color: #f97316 !important;
        border-color: #f97316 !important;
        color: #fff !important;
    }
    .pagination-wrapper nav a:hover {
        background-color: #fff7ed !important;
        border-color: #f97316 !important;
        color: #ea580c !important;
    }
    * { box-sizing: border-box; }
</style>

<script>
    feather.replace();
</script>

@endsection