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
    protected $signature = 'command:ImportDHIS2Data {startDate} {endDate}';

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

    public function loadEvents($startDate, $endDate): array
    {
        $httpClient = new GuzzleHttp\Client();
        $utility = new Utilities();
        $persistRecord = new PersistRecord();
        $persistClient = new PersistClient();
        $persistVaccination = new PersistVaccination();

        $facilities = Facility::all(); //Get all facilities from database
        $total_number_of_events = 0; //Total events counter
        $total_number_of_saved_events = 0; //Saved events counter
        $total_number_of_updated_events = 0; //Updated events counter

        foreach($facilities as $facility) {
            $time = date('Y-m-d H:i:s');
            $this->getOutput()->writeln("{$time} <info>Loading data from Facility Name:</info> {$facility->name}, <info>Facility DHIS2 UID:</info> {$facility->DHIS2_UID}, <info>Facility ID:</info> {$facility->id}");

            if(!empty($facility->DHIS2_UID)) {
                try {
                    $response = $httpClient->request('GET', env('DHIS2_BASE_URL')."events.json", [
                        'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                        'query' => [
                            'orgUnit' => $facility->DHIS2_UID,
                            'program' => 'yDuAzyqYABS',
                            'startDate' => $startDate,
                            'endDate' => $endDate,
                            'totalPages' => true
                        ]
                    ]);

                    if ($response->getStatusCode() == 200) {
                        $response_body = json_decode($response->getBody(), true);
                        $pageCount = $response_body['pager']['pageCount'];

                        for ($i = 1; $i <= $pageCount; $i++) {
                            $response = $httpClient->request('GET', env('DHIS2_BASE_URL') . "events.json", [
                                'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                                'query' => [
                                    'orgUnit' => $facility->DHIS2_UID,
                                    'program' => 'yDuAzyqYABS',
                                    'startDate' => $startDate,
                                    'endDate' => $endDate,
                                    'page' => $i,
                                ]
                            ]);

                            if ($response->getStatusCode() == 200) {
                                $response_body = json_decode($response->getBody(), true);

                                foreach ($response_body['events'] as $event) {
                                    $total_number_of_events++;
                                    $event_uid = $event['event'];

                                    if ($event['status'] == "SCHEDULE" || $event['status'] == "SKIPPED" || $event['status'] == "OVERDUE") {
                                        $time = date('Y-m-d H:i:s');
                                        $this->getOutput()->writeln("{$time} <comment>SKIPPING Event UID:</comment> {$event_uid}, <comment>because event is either SCHEDULED | SKIPPED | OVERDUE!</comment>");
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
                                        $tracked_entity_instance = $utility->getTrackedEntityInstance($httpClient, $tracked_entity_instance_uid);

                                        if (empty($client)) {
                                            //An if statement here perhaps to check if the tracked_entity
                                            $client_side_source_id = $tracked_entity_instance['trackedEntityInstance'];

                                            $new_client_side_record = $persistRecord->saveRecord($client_side_source_id, 'TRACKED_ENTITY_INSTANCE', $tracked_entity_instance);

                                            $client = $persistClient->saveClient($tracked_entity_instance, $facility->id, $new_client_side_record->id);
                                        } else {                                
                                            $created_at_timestamps_difference = $utility->getTimestampsDifferenceInSeconds($tracked_entity_instance['created'], $client->source_created_at);
                                            $updated_at_timestamps_difference = $utility->getTimestampsDifferenceInSeconds($client->source_updated_at, $tracked_entity_instance['lastUpdated']);

                                            if ($persistClient->shouldUpdate($client, $event['trackedEntityInstance'], $created_at_timestamps_difference, $updated_at_timestamps_difference)) {
                                                //get the existing record
                                                $old_client_side_record = Record::where('record_id', $client->source_id)->first();

                                                $updated_client_side_record = $persistRecord->updateRecord($old_client_side_record,  $tracked_entity_instance);

                                                //probably an if statement here
                                                $client = $persistClient->updateClient($client, $tracked_entity_instance, $facility->id);
                                            } else {
                                                $time = date('Y-m-d H:i:s');
                                                $this->getOutput()->writeln("{$time} <comment>SKIPPING Client UID:</comment> {$client->source_id}, <comment>as record is still upto date</comment>");
                                            }
                                        }

                                        $vaccination = Vaccination::where('source_id', $event_uid)->first();

                                        if (!empty($vaccination)) {                             
                                            $created_at_timestamps_difference = $utility->getTimestampsDifferenceInSeconds($event['created'], $vaccination->source_created_at);
                                            $updated_at_timestamps_difference = $utility->getTimestampsDifferenceInSeconds($vaccination->source_updated_at, $event['lastUpdated']);

                                            //Check for last updated ? Vaccination Update logic kicks in
                                            if ((empty($vaccination->source_created_at) || empty($vaccination->source_updated_at)) ||
                                                (($updated_at_timestamps_difference >= 2) && ($vaccination->client_id == $client->id) && 
                                                ($created_at_timestamps_difference <= 2) && ($created_at_timestamps_difference >= -2))) {
                                                $old_event_side_record = Record::where('record_id', $event_uid)->first();
                                                //Perhaps an if statement
                                                $updated_event_side_record = $persistRecord->updateRecord($old_event_side_record, $event);

                                                $vaccination = $persistVaccination->updateVaccination($vaccination, $event, $facility->id);

                                                $total_number_of_updated_events++;
                                                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                                                $event = json_encode($event, JSON_UNESCAPED_SLASHES);
                                                $time = date('Y-m-d H:i:s');
                                                $this->getOutput()->writeln("{$time} <info>UPDATING Event total :</info> {$total_number_of_events} ({$runTime}ms) \n {$event}");
                                            } else {
                                                $time = date('Y-m-d H:i:s');
                                                $this->getOutput()->writeln("{$time} <comment>SKIPPING Event UID:</comment> {$event_uid}, <comment>because event already exists in the DATABASE!</comment>");
                                            }
                                        } else {
                                            //Store new even data in record table
                                            $new_event_side_record = $persistRecord->saveRecord($event_uid, 'EVENT', $event);
                                            //Store new client, record and event data in the vaccination table
                                            $vaccination = $persistVaccination->saveVaccination($event, $client->id, $facility->id, $new_event_side_record->id);

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
            } else {//if(!empty($facility->DHIS2_UID))
                $time = date('Y-m-d H:i:s');
                Log::error("$time Facility with DHIS2 UID: {$facilityDhis2Uid}, NOT FOUND. UID is Invalid or Does Not Exist!");
                $this->getOutput()->writeln("$time <error>Facility with DHIS2 UID: {$facilityDhis2Uid}, NOT FOUND. UID is Invalid or Does Not Exist!");
            }
        } //End foreach($facilities as $facility)

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

        Log::info("$script_start_date_time: Loading data from DHIS2 Covax instance");
        $this->getOutput()->writeln("<info>$script_start_date_time Script started - Loading data from DHIS2 Covax instance</info>");

        $startDate = $this->argument('startDate');
        $endDate = $this->argument('endDate');

        $results = self::loadEvents($startDate, $endDate);

        $script_end_time = date('Y-m-d H:i:s');
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);

        Log::info("$script_end_time Script completed loading data from DHIS2 Covax instance: Duration: $script_run_time Number of Events: Saved - $results[0], Updated - $results[2] of $results[1]");
        $this->getOutput()->writeln("<info>$script_end_time Script completed:</info> Completed loading $results[0] and updated $results[2] of $results[1] events from DHIS2 Covax instance. Duration: $script_run_time");

        $this->info('command:ImportDHIS2Data Command Run successfully!');

        return Command::SUCCESS;
    }
}
