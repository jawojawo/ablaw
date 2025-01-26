<?php

// namespace Database\Seeders;

// use App\Models\LawCase;
// use App\Models\User;
// use Illuminate\Database\Seeder;
// use Spatie\Permission\Models\Permission;

// class PermissionSeeder extends Seeder
// {
//     public function run(): void
//     {
//         //user permissions
//         Permission::create(['name' => 'viewAny user']);
//         Permission::create(['name' => 'view user']);
//         Permission::create(['name' => 'update user']);
//         Permission::create(['name' => 'reset user password']);
//         Permission::create(['name' => 'delete user']);

//         //cases permissions
//         Permission::create(['name' => 'viewAny case']);
//         Permission::create(['name' => 'view case']);
//         Permission::create(['name' => 'create case']);
//         Permission::create(['name' => 'update case']);
//         Permission::create(['name' => 'delete case']);


//         Permission::create(['name' => 'view case deposit']);
//         Permission::create(['name' => 'create case deposit']);
//         Permission::create(['name' => 'update case deposit']);
//         Permission::create(['name' => 'delete case deposit']);

//         Permission::create(['name' => 'view case expense']);
//         Permission::create(['name' => 'create case expense']);
//         Permission::create(['name' => 'update case expense']);
//         Permission::create(['name' => 'delete case expense']);

//         Permission::create(['name' => 'view case hearing']);
//         Permission::create(['name' => 'create case hearing']);
//         Permission::create(['name' => 'update case hearing']);
//         Permission::create(['name' => 'delete case hearing']);

//         Permission::create(['name' => 'view case billing']);
//         Permission::create(['name' => 'create case billing']);
//         Permission::create(['name' => 'update case billing']);
//         Permission::create(['name' => 'delete case billing']);


//         //client permissions
//         Permission::create(['name' => 'viewAny client']);
//         Permission::create(['name' => 'create client']);
//         Permission::create(['name' => 'view client']);
//         Permission::create(['name' => 'update client']);
//         Permission::create(['name' => 'delete client']);

//         //associate permissions
//         Permission::create(['name' => 'viewAny associate']);
//         Permission::create(['name' => 'create associate']);
//         Permission::create(['name' => 'view associate']);
//         Permission::create(['name' => 'update associate']);
//         Permission::create(['name' => 'delete associate']);

//         //billing permissions
//         Permission::create(['name' => 'viewAny billing']);

//         //office expense permissions
//         Permission::create(['name' => 'viewAny office expense']);
//         Permission::create(['name' => 'create office expense']);
//         Permission::create(['name' => 'view office expense']);
//         Permission::create(['name' => 'update office expense']);
//         Permission::create(['name' => 'delete office expense']);

//         //custom events permissions
//         Permission::create(['name' => 'viewAny custom event']);
//         Permission::create(['name' => 'create custom event']);
//         Permission::create(['name' => 'view custom event']);
//         Permission::create(['name' => 'update custom event']);
//         Permission::create(['name' => 'delete custom event']);

//         $user = User::find(1);
//         $case1 = LawCase::find(1);
//         $case2 = LawCase::find(2);

//         // Assign 'update' permission to user for specific cases
//         $user->givePermissionTo('update', $case1);
//         $user->givePermissionTo('update', $case2);
//     }
// }
