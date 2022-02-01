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
 * The script ImportDHIS2Data.php
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

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

use App\Models\Facility;
use App\Models\Record;
use App\Models\Client;
use App\Models\Vaccination;

class ImportDHIS2Data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ImportDHIS2Data {programStartDate} {programEndDate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import DHIS2 Data from COVAX instance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getTrackedEntityInstance($tracked_entity_instance_uid, $facility_id): ? Client
    {
        $httpClient = new GuzzleHttp\Client();

        $response = $httpClient->request('GET', env('DHIS2_BASE_URL'). "trackedEntityInstances.json?trackedEntityInstance={$tracked_entity_instance_uid}", [
            'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
        ]);

        if ($response->getStatusCode() == 200) {
            $response_body = json_decode($response->getBody(), true);
            $trackedEntityInstance = $response_body['trackedEntityInstances'][0];
            $client_uid = $trackedEntityInstance['trackedEntityInstance'];

            $time = date('Y-m-d H:i:s');
            $this->getOutput()->writeln("<info>$time Getting TRACKED_ENTITY_INSTANCE: {$client_uid}</info>");

            //Store data in record table
            $record = new Record([
                'record_id' => $client_uid,
                'data_source' => 'MOH_DHIS2_COVAX',
                'data_type' => 'TRACKED_ENTITY_INSTANCE',
                'hash' => sha1(json_encode($trackedEntityInstance)),
                'data' => json_encode($trackedEntityInstance)
            ]);

            $record->save();

            //Create and save new client
            $client = new Client();
            $client->client_uid = $client_uid;

            foreach ($trackedEntityInstance['attributes'] as $attribute) {
                if ($attribute['attribute'] == 'zUQCBnWbBer') //Tracked Entity Attribute UID for Card Number
                    $client->card_number = $attribute['value'];
                else if ($attribute['attribute'] == 'Ewi7FUfcHAD') //Tracked Entity Attribute UID for NRC
                    $client->NRC = $attribute['value'];
                else if ($attribute['attribute'] == 'pd02AeZHXWi') //Tracked Entity Attribute UID for Passport Number
                    $client->passport_number = $attribute['value'];
                else if ($attribute['attribute'] == 'TfdH5KvFmMy') //Tracked Entity Attribute UID for First Name
                    $client->first_name = ucfirst(strtolower(trim($attribute['value'])));
                else if ($attribute['attribute'] == 'aW66s2QSosT') //Tracked Entity Attribute UID for Surname
                    $client->last_name = ucfirst(strtolower(trim($attribute['value'])));
                else if ($attribute['attribute'] == 'CklPZdOd6H1')  //Tracked Entity Attribute UID for Sex
                    $client->sex = $attribute['value'][0];
                else if ($attribute['attribute'] == 'mAWcalQYYyk')  //Tracked Entity Attribute UID for Age
                    $client->date_of_birth = $attribute['value'];
                else if ($attribute['attribute'] == 'ciCR6BBvIT4')  //Tracked Entity Attribute UID for Mobile phone number
                    $client->contact_number = $attribute['value'];
                else if ($attribute['attribute'] == 'ctpwSFedWFn')  //Tracked Entity Attribute UID for Email Address
                    $client->contact_email_address = $attribute['value'];
                else if ($attribute['attribute'] == 'VCtm2pySeEV')  //Tracked Entity Attribute UID for Address (current)
                    $client->address_line1 = $attribute['value'];
                else if ($attribute['attribute'] == 'gB3BrfkEmkC') //Tracked Entity Attribute UID for Guardian's NRC
                    $client->guardian_NRC = $attribute['value'];
                else if ($attribute['attribute'] == 'TodvbRCs4La') //Tracked Entity Attribute UID for Guardian's Passport Number
                    $client->guardian_passport_number = $attribute['value'];
                else if ($attribute['attribute'] == 'LY2bDXpNvS7')  //Tracked Entity Attribute UID for Occupation
                    $client->occupation = $attribute['value'];
            }

            $client->facility_id = $facility_id;
            $client->record_id = $record->id;

            /*
             * Clients with NRC lenght greater than 11 will cause a QueryExceprion therefore they are not saved
             *We need to find a way that this is communicated back to the Data people so that data cleaning can take place
             */
            $client->save(); //Save new client

            $trackedEntityInstance = json_encode($trackedEntityInstance, JSON_UNESCAPED_SLASHES);

            $time = date('Y-m-d H:i:s');
            $this->getOutput()->writeln("<info>$time Client saved:</info> {$client->id} \n {$trackedEntityInstance}");
        }

        return $client;
    }

    public function loadEvents($programStartDate, $programEndDate): array
    {
        $httpClient = new GuzzleHttp\Client();

        // disable cert verification
        // $client->setDefaultOption(['verify'=>false]);

        $facilities = Facility::all(); //Get all facilities from database
        $total_number_of_events = 0; //Total events counter
        $total_number_of_saved_events = 0; //Saved events counter

        foreach($facilities as $facility){
            if(!empty($facility->DHIS2_UID)){
                try {
                    $response = $httpClient->request('GET', env('DHIS2_BASE_URL')."events.json", [
                        'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                        'query' => [
                            'orgUnit' => $facility->DHIS2_UID,
                            'program' => 'yDuAzyqYABS',
                            'programStartDate' => $programStartDate,
                            'programEndDate' => $programEndDate,
                            'skipPaging' => true
                        ]
                    ]);

                    if ($response->getStatusCode() == 200){
                        $response_body = json_decode($response->getBody(), true);

                        foreach($response_body['events'] as $event){
                            if($event['status'] == "SCHEDULE")
                                continue;

                            $total_number_of_events++;
                            $startTime = microtime(true);
                            $event_uid = $event['event'];

                            try {
                                if (Record::where('hash', sha1(json_encode($event)))->exists()) {
                                    $time = date('Y-m-d H:i:s');
                                    $this->getOutput()->writeln("<comment>$time Skipping event:</comment> | $event_uid | because event already exist in the DATABASE!");
                                } else {
                                    DB::beginTransaction();

                                    //Store data in record table
                                    $record = new Record([
                                        'record_id' => $event_uid,
                                        'data_source' => 'MOH_DHIS2_COVAX',
                                        'data_type' => 'EVENT',
                                        'hash' => sha1(json_encode($event)),
                                        'data' => json_encode($event),
                                    ]);

                                    $record->save();

                                    $tracked_entity_instance_uid = $event['trackedEntityInstance'];
                                    $client = Client::where('client_uid', $tracked_entity_instance_uid)->first();

                                    if(empty($client)){
                                        $client = self::getTrackedEntityInstance($tracked_entity_instance_uid, $facility->id);
                                    }

                                    $total_number_of_events++;
                                    $time = date('Y-m-d H:i:s');

                                    $this->getOutput()
                                        ->writeln("<info>$time Saving event $total_number_of_events: Event UID: $event_uid, Facility UID: {$facility->DHIS2_UID}, TRACKED_ENTITY_INSTANCE: {$tracked_entity_instance_uid}</info>");

                                    // Retrieve the user by the attributes, or create it if it doesn't exist...
//                                    $vaccination = Vaccination::firstOrNew(array('event_uid' => '$event_uid'));
                                    $vaccination = new Vaccination();

                                    $vaccination->client_id = $client->id;
                                    $vaccination->date = $event['eventDate'];
                                    $vaccination->facility_id = $facility->id;
                                    $vaccination->event_uid = $event_uid;
                                    $vaccination->record_id = $record->id;

                                    switch ($event['programStage']) {
                                        case 'a1jCssI2LkW': //programStage: Vaccination Dose 1
                                            $vaccination->dose_number = '1';
                                            break;
                                        case 'RiV7VDxXQLN': //programStage: Vaccination Dose 2
                                            $vaccination->dose_number = '2';
                                            break;
                                        case 'jatC7jRwVKO': //programStage: Vaccination Booster Dose
                                            $vaccination->dose_number = 'Booster';
                                            break;
                                    }

                                    foreach ($event['dataValues'] as $dataValue) {
                                        if ($dataValue['dataElement'] == 'bbnyNYD1wgS') { //Vaccine Name
                                            switch ($dataValue['value']) {
                                                case 'AstraZeneca_zm':
                                                    $vaccination->vaccine_id = 1;
                                                    break;
                                                case 'Johnson_Johnsons_zm':
                                                    $vaccination->vaccine_id = 3;
                                                    break;
                                                case 'Sinopharm':
                                                    $vaccination->vaccine_id = 7;
                                                    break;
                                                case 'Pfizer':
                                                    $vaccination->vaccine_id = 6;
                                                    break;
                                                case 'Moderna':
                                                    $vaccination->vaccine_id = 4;
                                                    break;
                                            }
                                        } else if ($dataValue['dataElement'] == 'Yp1F4txx8tm'){ // Batch Number
                                            $vaccination->vaccine_batch_number = $dataValue['value'];
                                        } else if ($dataValue['dataElement'] == 'FFWcps4MfuH') { //Suggested date for next dose
                                            $vaccination->date_of_next_dose = $dataValue['value'];
                                        }
                                    }

                                    $vaccination->save();

                                    DB::commit(); //if no error on record, vaccination and importlog commit data to database

                                    $total_number_of_saved_events++;
                                    $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                                    $time = date('Y-m-d H:i:s');
                                    $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                                    $this->getOutput()->writeln("<info>$time Event number {$total_number_of_events} saved: ({$runTime}ms)</info> \n $event");
                                }
                            } catch (QueryException $e) {
                                DB::rollback(); //Rollback database transaction if any error occurs

                                $message = $e->getMessage();
                                $time = date('Y-m-d H:i:s');
                                $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                                Log::error( "$time QueryException: $message \n $event");
                                $this->getOutput()->writeln("<error>$time QueryException on event number $total_number_of_events: $message \n $event</error>");
                            } catch (Exception $e) {
                                DB::rollback(); //Rollback database transaction if any error occurs

                                $message = $e->getMessage();
                                $time = date('Y-m-d H:i:s');
                                $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                                Log::error( "$time Exception: $message \n $event");
                                $this->getOutput()->writeln("<error>$time Exception on event number $total_number_of_events: $message \n $event</error>");
                            }
                        } //End foreach
                    }//End if ($response->getStatusCode() == 200)

                } catch (ConnectException $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    Log::error( " $time ConnectException: $message");
                    $this->getOutput()->writeln("<error>$time ConnectException on event number $total_number_of_events: $message </error>");
                } catch (RequestException $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    Log::error( "$time RequestException - $message");
                    $this->getOutput()->writeln("<error>$time RequestException on event number $total_number_of_events: $message</error>");
                } catch (TransferException $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');

                    Log::error( "$time TransferException: $message");
                    $this->getOutput()->writeln("<error>$time TransferException on event number $total_number_of_events: $message</error>");
                } catch (GuzzleException $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                    Log::error( "$time Exception: $message \n $event");
                    Log::error($event);
                    $this->getOutput()->writeln("<error>$time GuzzleException on event number $total_number_of_events: $message \n $event</error>");
                } catch (Exception $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                    Log::error( "$time Exception: $message \n $event");
                    $this->getOutput()->writeln("<error>$time Exception on event number $total_number_of_events: $message \n $event</error>");
                }
            } else {//if(!empty($facility->DHIS2_UID))
                $time = date('Y-m-d H:i:s');

                Log::error("$time Skipping Facility ID: $facility->id,  Facility Name: $facility->name has no DHIS2_UID");
                $this->getOutput()->writeln("<error>$time Skipping facility:</error> Facility ID: $facility->id, Facility name: $facility->name has no DHIS2_UID");
            }
        } //End foreach($facilities as $facility)

        return array($total_number_of_saved_events, $total_number_of_events);
    }//End function loadEvents()

    /**
     * Execute the console command."
     *
     * @return int
     */
    public function handle()
    {
        $script_start_time = microtime(true); //Script start time counter
        $script_start_date_time = date('Y-m-d H:i:s');

        Log::info("$script_start_date_time: Loading data from DHIS2 Covax instance");
        $this->getOutput()->writeln("<info>$script_start_date_time Script started - Loading data from DHIS2 Covax instance</info>");

        $programStartDate = $this->argument('programStartDate');
        $programEndDate = $this->argument('programEndDate');

        $results = self::loadEvents($programStartDate, $programEndDate);

        $script_end_time = date('Y-m-d H:i:s');
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);

        Log::info("$script_end_time Script completed loading data from DHIS2 Covax instance: Duration: $script_run_time Number of Events: $results[0] of $results[1]");
        $this->getOutput()->writeln("<info>$script_end_time Script completed:</info> Completed loading $results[0] of $results[1] events from DHIS2 Covax instance. Duration: $script_run_time");

        $this->info('command:ImportDHIS2Data Command Run successfully!');

        return Command::SUCCESS;
    }
}
