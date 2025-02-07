<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $admin = User::create([
            'id' => 1,
            "name" => 'admin',
            'role' => 'admin',
            'username' => 'admin',
            'password' => bcrypt('admin'),
        ]);
        $aileen = User::create([
            "name" => 'Aileen France R. Botor',
            'role' => 'ABLAW Admin and Billing',
            'username' => 'aileen.botor',
            'password' => bcrypt('aileen.botor'),
        ]);

        // foreach (range(1, 9) as $index) {
        //     $user = User::create([
        //         'name' => $faker->name,
        //         'role' => $faker->jobTitle,
        //         'username' => 'user' . $index,
        //         'password' => bcrypt('user' . $index),
        //         'address' => $faker->address,
        //     ]);
        //     $contactTypes = config('enums.contact_types');
        //     foreach (range(1, rand(1, 5)) as $contactIndex) {
        //         $contactType = $faker->randomElement($contactTypes);
        //         Contact::create([
        //             'contact_type' => $contactType,
        //             'contact_value' => ($contactType == 'phone') ? $faker->unique()->phoneNumber : $faker->unique()->email(),
        //             'contact_label' => rand(1, 100) <= 50 ? $faker->word : null,
        //             'contactable_id' => $user->id,
        //             'contactable_type' => User::class,
        //         ]);
        //     }
        //     foreach (range(0, rand(1, 25)) as $noteIndex) {
        //         $user->notes()->create([
        //             'note' => $faker->sentence($faker->numberBetween(10, 30)),
        //             'user_id' => 1,
        //         ]);
        //     }
        // }
    }
}
