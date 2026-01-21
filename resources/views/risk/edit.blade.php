@extends('layouts.app')

@section('title', 'Edit Risk')

@section('content')



<div class="container mx-auto px-4 py-6 max-w-6xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-orange-600 mb-2">Edit Risk</h1>
        <p class="text-gray-600">Update risk details in the enterprise risk register</p>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-validation bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-6 shadow-sm" role="alert">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold mb-2">Please fix the following errors:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm" role="alert">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

<form action="{{ route('risk-register.update', ['risk_register' => $risk->id]) }}"
      method="POST"
      class="bg-white rounded-xl shadow-md p-8 space-y-8">


        @method('PUT')

        <!-- BASIC INFORMATION -->
        <div class="space-y-6">
            <div class="flex items-center gap-2 pb-2 border-b border-gray-200">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Basic Information</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Risk Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Risk Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title"
                           name="title" 
                           value="{{ old('title', $risk->title) }}"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="Enter a clear, concise risk title"
                           required>
                </div>

                <!-- Department (Read-Only) -->
                <div>
                    <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">
                        Department <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="department"
                           name="department" 
                           value="{{ old('department', $risk->department) }}" 
                           class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-50 cursor-not-allowed text-gray-600" 
                           readonly
                           required
                           aria-readonly="true">
                    <p class="text-xs text-gray-500 mt-1">Your department from your user profile</p>
                </div>

                <!-- Risk Category -->
                <div>
                    <label for="risk_category" class="block text-sm font-semibold text-gray-700 mb-2">
                        Risk Category <span class="text-red-500">*</span>
                    </label>
                    <select name="risk_category" 
                            id="risk_category"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            required>
                        <option value="">-- Select Category --</option>
                        @foreach(['Strategic', 'Operational', 'Financial', 'Compliance', 'Technology', 'Reputational', 'Data Privacy', 'Security'] as $category)
                            <option value="{{ $category }}" {{ old('risk_category', $risk->risk_category) == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Risk Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" 
                              id="description"
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                              placeholder="Describe the risk in detail, including potential causes and consequences"
                              required>{{ old('description', $risk->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- RISK ASSESSMENT -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <div class="flex items-center gap-2 pb-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Risk Assessment (Inherent Risk)</h2>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> Assess the risk before considering any controls or mitigation measures.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Likelihood -->
                <div>
                    <label for="likelihood" class="block text-sm font-semibold text-gray-700 mb-2">
                        Likelihood <span class="text-red-500">*</span>
                    </label>
                    <select name="likelihood" 
                            id="likelihood"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            required>
                        <option value="">-- Select --</option>
                        <option value="1" {{ old('likelihood', $risk->likelihood) == '1' ? 'selected' : '' }}>1 - Rare</option>
                        <option value="2" {{ old('likelihood', $risk->likelihood) == '2' ? 'selected' : '' }}>2 - Unlikely</option>
                        <option value="3" {{ old('likelihood', $risk->likelihood) == '3' ? 'selected' : '' }}>3 - Possible</option>
                        <option value="4" {{ old('likelihood', $risk->likelihood) == '4' ? 'selected' : '' }}>4 - Likely</option>
                        <option value="5" {{ old('likelihood', $risk->likelihood) == '5' ? 'selected' : '' }}>5 - Almost Certain</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">How likely is this risk to occur?</p>
                </div>

                <!-- Impact -->
                <div>
                    <label for="impact" class="block text-sm font-semibold text-gray-700 mb-2">
                        Impact <span class="text-red-500">*</span>
                    </label>
                    <select name="impact" 
                            id="impact"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            required>
                        <option value="">-- Select --</option>
                        <option value="1" {{ old('impact', $risk->impact) == '1' ? 'selected' : '' }}>1 - Insignificant</option>
                        <option value="2" {{ old('impact', $risk->impact) == '2' ? 'selected' : '' }}>2 - Minor</option>
                        <option value="3" {{ old('impact', $risk->impact) == '3' ? 'selected' : '' }}>3 - Moderate</option>
                        <option value="4" {{ old('impact', $risk->impact) == '4' ? 'selected' : '' }}>4 - Major</option>
                        <option value="5" {{ old('impact', $risk->impact) == '5' ? 'selected' : '' }}>5 - Catastrophic</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">What would be the impact if it occurs?</p>
                </div>

                <!-- Risk Level (Auto-calculated) -->
                <div>
                    <label for="risk_level" class="block text-sm font-semibold text-gray-700 mb-2">
                        Risk Level <span class="text-red-500">*</span>
                    </label>
                    <select name="risk_level" 
                            id="risk_level"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            required>
                        <option value="">-- Select --</option>
                        <option value="low" {{ old('risk_level', $risk->risk_level) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('risk_level', $risk->risk_level) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('risk_level', $risk->risk_level) == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('risk_level', $risk->risk_level) == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Will be auto-calculated based on score</p>
                </div>

                <!-- Inherent Risk Score Display -->
                <div class="md:col-span-3">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Inherent Risk Score</p>
                        <div id="risk_score_display" class="text-3xl font-bold text-orange-600">
                            {{ old('likelihood', $risk->likelihood) && old('impact', $risk->impact) ? old('likelihood', $risk->likelihood) * old('impact', $risk->impact) : '--' }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Calculated as: Likelihood × Impact</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CURRENT CONTROLS -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <div class="flex items-center gap-2 pb-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Current Controls & Residual Risk</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Current Controls -->
                <div class="md:col-span-2">
                    <label for="current_controls" class="block text-sm font-semibold text-gray-700 mb-2">
                        Current Controls
                    </label>
                    <textarea name="current_controls" 
                              id="current_controls"
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                              placeholder="Describe existing controls, measures, or safeguards currently in place">{{ old('current_controls', $risk->current_controls) }}</textarea>
                </div>

                <!-- Residual Risk Score -->
                <div>
                    <label for="residual_risk_score" class="block text-sm font-semibold text-gray-700 mb-2">
                        Residual Risk Score
                    </label>
                    <input type="number" 
                           id="residual_risk_score"
                           name="residual_risk_score" 
                           value="{{ old('residual_risk_score', $risk->residual_risk_score) }}"
                           min="1"
                           max="25"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="1-25">
                    <p class="text-xs text-gray-500 mt-1">Risk score after applying current controls</p>
                </div>
            </div>
        </div>

        <!-- MITIGATION PLAN -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <div class="flex items-center gap-2 pb-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Mitigation & Action Plan</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Mitigation Plan -->
                <div class="md:col-span-2">
                    <label for="mitigation_plan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Mitigation Plan
                    </label>
                    <textarea name="mitigation_plan" 
                              id="mitigation_plan"
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                              placeholder="Describe planned actions to reduce or eliminate this risk">{{ old('mitigation_plan', $risk->mitigation_plan) }}</textarea>
                </div>

                <!-- Action -->
                <div class="md:col-span-2">
                    <label for="action" class="block text-sm font-semibold text-gray-700 mb-2">
                        Specific Actions
                    </label>
                    <textarea name="action" 
                              id="action"
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                              placeholder="List specific action items or steps to be taken">{{ old('action', $risk->action) }}</textarea>
                </div>

                <!-- Expected Response -->
                <div class="md:col-span-2">
                    <label for="expected_response" class="block text-sm font-semibold text-gray-700 mb-2">
                        Expected Response/Outcome
                    </label>
                    <textarea name="expected_response" 
                              id="expected_response"
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                              placeholder="What outcome do you expect from the mitigation plan?">{{ old('expected_response', $risk->expected_response) }}</textarea>
                </div>

                <!-- Target Date -->
                <div>
                    <label for="target_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Target Completion Date
                    </label>
                    <input type="date" 
                           id="target_date"
                           name="target_date" 
                           value="{{ old('target_date', $risk->target_date) }}"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <!-- Review Date -->
                <div>
                    <label for="review_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Next Review Date
                    </label>
                    <input type="date" 
                           id="review_date"
                           name="review_date" 
                           value="{{ old('review_date', $risk->review_date) }}"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>
        </div>

        <!-- OWNERSHIP & STATUS -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <div class="flex items-center gap-2 pb-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Ownership & Status</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Response Owner -->
                <div>
                    <label for="response_owner" class="block text-sm font-semibold text-gray-700 mb-2">
                        Risk Owner/Responsible Person
                    </label>
                    <input type="text" 
                           id="response_owner"
                           name="response_owner" 
                           value="{{ old('response_owner', $risk->response_owner) }}"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="Enter name or role">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status"
                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            required>
                        <option value="open" {{ old('status', $risk->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ old('status', $risk->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="mitigated" {{ old('status', $risk->status) == 'mitigated' ? 'selected' : '' }}>Mitigated</option>
                        <option value="closed" {{ old('status', $risk->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SUBMIT BUTTONS -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('risk-register.index') }}" 
               class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="reset" 
                    class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                Reset Form
            </button>
            <button type="submit" 
                    class="bg-orange-600 text-white px-8 py-3 rounded-lg hover:bg-orange-700 transition-colors font-semibold shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Risk
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const likelihoodSelect = document.getElementById('likelihood');
    const impactSelect = document.getElementById('impact');
    const riskLevelSelect = document.getElementById('risk_level');
    const scoreDisplay = document.getElementById('risk_score_display');

    function calculateRiskScore() {
        const likelihood = parseInt(likelihoodSelect.value) || 0;
        const impact = parseInt(impactSelect.value) || 0;
        
        if (likelihood && impact) {
            const score = likelihood * impact;
            scoreDisplay.textContent = score;
            
            // Auto-select risk level based on score
            let level = 'low';
            if (score >= 20) {
                level = 'critical';
            } else if (score >= 12) {
                level = 'high';
            } else if (score >= 6) {
                level = 'medium';
            }
            
            riskLevelSelect.value = level;
            
            // Color the score display
            scoreDisplay.classList.remove('text-orange-600', 'text-red-600', 'text-yellow-600', 'text-green-600');
            if (score >= 20) {
                scoreDisplay.classList.add('text-red-600');
            } else if (score >= 12) {
                scoreDisplay.classList.add('text-orange-600');
            } else if (score >= 6) {
                scoreDisplay.classList.add('text-yellow-600');
            } else {
                scoreDisplay.classList.add('text-green-600');
            }
        } else {
            scoreDisplay.textContent = '--';
        }
    }

    likelihoodSelect.addEventListener('change', calculateRiskScore);
    impactSelect.addEventListener('change', calculateRiskScore);
    
    // Calculate on page load if values exist
    calculateRiskScore();

    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease-out, max-height 0.5s ease-out, margin 0.5s ease-out";
            alert.style.opacity = 0;
            alert.style.maxHeight = 0;
            alert.style.marginBottom = 0;
            setTimeout(() => alert.remove(), 500);
        }, 10000);
    });
});
</script>
@endsection