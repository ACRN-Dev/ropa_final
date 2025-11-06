@extends('layouts.admin')

@section('title', 'ROPA Risk Scores')

@section('content')
<div class="container mx-auto p-6">

    <h2 class="text-2xl font-bold mb-6 text-indigo-700 flex items-center">
        <i data-feather="shield" class="w-6 h-6 mr-2"></i> ROPA Risk Scores
    </h2>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 flex items-center">
            <i data-feather="check-circle" class="w-5 h-5 mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Add Risk Score Button -->
    <div class="flex items-center mb-4 space-x-2">
        <a href="{{ route('risk_scores.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center">
            <i data-feather="plus-circle" class="w-5 h-5 mr-2"></i> Add Risk Score
        </a>
    </div>

    <!-- Risk Scores Table -->
    <div class="overflow-x-auto">
        <table class="w-full border mt-4 text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">ID</th>
                    <th class="border p-2">ROPA</th>
                    <th class="border p-2">Risk Score</th>
                    <th class="border p-2">Risk Level</th>
                    <th class="border p-2">Reviewed By</th>
                    <th class="border p-2">Date</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riskScores as $score)
                    <tr>
                        <td class="border p-2">{{ $score->id }}</td>
                        <td class="border p-2">{{ $score->ropa->organisation_name ?? 'N/A' }}</td>
                        <td class="border p-2">{{ $score->risk_score ?? 'N/A' }}</td>
                        <td class="border p-2">{{ $score->review_status ?? 'N/A' }}</td>
                        <td class="border p-2">{{ $score->reviewed_by ?? 'N/A' }}</td>
                        <td class="border p-2">{{ $score->created_at->format('d M Y') }}</td>
                        <td class="border p-2 flex items-center space-x-2">
                            <a href="{{ route('risk_scores.edit', $score->id) }}"
                               class="text-yellow-600 flex items-center hover:text-yellow-800">
                                <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit
                            </a>
                            <form action="{{ route('risk_scores.destroy', $score->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this score?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 flex items-center hover:text-red-800">
                                    <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 p-4">No risk scores found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $riskScores->links() }}
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
