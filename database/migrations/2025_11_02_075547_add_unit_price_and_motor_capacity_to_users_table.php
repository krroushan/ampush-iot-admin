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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('unit_price', 8, 2)->default(6.00)->after('fcm_token')->comment('Electricity cost per kWh in ₹');
            $table->integer('motor_pumping_capacity')->default(50)->after('unit_price')->comment('Motor pumping capacity in liters per minute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'motor_pumping_capacity']);
        });
    }
};
