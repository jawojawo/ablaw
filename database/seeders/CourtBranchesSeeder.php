<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\CourtBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CourtBranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $csvFile = fopen(base_path('database/seeders/CourtBranches.csv'), 'r');
        // Skip the header row (if present)
        fgetcsv($csvFile);

        while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
            $cleanedType = preg_replace('/\s*\(.*?\)\s*/', '', $data[2]);
            $CourtBranch = CourtBranch::create([
                'region' => $data[0],
                'city' => $data[1],
                'type' =>  $cleanedType,
                'branch' => trim($data[3]) ?: null,
                'judge' => trim($data[4]) ?: null,
                'address' => $faker->address,
            ]);
            $contactTypes = config('enums.contact_types');
            foreach (range(1, rand(1, 5)) as $contactIndex) {
                Contact::create([
                    'contact_type' => $faker->randomElement($contactTypes),
                    'contact_value' => $faker->unique()->phoneNumber,
                    'contact_label' => rand(1, 100) <= 50 ? $faker->word : null,
                    'contactable_id' => $CourtBranch->id,
                    'contactable_type' => CourtBranch::class,
                ]);
            }
        }

        fclose($csvFile);
    }
}
