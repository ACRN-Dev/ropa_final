@extends('layouts.admin')

@section('title', 'Admin | Review View')

@section('content')
<div class="container mx-auto p-6">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <a href="{{ route('admin.reviews.index') }}"
           class="bg-gray-500 px-4 py-2 rounded-lg text-white hover:bg-gray-600 flex items-center gap-1">
            <i data-feather="arrow-left" class="w-4 h-4"></i> Back
        </a>

        <h2 class="text-2xl font-bold text-orange-500 flex items-center gap-2">
            <i data-feather="file-text" class="w-6 h-6"></i> Review Details
        </h2>
    </div>

    @php
        $ropa = $review->ropa;
        $sections = $ropa::sections();

        function renderValue($value) {
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }
            if (is_array($value)) {
                return implode(', ', array_map(fn($v) => renderValue($v), $value));
            } elseif (is_bool($value)) {
                return $value
                    ? '<span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs font-semibold">Yes</span>'
                    : '<span class="bg-red-100 text-red-800 px-2 py-0.5 rounded-full text-xs font-semibold">No</span>';
            } elseif ($value === null || $value === '') {
                return 'N/A';
            } else {
                return $value;
            }
        }

        $icons = [
            'organisation_name' => 'briefcase',
            'department' => 'layers',
            'processes' => 'cpu',
            'data_sources' => 'database',
            'data_formats' => 'file-text',
            'information_nature' => 'info',
            'personal_data_categories' => 'user',
            'records_count' => 'list',
            'data_volume' => 'bar-chart-2',
            'retention_period_years' => 'clock',
            'access_estimate' => 'eye',
            'retention_rationale' => 'book-open',
            'information_shared' => 'share-2',
            'local_organizations' => 'home',
            'transborder_countries' => 'globe',
            'access_control' => 'lock',
            'access_measures' => 'shield',
            'technical_measures' => 'tool',
            'organisational_measures' => 'users',
            'lawful_basis' => 'file-check',
            'risk_report' => 'alert-circle',
        ];
    @endphp

    <div x-data="reviewData(@json($review->risks), {{ $review->overall_risk_score ?? 0 }}, '{{ $review->impact_level ?? '' }}')" x-cloak class="bg-white rounded-lg shadow-lg">
        <div class="flex border-b border-gray-200">
            <button @click="tab='details'" :class="tab==='details' ? 'border-orange-500 text-orange-600' : 'text-gray-600'"
                    class="px-4 py-2 font-medium border-b-2 focus:outline-none">
                Review Details
            </button>

            <button @click="tab='risk'" :class="tab==='risk' ? 'border-orange-500 text-orange-600' : 'text-gray-600'"
                    class="px-4 py-2 font-medium border-b-2 focus:outline-none">
                Risk Assessment
            </button>

            <button @click="tab='score'" :class="tab==='score' ? 'border-orange-500 text-orange-600' : 'text-gray-600'"
                    class="px-4 py-2 font-medium border-b-2 focus:outline-none">
                Risk Score
            </button>
        </div>

        <div class="p-5">

            {{-- Review Details Tab --}}
            <div x-show="tab==='details'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach ($sections as $section)
                        @php
                            $value = $ropa->{$section};
                            $skipIfEmpty = ['technical_measures_other', 'lawful_basis_other', 'organisational_measures_other'];
                            if (in_array($section, $skipIfEmpty) && empty($value)) continue;
                        @endphp

                        <div class="bg-gray-50 shadow rounded-xl p-5 flex flex-col gap-2 hover:shadow-lg transition transform hover:scale-105">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-feather="{{ $icons[$section] ?? 'circle' }}" class="w-5 h-5 text-orange-500"></i>
                                <h4 class="font-semibold text-gray-800">{{ ucwords(str_replace('_', ' ', $section)) }}</h4>
                            </div>
                            <p class="text-gray-600 text-sm">{!! renderValue($value) !!}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Compliance Documents --}}
                <div class="bg-white shadow-lg rounded-xl p-5 mb-6">
                    <div class="space-y-4">

                        {{-- FIXED FIELD NAME --}}
                        @if ($review->data_processing_agreement_file)
                            <a href="{{ asset('storage/'.$review->data_processing_agreement_file) }}" target="_blank"
                               class="flex items-center gap-2 text-blue-600 hover:underline">
                                <i data-feather="file-text" class="w-4 h-4"></i> View Data Processing Agreement (DPA)
                            </a>
                        @endif

                        {{-- FIXED FIELD NAME --}}
                        @if ($review->data_protection_impact_assessment_file)
                            <a href="{{ asset('storage/'.$review->data_protection_impact_assessment_file) }}" target="_blank"
                               class="flex items-center gap-2 text-blue-600 hover:underline">
                                <i data-feather="file-text" class="w-4 h-4"></i> View Data Protection Impact Assessment (DPIA)
                            </a>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Risk Tab --}}
            <div x-show="tab==='risk'" class="space-y-6">
               <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm()">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="flex flex-col gap-2">
                            <label class="font-medium">Children Data Transfer?</label>
                            <select name="children_data_transfer" class="border border-gray-300 rounded-lg px-3 py-2">
                                <option value="1" {{ $review->children_data_transfer ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$review->children_data_transfer ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-medium">Vulnerable Population Data Transfer?</label>
                            <select name="vulnerable_population_transfer" class="border border-gray-300 rounded-lg px-3 py-2">
                                <option value="1" {{ $review->vulnerable_population_transfer ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$review->vulnerable_population_transfer ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <input type="hidden" name="ropa_id" value="{{ $review->ropa_id }}">

                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-medium">Data Sharing Agreement (Upload or Link)</label>
                            <input type="file" name="data_sharing_agreement" class="border border-gray-300 rounded-lg px-3 py-2">
                            @if ($review->data_sharing_agreement)
                                <a href="{{ asset('storage/'.$review->data_sharing_agreement) }}" target="_blank"
                                   class="text-blue-600 hover:underline mt-1">View Existing Document</a>
                            @endif
                        </div>
                    </div>

                    {{-- RISK LOOP --}}
                    <div class="space-y-4 mt-4">
                        <template x-for="(risk, index) in risks" :key="index">
                            <div class="p-4 border border-gray-200 rounded-lg flex flex-col gap-2 bg-gray-50">
                                <div class="flex flex-col md:flex-row gap-2 md:items-center">
                                    <input type="text" :name="'risks['+index+'][name]'" x-model="risk.name"
                                           placeholder="Risk Name"
                                           class="border border-gray-300 rounded-lg px-3 py-2 flex-1">

                                    <input type="number" min="1" max="5" :name="'risks['+index+'][probability]'" x-model.number="risk.probability"
                                           @input="calculateScore()"
                                           placeholder="Probability"
                                           class="border border-gray-300 rounded-lg px-3 py-2 w-32">

                                    <input type="number" min="1" max="5" :name="'risks['+index+'][impact]'" x-model.number="risk.impact"
                                           @input="calculateScore()"
                                           placeholder="Impact"
                                           class="border border-gray-300 rounded-lg px-3 py-2 w-32">

                                    <button type="button" @click="removeRisk(index)"
                                            class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </template>

                        <button type="button" @click="addRisk()"
                                class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                            Add Risk
                        </button>
                    </div>

                    {{-- Mitigation --}}
                    <div class="flex flex-col gap-2 mt-4">
                        <label class="font-medium">Mitigation Measures</label>
                        <textarea name="mitigation_measures" rows="4"
                                  class="border border-gray-300 rounded-lg px-3 py-2"
                                  placeholder="Describe mitigation measures">{{ $review->mitigation_measures }}</textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
                            Save
                        </button>
                    </div>
                </form>
            </div>

            {{-- Score Tab --}}
            <div x-show="tab==='score'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="(risk, index) in risks" :key="index">
                        <div class="p-4 border border-gray-200 rounded-lg flex flex-col gap-2 bg-gray-50">
                            <p class="font-medium">Risk: <span x-text="risk.name || 'Unnamed Risk'"></span></p>
                            <p>Probability: <span x-text="risk.probability"></span></p>
                            <p>Impact: <span x-text="risk.impact"></span></p>
                            <p>Score: <span x-text="risk.probability * risk.impact"></span></p>
                        </div>
                    </template>
                </div>

                <div class="mt-4 p-4 border border-gray-300 rounded-lg bg-gray-100">
                    <p class="font-bold text-lg">Overall Risk Score: <span x-text="overallScore + '%'"></span></p>
                    <p class="font-bold text-lg">Impact Level: <span x-text="impactLevel"></span></p>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    feather.replace();

    function reviewData(existingRisks = [], initialScore = 0, initialLevel = '') {
        return {
            tab: 'details',
            risks: existingRisks.map(r => ({ ...r, probability: r.probability || 1, impact: r.impact || 1 })),
            overallScore: parseInt(initialScore),
            impactLevel: initialLevel || '',
            calculateScore() {
                if (!this.risks.length) {
                    this.overallScore = 0;
                    this.impactLevel = '';
                    return;
                }
                const totalScore = this.risks.reduce((sum, r) => sum + (r.probability * r.impact), 0);
                const maxScore = this.risks.length * 5 * 5;
                const percent = Math.round((totalScore / maxScore) * 100);
                this.overallScore = percent;

                if (percent <= 20) this.impactLevel = 'Low';
                else if (percent <= 60) this.impactLevel = 'Medium';
                else if (percent <= 80) this.impactLevel = 'High';
                else this.impactLevel = 'Critical';
            },
            addRisk() {
                this.risks.push({ name: '', probability: 1, impact: 1 });
                this.calculateScore();
            },
            removeRisk(index) {
                this.risks.splice(index, 1);
                this.calculateScore();
            },
            submitForm() {
                this.calculateScore();
                this.$root.querySelector('form').submit();
            }
        }
    }
</script>
@endsection
