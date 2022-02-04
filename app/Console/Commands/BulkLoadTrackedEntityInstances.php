<?php

namespace App\Console\Commands;

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
 * The script BulkLoadDHIS2Data.php
 *
 * This script seeds provinces into the database.
 * @package IR
 * @subpackage Commands
 * @access public
 * @author Chisanga Louis Siwale <Chisanga.Siwaled@moh.gov.zm>
 * @copyright Copyright &copy; 2021 Ministry of Health, GRZ.
 * @since v1.0
 * @version v1.0
 */

use App\Models\Client;
use App\Models\Facility;
use App\Models\Record;
use Exception;
use Illuminate\Console\Command;

class BulkLoadTrackedEntityInstances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:BulkLoadTrackedEntityInstances {file_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Mode for opening a file as read-only.
     */
    const FILE_MODE_READ = 'r';

    /**
     * If the file has a header row.
     */
    const HAS_HEADER_ROW = true;

    /**
     * If the facility names should be converted to Title Case.
     */
    const USE_TITLE_CASE = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file_path = $this->argument('file_path');

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
                $facility = Facility::where('DHIS2_UID', '=', $row[1])->first();
                $client_uid = $row[0];

                if(!empty($facility)) {
                    //Store data in record table
                    $record = new Record([
                        'record_id' => $client_uid,
                        'data_source' => 'MOH_DHIS2_COVAX',
                        'data_type' => 'TRACKED_ENTITY_INSTANCE',
                        'hash' => sha1(json_encode($row)),
                        'data' => json_encode($row)
                    ]);

                    $record->save();

                    //Create and save new client
                    $client = new Client();
                    $client->client_uid = $client_uid;
                    $client->card_number = $row[1]; //Tracked Entity Attribute UID for Card Number
                    $client->NRC = $row[2]; //Tracked Entity Attribute UID for NRC
                    $client->passport_number = $row[3]; //Tracked Entity Attribute UID for Passport Number
                    $client->first_name = ucfirst(strtolower(trim($row[4]))); //Tracked Entity Attribute UID for First Name
                    $client->last_name = ucfirst(strtolower(trim($row[5]))); //Tracked Entity Attribute UID for Surname
                    $client->sex = $row[6][0];  //Tracked Entity Attribute UID for Sex
                    $client->date_of_birth = $row[7];  //Tracked Entity Attribute UID for Age
                    $client->contact_number = $row[8];  //Tracked Entity Attribute UID for Mobile phone number
                    $client->contact_email_address = $row[9];  //Tracked Entity Attribute UID for Email Address
                    $client->address_line1 = $row[10];  //Tracked Entity Attribute UID for Address (current)
                    $client->guardian_NRC = $row[11]; //Tracked Entity Attribute UID for Guardian's NRC
                    $client->guardian_passport_number = $row[12]; //Tracked Entity Attribute UID for Guardian's Passport Number
                    $client->occupation = $row[13];  //Tracked Entity Attribute UID for Occupation

                    $client->facility_id = $facility->facility_id;
                    $client->record_id = $record->id;

                    // throw an exception unless the facility could be saved
                    throw_unless($client->save(), new Exception("Failed to save client '$row[2]'"));
                }
            }
        } finally {
            // if the file has been opened, close it
            if (! empty($file)) {
                fclose($file);
            }
        }

        return Command::SUCCESS;
    }
}
