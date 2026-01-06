@extends('layouts.admin')

@section('title', 'Admin | Submitted ROPAs')

@section('content')
<div class="container mx-auto p-4 sm:p-6">

    <!-- Alerts -->
    @foreach (['success' => 'green', 'error' => 'red', 'warning' => 'yellow'] as $key => $color)
        @if (session($key))
            <div id="alert-{{ $key }}" 
                 class="mb-4 bg-{{ $color }}-100 border border-{{ $color }}-300 text-{{ $color }}-800 px-4 py-3 rounded">
                {{ session($key) }}
            </div>
        @endif
    @endforeach

    <!-- Heading -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-orange-500 flex items-center">
            <i data-feather="folder" class="w-6 h-6 mr-2"></i>
            Submitted ROPAs
        </h2>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <!-- Search -->
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-1 gap-2">
            <input type="text" name="search" placeholder="Search by Reviewer or ROPA ID"
                value="{{ request('search') }}"
                class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">

            <button type="submit"
                class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition flex items-center gap-1">
                <i data-feather="search" class="w-4 h-4"></i> Search
            </button>
        </form>

        <!-- Filter Buttons -->
        <div class="flex gap-2 flex-wrap">
            @foreach (['dpa' => ['1' => 'green', '0' => 'red'], 'dpia' => ['1' => 'green', '0' => 'red']] as $filter => $options)
                @foreach ($options as $value => $color)
                    <a href="{{ route('admin.reviews.index', array_merge(request()->all(), [$filter => $value])) }}"
                       class="bg-{{ $color }}-500 text-white px-3 py-2 rounded hover:bg-{{ $color }}-600 transition flex items-center gap-1 text-sm">
                        <i data-feather="{{ $filter === 'dpa'
                            ? ($value == 1 ? 'check-circle' : 'x-circle')
                            : ($value == 1 ? 'shield' : 'shield-off') }}"
                           class="w-4 h-4"></i>
                        {{ strtoupper($filter) }} {{ $value == 1 ? 'Yes' : 'No' }}
                    </a>
                @endforeach
            @endforeach

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
                    <th class="py-3 px-4 text-left w-32">Processing ID</th>
                    <th class="py-3 px-4 text-left w-40">Review Status</th>
                    <th class="py-3 px-4 text-left w-64">Risk Score</th>
                    <th class="py-3 px-4 text-left w-40">Children Transfer</th>
                    <th class="py-3 px-4 text-left w-48">Vulnerable Population</th>
                    <th class="py-3 px-4 text-left w-32">Created</th>
                    <th class="py-3 px-4 text-center w-32">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($reviews as $review)
                <tr class="hover:bg-gray-50 transition">

                    <!-- ROPA ID -->
                    <td class="py-3 px-4 font-medium text-gray-700">{{ $review->ropa->id }}</td>

                    <!-- Review Status -->
                    <td class="py-3 px-4">
                        @php
                            $statusColors = [
                                \App\Models\Review::STATUS_PENDING => 'bg-gray-300 text-gray-800',
                                \App\Models\Review::STATUS_IN_PROGRESS => 'bg-yellow-300 text-yellow-800',
                                \App\Models\Review::STATUS_REVIEWED => 'bg-green-300 text-green-800',
                            ];
                            $statusClass = $statusColors[$review->status] ?? 'bg-gray-300 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                            {{ $review->status }}
                        </span>
                    </td>

                    <!-- Risk Score Progress Bar -->
                    <td class="py-3 px-4">
                        @php
                            $score = $review->calculated_overall_risk_score;
                            $color = match(true) {
                                $score <= 20 => 'bg-green-500',
                                $score <= 60 => 'bg-yellow-500',
                                $score <= 80 => 'bg-orange-500',
                                default => 'bg-red-500'
                            };
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="{{ $color }} h-4 rounded-full transition-all duration-500" style="width: {{ $score }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">{{ $score }}%</p>
                    </td>

                    <!-- Children Data Transfer -->
                    <td class="py-3 px-4">
                        <span class="{{ $review->children_data_transfer ? 'text-green-600' : 'text-red-600' }}">
                            {{ $review->children_data_transfer ? 'Yes' : 'No' }}
                        </span>
                    </td>

                    <!-- Vulnerable Population Transfer -->
                    <td class="py-3 px-4">
                        <span class="{{ $review->vulnerable_population_transfer ? 'text-green-600' : 'text-red-600' }}">
                            {{ $review->vulnerable_population_transfer ? 'Yes' : 'No' }}
                        </span>
                    </td>

                    <!-- Date -->
                    <td class="py-3 px-4 text-gray-600">{{ $review->created_at->format('d M Y') }}</td>

                    <!-- Actions -->
                    <td class="py-3 px-4">
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="text-orange-500 hover:text-orange-600">
                                <i data-feather="eye" class="w-5 h-5"></i>
                            </a>
                            @if($review->ropa)
                                <a href="{{ route('admin.ropa.export', $review->ropa->id) }}" class="text-green-600 hover:text-green-700">
                                    <i data-feather="download" class="w-5 h-5"></i>
                                </a>
                            @endif
                            <a href="#" class="text-blue-600 hover:text-blue-700">
                                <i data-feather="share-2" class="w-5 h-5"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" onsubmit="return confirm('Delete this review?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
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
    setTimeout(() => {
        ['alert-success', 'alert-error', 'alert-warning'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.style.transition = "opacity .5s";
                el.style.opacity = "0";
                setTimeout(() => el.remove(), 500);
            }
        });
    }, 10000);

    feather.replace();
</script>

@endsection
