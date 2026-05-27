<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ropa_issues', function (Blueprint $table) {
            
            // Add risk_level column if missing
            if (!Schema::hasColumn('ropa_issues', 'risk_level')) {
                $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])
                      ->default('low')
                      ->after('description');
            }

            // Add status column if missing
            if (!Schema::hasColumn('ropa_issues', 'status')) {
                $table->enum('status', ['open', 'resolved'])
                      ->default('open')
                      ->after('risk_level');
            }

            // Add soft deletes if missing
            if (!Schema::hasColumn('ropa_issues', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add index ONLY if it doesn't already exist
        if (! $this->indexExists('ropa_issues', 'ropa_issues_risk_level_index')) {
            Schema::table('ropa_issues', function (Blueprint $table) {
                $table->index('risk_level', 'ropa_issues_risk_level_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ropa_issues', function (Blueprint $table) {

            // Drop soft deletes
            if (Schema::hasColumn('ropa_issues', 'deleted_at')) {
                $table->dropSoftDeletes();
            }

            // Drop status
            if (Schema::hasColumn('ropa_issues', 'status')) {
                $table->dropColumn('status');
            }

            // Drop risk_level
            if (Schema::hasColumn('ropa_issues', 'risk_level')) {
                $table->dropColumn('risk_level');
            }
        });

        // Drop the index if it exists
        if ($this->indexExists('ropa_issues', 'ropa_issues_risk_level_index')) {
            if (DB::getDriverName() === 'sqlite') {
                DB::statement('DROP INDEX ropa_issues_risk_level_index');
            } else {
                Schema::table('ropa_issues', function (Blueprint $table) {
                    $table->dropIndex('ropa_issues_risk_level_index');
                });
            }
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        if (DB::getDriverName() === 'sqlite') {
            return collect(DB::select("PRAGMA index_list('{$table}')"))
                ->contains(fn ($row) => $row->name === $index);
        }

        if (DB::getDriverName() === 'mysql') {
            return DB::table('information_schema.statistics')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', $table)
                ->where('index_name', $index)
                ->exists();
        }

        return false;
    }
};
