<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Drop foreign key safely
        try {
            Schema::table('user_activities', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Exception $e) {}

        // 2️⃣ Add new columns safely
        Schema::table('user_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('user_activities', 'model')) {
                $table->string('model')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('user_activities', 'description')) {
                $table->text('description')->nullable()->after('model');
            }
            if (!Schema::hasColumn('user_activities', 'old_values')) {
                $table->json('old_values')->nullable()->after('description');
            }
            if (!Schema::hasColumn('user_activities', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }

            // Drop unused columns
            if (Schema::hasColumn('user_activities', 'meta')) {
                $table->dropColumn('meta');
            }
            if (Schema::hasColumn('user_activities', 'updated_at')) {
                $table->dropColumn('updated_at');
            }

            // Drop old index safely
            try {
                $table->dropIndex('user_activities_user_id_action_created_at_index');
            } catch (\Exception $e) {}
        });

        // 3️⃣ Re-add foreign key and new indexes
        Schema::table('user_activities', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['user_id', 'created_at']);
            $table->index(['model', 'model_id']);
        });
    }

    public function down(): void
    {
        // Drop FK safely
        try {
            Schema::table('user_activities', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Exception $e) {}

        // Restore old column
        Schema::table('user_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('user_activities', 'model_type')) {
                $table->string('model_type')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('user_activities', 'meta')) {
                $table->json('meta')->nullable();
            }
            if (!Schema::hasColumn('user_activities', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        // Drop new columns safely
        Schema::table('user_activities', function (Blueprint $table) {
            $table->dropColumn(['model', 'description', 'old_values', 'new_values']);

            try {
                $table->dropIndex(['model', 'model_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['user_id', 'created_at']);
            } catch (\Exception $e) {}
        });

        // Restore old index and FK
        Schema::table('user_activities', function (Blueprint $table) {
            $table->index(['user_id', 'action', 'created_at']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};
