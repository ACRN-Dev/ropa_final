@extends('layouts.admin')

@section('title', 'User Activities')

@section('content')
<div class="container mx-auto p-4 sm:p-6">
    <!-- Page Header -->
    <h2 class="text-2xl font-bold mb-6 text-indigo-700 flex items-center">
        <i data-feather="activity" class="w-6 h-6 mr-2"></i> User Activities (System Logs)
    </h2>

    <!-- Export and Search -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <a href="{{ route('activities.export') }}" 
           class="flex items-center bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-indigo-600 hover:text-white transition text-sm sm:text-base shadow-sm">
            <i data-feather="download" class="w-4 h-4 mr-2"></i> Export CSV
        </a>

        <form method="GET" action="{{ route('activities.index') }}" class="flex items-center w-full sm:w-1/3">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Search by user, action, or IP..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm sm:text-base">
            <button type="submit" 
                    class="ml-2 flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm sm:text-base">
                <i data-feather="search" class="w-4 h-4 mr-1"></i> Search
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full table-auto border-collapse text-sm sm:text-base">
            <thead class="bg-indigo-700 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">User</th>
                    <th class="py-3 px-4 text-left">Action</th>
                    <th class="py-3 px-4 text-left">Model</th>
                    <th class="py-3 px-4 text-left">IP Address</th>
                    <th class="py-3 px-4 text-left">Date</th>
                    <th class="py-3 px-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activities as $activity)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-semibold">{{ optional($activity->user)->name ?? 'Guest' }}</td>
                        <td class="py-3 px-4">{{ $activity->action }}</td>
                        <td class="py-3 px-4">{{ class_basename($activity->model_type) }} #{{ $activity->model_id }}</td>
                        <td class="py-3 px-4">{{ $activity->ip_address }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $activity->created_at->diffForHumans() }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center space-x-3">
                                <!-- View -->
                                <a href="{{ route('activities.show', $activity->id) }}" 
                                   class="text-blue-600 hover:text-blue-800" title="View Details">
                                    <i data-feather="eye"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('activities.destroy', $activity->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this log entry?')">
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
                        <td colspan="6" class="py-4 text-center text-gray-500">No activities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
