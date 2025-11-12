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
        Schema::create('ropas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['Reviewed', 'Pending'])->default('Pending');
            $table->timestamp('date_submitted')->nullable();
            $table->string('other_specify')->nullable();
            $table->boolean('information_shared')->default(false);
            $table->text('information_nature')->nullable();
            $table->boolean('outsourced_processing')->default(false);
            $table->string('processor')->nullable();
            $table->boolean('transborder_processing')->default(false);
            $table->json('personal_data_category')->nullable();
            $table->string('country')->nullable();
            $table->json('lawful_basis')->nullable();
            $table->integer('retention_period_years')->nullable();
            $table->text('retention_rationale')->nullable();
            $table->integer('users_count')->nullable();
            $table->boolean('access_control')->default(false);   
            $table->string('organisation_name')->nullable();
            $table->string('department_name')->nullable();
            $table->string('other_department')->nullable();
            $table->json('data_sources')->nullable();   
            $table->json('data_formats')->nullable();   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ropas');
    }
};
