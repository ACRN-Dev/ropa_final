<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_activities', function (Blueprint $table) {
            // Add 'model' as an alias-friendly column (the model->fillable uses 'model')
            if (!Schema::hasColumn('user_activities', 'model')) {
                $table->string('model')->nullable()->after('model_type');
            }
            // Human-readable description
            if (!Schema::hasColumn('user_activities', 'description')) {
                $table->text('description')->nullable()->after('model');
            }
            // Before/after snapshots for updates
            if (!Schema::hasColumn('user_activities', 'old_values')) {
                $table->json('old_values')->nullable()->after('description');
            }
            if (!Schema::hasColumn('user_activities', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->dropColumn(['model', 'description', 'old_values', 'new_values']);
        });
    }
};