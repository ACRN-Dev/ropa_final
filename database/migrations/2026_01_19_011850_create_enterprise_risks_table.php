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
        Schema::create('enterprise_risks', function (Blueprint $table) {
            $table->id();

            // Risk identification
            $table->string('risk_id')->unique(); // RISK-2025-0001
            $table->string('title');
            $table->text('description');
            $table->string('department');

            // Risk classification
            $table->string('risk_category');
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('medium');

            // Inherent Risk (before controls)
            $table->tinyInteger('likelihood')->comment('1-5: 1=Rare, 5=Almost Certain');
            $table->tinyInteger('impact')->comment('1-5: 1=Insignificant, 5=Catastrophic');
            $table->tinyInteger('inherent_risk_score')->comment('likelihood × impact');

            // Current Controls
            $table->text('current_controls')->nullable();

            // Residual Risk (after controls)
            $table->tinyInteger('residual_risk_score')->nullable();

            // Mitigation & action
            $table->text('mitigation_plan')->nullable();
            $table->text('action')->nullable();
            $table->text('expected_response')->nullable();

            // Ownership & status
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('response_owner')->nullable(); // Backup text field
            $table->enum('status', ['open', 'in_progress', 'mitigated', 'closed'])->default('open');

            // Dates
            $table->date('target_date')->nullable();
            $table->date('review_date')->nullable();

            // Enterprise / ROPA linkage (future-proof)
            $table->string('source_type')->nullable()->comment('ROPA, SYSTEM, VENDOR, MANUAL');
            $table->unsignedBigInteger('source_id')->nullable();

            $table->timestamps();
            $table->softDeletes(); // For soft deletion if needed

            // Indexes for better query performance
            $table->index('risk_level');
            $table->index('status');
            $table->index('department');
            $table->index(['source_type', 'source_id']);
            $table->index('owner_id');

            // Foreign key for ROPA relationship
            $table->foreign('source_id')
                  ->references('id')
                  ->on('ropas')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprise_risks');
    }
};