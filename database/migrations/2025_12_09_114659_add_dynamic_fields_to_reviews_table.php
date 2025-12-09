<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'section_scores')) {
                $table->json('section_scores')->nullable()->after('comment');
            }

            if (!Schema::hasColumn('reviews', 'data_processing_agreement_file')) {
                $table->string('data_processing_agreement_file')->nullable()->after('section_scores');
            }

            if (!Schema::hasColumn('reviews', 'data_protection_impact_assessment_file')) {
                $table->string('data_protection_impact_assessment_file')->nullable()->after('data_processing_agreement_file');
            }

            if (!Schema::hasColumn('reviews', 'data_sharing_agreement')) {
                $table->string('data_sharing_agreement')->nullable()->after('data_protection_impact_assessment_file');
            }

            if (!Schema::hasColumn('reviews', 'risks')) {
                $table->json('risks')->nullable()->after('data_sharing_agreement');
            }

            if (!Schema::hasColumn('reviews', 'mitigation_measures')) {
                $table->text('mitigation_measures')->nullable()->after('risks');
            }

            if (!Schema::hasColumn('reviews', 'overall_risk_score')) {
                $table->integer('overall_risk_score')->nullable()->after('mitigation_measures');
            }

            if (!Schema::hasColumn('reviews', 'impact_level')) {
                $table->string('impact_level')->nullable()->after('overall_risk_score');
            }

            if (!Schema::hasColumn('reviews', 'children_data_transfer')) {
                $table->boolean('children_data_transfer')->default(false)->after('impact_level');
            }

            if (!Schema::hasColumn('reviews', 'vulnerable_population_transfer')) {
                $table->boolean('vulnerable_population_transfer')->default(false)->after('children_data_transfer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $columns = [
                'section_scores',
                'data_processing_agreement_file',
                'data_protection_impact_assessment_file',
                'data_sharing_agreement',
                'risks',
                'mitigation_measures',
                'overall_risk_score',
                'impact_level',
                'children_data_transfer',
                'vulnerable_population_transfer'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('reviews', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
