@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Submitted ROPAs</h2>
            <p class="text-muted">Review and manage all submitted Records of Processing Activities</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3">
                <!-- Search -->
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="search" 
                        name="search" 
                        placeholder="Search by user, ROPA ID, or organization"
                        value="{{ request('search') }}"
                    >
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Reviewed" {{ request('status') == 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                    </select>
                </div>

                <!-- Risk Level Filter -->
                <div class="col-md-2">
                    <label for="risk_level" class="form-label">Risk Level</label>
                    <select class="form-select" id="risk_level" name="risk_level">
                        <option value="">All</option>
                        <option value="critical" {{ request('risk_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                        <option value="high" {{ request('risk_level') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('risk_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('risk_level') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <!-- Sort -->
                <div class="col-md-2">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="score_high" {{ request('sort') == 'score_high' ? 'selected' : '' }}>Highest Score</option>
                        <option value="score_low" {{ request('sort') == 'score_low' ? 'selected' : '' }}>Lowest Score</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="mb-2">Total ROPAs</h6>
                    <h3 class="mb-0">{{ $ropas->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="mb-2">Pending Review</h6>
                    <h3 class="mb-0">{{ $ropas->where('status', 'Pending')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="mb-2">Reviewed</h6>
                    <h3 class="mb-0">{{ $ropas->where('status', 'Reviewed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="mb-2">High Risk</h6>
                    <h3 class="mb-0">{{ $ropas->filter(fn($r) => in_array($r->risk_level, ['critical', 'high']))->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- ROPAs Table -->
    <div class="card">
        <div class="card-body">
            @if($ropas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Organization</th>
                                <th>Department</th>
                                <th>Submitted By</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Risk Level</th>
                                <th>Open Risks</th>
                                <th>Reviews</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ropas as $ropa)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $ropa->id }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $ropa->organisation_name ?? 'N/A' }}</strong>
                                        @if($ropa->other_organisation_name)
                                            <br>
                                            <small class="text-muted">{{ implode(', ', (array)$ropa->other_organisation_name) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $ropa->department ?? 'N/A' }}
                                        @if($ropa->other_department)
                                            <br>
                                            <small class="text-muted">{{ implode(', ', (array)$ropa->other_department) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2">
                                                {{ substr($ropa->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                {{ $ropa->user->name ?? 'N/A' }}
                                                <br>
                                                <small class="text-muted">{{ $ropa->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">{{ $ropa->created_at->format('M d, Y') }}</span>
                                        <small class="text-muted">{{ $ropa->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($ropa->isPending())
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i> Pending
                                            </span>
                                        @elseif($ropa->isReviewed())
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Reviewed
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $ropa->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $riskLevel = $ropa->risk_level;
                                            $riskBadgeClass = match($riskLevel) {
                                                'critical' => 'danger',
                                                'high' => 'warning',
                                                'medium' => 'info',
                                                'low' => 'success',
                                                default => 'secondary'
                                            };
                                            $riskIcon = match($riskLevel) {
                                                'critical' => 'fa-exclamation-triangle',
                                                'high' => 'fa-exclamation-circle',
                                                'medium' => 'fa-info-circle',
                                                'low' => 'fa-check-circle',
                                                default => 'fa-minus-circle'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $riskBadgeClass }}">
                                            <i class="fas {{ $riskIcon }} me-1"></i>
                                            {{ ucfirst($riskLevel) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($ropa->hasRisks())
                                            <span class="badge bg-{{ $ropa->open_risks_count > 0 ? 'danger' : 'secondary' }}">
                                                {{ $ropa->open_risks_count }} / {{ $ropa->risks_count }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $reviewCount = $ropa->reviews->count();
                                            $avgScore = $ropa->reviews->avg('average_score');
                                        @endphp
                                        <span class="badge bg-info">{{ $reviewCount }}</span>
                                        @if($avgScore)
                                            <br>
                                            <small class="badge bg-{{ $avgScore >= 80 ? 'success' : ($avgScore >= 60 ? 'warning' : 'danger') }}">
                                                {{ number_format($avgScore, 0) }}%
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="" 
                                               class="btn btn-outline-primary" 
                                               title="View Details"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($ropa->hasRisks())
                                                <a href="{{ route('admin.risks.index', ['ropa_id' => $ropa->id]) }}" 
                                                   class="btn btn-outline-danger" 
                                                   title="View Risks"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-shield-alt"></i>
                                                </a>
                                            @endif
                                            @if($reviewCount > 0)
                                                <a href="{{ route('admin.reviews.index', ['ropa_id' => $ropa->id]) }}" 
                                                   class="btn btn-outline-info" 
                                                   title="View Reviews"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-comments"></i>
                                                </a>
                                            @endif
                                            <a href="" 
                                               class="btn btn-outline-warning" 
                                               title="Edit"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $ropas->firstItem() }} to {{ $ropas->lastItem() }} of {{ $ropas->total() }} ROPAs
                    </div>
                    <div>
                        {{ $ropas->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No ROPAs found</h5>
                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                    @if(request()->hasAny(['search', 'status', 'risk_level', 'sort']))
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-refresh me-1"></i> Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Auto-submit form on filter change
    document.querySelectorAll('#status, #risk_level, #sort').forEach(element => {
        element.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endpush
@endsection