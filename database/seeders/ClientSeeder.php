<?php

// namespace Database\Seeders;

// use App\Models\Client;
// use Faker\Generator as Faker;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class ClientSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(Faker $faker): void
//     {
//         for ($i = 0; $i < 1000; $i++) {
//             $firstName = $faker->firstName;
//             $lastName = $faker->lastName;
//             Client::create([
//                 'first_name' => $firstName,
//                 'last_name' => $lastName,
//                 'suffix' => rand(1, 100) <= 10 ? $faker->suffix : null,
//                 'email' => $lastName . '.' . $firstName . $i . '@test.com',
//                 'phone' => '912345' . str_pad($i, 4, '0', STR_PAD_LEFT),
//                 'info' => $faker->text(15)
//             ]);

//         }
//     }
// }


namespace Database\Seeders;

use App\Models\AdministrativeFeeCategory;
use App\Models\LawCase;
use App\Models\Client;
use App\Models\Associate;
use App\Models\Contact;
use App\Models\CourtBranch;
use App\Models\PaymentType;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        foreach (range(1, 20) as $index) {

            $client = Client::create([
                'name' => $faker->name,
                'address' => $faker->address,
            ]);
            $contactTypes = config('enums.contact_types');
            foreach (range(1, rand(1, 5)) as $contactIndex) {
                $contactType = $faker->randomElement($contactTypes);
                Contact::create([
                    'contact_type' => $contactType,
                    'contact_value' => ($contactType == 'phone') ? $faker->unique()->phoneNumber : $faker->unique()->email(),
                    'contact_label' => rand(1, 100) <= 50 ? $faker->word : null,
                    'contactable_id' => $client->id,
                    'contactable_type' => Client::class,
                ]);
            }
            foreach (range(0, rand(1, 25)) as $noteIndex) {

                $client->notes()->create([
                    'note' => $faker->sentence($faker->numberBetween(10, 30)),
                    'user_id' => User::inRandomOrder()->first()->id,
                ]);
            }
            // Call LawCaseSeeder logic for this client
            (new LawCaseSeeder())->createLawCasesForClient($client);
        }
    }
}

class LawCaseSeeder extends Seeder
{
    public function createLawCasesForClient(Client $client)
    {
        $faker = Faker::create();
        $partyRoles = config('enums.party_roles');
        $caseTypes = config('enums.case_types');

        foreach (range(1, rand(1, 10)) as $index) {
            $lawCase = LawCase::create([
                'case_number' => strtoupper($faker->bothify('CASE-##??##-###')),
                'case_title' => $faker->sentence($faker->numberBetween(3, 10)),
                'client_id' => $client->id,
                'party_role' => $faker->randomElement($partyRoles),
                'associate_id' => Associate::inRandomOrder()->first()->id,
                'opposing_party' => $faker->company,
                'case_type' => $faker->randomElement($caseTypes),
                'total_deposits' => 0,
                'total_fees' => 0,
                'user_id' => User::inRandomOrder()->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $contactTypes = config('enums.contact_types');
            foreach (range(1, rand(1, 5)) as $contactIndex) {
                Contact::create([
                    'contact_type' => $faker->randomElement($contactTypes),
                    'contact_value' => $faker->unique()->phoneNumber,
                    'contact_label' => rand(1, 100) <= 50 ? $faker->word : null,
                    'contactable_id' => $lawCase->id,
                    'contactable_type' => LawCase::class,
                ]);
            }
            // Generate related data for this LawCase
            $this->createRelatedDataForLawCase($lawCase, $faker);
        }
    }

    protected function createRelatedDataForLawCase(LawCase $lawCase, $faker)
    {
        $paymentTypes = config('enums.payment_types');
        foreach (range(1, $faker->numberBetween(0, 20)) as $adminDeposit) {
            $lawCase->adminDeposits()->create([
                'payment_type' => $faker->randomElement($paymentTypes),
                'amount' => $faker->randomFloat(2, 100, 5000),
                'deposit_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'user_id' =>  User::inRandomOrder()->first()->id,
            ]);
        }

        foreach (range(1, $faker->numberBetween(0, 20)) as $adminFee) {
            $lawCase->adminFees()->create([
                // 'administrative_fee_category_id' => AdministrativeFeeCategory::inRandomOrder()->first()->id,
                'type' => $faker->randomElement(['mailing fee', 'utilities', 'license fees', 'court filing fees', 'travel expense']),
                'amount' => $faker->randomFloat(2, 100, 500),
                'fee_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'user_id' =>  User::inRandomOrder()->first()->id,
            ]);
        }

        foreach (range(1, $faker->numberBetween(0, 20)) as $hearing) {
            $lawCase->hearings()->create([
                'title' => $faker->sentence($faker->numberBetween(3, 10)),
                'court_branch_id' => CourtBranch::inRandomOrder()->first()->id,
                'hearing_date' => $faker->dateTimeBetween('-1 month', '+3 months'),
                'user_id' =>  User::inRandomOrder()->first()->id,
            ]);
        }

        foreach (range(1, $faker->numberBetween(0, 20)) as $b) {
            $start_date = Carbon::instance($faker->dateTimeBetween('-6 months', '+6 months'));
            $end_date = (clone $start_date)->addDays(random_int(7, 90));
            // $a = $faker->dateTimeBetween('-6 months', '+6 months');
            // $b = $faker->dateTimeBetween($a, '+' . rand(7, 90) . ' days');
            $billing = $lawCase->billings()->create([
                'title' => $faker->sentence($faker->numberBetween(3, 10)),
                'amount' => $faker->randomFloat(2, 5000, 10000),
                'billing_date' => $start_date,
                'due_date' => $end_date,
                'user_id' =>  User::inRandomOrder()->first()->id,
            ]);
            $paymentTypes = config('enums.payment_types');

            foreach (range(1, $faker->numberBetween(0, 5)) as $billingDeposit) {
                $depositStartDate = (clone $start_date)->addDays(random_int(1, 7));
                $billing->deposits()->create([
                    'payment_type' => $faker->randomElement($paymentTypes),
                    'amount' => $faker->randomFloat(2, 1000, 2000),
                    'deposit_date' => $depositStartDate,
                    'received_from' =>  rand(1, 100) <= 10 ? $faker->name : $billing->client->name,
                    'user_id' =>  User::inRandomOrder()->first()->id,
                ]);
            }
        }
    }
}
