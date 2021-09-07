<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccine;

use Exception;

class VaccineSeeder extends Seeder
{
     /**
     * Mode for opening a file as read-only.
     */
    const FILE_MODE_READ = 'r';

    /**
     * Path of the seed file relative to the `database` directory.
     */
    const DATABASE_FILE_PATH = 'data/covid19_vaccines.csv';

    /**
     * If the file has a header row.
     */
    const HAS_HEADER_ROW = true;

    /**
     * If the facility names should be converted to Title Case.
     */
    const USE_TITLE_CASE = false;


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

                // Make the new vaccine model
                $vaccine = new Vaccine([                                  
                    'product_name' => $row[0],
                    'short_description' => $row[1],
                    'cdc_cvx_code' => $row[2],
                    'vaccine_manufacturer' => $row[3],
                    'cdc_mvx_code' => $row[4],
                    'vaccine_status' => $row[5],
                    'vaccine_group' => $row[6],
                ]);

                // throw an exception unless the vaccine could be saved
                throw_unless($vaccine->save(), new Exception("Failed to save vaccine '$row[0]'"));
                
            }
        } finally {
            // if the file has been opened, close it
            if (! empty($file)) {
                fclose($file);
            }
        }
    }
}
