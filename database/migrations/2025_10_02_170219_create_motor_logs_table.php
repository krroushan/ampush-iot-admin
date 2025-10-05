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
        Schema::create('motor_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('timestamp')->index();
            $table->string('motor_status', 10);
            $table->float('voltage')->nullable();
            $table->float('current')->nullable();
            $table->float('water_level')->nullable();
            $table->string('mode', 20)->nullable();
            $table->string('clock', 50)->nullable();
            $table->string('command', 20);
            $table->string('phone_number', 20)->index();
            $table->boolean('is_synced')->default(false)->index();
            $table->timestamps();
            
            // Additional indexes for better performance
            $table->index(['phone_number', 'timestamp']);
            $table->index(['motor_status', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motor_logs');
    }
};
