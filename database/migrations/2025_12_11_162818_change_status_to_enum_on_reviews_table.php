<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Change the status column to ENUM
            $table->enum('status', ['Pending', 'In Progress', 'Reviewed'])
                  ->default('Pending')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Revert back to string if needed
            $table->string('status')->default('Pending')->change();
        });
    }
};
