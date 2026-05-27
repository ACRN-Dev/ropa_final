<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('ropas', 'risk_level')) {
            Schema::table('ropas', function (Blueprint $table) {
                $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])
                    ->nullable()
                    ->default(null)
                    ->after('status');
            });

            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE `ropas` MODIFY `risk_level` VARCHAR(20) NULL DEFAULT NULL');

        DB::table('ropas')->where('risk_level', 'Low')->update(['risk_level' => 'low']);
        DB::table('ropas')->where('risk_level', 'Medium')->update(['risk_level' => 'medium']);
        DB::table('ropas')->where('risk_level', 'High')->update(['risk_level' => 'high']);
        DB::table('ropas')->where('risk_level', 'Critical')->update(['risk_level' => 'critical']);

        DB::statement("ALTER TABLE `ropas` MODIFY `risk_level` ENUM('low', 'medium', 'high', 'critical') NULL DEFAULT NULL AFTER `status`");
    }

    public function down(): void
    {
        if (! Schema::hasColumn('ropas', 'risk_level') || DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE `ropas` MODIFY `risk_level` VARCHAR(20) NULL DEFAULT NULL');

        DB::table('ropas')->where('risk_level', 'low')->update(['risk_level' => 'Low']);
        DB::table('ropas')->where('risk_level', 'medium')->update(['risk_level' => 'Medium']);
        DB::table('ropas')->where('risk_level', 'high')->update(['risk_level' => 'High']);
        DB::table('ropas')->where('risk_level', 'critical')->update(['risk_level' => 'Critical']);

        DB::statement("ALTER TABLE `ropas` MODIFY `risk_level` ENUM('Low', 'Medium', 'High', 'Critical') NULL DEFAULT NULL AFTER `risk_report`");
    }
};
