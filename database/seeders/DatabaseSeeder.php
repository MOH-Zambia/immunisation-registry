<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TruncateAllTables::class,
            CountrySeeder::class,
            ProvinceSeeder::class,
            DistrictSeeder::class,
            FacilitySeeder::class,
            VaccineSeeder::class,
        ]);

        Role::create([
            'name' => 'Administrator',
        ]);

        Role::create([
            'name' => 'Moderator',
        ]);

        Role::create([
            'name' => 'Authenticated User',
        ]);

        User::create([
            'role_id' => 1,
            'first_name' => 'Administrator',
            'last_name' => 'Administrator',
            'email' => 'ir@moh.gov.zm',
            'password' => '$2y$10$uh6UV1v7u7ZSOclAt5AeWu8.QriT0fasQ1zv1asG1fbzN4fdENy5m', //m0h1ct11
        ]);

        User::factory(120)->create();


    }
}
