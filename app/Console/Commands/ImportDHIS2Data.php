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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
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
    protected $signature = 'command:ImportDHIS2Data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import DHIS2 Data from COVAX instance';

    /**
     * Base URL for trackedEntityInstances
     */
    const TRACKED_ENTITY_INSTANCES_URL = 'https://dhis2.moh.gov.zm/covax/api/trackedEntityInstances.json';

    /**
     * Base URL for enrollments
     */
    const ENROLLMENTS_URL = 'https://dhis2.moh.gov.zm/covax/api/enrollments.json';

    /**
     * Base URL for events
     */
    const EVENTS_URL = 'https://dhis2.moh.gov.zm/covax/api/events.json';

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
        $client = null;
        $httpClient = new GuzzleHttp\Client();

        try {
            $response = $httpClient->request('GET', self::TRACKED_ENTITY_INSTANCES_URL . "?trackedEntityInstance={$tracked_entity_instance_uid}", [
                'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
            ]);
            if ($response->getStatusCode() == 200) {
                $responce_body = json_decode($response->getBody(), true);
                $trackedEntityInstance = $responce_body['trackedEntityInstances'][0];

                $client_uid = $trackedEntityInstance['trackedEntityInstance'];

                //            $this->getOutput()->writeln("<comment>\t Getting {$client_uid} tracked entity instance");

                $card_number = "";
                $NRC = "";
                $passport_number = "";
                $first_name = "";
                $last_name = "";
                $sex = "";
                $date_of_birth = null;
                $contact_number = "";
                $address_line1 = "";
                $occupation = "";

                foreach ($trackedEntityInstance['attributes'] as $attribute) {
                    if ($attribute['attribute'] == 'zUQCBnWbBer') //Attribute ID for Card Number
                        $card_number = $attribute['value'];
                    else if ($attribute['attribute'] == 'Ewi7FUfcHAD') //Attribute ID for NRC
                        $NRC = $attribute['value'];
                    else if ($attribute['attribute'] == 'pd02AeZHXWi') //Attribute ID for Passport Number
                        $passport_number = $attribute['value'];
                    else if ($attribute['attribute'] == 'TfdH5KvFmMy') //Attribute ID for First Name
                        $first_name = ucfirst(strtolower(trim($attribute['value'])));
                    else if ($attribute['attribute'] == 'aW66s2QSosT') //Attribute ID for Surname
                        $last_name = ucfirst(strtolower(trim($attribute['value'])));
                    else if ($attribute['attribute'] == 'CklPZdOd6H1')  //Attribute ID for Sex
                        $sex = $attribute['value'][0];
                    else if ($attribute['attribute'] == 'mAWcalQYYyk')  //Attribute ID for Age
                        $date_of_birth = $attribute['value'];
                    else if ($attribute['attribute'] == 'ciCR6BBvIT4')  //Attribute ID for Mobile phone number
                        $contact_number = $attribute['value'];
                    else if ($attribute['attribute'] == 'VCtm2pySeEV')  //Attribute ID for Address (current)
                        $address_line1 = $attribute['value'];
                    else if ($attribute['attribute'] == 'LY2bDXpNvS7')  //Attribute ID for Occupation
                        $occupation = $attribute['value'];
                }

                //Store data in record table
                $record = new Record([
                    'data_source' => 'MOH_DHIS2_COVAX',
                    'data_type' => 'TRACKED_ENTITY_INSTANCE',
                    'data' => json_encode($trackedEntityInstance),
                ]);

                $record->save();

                //Create new client
                $client = new Client([
                    'client_uid' => $client_uid,
                    'card_number' => $card_number,
                    'NRC' => $NRC,
                    'passport_number' => $passport_number,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'sex' => $sex,
                    'date_of_birth' => $date_of_birth,
                    'contact_number' => $contact_number,
                    'address_line1' => $address_line1,
                    'occupation' => $occupation,
                    'facility_id' => $facility_id,
                    'record_id' => $record->id,
                ]);

                $client->save();

                $this->getOutput()->writeln("<info>\t Client saved:</info> {$client->id}");

                // Create record in ImportLog table
                ImportLog::create([
                    'hash' => Hash::make(json_encode($trackedEntityInstance)),
                ]);
            }
        } catch (ConnectException $e) {
            // Connection exceptions are not caught by RequestException
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');
            Log::error( " $time: ConnectException - $message");
            $this->getOutput()->writeln("<error>$time: $message </error>");
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');
            Log::error( "$time: RequestException - $message");
            $this->getOutput()->writeln("<error>$time: RequestException: $message</error>");
        } catch (TransferException $e) {
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');
            Log::error( "$time: TransferException: $message");
            $this->getOutput()->writeln("<error>$time: TransferException: $message</error>");
        }catch (Exception $e) {
            $message = $e->getMessage();
            $time = date('Y-m-d H:i:s');
            Log::error( "$time: Exception: $message");
            $this->getOutput()->writeln("<error>$time: Exception: $message</error>");
        }
        return $client;
    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function loadEvents(){
        $httpClient = new GuzzleHttp\Client();

        // disable cert verification
        // $client->setDefaultOption(['verify'=>false]);

        $facilities = Facility::all();
        $number_of_events = 0;
        $script_start_time = microtime(true);
        $script_start_date_time = date('Y-m-d H:i:s');

        foreach($facilities as $facility){
            if(!empty($facility->DHIS2_UID)){
                try {

                    Log::info("$script_start_date_time: Loading data from DHIS2 Covax instance");
                    $this->getOutput()->writeln("<info>$script_start_date_time: Script started - Loading data from DHIS2 Covax instance</info>");

                    $response = $httpClient->request('GET', self::EVENTS_URL, [
                        'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                        'query' => [
                            'orgUnit'=>$facility->DHIS2_UID,
                            'program'=>'yDuAzyqYABS',
                            'programStartDate'=>'2020-04-01',
                            'programEndDate'=>'2021-08-27'
                        ]
                    ]);

                    if ($response->getStatusCode() == 200){
                        $response_body = json_decode($response->getBody(), true);

                        foreach($response_body['events'] as $event){
                            $number_of_events++;
                            $startTime = microtime(true);

                            if($event['status'] == 'COMPLETED') { //Process only completed events
                                $this->getOutput()
                                    ->writeln("<comment>Facility: {$facility->DHIS2_UID}, {$facility->id} Saving data for event:</comment> {$event['event']} <comment>for tracked entity instance:</comment> {$event['trackedEntityInstance']}");

                                $event_uid = $event['event'];
                                $tracked_entity_instance_uid = $event['trackedEntityInstance'];
                                $client = Client::where('client_uid', $tracked_entity_instance_uid)->first();

                                if(empty($client)){
                                    $client = self::getTrackedEntityInstance($tracked_entity_instance_uid, $facility->id);
                                }

                                // Check if hash of $event exist in import log
                                $import_log = ImportLog::where('hash', Hash::make(json_encode($event)))->first();

                                if(empty($import_log)){
                                    //Store data in record table
                                    $record = new Record([
                                        'data_source' => 'MOH_DHIS2_COVAX',
                                        'data_type' => 'EVENT',
                                        'data' => json_encode($event),
                                    ]);

                                    $record->save();

                                    //Create and save new vaccination event
                                    $vaccine_id = null;
                                    $dose_number = '1';
                                    $date_of_next_dose = null;

                                    foreach($event['dataValues'] as $dataValue){
                                        if($dataValue['dataElement'] == 'bbnyNYD1wgS'){ //Vaccine Name
                                            switch($dataValue['value']){
                                                case 'AstraZeneca_zm':
                                                    $vaccine_id = 1;
                                                    break;
                                                case 'Johnson_Johnsons_zm':
                                                    $vaccine_id = 3;
                                                    break;
                                                case 'Sinopharm':
                                                    $vaccine_id = 7;
                                                    break;
                                            }
                                        } else if ($dataValue['dataElement'] == 'LUIsbsm3okG'){ // Dose Number
                                            switch($dataValue['value']){
                                                case 'DOSE1':
                                                    $dose_number = '1';
                                                    break;
                                                case 'DOSE2':
                                                    $dose_number = '2';
                                                    break;
                                                case 'DOSE3':
                                                    $dose_number = '3';
                                                    break;
                                                case 'BOOSTER':
                                                    $dose_number = 'Booster';
                                                    break;
                                            }
                                        } else if ($dataValue['dataElement'] == 'FFWcps4MfuH'){ //Suggested date for next dose
                                            $date_of_next_dose = $dataValue['value'];
                                        }
                                    }

                                    if (!empty($vaccine_id)) {
                                        $vaccination = new Vaccination([
                                            'client_id' => $client->id,
                                            'vaccine_id' => $vaccine_id,
                                            'date' => $event['eventDate'],
                                            'dose_number' => $dose_number,
                                            'date_of_next_dose' => $date_of_next_dose,
                                            'facility_id' => $facility->id,
                                            'event_id' => $event_uid,
                                            'record_id' => $record->id,
                                        ]);
                                        $vaccination->save();
                                    }

                                    // Create record in ImportLog table
                                    ImportLog::create([
                                        'hash' => Hash::make(json_encode($event)),
                                    ]);

                                    $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                                    $this->getOutput()->writeln("<info>Event saved:</info> {$event['event']} ({$runTime}ms)");
                                } else {
                                    $this->getOutput()->writeln("<info>Skipping event:</info> {$event['event']} because event already exist in the DATABASE!");
                                    Log::info("\t Skipping event: {$event['event']} because event already exist in the DATABASE!");
                                }
                            } else {
                                $this->getOutput()->writeln("<info>Skipping event:</info> {$event['event']} because it is not COMPLETE!");
                                Log::info("\t Skipping event: {$event['event']} because it is not COMPLETE!");
                            }
                        }
                    }//End if ($response->getStatusCode() == 200)
                } catch (ConnectException $e) {
                    // Connection exceptions are not caught by RequestException
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    Log::error( " $time: ConnectException - $message");
                    $this->getOutput()->writeln("<error>$time: $message </error>");
                } catch (RequestException $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    Log::error( "$time: RequestException - $message");
                    $this->getOutput()->writeln("<error>$time: RequestException: $message</error>");
                } catch (TransferException $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    Log::error( "$time: TransferException: $message");
                    $this->getOutput()->writeln("<error>$time: TransferException: $message</error>");
                }catch (Exception $e) {
                    $message = $e->getMessage();
                    $time = date('Y-m-d H:i:s');
                    Log::error( "$time: Exception: $message");
                    $this->getOutput()->writeln("<error>$time: Exception: $message</error>");
                }
            } else {//if(!empty($facility->DHIS2_UID))
                Log::alert(date('Y-m-d H:i:s').": $facility has no DHIS2_UID");
                $this->getOutput()->writeln("<info>Skipping facility:</info>
                    Facility ID: $facility->id, Facility name: $facility->name has no DHIS2_UID");
            }
        } //End foreach($facilities as $facility)

        $script_start_end_time = date('Y-m-d H:i:s');
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);
        Log::info("$script_start_end_time: Completed loading data from DHIS2 Covax instance: Duration: $script_run_time Number of Events: $number_of_events");
        $this->getOutput()->writeln("<info>Script completed:</info> Completed loading data from DHIS2 Covax instance. Duration: $script_run_time");
    }

    /**
     * Execute the console command."
     *
     * @return int
     */
    public function handle()
    {
        self::loadEvents();
        return Command::SUCCESS;
    }
}
