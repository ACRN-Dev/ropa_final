@extends('layouts.app')

@section('title', 'My Submitted ROPA Records')

@section('content')
<div class="container mx-auto p-4 sm:p-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h2 class="text-2xl font-bold text-indigo-700 flex items-center">
            <i data-feather="file-text" class="w-6 h-6 mr-2"></i> My Submitted ROPA Records
        </h2>

        <!-- Create New ROPA Button -->
        <a href="{{ route('ropa.create') }}"
           class="mt-3 sm:mt-0 inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            <i data-feather="plus-circle" class="w-4 h-4 mr-2"></i> New ROPA
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <!-- Total ROPA -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center space-x-4 border-l-4 border-indigo-600">
            <div class="p-2 bg-indigo-100 rounded-full">
                <i data-feather="folder" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Records</h3>
                <p class="text-xl font-bold text-gray-800">
                    {{ \App\Models\Ropa::where('user_id', auth()->id())->count() }}
                </p>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center space-x-4 border-l-4 border-yellow-500">
            <div class="p-2 bg-yellow-100 rounded-full">
                <i data-feather="clock" class="w-6 h-6 text-yellow-500"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Pending Reviews</h3>
                <p class="text-xl font-bold text-gray-800">
                    {{ \App\Models\Ropa::where('user_id', auth()->id())->where('status', 'pending')->count() }}
                </p>
            </div>
        </div>

        <!-- Reviewed -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center space-x-4 border-l-4 border-green-600">
            <div class="p-2 bg-green-100 rounded-full">
                <i data-feather="check-circle" class="w-6 h-6 text-green-600"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Reviewed Records</h3>
                <p class="text-xl font-bold text-gray-800">
                    {{ \App\Models\Ropa::where('user_id', auth()->id())->where('status', 'reviewed')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('ropa.index') }}" class="mb-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search by organisation"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm sm:text-base"
        >
        <button 
            type="submit" 
            class="flex items-center justify-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm sm:text-base"
        >
            <i data-feather="search" class="w-4 h-4 mr-1"></i> Search
        </button>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full table-fixed border-collapse text-sm sm:text-base">
            <thead class="bg-indigo-700 text-white">
                <tr>
                    <th class="w-1/6 py-3 px-4 text-left">
                        <a href="{{ route('ropa.index', array_merge(request()->all(), ['sort' => 'organisation_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                            <span>Organisation</span>
                            @if(request('sort') === 'organisation_name')
                                <i data-feather="{{ request('direction') === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="w-3 h-3"></i>
                            @endif
                        </a>
                    </th>
                    <th class="w-1/6 py-3 px-4 text-left">
                        <a href="{{ route('ropa.index', array_merge(request()->all(), ['sort' => 'department_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                            <span>Department</span>
                            @if(request('sort') === 'department_name')
                                <i data-feather="{{ request('direction') === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="w-3 h-3"></i>
                            @endif
                        </a>
                    </th>
                    <th class="w-1/12 py-3 px-4 text-left">
                        <a href="{{ route('ropa.index', array_merge(request()->all(), ['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                            <span>Status</span>
                            @if(request('sort') === 'status')
                                <i data-feather="{{ request('direction') === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="w-3 h-3"></i>
                            @endif
                        </a>
                    </th>
                    <th class="w-1/12 py-3 px-4 text-left">Score</th>
                    <th class="w-1/6 py-3 px-4 text-left">
                        <a href="{{ route('ropa.index', array_merge(request()->all(), ['sort' => 'comment', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                            <span>Comment</span>
                            @if(request('sort') === 'comment')
                                <i data-feather="{{ request('direction') === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="w-3 h-3"></i>
                            @endif
                        </a>
                    </th>
                    <th class="w-1/6 py-3 px-4 text-left">
                        <a href="{{ route('ropa.index', array_merge(request()->all(), ['sort' => 'date_submitted', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                            <span>  Date</span>
                            @if(request('sort') === 'date_submitted')
                                <i data-feather="{{ request('direction') === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="w-3 h-3"></i>
                            @endif
                        </a>
                    </th>
                    <th class="w-1/12 py-3 px-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ropas as $ropa)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-semibold truncate">{{ $ropa->organisation_name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 truncate">{{ $ropa->department_name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'reviewed' => 'bg-green-100 text-green-800'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $statusClasses[$ropa->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($ropa->status ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600 font-medium">
                            <i data-feather="activity" class="inline w-4 h-4 text-indigo-500 mr-1"></i> Unscored
                        </td>
                        <td class="py-3 px-4 text-gray-700 truncate">
                            {{ $ropa->comment ?? 'N/A' }}
                        </td>
                        <td class="py-3 px-4 text-gray-600">
                            {{ $ropa->date_submitted ? \Carbon\Carbon::parse($ropa->date_submitted)->format('d M Y, h:i A') : 'N/A' }}
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('ropa.show', $ropa->id) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                    <i data-feather="eye" class="w-5 h-5"></i>
                                </a>
                                <a href="{{ route('ropa.print', $ropa->id) }}" class="text-green-600 hover:text-green-800" title="Print PDF" target="_blank">
                                    <i data-feather="printer" class="w-5 h-5"></i>
                                </a>
                                <form action="{{ route('ropa.destroy', $ropa->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <i data-feather="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">No ROPA records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $ropas->links() }}
    </div>
</div>

<!-- Feather Icons JS -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        feather.replace({ 'aria-hidden': 'true' });
    });
</script>
@endsection
