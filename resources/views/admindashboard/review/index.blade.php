@extends('layouts.admin')

@section('title', 'Admin | Submitted ROPAs')

@section('content')
<div class="container mx-auto p-4 sm:p-6">

    <!-- Heading -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-orange-500 flex items-center">
            <i data-feather="folder" class="w-6 h-6 mr-2"></i>
            Submitted ROPAs
        </h2>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-1 gap-2">
            <input type="text" name="search" placeholder="Search by Reviewer or ROPA ID"
                   value="{{ request('search') }}"
                   class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition flex items-center gap-1">
                <i data-feather="search" class="w-4 h-4"></i> Search
            </button>
        </form>

        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.reviews.index', array_merge(request()->all(), ['dpa' => 1])) }}"
               class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 transition flex items-center gap-1 text-sm">
                <i data-feather="check-circle" class="w-4 h-4"></i> DPA Yes
            </a>
            <a href="{{ route('admin.reviews.index', array_merge(request()->all(), ['dpa' => 0])) }}"
               class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition flex items-center gap-1 text-sm">
                <i data-feather="x-circle" class="w-4 h-4"></i> DPA No
            </a>
            <a href="{{ route('admin.reviews.index', array_merge(request()->all(), ['dpia' => 1])) }}"
               class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 transition flex items-center gap-1 text-sm">
                <i data-feather="shield" class="w-4 h-4"></i> DPIA Yes
            </a>
            <a href="{{ route('admin.reviews.index', array_merge(request()->all(), ['dpia' => 0])) }}"
               class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition flex items-center gap-1 text-sm">
                <i data-feather="shield-off" class="w-4 h-4"></i> DPIA No
            </a>
            <a href="{{ route('admin.reviews.index') }}"
               class="bg-gray-500 text-white px-3 py-2 rounded hover:bg-gray-600 transition flex items-center gap-1 text-sm">
                <i data-feather="refresh-cw" class="w-4 h-4"></i> Reset
            </a>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-orange-500 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Reviewer</th>
                    <th class="py-3 px-4 text-left">ROPA ID</th>
                    <th class="py-3 px-4 text-left">Average Score</th>
                    <th class="py-3 px-4 text-left">DPA</th>
                    <th class="py-3 px-4 text-left">DPIA</th>
                    <th class="py-3 px-4 text-left">Created</th>
                    <th class="py-3 px-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($reviews as $review)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4">{{ $review->user->name ?? 'Admin' }}</td>
                        <td class="py-3 px-4">ROPA #{{ $review->ropa->id }}</td>
                        <td class="py-3 px-4 font-semibold text-blue-600">{{ $review->average_score ?? '—' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $review->data_processing_agreement ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $review->data_processing_agreement ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $review->data_protection_impact_assessment ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $review->data_protection_impact_assessment ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $review->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex justify-center space-x-2">
                                <!-- View -->
                                <a href="{{ route('admin.reviews.show', $review->id) }}"
                                   class="text-orange-500 hover:text-orange-600 p-2 rounded transition">
                                    <i data-feather="eye" class="w-5 h-5"></i>
                                </a>

                                <!-- Export -->
                                @if($review->ropa)
                                    <a href="{{ route('admin.ropa.export', $review->ropa->id) }}"
                                       class="text-green-600 hover:text-green-700 p-2 rounded transition">
                                        <i data-feather="download" class="w-5 h-5"></i>
                                    </a>
                                @endif

                                <!-- Share -->
                                <a href=""
                                   class="text-blue-600 hover:text-blue-700 p-2 rounded transition" title="Share ROPA">
                                    <i data-feather="share-2" class="w-5 h-5"></i>
                                </a>

                                <!-- Delete -->
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}"
                                      onsubmit="return confirm('Delete this review?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 p-2 rounded transition">
                                        <i data-feather="trash" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-gray-500">
                            <i data-feather="info" class="w-6 h-6 inline-block mr-2 text-gray-400"></i>
                            No submitted ROPAs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $reviews->links() }}
    </div>

</div>

<script>
    feather.replace();
</script>

@endsection
