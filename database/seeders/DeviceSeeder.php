<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\User;
use Faker\Factory as Faker;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all customers
        $customers = User::where('role', 'customer')->get();
        
        if ($customers->isEmpty()) {
            $this->command->info('No customers found. Please run CustomerSeeder first.');
            return;
        }

        // Create devices for each customer (1-3 devices per customer)
        foreach ($customers as $customer) {
            $deviceCount = rand(1, 3);
            
            for ($i = 1; $i <= $deviceCount; $i++) {
                $isActive = $faker->boolean(85); // 85% active
                
                Device::create([
                    'device_name' => $customer->name . ' - Device ' . $i,
                    'sms_number' => $faker->unique()->numerify('+91##########'),
                    'user_id' => $customer->id,
                    'is_active' => $isActive,
                    'description' => $faker->optional(0.7)->sentence(),
                    'last_activity_at' => $isActive ? $faker->dateTimeBetween('-7 days', 'now') : $faker->optional(0.5)->dateTimeBetween('-30 days', '-8 days'),
                    'created_at' => $faker->dateTimeBetween('-6 months', '-1 month'),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create some unassigned devices
        for ($i = 1; $i <= 5; $i++) {
            Device::create([
                'device_name' => 'Unassigned Device ' . $i,
                'sms_number' => $faker->unique()->numerify('+91##########'),
                'user_id' => null,
                'is_active' => $faker->boolean(50),
                'description' => 'Device awaiting assignment to customer',
                'last_activity_at' => $faker->optional(0.3)->dateTimeBetween('-7 days', 'now'),
                'created_at' => $faker->dateTimeBetween('-3 months', '-1 week'),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Devices seeded successfully!');
    }
}
