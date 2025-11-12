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
        Schema::create('risk_weight_settings', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to ropas
            $table->foreignId('ropa_id')
                  ->constrained('ropas')
                  ->onDelete('cascade'); // Cascade delete
            
            $table->string('field_name');
            $table->decimal('weight', 8, 2); // Adjust precision if needed
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_weight_settings');
    }
};
