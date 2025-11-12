<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ropas', function (Blueprint $table) {
            // Change the status column to ENUM with default 'Pending'
            $table->enum('status', ['Reviewed', 'Pending'])->default('Pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('ropas', function (Blueprint $table) {
            // Revert back to string (nullable)
            $table->string('status')->nullable()->change();
        });
    }
};
