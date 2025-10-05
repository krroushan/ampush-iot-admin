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
        Schema::table('motor_logs', function (Blueprint $table) {
            $table->integer('run_time')->nullable()->after('water_level')->comment('Run time in seconds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motor_logs', function (Blueprint $table) {
            $table->dropColumn('run_time');
        });
    }
};