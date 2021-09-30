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

    public function getTrackedEntityInstance($tracked_entity_instance_uid, $facility_id): ?Client
    {
        $httpClient = new GuzzleHttp\Client();

        $response = $httpClient->request('GET', self::TRACKED_ENTITY_INSTANCES_URL."?trackedEntityInstance={$tracked_entity_instance_uid}", [
            'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
        ]);

        $client = null;

        if ($response->getStatusCode() == 200){
            $responce_body = json_decode($response->getBody(), true);
            $trackedEntityInstance = $responce_body['trackedEntityInstances'][0];
            // Anonymous DB Transaction function

            $client_id = $trackedEntityInstance['trackedEntityInstance'];
            // Check if hash of $trackedEntityInstance exist in import log
            $import_log = ImportLog::where('hash', Hash::make(json_encode($trackedEntityInstance)))->first();

            if(empty($import_log)){
                //Store data in reocrd table
                $record = new Record([
                    'data_source' => 'MOH_DHIS2_COVAX',
                    'data_type' => 'TRACKED_ENTITY_INSTANCE',
                    'data' => json_encode($trackedEntityInstance),
                ]);

                $record->save();

                //Create a client record
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

                foreach($trackedEntityInstance['attributes'] as $attribute){
                    if($attribute['attribute'] == 'zUQCBnWbBer') //Attribute ID for Card Number
                        $card_number = $attribute['value'];
                    else if($attribute['attribute'] == 'Ewi7FUfcHAD') //Attribute ID for NRC
                        $NRC = $attribute['value'];
                    else if($attribute['attribute'] == 'pd02AeZHXWi') //Attribute ID for Passport Number
                        $passport_number = $attribute['value'];
                    else if($attribute['attribute'] == 'TfdH5KvFmMy') //Attribute ID for First Name
                        $first_name = $attribute['value'];
                    else if($attribute['attribute'] == 'aW66s2QSosT') //Attribute ID for Surname
                        $last_name = $attribute['value'];
                    else if($attribute['attribute'] == 'CklPZdOd6H1')  //Attribute ID for Sex
                        $sex = $attribute['value'][0];
                    else if($attribute['attribute'] == 'mAWcalQYYyk')  //Attribute ID for Age
                        $date_of_birth = $attribute['value'];
                    else if($attribute['attribute'] == 'ciCR6BBvIT4')  //Attribute ID for Mobile phone number
                        $contact_number = $attribute['value'];
                    else if($attribute['attribute'] == 'VCtm2pySeEV')  //Attribute ID for Address (current)
                        $address_line1 = $attribute['value'];
                    else if($attribute['attribute'] == 'LY2bDXpNvS7')  //Attribute ID for Occupation
                        $occupation = $attribute['value'];
                }

                $client = new Client([
                    'client_id' => $client_id,
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

                // Create record in ImportLog table
                ImportLog::create([
                    'hash' => Hash::make(json_encode($trackedEntityInstance)),
                ]);
            }
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

        foreach($facilities as $facility){
            if(!empty($facility->DHIS2_UID)){
                try {
                    Log::info('Loading data from: '.self::EVENTS_URL);

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
                            $startTime = microtime(true);

                            $this->getOutput()
                                ->writeln("<comment>Facility: {$facility->DHIS2_UID}, {$facility->id} Saving data for event:</comment> {$event['event']} <comment>for tracked entity instance:</comment> {$event['trackedEntityInstance']}");

                            $event_uid = $event['event'];
                            $tracked_entity_instance_uid = $event['trackedEntityInstance'];
                            $client = Client::where('client_id', $tracked_entity_instance_uid)->first();

                            if(empty($client)){
                                $this->getOutput()->writeln("<comment>\t Getting {$event['trackedEntityInstance']} tracked entity instance for event:</comment> {$event['event']}");
                                $client = self::getTrackedEntityInstance($tracked_entity_instance_uid, $facility->id);
                                $this->getOutput()->writeln("<info>\t Client saved:</info> {$client->id}");
                            }

                            if($event['status'] == 'COMPLETED') { //Process only completed events

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
                                    $dose_number = "First";
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
                                                    $dose_number = 'First';
                                                    break;
                                                case 'DOSE2':
                                                    $dose_number = 'Second';
                                                    break;
                                                case 'DOSE3':
                                                    $dose_number = 'Third';
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
                                }
                            } else {
                                $this->getOutput()->writeln("<info>Skipping event:</info> {$event['event']} because it is not COMPLETE!");
                            }
                        }
                    }//End if ($response->getStatusCode() == 200)
                } catch (TransferException $e) {
                    // you can catch here 400 response errors and 500 response errors
                    // You can either use logs here use Illuminate\Support\Facades\Log;
                    $error['error'] = $e->getMessage();
                    $error['request'] = $e->getRequest();
                    if($e->hasResponse()){
                        if ($e->getResponse()->getStatusCode() == '400'){
                            $error['response'] = $e->getResponse();
                        }
                    }
                    Log::error('Error occurred in get request.', ['error' => $error]);
                }catch(Exception $e){
                    //other errors
                }
            } //if(!empty($facility->DHIS2_UID))
        } //End foreach($facilities as $facility)
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // self::loadTrackedEntityInstances();
        self::loadEvents();
        return 0;
    }
}
