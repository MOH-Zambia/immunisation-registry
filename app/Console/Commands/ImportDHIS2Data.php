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
use Exception;

use App\Models\Facility;
use App\Models\ImportLog;
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

        try {
            $response = $httpClient->request('GET', env('DHIS2_BASE_URL'). "trackedEntityInstances.json?trackedEntityInstance={$tracked_entity_instance_uid}", [
                'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
            ]);

            if ($response->getStatusCode() == 200) {
                $responce_body = json_decode($response->getBody(), true);
                $trackedEntityInstance = $responce_body['trackedEntityInstances'][0];

                $time = date('Y-m-d H:i:s');
                $this->getOutput()->writeln("<info>$time Getting TRACKED_ENTITY_INSTANCE: </info>{$trackedEntityInstance['trackedEntityInstance']}");

                //Store data in record table
                $record = new Record([
                    'data_source' => 'MOH_DHIS2_COVAX',
                    'data_type' => 'TRACKED_ENTITY_INSTANCE',
                    'data' => json_encode($trackedEntityInstance)
                ]);

                $record->save();

                //Create and save new client
                $client = new Client();
                $client->client_uid = $trackedEntityInstance['trackedEntityInstance'];

                foreach ($trackedEntityInstance['attributes'] as $attribute) {
                    if ($attribute['attribute'] == 'zUQCBnWbBer') //Attribute ID for Card Number
                        $client->card_number = $attribute['value'];
                    else if ($attribute['attribute'] == 'Ewi7FUfcHAD') //Attribute ID for NRC
                        $client->NRC = $attribute['value'];
                    else if ($attribute['attribute'] == 'pd02AeZHXWi') //Attribute ID for Passport Number
                        $client->passport_number = $attribute['value'];
                    else if ($attribute['attribute'] == 'TfdH5KvFmMy') //Attribute ID for First Name
                        $client->first_name = ucfirst(strtolower(trim($attribute['value'])));
                    else if ($attribute['attribute'] == 'aW66s2QSosT') //Attribute ID for Surname
                        $client->last_name = ucfirst(strtolower(trim($attribute['value'])));
                    else if ($attribute['attribute'] == 'CklPZdOd6H1')  //Attribute ID for Sex
                        $client->sex = $attribute['value'][0];
                    else if ($attribute['attribute'] == 'mAWcalQYYyk')  //Attribute ID for Age
                        $client->date_of_birth = $attribute['value'];
                    else if ($attribute['attribute'] == 'ciCR6BBvIT4')  //Attribute ID for Mobile phone number
                        $client->contact_number = $attribute['value'];
                    else if ($attribute['attribute'] == 'ctpwSFedWFn')  //Attribute ID for Email Address
                        $client->contact_email_address = $attribute['value'];
                    else if ($attribute['attribute'] == 'VCtm2pySeEV')  //Attribute ID for Address (current)
                        $client->address_line1 = $attribute['value'];
                    else if ($attribute['attribute'] == 'LY2bDXpNvS7')  //Attribute ID for Occupation
                        $client->occupation = $attribute['value'];
                }

                $client->facility_id = $facility_id;
                $client->record_id = $record->id;

                /*
                 * Clients with NRC lenght greater than 11 will cause a QueryExceprion therefore they are not saved
                 *We need to find a way that this is communicated back to the Data people so that data cleaning can take place
                 */
                $client->save(); //Save new client

                // Create record in ImportLog table
                ImportLog::create([
                    'hash' => sha1(json_encode($trackedEntityInstance)),
                ]);

                $trackedEntityInstance = json_encode($trackedEntityInstance, JSON_UNESCAPED_SLASHES);

                $time = date('Y-m-d H:i:s');
                $this->getOutput()->writeln("<info>$time Client saved:</info> {$client->id} \n {$trackedEntityInstance}");
            }
        } catch (ConnectException $e) {
            // Connection exceptions are not caught by RequestException
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');

            Log::error( " $time: ConnectException - $message");
            $this->getOutput()->writeln("<error>$time $message </error>");
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');

            Log::error( "$time: RequestException - $message");
            $this->getOutput()->writeln("<error>$time RequestException: $message</error>");
        } catch (TransferException $e) {
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');

            Log::error( "$time: TransferException: $message");
            $this->getOutput()->writeln("<error>$time TransferException: $message</error>");
        }catch (QueryException $e) {
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');
            $trackedEntityInstance = json_encode($trackedEntityInstance, JSON_UNESCAPED_SLASHES);

            Log::error( "$time: QueryException: $message \n $trackedEntityInstance");
            $this->getOutput()->writeln("<error>$time QueryException: $message \n $trackedEntityInstance</error>");
        } catch (Exception $e) {
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');
            $trackedEntityInstance = json_encode($trackedEntityInstance, JSON_UNESCAPED_SLASHES);

            Log::error( "$time: Exception: $message \n $trackedEntityInstance");
            $this->getOutput()->writeln("<error>$time Exception: $message \n $trackedEntityInstance</error>");
        }
        return $client;
    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function loadEvents($programStartDate, $programEndDate){
        $httpClient = new GuzzleHttp\Client();

        // disable cert verification
        // $client->setDefaultOption(['verify'=>false]);

        $facilities = Facility::all(); //Get all facilities from database
        $number_of_events = 0;
        $script_start_time = microtime(true);
        $script_start_date_time = date('Y-m-d H:i:s');

        Log::info("$script_start_date_time: Loading data from DHIS2 Covax instance");
        $this->getOutput()->writeln("<info>$script_start_date_time Script started - Loading data from DHIS2 Covax instance</info>");

        foreach($facilities as $facility){
            if(!empty($facility->DHIS2_UID)){
                try {
                    $response = $httpClient->request('GET', env('DHIS2_BASE_URL')."events.json", [
                        'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                        'query' => [
                            'orgUnit'=>$facility->DHIS2_UID,
                            'program'=>'yDuAzyqYABS',
                            'programStartDate'=> $programStartDate,
                            'programEndDate'=> $programEndDate
                        ]
                    ]);

                    if ($response->getStatusCode() == 200){
                        $response_body = json_decode($response->getBody(), true);

                        foreach($response_body['events'] as $event){
                            DB::beginTransaction();

                            $startTime = microtime(true);

                            $event_uid = $event['event'];
                            $tracked_entity_instance_uid = $event['trackedEntityInstance'];
                            $client = Client::where('client_uid', $tracked_entity_instance_uid)->first();

                            if(empty($client)){
                                $client = self::getTrackedEntityInstance($tracked_entity_instance_uid, $facility->id);
                            }

                            // Check if hash of $event exist in import log
                            $import_log = ImportLog::where('hash', sha1(json_encode($event)))->first();

                            try {
                                if (empty($import_log)) {
                                    $time = date('Y-m-d H:i:s');
                                    $this->getOutput()
                                        ->writeln("<info>$time Saving event:</info> Facility UID: {$facility->DHIS2_UID}, TRACKED_ENTITY_INSTANCE: {$event['trackedEntityInstance']}");

                                    //Store data in record table
                                    $record = new Record([
                                        'data_source' => 'MOH_DHIS2_COVAX',
                                        'data_type' => 'EVENT',
                                        'data' => json_encode($event),
                                    ]);

                                    $record->save();

                                    //Create and save new vaccination event
                                    $vaccination = new Vaccination();
                                    $vaccination->client_id = $client->id;
                                    $vaccination->date = $event['eventDate'];

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

                                    $vaccination->facility_id = $facility->id;
                                    $vaccination->event_id = $event_uid;
                                    $vaccination->record_id = $record->id;

                                    if (Vaccination::where('event_id', $event_uid)->exists())
                                        $vaccination->update();
                                    else
                                        $vaccination->save();

                                    // Create record in ImportLog table
                                    ImportLog::create([
                                        'hash' => sha1(json_encode($event)),
                                    ]);

                                    $number_of_events++;

                                    $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                                    $time = date('Y-m-d H:i:s');
                                    $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                                    Log::warning("$time Event saved: ({$runTime}ms) \n $event");
                                    $this->getOutput()->writeln("<info>$time Event saved: ({$runTime}ms)</info> \n $event");
                                } else {
                                    $time = date('Y-m-d H:i:s');
                                    $this->getOutput()->writeln("<comment>$time Skipping event:</comment> {$event['event']} because event already exist in the DATABASE!");
//                                        Log::warning("$time Skipping event: {$event['event']} because event already exist in the DATABASE!");
                                }
                            } catch (QueryException $e) {
                                DB::rollback(); //Rollback database transaction if any error occurs

                                $message = $e->getMessage();
                                $time = date('Y-m-d H:i:s');
                                $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                                Log::error( "$time QueryException: $message \n $event");
                                $this->getOutput()->writeln("<error>$time QueryException: $message \n $event</error>");
                            }

                            DB::commit(); //if no error on record, vaccination and importlog commit data to database

                        } //End foreach
                    }//End if ($response->getStatusCode() == 200)
                } catch (ConnectException $e) {
                    // Connection exceptions are not caught by RequestException
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    Log::error( " $time ConnectException: $message");
                    $this->getOutput()->writeln("<error>$time ConnectException: $message </error>");
                } catch (RequestException $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    Log::error( "$time RequestException - $message");
                    $this->getOutput()->writeln("<error>$time RequestException: $message</error>");
                } catch (TransferException $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    Log::error( "$time TransferException: $message");
                    $this->getOutput()->writeln("<error>$time TransferException: $message</error>");
                } catch (Exception $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                    Log::error( "$time Exception: $message \n $event");
                    $this->getOutput()->writeln("<error>$time Exception: $message \n $event</error>");
                }
            } else {//if(!empty($facility->DHIS2_UID))
                $time = date('Y-m-d H:i:s');

                Log::error("$time Skipping Facility ID: $facility->id,  Facility Name: $facility->name has no DHIS2_UID");
                $this->getOutput()->writeln("<error>$time Skipping facility:</error> Facility ID: $facility->id, Facility name: $facility->name has no DHIS2_UID");
            }
        } //End foreach($facilities as $facility)

        $script_end_time = date('Y-m-d H:i:s');
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);
        Log::info("$script_end_time Script completed loading data from DHIS2 Covax instance: Duration: $script_run_time Number of Events: $number_of_events");
        $this->getOutput()->writeln("<info>$script_end_time Script completed:</info> Completed loading $number_of_events events from DHIS2 Covax instance. Duration: $script_run_time");
    }

    /**
     * Execute the console command."
     *
     * @return int
     */
    public function handle()
    {
        $programStartDate = $this->argument('programStartDate');
        $programEndDate = $this->argument('programEndDate');

        self::loadEvents($programStartDate, $programEndDate);

        return Command::SUCCESS;
    }
}
