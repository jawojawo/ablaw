<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficeExpense;
use App\Models\CustomEvent;
use Carbon\Carbon;
use Faker\Factory as Faker;

class OfficeExpenseAndCustomEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Seed Office Expenses
        foreach (range(1, 50) as $index) {
            OfficeExpense::create([
                'description' => $faker->sentence,
                'amount' => $faker->randomFloat(2, 100, 10000),
                'expense_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'type' => $faker->randomElement(['Office Supplies', 'Utilities', 'Maintenance', 'Travel']),
                'user_id' => 1,
            ]);
        }

        // Seed Custom Events
        foreach (range(1, 30) as $index) {
            $start_time = Carbon::instance($faker->dateTimeBetween('-6 months', '+6 months'));
            $end_time =   (clone $start_time)->addDays(random_int(0, 7));
            CustomEvent::create([
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'type' => $faker->randomElement(['Meeting', 'Seminar', 'Conference', 'Workshop']),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'location' => $faker->boolean(75) ? $faker->address : null,
                'user_id' => 1,
            ]);
        }
    }
}
