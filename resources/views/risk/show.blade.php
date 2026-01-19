@extends('layouts.app')

@section('title', 'View Risk')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-orange-600 mb-2">Risk Details</h1>
            <p class="text-gray-600">Enterprise Risk Register – View Mode</p>
        </div>

        <div class="flex gap-3">
            {{-- Edit Button --}}
    <a href="{{ route('risk-register.edit', ['risk_register' => $risk->id]) }}"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
        Edit
    </a>

    {{-- Delete Button --}}
    <form action="{{ route('risk-register.destroy', ['risk_register' => $risk->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this risk?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium">
            Delete
        </button>
    </form>
        </div>
    </div>

    <!-- BASIC INFORMATION -->
    <div class="bg-white rounded-xl shadow-md p-8 space-y-8">
        <div class="space-y-6">
            <div class="flex items-center gap-2 pb-2 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Basic Information</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <p class="text-sm font-semibold text-gray-700">Risk Title</p>
                    <p class="mt-1 text-gray-900">{{ $risk->title }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700">Department</p>
                    <p class="mt-1 text-gray-900">{{ $risk->department }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700">Risk Category</p>
                    <p class="mt-1 text-gray-900">{{ $risk->risk_category }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm font-semibold text-gray-700">Description</p>
                    <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $risk->description }}</p>
                </div>
            </div>
        </div>

        <!-- RISK ASSESSMENT -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Risk Assessment (Inherent)</h2>

            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Likelihood</p>
                    <p class="mt-1">{{ $risk->likelihood }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700">Impact</p>
                    <p class="mt-1">{{ $risk->impact }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700">Risk Level</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-white text-sm
                        @if($risk->risk_level === 'critical') bg-red-600
                        @elseif($risk->risk_level === 'high') bg-orange-600
                        @elseif($risk->risk_level === 'medium') bg-yellow-500
                        @else bg-green-600 @endif">
                        {{ ucfirst($risk->risk_level) }}
                    </span>
                </div>

                <div class="md:col-span-3">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-gray-700">Inherent Risk Score</p>
                        <div class="text-3xl font-bold text-orange-600">
                            {{ $risk->likelihood * $risk->impact }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CURRENT CONTROLS -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Current Controls & Residual Risk</h2>
            <div>
                <p class="text-sm font-semibold text-gray-700">Current Controls</p>
                <p class="mt-1 whitespace-pre-line">{{ $risk->current_controls ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700">Residual Risk Score</p>
                <p class="mt-1">{{ $risk->residual_risk_score ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- MITIGATION PLAN -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Mitigation & Action Plan</h2>
            <div>
                <p class="text-sm font-semibold text-gray-700">Mitigation Plan</p>
                <p class="mt-1 whitespace-pre-line">{{ $risk->mitigation_plan ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700">Specific Actions</p>
                <p class="mt-1 whitespace-pre-line">{{ $risk->action ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700">Expected Response</p>
                <p class="mt-1 whitespace-pre-line">{{ $risk->expected_response ?? 'N/A' }}</p>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Target Date</p>
                    <p class="mt-1">{{ optional($risk->target_date)->format('d M Y') ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Next Review Date</p>
                    <p class="mt-1">{{ optional($risk->review_date)->format('d M Y') ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- OWNERSHIP & STATUS -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Ownership & Status</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Risk Owner</p>
                    <p class="mt-1">{{ $risk->response_owner ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Status</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-white text-sm
                        @if($risk->status === 'closed') bg-gray-600
                        @elseif($risk->status === 'mitigated') bg-green-600
                        @elseif($risk->status === 'in_progress') bg-blue-600
                        @else bg-orange-600 @endif">
                        {{ ucfirst(str_replace('_', ' ', $risk->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- FOOTER ACTIONS -->
        <div class="flex justify-end pt-4">
            <a href="{{ route('risk-register.index') }}"
               class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-medium">
                Back to Register
            </a>
        </div>
    </div>
</div>
@endsection
