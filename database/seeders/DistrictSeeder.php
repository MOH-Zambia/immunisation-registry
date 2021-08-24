<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;

use App\Models\District;
use App\Models\Province;

class DistrictSeeder extends Seeder
{

    /**
     * Path of the seed file relative to the `database` directory.
     */
    const DATABASE_FILE_PATH = 'data/Districts.json';


    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run()
    {
        // resolve the path of the seed file
        $file_path = database_path(self::DATABASE_FILE_PATH);

        // Read JSON file
        $json = file_get_contents($file_path);
        $json_data = json_decode($json);

        foreach($json_data->features as $feature){

            $province = Province::where('name', '=', (string)$feature->properties->PROVINCE)->first();
            // $province = Province::where('name', 'Southern')->first();
            // $province = Province::first();

            // dd($province->population);

            // dd($province->population);

            // Make the new district model
            $district = new District([
                'province_id' => $province->id,
                'name' => $feature->properties->NAME, 
                'district_type' => $feature->properties->FEATURE_TY,
                'population' => $feature->properties->POPULATION,
                // 'pop_density' => $feature->properties->POP_DENSIT,
                // 'area_sq_km' => $feature->properties->AREA_SQ_KM,       
                'geometry' => Polygon::fromJson(json_encode($feature->geometry)),
            ]);

            $district->save();

        }

    }
}

