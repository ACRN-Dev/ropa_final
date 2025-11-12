@extends('layouts.admin')

@section('title', 'ROPA Records')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary">ROPA Records</h3>
        <a href="{{ route('ropas.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New ROPA
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>id</th>
                    <th>Organisation</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                    <th>Risk Score (%)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ropas as $ropa)
                    <tr>
                        <td>{{ $ropa->id }}</td>
                        <td>{{ $ropa->organisation_name }}</td>
                        <td>{{ $ropa->department_name ?? $ropa->other_department }}</td>
                        <td>{{ $ropa->status }}</td>
                        <td>{{ $ropa->date_submitted?->format('Y-m-d') ?? 'N/A' }}</td>
                        <td>{{ $ropa->calculateRiskScore() }}%</td>
                        <td>
                            <a href="{{ route('ropas.weights.edit', $ropa->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-sliders"></i> Manage Weights
                            </a>
                            <a href="{{ route('ropas.edit', $ropa->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('ropas.destroy', $ropa->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No ROPA records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $ropas->links() }}
    </div>
</div>
@endsection
