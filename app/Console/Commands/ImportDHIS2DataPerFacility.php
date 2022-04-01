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
 * The script ImportDHIS2DataPerFacility.php
 *
 * This script fetches DHIS2 Data per Facility, via a passed DHIS2 UID and a speficied period, start and end dates respectively.
 * @package IR
 * @subpackage Commands
 * @access public
 * @author Mullenga Chiwelle <Mulenga.Chiwele@MOH.gov.zm>
 * @copyright Copyright &copy; 2022 Ministry of Health, GRZ.
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

class ImportDHIS2DataPerFacility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ImportDHIS2DataPerFacility {startDate} {endDate} {facilityDhis2Uid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import DHIS2 Data per Facility from COVAX instance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function getTimestampFromString($_date_time)
    {
        $_date_time_ = strtotime($_date_time);
        return date('Y-m-d H:i:s', $_date_time_);
    }

    public function getTrackedEntityInstance($_httpClient, $_tracked_entity_instance_uid)
    {
        $_response = $_httpClient->request('GET', env('DHIS2_BASE_URL'). "trackedEntityInstances.json?trackedEntityInstance={$_tracked_entity_instance_uid}", [
            'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
        ]);

        if ($_response->getStatusCode() == 200) {
            $_response_body = json_decode($_response->getBody(), true);
            return $_response_body['trackedEntityInstances'][0];
        };
        return json_encode(json_decode ("{}"));
    }

    public function saveRecord($_source_id, $_data_type, $_data): ? Record
    {
        //_source_id is passed seperately as it is retrieved differently | event vs tracked_entity_instance
        $_record =  new Record([
            'record_id' => $_source_id,
            'data_source' => 'MOH_DHIS2_COVAX',
            'data_type' => $_data_type,
            'hash' => sha1(json_encode($_data)),
            'data' => json_encode($_data)
        ]);

        $_record -> save();

        return $_record;
    }

    public function updateRecord($_old_record, $_updated_data): ? Record
    {
        //only 'hash' and 'data' fields are to be updated, unless otherwise.
        $_old_record->hash = sha1(json_encode($_updated_data));
        $_old_record->data = json_encode($_updated_data);

        $_old_record->update();

        return $_old_record;
    }

    public function assignCommonClientFields($_client, $_tracked_entity_instance, $_facility_id): ? Client
    {
        foreach ($_tracked_entity_instance['attributes'] as $attribute) {
            if ($attribute['attribute'] == 'zUQCBnWbBer') //Tracked Entity Attribute UID for Card Number
                $_client->card_number = $attribute['value'];
            else if ($attribute['attribute'] == 'Ewi7FUfcHAD') //Tracked Entity Attribute UID for NRC
                $_client->NRC = $attribute['value'];
            else if ($attribute['attribute'] == 'pd02AeZHXWi') //Tracked Entity Attribute UID for Passport Number
                $_client->passport_number = $attribute['value'];
            else if ($attribute['attribute'] == 'TfdH5KvFmMy') //Tracked Entity Attribute UID for First Name
                $_client->first_name = ucfirst(strtolower(trim($attribute['value'])));
            else if ($attribute['attribute'] == 'aW66s2QSosT') //Tracked Entity Attribute UID for Surname
                $_client->last_name = ucfirst(strtolower(trim($attribute['value'])));
            else if ($attribute['attribute'] == 'Bag3HrPOKRm') //Tracked Entity Attribute UID for Other Names
                $_client->other_names = ucfirst(strtolower(trim($attribute['value'])));
            else if ($attribute['attribute'] == 'CklPZdOd6H1')  //Tracked Entity Attribute UID for Sex
                $_client->sex = $attribute['value'][0];
            else if ($attribute['attribute'] == 'mAWcalQYYyk')  //Tracked Entity Attribute UID for DOB
                $_client->date_of_birth = $attribute['value'];
            else if ($attribute['attribute'] == 'ciCR6BBvIT4')  //Tracked Entity Attribute UID for Mobile phone number
                $_client->contact_number = $attribute['value'];
            else if ($attribute['attribute'] == 'ctpwSFedWFn')  //Tracked Entity Attribute UID for Email Address
                $_client->contact_email_address = $attribute['value'];
            else if ($attribute['attribute'] == 'VCtm2pySeEV')  //Tracked Entity Attribute UID for Address (current)
                $_client->address_line1 = $attribute['value'];
            else if ($attribute['attribute'] == 'gB3BrfkEmkC') //Tracked Entity Attribute UID for Guardian's NRC
                $_client->guardian_NRC = $attribute['value'];
            else if ($attribute['attribute'] == 'TodvbRCs4La') //Tracked Entity Attribute UID for Guardian's Passport Number
                $_client->guardian_passport_number = $attribute['value'];
            else if ($attribute['attribute'] == 'LY2bDXpNvS7')  //Tracked Entity Attribute UID for Occupation
                $_client->occupation = $attribute['value'];
        }
        $_client->facility_id = $_facility_id;
        $_client->source_created_at = $_tracked_entity_instance['created'];
        $_client->source_updated_at = $_tracked_entity_instance['lastUpdated'];

        return $_client;
    }

    public function saveClient($_tracked_entity_instance, $_facility_id, $_record_id): ? Client
    {
        $_client = new Client();
        $_client->source_id = $_tracked_entity_instance['trackedEntityInstance'];
        $_client->record_id = $_record_id;

        $_client = self::assignCommonClientFields($_client, $_tracked_entity_instance, $_facility_id);

        $_client->save(); //Save new client

        $_time = date('Y-m-d H:i:s');
        $this->getOutput()->writeln("{$_time} <info>SAVED Client ID Number:</info> {$_client->id} <info>UID:</info> {$_client->source_id} <info>First Name:</info> {$_client->first_name} <info>Surname:</info> {$_client->last_name} <info>DOB:</info> {$_client->date_of_birth} <info>Sex:</info> {$_client->sex} <info>Created At:</info> {$_client->source_created_at} <info>Facility:</info> {$_client->facility_id}");

        return $_client;
    }

    public function updateClient($_client, $_tracked_entity_instance, $_facility_id): ? Client
    {
        $_client = self::assignCommonClientFields($_client, $_tracked_entity_instance, $_facility_id);

        $_client->update(); //Update client info

        $_tracked_entity_instance_json = json_encode($_tracked_entity_instance, JSON_UNESCAPED_SLASHES);
        $_time = date('Y-m-d H:i:s');
        $this->getOutput()->writeln("{$_time} <info>UPDATED Client ID Number:</info> {$_client->id} <info>UID:</info> {$_client->source_id} <info>First Name:</info> {$_client->first_name} <info>Surname:</info> {$_client->last_name} <info>DOB:</info> {$_client->date_of_birth} <info>Sex:</info> {$_client->sex} <info>Created At:</info> {$_client->source_created_at} <info>Facility:</info> {$_client->facility_id}");

        return $_client;
    }

    public function assignCommonVaccinationFields($_vaccination, $_event, $_facility_id): ? Vaccination
    {
        $_vaccination->date = $_event['eventDate'];

        switch ($_event['programStage']) {
            case 'a1jCssI2LkW': //programStage: Vaccination Dose 1
                $_vaccination->dose_number = '1';
                break;
            case 'RiV7VDxXQLN': //programStage: Vaccination Dose 2
                $_vaccination->dose_number = '2';
                break;
            case 'jatC7jRwVKO': //programStage: Vaccination Booster Dose
                $_vaccination->dose_number = 'Booster';
                break;
        }

        foreach ($_event['dataValues'] as $dataValue) {
            if ($dataValue['dataElement'] == 'bbnyNYD1wgS') { //Vaccine Name
                switch ($dataValue['value']) {
                    case 'AstraZeneca_zm':
                        $_vaccination->vaccine_id = 1;
                        break;
                    case 'Johnson_Johnsons_zm':
                        $_vaccination->vaccine_id = 3;
                        break;
                    case 'Sinopharm':
                        $_vaccination->vaccine_id = 7;
                        break;
                    case 'Pfizer':
                        $_vaccination->vaccine_id = 6;
                        break;
                    case 'Moderna':
                        $_vaccination->vaccine_id = 4;
                        break;
                }
            } else if ($dataValue['dataElement'] == 'Yp1F4txx8tm') { //Batch Number
                $_vaccination->vaccine_batch_number = $dataValue['value'];
            } else if ($dataValue['dataElement'] == 'FFWcps4MfuH') { //Suggested date for next dose
                $_vaccination->date_of_next_dose = $dataValue['value'];
            }
        }

        $_vaccination->facility_id = $_facility_id;
        $_vaccination->source_created_at = $_event['created'];
        $_vaccination->source_updated_at = $_event['lastUpdated'];

        return $_vaccination;
    }

    public function saveVaccination($_event, $_client_id, $_facility_id, $_record_id): ? Vaccination
    {
        $_vaccination = new Vaccination();

        $_vaccination->client_id = $_client_id;
        $_vaccination->record_id = $_record_id;
        $_vaccination->source_id = $_event['event'];

        $_vaccination = self::assignCommonVaccinationFields($_vaccination, $_event, $_facility_id);

        $_vaccination->save();

        $_time = date('Y-m-d H:i:s');
        $this->getOutput()->writeln("{$_time} <info>SAVED Vaccination, ID Number:</info> {$_vaccination->id}, <info>UID :</info> {$_vaccination->source_id}, <info>Client ID:</info> {$_vaccination->client_id}, <info>Dose:</info> {$_vaccination->dose_number}, <info>Event Date:</info> {$_vaccination->date}, <info>Facility:</info> {$_vaccination->facility_id}");

        return $_vaccination;
    }

    public function updateVaccination($_vaccination, $_event, $_facility_id): ? Vaccination
    {
        $_vaccination = self::assignCommonVaccinationFields($_vaccination, $_event, $_facility_id);

        $_vaccination->update();

        $_time = date('Y-m-d H:i:s');
        $this->getOutput()->writeln("{$_time} <info>UPDATED Vaccination, ID Number:</info> {$_vaccination->id}, <info>UID :</info> {$_vaccination->source_id}, <info>Client ID:</info> {$_vaccination->client_id}, <info>Dose:</info> {$_vaccination->dose_number}, <info>Event Date:</info> {$_vaccination->date}, <info>Facility:</info> {$_vaccination->facility_id}");

        return $_vaccination;
    }

    public function loadEvents($startDate, $endDate, $facilityDhis2Uid): array
    {
        $httpClient = new GuzzleHttp\Client();

        $facility = Facility::where('DHIS2_UID', $facilityDhis2Uid)->first();; //Get Facility via the supplied DHIS2 UID
        $total_number_of_events = 0; //Total events counter
        $total_number_of_saved_events = 0; //Saved events counter
        $total_number_of_updated_events = 0; //Updated events counter

        if (!empty($facility)) {
            $_time = date('Y-m-d H:i:s');
            $this->getOutput()->writeln("{$_time} <info>Loading data from Facility Name:</info> {$facility->name}, <info>Facility DHIS2 UID:</info> {$facility->DHIS2_UID}, <info>Facility ID:</info> {$facility->id}");

            try {
                $response = $httpClient->request('GET', env('DHIS2_BASE_URL')."events.json", [
                    'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                    'query' => [
                        'orgUnit' => $facility->DHIS2_UID,
                        'program' => 'yDuAzyqYABS',
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'totalPages' => true,
                        'pageSize' => 100
                    ]
                ]);

                if ($response->getStatusCode() == 200) {
                    $time = date('Y-m-d H:i:s');
                    $this->getOutput()->writeln("{$time} <comment>Before json_decode</comment>");

                    $response_body = json_decode($response->getBody(), true);
                    $pageCount = $response_body['pager']['pageCount'];
                    
                    $response_body_pager = json_encode($response_body['pager']);
                    $time = date('Y-m-d H:i:s');
                    $this->getOutput()->writeln("{$time} <comment>OUTER Response Body Pager :</comment> {$response_body_pager}");

                    for ($i = 1; $i <= $pageCount; $i++) {
                        $response = $httpClient->request('GET', env('DHIS2_BASE_URL')."events.json", [
                            'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                            'query' => [
                                'orgUnit' => $facility->DHIS2_UID,
                                'program' => 'yDuAzyqYABS',
                                'startDate' => $startDate,
                                'endDate' => $endDate,
                                'page' => $i,
                                'skipMeta' => true
                            ]
                        ]);

                        if ($response->getStatusCode() == 200) {
                            $response_body = json_decode($response->getBody(), true);

                            $response_body_pager = json_encode($response_body['pager']);
                            $time = date('Y-m-d H:i:s');
                            $this->getOutput()->writeln("{$time} <comment>INNER Response Body Pager :</comment> {$response_body_pager}");

                            foreach ($response_body['events'] as $event) {
                                $total_number_of_events++;
                                $event_uid = $event['event'];
        
                                if ($event['status'] == "SCHEDULE" || $event['status'] == "SKIPPED" || $event['status'] == "OVERDUE") {
                                    $time = date('Y-m-d H:i:s');
                                    $this->getOutput()->writeln("{$time} <comment>SKIPPING Event UID:</comment> {$event_uid}, <comment>because event is either SCHEDULED | SKIPPED!</comment>");
                                    continue;
                                }

                                try {
                                    $startTime = microtime(true);
                                    //Initialise transaction
                                    DB::beginTransaction();

                                    //Preliminary assignments
                                    $tracked_entity_instance_uid = $event['trackedEntityInstance'];
                                    $client = Client::where('source_id', $tracked_entity_instance_uid)->first();
                                    //Get latest tracked entity instance
                                    $tracked_entity_instance = self::getTrackedEntityInstance($httpClient, $tracked_entity_instance_uid);

                                    if (empty($client)) {
                                        //An if statement here perhaps to check if the tracked_entity
                                        $client_side_source_id = $tracked_entity_instance['trackedEntityInstance'];

                                        $new_client_side_record = self::saveRecord($client_side_source_id, 'TRACKED_ENTITY_INSTANCE', $tracked_entity_instance);

                                        $client = self::saveClient($tracked_entity_instance, $facility->id, $new_client_side_record->id);
                                    } else {
                                        $source_client_last_updated = self::getTimestampFromString($tracked_entity_instance['lastUpdated']);
                                        $source_client_created = self::getTimestampFromString($tracked_entity_instance['created']);

                                        if ((empty($client->source_created_at) || empty($client->source_updated_at)) ||
                                            (($client->source_updated_at < $source_client_last_updated) && ($client->source_created_at == $source_client_created))) {
                                            //get the existing record
                                            $old_client_side_record = Record::where('record_id', $client->source_id)->first();

                                            $updated_client_side_record = self::updateRecord($old_client_side_record,  $tracked_entity_instance);

                                            //probably an if statement here
                                            $client = self::updateClient($client, $tracked_entity_instance, $facility->id);
                                        } else {
                                            $time = date('Y-m-d H:i:s');
                                            $this->getOutput()->writeln("{$time} <comment>SKIPPING Client UID:</comment> {$client->source_id}, <comment>as record is still upto date</comment>");
                                        }
                                    }

                                    $vaccination = Vaccination::where('source_id', $event_uid)->first();

                                    if (!empty($vaccination)) {
                                        $source_event_last_updated = self::getTimestampFromString($event['lastUpdated']);
                                        $source_event_created = self::getTimestampFromString($event['created']);

                                        //Check for last updated ? Vaccination Update logic kicks in
                                        if ((empty($vaccination->source_created_at) || empty($vaccination->source_updated_at)) ||
                                            (($vaccination->source_updated_at < $source_event_last_updated) && ($vaccination->client_id == $client->id) && ($vaccination->source_created_at == $source_event_created))) {
                                            $old_event_side_record = Record::where('record_id', $event_uid)->first();
                                            //Perhaps an if statement
                                            $updated_event_side_record = self::updateRecord($old_event_side_record, $event);

                                            $vaccination = self::updateVaccination($vaccination, $event, $facility->id);

                                            $total_number_of_updated_events++;
                                            $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                                            $time = date('Y-m-d H:i:s');
                                            $this->getOutput()->writeln("{$time} <info>UPDATING Event total:</info> {$total_number_of_events} ({$runTime}ms), <info>Event UID :</info> {$event_uid}");
                                        } else {
                                            $time = date('Y-m-d H:i:s');
                                            $this->getOutput()->writeln("{$time} <comment>SKIPPING Event UID:</comment> {$event_uid}, <comment>because event already exists in the DATABASE!</comment>");
                                        }
                                    } else {
                                        //Store new even data in record table
                                        $new_event_side_record = self::saveRecord($event_uid, 'EVENT', $event);
                                        //Store new client, record and event data in the vaccination table
                                        $vaccination = self::saveVaccination($event, $client->id, $facility->id, $new_event_side_record->id);

                                        $total_number_of_saved_events++;
                                        $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                                        $event = json_encode($event, JSON_UNESCAPED_SLASHES);
                                        $time = date('Y-m-d H:i:s');
                                        $this->getOutput()->writeln("{$time} <info>SAVING Event:</info> {$total_number_of_events}, <info>Event UID:</info> {$event_uid}, <info>Facility:</info> {$facility->name}, <info>Facility UID:</info> {$facility->DHIS2_UID}, <info>TRACKED_ENTITY_INSTANCE:</info> {$tracked_entity_instance_uid}");
                                    }

                                    DB::commit(); //if no error on record, vaccination and importlog commit data to database
                                    $this->getOutput()->writeln("{$time} <info>PERSISTED Event:</info> {$event_uid} <info>to the DATABASE!</info>");

                                } catch (QueryException $e) {
                                    DB::rollback(); //Rollback database transaction if any error occurs

                                    $message = $e->getMessage();
                                    $time = date('Y-m-d H:i:s');
                                    $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                                    Log::error("$time QueryException: $message \n $event");
                                    $this->getOutput()->writeln("<error>{$time} QueryException on Event Number {$total_number_of_events}: $message \n $event</error>");
                                } catch (Exception $e) {
                                    DB::rollback(); //Rollback database transaction if any error occurs

                                    $message = $e->getMessage();
                                    $time = date('Y-m-d H:i:s');
                                    $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                                    Log::error("$time Exception: $message \n $event");
                                    $this->getOutput()->writeln("<error>{$time} Exception on event number {$total_number_of_events}: $message \n $event</error>");
                                }
                            } //End foreach
                        }//End if ($response->getStatusCode() == 200)
                    }//End $i = 1; $i <= $pageCount; $i++
                }//End $response->getStatusCode() == 200

            } catch (ConnectException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( " $time ConnectException: $message");
                $this->getOutput()->writeln("<error>{$time} ConnectException on event number $total_number_of_events: $message</error>");
            } catch (RequestException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( "$time RequestException - $message");
                $this->getOutput()->writeln("<error>{$time} RequestException on event number $total_number_of_events: $message</error>");
            } catch (TransferException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');

                Log::error( "$time TransferException: $message");
                $this->getOutput()->writeln("<error>{$time} TransferException on event number $total_number_of_events: $message</error>");
            } catch (GuzzleException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                Log::error( "$time Exception: $message \n $event");
                Log::error($event);
                $this->getOutput()->writeln("<error>{$time} GuzzleException on event number $total_number_of_events: $message \n $event</error>");
            } catch (Exception $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                $event = json_encode($event, JSON_UNESCAPED_SLASHES);

                Log::error( "$time Exception: $message \n $event");
                $this->getOutput()->writeln("<error>{$time} Exception on event number $total_number_of_events: $message \n $event</error>");
            }

        } else {
            $time = date('Y-m-d H:i:s');

            Log::error("$time Facility with DHIS2 UID: {$facilityDhis2Uid}, NOT FOUND. UID is Invalid or Does Not Exist!");
            $this->getOutput()->writeln("$time <error>Facility with DHIS2 UID: {$facilityDhis2Uid}, NOT FOUND. UID is Invalid or Does Not Exist!");
        }

        return array($total_number_of_saved_events, $total_number_of_events, $total_number_of_updated_events);

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

        Log::info("$script_start_date_time: Loading Per Facility Data from the DHIS2 Covax instance");
        $this->getOutput()->writeln("<info>$script_start_date_time Script started - Loading Per Facility Data from the DHIS2 Covax instance</info>");

        $startDate = $this->argument('startDate');
        $endDate = $this->argument('endDate');
        $facilityDhis2Uid = $this->argument('facilityDhis2Uid');

        $results = self::loadEvents($startDate, $endDate, $facilityDhis2Uid);

        $script_end_time = date('Y-m-d H:i:s');
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);

        Log::info("$script_end_time Script completed loading Per Facility Data from the DHIS2 Covax instance: Duration: $script_run_time Number of Events: Saved - $results[0], Updated - $results[2] of $results[1]");
        $this->getOutput()->writeln("<info>$script_end_time Script completed:</info> Completed loading Per Facility $results[0] and updated $results[2] of $results[1] events from the DHIS2 Covax instance. Duration: $script_run_time");

        $this->info('command:ImportDHIS2DataPerFacility Command Run successfully!');

        return Command::SUCCESS;
    }
}
