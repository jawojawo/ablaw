<?php

// namespace Database\Seeders;

// use App\Models\AdministrativeFeeCategory;
// use Illuminate\Database\Seeder;
// use App\Models\LawCase;
// use App\Models\Client;
// use App\Models\Associate;
// use App\Models\CourtBranch;
// use App\Models\PaymentType;
// use App\Models\User;
// use Faker\Factory as Faker;

// class LawCaseSeeder extends Seeder
// {
//     public function run()
//     {
//         $faker = Faker::create();


//         $partyRoles = config('enums.party_roles');
//         $caseTypes = config('enums.case_types');

//         foreach (range(1, 100) as $index) {
//             $lawCase = LawCase::create([
//                 'case_number' => strtoupper($faker->bothify('CASE-##??##-###')),
//                 'case_title' => $faker->sentence($faker->numberBetween(3, 10)),
//                 'client_id' => Client::inRandomOrder()->first()->id,
//                 'party_role' => $faker->randomElement($partyRoles),
//                 'associate_id' => Associate::inRandomOrder()->first()->id,
//                 'opposing_party' => $faker->company,
//                 'case_type' => $faker->randomElement($caseTypes),
//                 'total_deposits' => 0,
//                 'total_fees' => 0,
//                 'user_id' => User::inRandomOrder()->first()->id,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//             foreach (range(1,  $faker->numberBetween(0, 20)) as $adminDeposit) {
//                 $lawCase->adminDeposits()->create([
//                     'payment_type_id' => PaymentType::inRandomOrder()->first()->id, // Assuming there's a PaymentType model
//                     'amount' => $faker->randomFloat(2, 100, 5000), // Amount between 100 and 5000
//                     'deposit_date' => $faker->dateTimeBetween('-1 year', 'now'),
//                     'user_id' => 1,
//                 ]);
//             }
//             foreach (range(1,  $faker->numberBetween(0, 20)) as $adminFee) {
//                 $lawCase->adminFees()->create([
//                     'administrative_fee_category_id' => AdministrativeFeeCategory::inRandomOrder()->first()->id, // Assuming there's a PaymentType model
//                     'amount' => $faker->randomFloat(2, 100, 500), // Amount between 100 and 5000
//                     'fee_date' => $faker->dateTimeBetween('-1 year', 'now'),
//                     'user_id' => 1,
//                 ]);
//             }
//             foreach (range(1, $faker->numberBetween(0, 20)) as $hearing) {
//                 $lawCase->hearings()->create([
//                     'title' =>  $faker->sentence($faker->numberBetween(3, 10)),
//                     'court_branch_id' => CourtBranch::inRandomOrder()->first()->id, // Amount between 100 and 5000
//                     'hearing_date' => $faker->dateTimeBetween('-1 month', '+3 month'),
//                     'user_id' => 1,
//                 ]);
//             }
//             foreach (range(1, $faker->numberBetween(0, 20)) as $b) {
//                 $billing = $lawCase->billings()->create([
//                     'title' =>  $faker->sentence($faker->numberBetween(3, 10)),
//                     'amount' =>  $faker->randomFloat(2, 5000, 10000),  // Amount between 100 and 5000
//                     'billing_date' => $faker->dateTimeBetween('-1 month', 'now'),
//                     'due_date' => $faker->dateTimeBetween('-1 year', '+3 month'),
//                     'user_id' => 1,
//                 ]);
//                 foreach (range(1, $faker->numberBetween(0, 5)) as $billingDeposit) {
//                     $billing->deposits()->create([
//                         'payment_type_id' => PaymentType::inRandomOrder()->first()->id,
//                         'amount' => $faker->randomFloat(2, 1000, 2000),
//                         'deposit_date' => $faker->dateTimeBetween('-1 year', '+3 month'),
//                         'user_id' => 1,
//                     ]);
//                 }
//             }
//         }
//     }
// }
