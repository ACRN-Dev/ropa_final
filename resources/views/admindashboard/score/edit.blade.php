@extends('layouts.admin')

@section('title', 'Edit ROPA Record')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h4 class="mb-0">
                <i class="bi bi-pencil-square me-2"></i> Edit ROPA Record #{{ $ropa->id }}
            </h4>
            <a href="{{ route('ropas.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('ropas.update', $ropa->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ================= ROPA Details ================= --}}
                <h5 class="text-primary mb-3">ROPA Details</h5>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="organisation_name" class="form-label">Organisation Name</label>
                        <input type="text" class="form-control" id="organisation_name" name="organisation_name"
                               value="{{ old('organisation_name', $ropa->organisation_name) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="department_name" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="department_name" name="department_name"
                               value="{{ old('department_name', $ropa->department_name) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="Pending" {{ old('status', $ropa->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ old('status', $ropa->status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ old('status', $ropa->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="date_submitted" class="form-label">Date Submitted</label>
                        <input type="date" class="form-control" id="date_submitted" name="date_submitted"
                               value="{{ old('date_submitted', $ropa->date_submitted?->format('Y-m-d')) }}">
                    </div>

                </div>

                {{-- ================= Risk Weight Editor ================= --}}
                <hr class="my-4">
                <h5 class="text-primary mb-3">Risk Weight Settings (%)</h5>

                @php
                    $scoreableFields = [
                        'status',
                        'other_specify',
                        'information_shared',
                        'information_nature',
                        'outsourced_processing',
                        'processor',
                        'transborder_processing',
                        'country',
                        'lawful_basis',
                        'retention_period_years',
                        'retention_rationale',
                        'users_count',
                        'access_control',
                        'personal_data_category',
                    ];
                    $weights = $ropa->riskWeightSettings->pluck('weight', 'field_name');
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Field</th>
                                <th>Weight (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scoreableFields as $field)
                                <tr>
                                    <td class="text-capitalize">{{ str_replace('_', ' ', $field) }}</td>
                                    <td style="width: 150px;">
                                        <input type="number" name="weights[{{ $field }}]"
                                               class="form-control"
                                               value="{{ old('weights.' . $field, $weights[$field] ?? 0) }}"
                                               min="0" max="100" step="0.01">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save"></i> Save All Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
