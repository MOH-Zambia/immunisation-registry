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
        //  User::factory(1)->create();

        Role::create([
            'name' => 'Administrator',
        ]);

        User::create([
            'name' => 'Administrator',
            'email' => 'ir@moh.gov.zm',
            'password' => Hash::make('m0h1ct11'),
        ]);


        $this->call([
            CountrySeeder::class,
            ProvinceSeeder::class,
            DistrictSeeder::class,
            FacilitySeeder::class,
            VaccineSeeder::class,
        ]);
    }
}
