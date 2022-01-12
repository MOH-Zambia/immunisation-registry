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
            'description' => 'System administrator'
        ]);

        Role::create([
            'name' => 'Moderator',
            'description' => 'System manager'
        ]);

        Role::create([
            'name' => 'Authenticated User',
            'description' => 'Public user with account on the system'
        ]);

        $host= gethostname();
        $ip = gethostbyname($host);

        User::create([
            'role_id' => 1,
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'email' => 'ir@moh.gov.zm',
            'email_verified_at' => now(),
            'password' => '$2y$10$uh6UV1v7u7ZSOclAt5AeWu8.QriT0fasQ1zv1asG1fbzN4fdENy5m', //m0h1ct11
        ]);

        User::create([
            'role_id' => 2,
            'first_name' => 'DHIO',
            'last_name' => 'User',
            'email' => 'dhio.ir@moh.gov.zm',
            'email_verified_at' => now(),
            'password' => Hash::make('M0h@2021'), //M0h@2021
        ]);
    }
}
