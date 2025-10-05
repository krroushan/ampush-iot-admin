<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MotorLog;
use App\Models\User;
use Carbon\Carbon;

class MotorLogSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get existing customers with phone numbers
        $customers = User::where('role', 'customer')
            ->whereNotNull('phone_number')
            ->get();

        if ($customers->isEmpty()) {
            $this->command->info('No customers found. Please run CustomerSeeder first.');
            return;
        }

        $motorLogs = [];

        foreach ($customers as $customer) {
            // Generate logs for the last 30 days
            $startDate = Carbon::now()->subDays(30);
            
            for ($i = 0; $i < 50; $i++) {
                $timestamp = $startDate->copy()->addHours(rand(0, 720))->timestamp * 1000;
                $motorStatus = ['ON', 'OFF', 'STATUS'][array_rand(['ON', 'OFF', 'STATUS'])];
                $command = ['MOTORON', 'MOTOROFF', 'STATUS'][array_rand(['MOTORON', 'MOTOROFF', 'STATUS'])];
                
                $motorLogs[] = [
                    'timestamp' => $timestamp,
                    'motor_status' => $motorStatus,
                    'voltage' => $motorStatus === 'ON' ? rand(200, 240) + (rand(0, 100) / 100) : 0,
                    'current' => $motorStatus === 'ON' ? rand(1, 5) + (rand(0, 100) / 100) : 0,
                    'water_level' => rand(0, 100),
                    'run_time' => $motorStatus === 'OFF' ? rand(10, 300) : null,
                    'mode' => ['Fully Automatic', 'Semi Automatic'][array_rand(['Fully Automatic', 'Semi Automatic'])],
                    'clock' => ['ON', 'OFF'][array_rand(['ON', 'OFF'])],
                    'command' => $command,
                    'phone_number' => $customer->phone_number,
                    'is_synced' => rand(0, 1) == 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in batches
        $chunks = array_chunk($motorLogs, 500);
        foreach ($chunks as $chunk) {
            MotorLog::insert($chunk);
        }

        $this->command->info('Motor logs seeded successfully!');
    }
}