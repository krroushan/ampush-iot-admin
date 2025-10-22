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
            $table->foreignId('device_id')->nullable()->after('id')->constrained('devices')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->after('device_id')->constrained('users')->onDelete('set null');
            
            // Add indexes for better performance
            $table->index('device_id');
            $table->index('user_id');
            $table->index(['device_id', 'timestamp']);
            $table->index(['user_id', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motor_logs', function (Blueprint $table) {
            $table->dropForeign(['device_id']);
            $table->dropForeign(['user_id']);
            $table->dropIndex(['device_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['device_id', 'timestamp']);
            $table->dropIndex(['user_id', 'timestamp']);
            $table->dropColumn(['device_id', 'user_id']);
        });
    }
};
