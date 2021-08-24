<?php

namespace Database\Seeders;

/*
 * © Copyright 2021 Ministry of Health, GRZ.
 * 
 * This File is part of Immunisation Registry (IR)
 * 
 * IR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

 
/**
 * The script ProvinceSeeder
 * 
 * This script seeds provinces into the database.
 * @package IR
 * @subpackage DatabaseSeeder
 * @access public
 * @author Chisanga Louis Siwale <Chisanga.Siwaled@moh.gov.zm>
 * @copyright Copyright &copy; 2021 Ministry of Health, GRZ. 
 * @since v1.0
 * @version v1.0
 */

use Illuminate\Database\Seeder;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon;

use Exception;
use App\Models\Province;

class ProvinceSeeder extends Seeder
{

    /**
     * Path of the seed file relative to the `database` directory.
     */
    const DATABASE_FILE_PATH = 'data/Province.json';

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
            // Make the new province model
            $province = new Province([ 
                'name' => $feature->properties->NAME, 
                'population' => $feature->properties->POPULATION,
                'pop_density' => $feature->properties->POP_DENSIT,
                'area_sq_km' => $feature->properties->AREA_SQ_KM,       
                'geometry' => Polygon::fromJson(json_encode($feature->geometry)),
            ]);

            $province->save();

        }
    }
}
