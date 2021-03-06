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
 * The script FacilitySeeder
 *
 * This script seeds facilities into the database.
 * @package IR
 * @subpackage DatabaseSeeder
 * @access public
 * @author Chisanga Louis Siwale <Chisanga.Siwaled@moh.gov.zm>
 * @copyright Copyright &copy; 2021 Ministry of Health, GRZ.
 * @since v1.0
 * @version v1.0
 */

use Illuminate\Database\Seeder;
use Grimzy\LaravelMysqlSpatial\Types\Point;

use App\Models\Facility;
use App\Models\District;

use Exception;
use Illuminate\Support\Facades\Log;

class FacilitySeeder extends Seeder
{
    /**
     * Mode for opening a file as read-only.
     */
    const FILE_MODE_READ = 'r';

    /**
     * Path of the seed file relative to the `database` directory.
     */
    const DATABASE_FILE_PATH = 'data/facility_list_update_2022-05-27.csv';

    /**
     * If the file has a header row.
     */
    const HAS_HEADER_ROW = true;

    /**
     * If the facility names should be converted to Title Case.
     */
    const USE_TITLE_CASE = false;


    /**
     * Parses the geometry to a polygon from well-known text.
     *
     * @param mixed $geometry
     * @return \Grimzy\LaravelMysqlSpatial\Types\Polygon
     */
    protected function parseGeometryToPolygon($geometry): Polygon
    {
        return Polygon::fromWKT($geometry);
    }

    public function facilityExistsInDB($facilityDhis2Uid): bool
    {
        $facility = Facility::where('DHIS2_UID', $facilityDhis2Uid)->first();
        return !empty($facility);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run()
    {
        // Saved Facilities count
        $saved_facilities = 0;
        $total_facilities = 0;
        $skipped_facilities = 0;
        // resolve the path of the seed file
        $file_path = database_path(self::DATABASE_FILE_PATH);

        // throw an exception unless a file exists at the given location
        throw_unless(file_exists($file_path), new Exception("No file found at path '$file_path'"));

        try {
            // open the specified file at the given location
            $file = fopen($file_path, self::FILE_MODE_READ);

            // if the first row should be skipped, read the first row of the file
            if (self::HAS_HEADER_ROW ) {
                fgetcsv($file);
            }

            // while there's a row to be read in the file, read the next row
            while ($row = fgetcsv($file)) {
                $total_facilities++;
                $district = District::where('name', '=', $row[1])->first();
                try{
                    if(!empty($district)){
                        if (self::facilityExistsInDB($row[4])) {
                            $skipped_facilities++;
                            $time = date('Y-m-d H:i:s');
                            $this->command->comment("<comment>{$time} Skipped Facility $row[2]: As it already exists in the DB </comment>");
                        } else {
                            $facility = new Facility([
                                'district_id' => $district->id,
                                'name' => $row[2],
                                'HMIS_code' => $row[3],
                                'DHIS2_UID' => $row[4],
                                'smartcare_GUID' => $row[5],
                                'eLMIS_ID' => $row[6],
                                'iHRIS_ID' => $row[7],
                                'ownership' => $row[9],
                                'facility_type' => $row[10],
                                'catchment_population_head_count' => empty($row[13]) ? null : $row[13],
                                'catchment_population_cso' => empty($row[14]) ? null : $row[14],
                                'operation_status' => $row[15],
                                'location_type' => $row[8],
                                'location' => new Point($row[12], $row[11]),	// (lat, lng)
                            ]);

                            $facility->save();

                            $saved_facilities++;
                            $time = date('Y-m-d H:i:s');
                            $this->command->comment("<info>{$time} Saved Facility $row[2]: </info> SUCCESS");
                        }
                    } else {
                        $skipped_facilities++;
                        $time = date('Y-m-d H:i:s');
                        $this->command->comment("<comment>{$time} District : {$$row[1]} NOT FOUND, hence Facility : {$row[2]} was not saved. </comment> FAILURE");
                    }
                } catch (Exception $e)  {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');

                    $this->command->error("<error>{$time} $message Exception on row number $row[2]: </error> Failed to save facility");
                }
            }

            $time = date('Y-m-d H:i:s');
            $this->command->comment("<comment>{$time} Saved a total of :</comment> {$saved_facilities} and <comment> Skipped a total of :</comment> {$skipped_facilities} <comment>Facility(ies) from a total of :</comment> {$total_facilities} <comment>scanned facilities</comment>");

        } catch (Exception $e)  {
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');

            $this->command->error("<error>{$time} {$message}");

            // if the file has been opened, close it
            if (! empty($file)) {
                fclose($file);
            }
        }

    }
}
