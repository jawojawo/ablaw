<?php

namespace Database\Seeders;

use App\Models\Associate;
use App\Models\Contact;
use Faker\Generator as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssociateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 0; $i < 100; $i++) {
            $associate = Associate::create([
                'name' => $faker->name,
                'address' => $faker->address,
            ]);
            $contactTypes = config('enums.contact_types');
            foreach (range(1, rand(1, 5)) as $contactIndex) {
                Contact::create([
                    'contact_type' => $faker->randomElement($contactTypes),
                    'contact_value' => $faker->unique()->phoneNumber,
                    'contact_label' => rand(1, 100) <= 50 ? $faker->word : null,
                    'contactable_id' => $associate->id,
                    'contactable_type' => Associate::class,
                ]);
            }
            // $contactTypes = config('enums.contact_types');
            // foreach (range(1, rand(1, 5)) as $contactIndex) {
            //     Associate::create([
            //         'contact_type' => $faker->randomElement($contactTypes),
            //         'contact_value' => $faker->unique()->phoneNumber,
            //         'contact_label' => rand(1, 100) <= 50 ? $faker->word : null,
            //         'contactable_id' => $associate->id,
            //         'contactable_type' => Associate::class,
            //     ]);
            // }
        }
    }
}
