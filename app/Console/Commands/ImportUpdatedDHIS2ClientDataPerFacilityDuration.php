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
 * The script ImportUpdatedDHIS2Data.php
 *
 * This script fetches Updated DHIS2 Data, via a passed DHIS2 UID.
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

class ImportUpdatedDHIS2ClientDataPerFacilityDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ImportUpdatedDHIS2ClientDataPerFacilityDuration {startDate} {endDate} {facilityDhis2Uid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Updated DHIS2 Client Data only from the COVAX Instance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function loadEvents($startDate, $endDate, $facilityDhis2Uid): array
    {
        $httpClient = new GuzzleHttp\Client();
        $utility = new Utilities();
        $persistRecord = new PersistRecord();
        $persistClient = new PersistClient();
        $facility = Facility::where('DHIS2_UID', $facilityDhis2Uid)->first();
        $total_clients = 0;
        $total_updated_clients = 0;
        $total_skipped_clients = 0;

        if(!empty($facility)) {
            
            $time = date('Y-m-d H:i:s');
            $this->getOutput()->writeln("{$time} <info>Loading Updated DHIS2 Client Data between Start-Date:</info>{$startDate} <info>and End-Date:</info>{$endDate}<info> from:</info> {$facility->name}, <info>UID:</info> {$facility->DHIS2_UID}, <info>ID:</info> {$facility->id}");
            
            try {
                $response = $httpClient->request('GET', env('DHIS2_BASE_URL')."trackedEntityInstances.json?", [
                    'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                    'query' => [
                        'ou' => $facility->DHIS2_UID,
                        'program' => 'yDuAzyqYABS',
                        'lastUpdatedStartDate' => $startDate,
                        'lastUpdatedEndDate' => $endDate,
                        'skipPaging' => true
                    ]
                ]);

                if ($response->getStatusCode() == 200) {
                    $response_body = json_decode($response->getBody(), true);

                    foreach ($response_body['trackedEntityInstances'] as $tracked_entity_instance) {
                        $total_clients++;

                        $tracked_entity_instance_uid = $tracked_entity_instance['trackedEntityInstance'];

                        if ($tracked_entity_instance['inactive'] == true || $tracked_entity_instance['deleted'] == true) {
                            $total_skipped_clients++;
                            $time = date('Y-m-d H:i:s');
                            $this->getOutput()->writeln("{$time} <comment>SKIPPING Client UID:</comment> {$tracked_entity_instance_uid}, <comment>because Client is either INACTIVE | DELETED!</comment>");
                            continue;
                        }

                        try {
                            $startTime = microtime(true);
                            //Initialise transaction
                            DB::beginTransaction();

                            $client = Client::where('source_id', $tracked_entity_instance_uid)->first();

                            if (empty($client)) {
                                $total_skipped_clients++;
                                $time = date('Y-m-d H:i:s');
                                $this->getOutput()->writeln("{$time} <comment>SKIPPING Client UID:</comment> {$tracked_entity_instance_uid}, <comment>because Client currently does not have existing records on the destination platform!</comment>");
                            } else {
                                $created_at_timestamps_difference = $utility->getTimestampsDifferenceInSeconds($tracked_entity_instance['created'], $client->source_created_at);
                                $updated_at_timestamps_difference = $utility->getTimestampsDifferenceInSeconds($client->source_updated_at, $tracked_entity_instance['lastUpdated']);

                                if ($persistClient->shouldUpdate($client, $tracked_entity_instance, $created_at_timestamps_difference, $updated_at_timestamps_difference)) {
                                    //get the existing record
                                    $old_client_side_record = Record::where('record_id', $client->source_id)->first();

                                    $updated_client_side_record = $persistRecord->updateRecord($old_client_side_record,  $tracked_entity_instance);

                                    //probably an if statement here
                                    $client = $persistClient->updateClient($client, $tracked_entity_instance, $facility->id);

                                    $total_updated_clients++;
                                } else {
                                    $total_skipped_clients++;

                                    $time = date('Y-m-d H:i:s');
                                    $this->getOutput()->writeln("{$time} <comment>SKIPPING Client UID:</comment> {$client->source_id}, <comment>as record is still upto date</comment>");
                                }
                            }

                            DB::commit(); //if no error on record and client data importlog commit data to database
                            $this->getOutput()->writeln("{$time} <info>PERSISTED Updated Client Data:</info> {$tracked_entity_instance_uid} <info>to the DATABASE!</info>");

                        } catch (QueryException $e) {
                            DB::rollback(); //Rollback database transaction if any error occurs            

                            $message = $e->getMessage();
                            $time = date('Y-m-d H:i:s');
                            $tracked_entity_instance = json_encode($tracked_entity_instance, JSON_UNESCAPED_SLASHES);

                            Log::error("$time QueryException: $message \n $tracked_entity_instance");
                            $this->getOutput()->writeln("<error>{$time} QueryException on Client number {$total_clients}: $message \n $tracked_entity_instance</error>");
                        } catch (Exception $e) {
                            DB::rollback(); //Rollback database transaction if any error occurs

                            $message = $e->getMessage();
                            $time = date('Y-m-d H:i:s');
                            $tracked_entity_instance = json_encode($tracked_entity_instance, JSON_UNESCAPED_SLASHES);

                            Log::error("$time Exception: $message \n $tracked_entity_instance");
                            $this->getOutput()->writeln("<error>{$time} Exception on Client number {$total_clients}: $message \n $tracked_entity_instance</error>");
                        }
                    } //End foreach
                }//End $response->getStatusCode() == 200

            } catch (ConnectException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( " $time ConnectException: $message");
                $this->getOutput()->writeln("<error>{$time} ConnectException on Client number $total_clients: $message</error>");
            } catch (RequestException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( "$time RequestException - $message");
                $this->getOutput()->writeln("<error>{$time} RequestException on Client number $total_clients: $message</error>");
            } catch (TransferException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');

                Log::error( "$time TransferException: $message");
                $this->getOutput()->writeln("<error>{$time} TransferException on Client number $total_clients: $message</error>");
            } catch (GuzzleException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');

                Log::error( "$time Exception: $message \n $tracked_entity_instance");
                Log::error($tracked_entity_instance);
                $this->getOutput()->writeln("<error>{$time} GuzzleException on Client number $total_clients: $message \n </error>");
            } catch (Exception $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');

                Log::error( "$time Exception: $message \n");
                $this->getOutput()->writeln("<error>{$time} Exception on Client number $total_clients: $message \n </error>");
            }

        } else {
            $time = date('Y-m-d H:i:s');

            Log::error("$time Facility with DHIS2 UID: {$facilityDhis2Uid}, NOT FOUND. UID is Invalid or Does Not Exist!");
            $this->getOutput()->writeln("$time <error>Facility with DHIS2 UID: {$facilityDhis2Uid}, NOT FOUND. UID is Invalid or Does Not Exist!</error>");
        }

        return array($total_clients, $total_updated_clients, $total_skipped_clients);

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

        Log::info("$script_start_date_time: Loading Updated Client Data from DHIS2 Covax Instance");
        $this->getOutput()->writeln("<info>$script_start_date_time Script started - Loading Updated Client Data from DHIS2 Covax Instance</info>");

        $startDate = $this->argument('startDate');
        $endDate = $this->argument('endDate');
        $facilityDhis2Uid = $this->argument('facilityDhis2Uid');

        $results = self::loadEvents($startDate, $endDate, $facilityDhis2Uid);

        $script_end_time = date('Y-m-d H:i:s');
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);

        Log::info("$script_end_time Script completed loading Updated Client Data from DHIS2 Covax Instance Duration: $script_run_time Number of Clients: Updated - $results[1] and Skipped - $results[2] of $results[0] Total Clients");
        Log::info("$script_end_time Number of Clients Data: Updated - $results[1], Skipped - $results[2]");
        $this->getOutput()->writeln("<info>$script_end_time Script completed:</info> Completed loading Updated Client Data from DHIS2 Covax Instance: Number of Clients: Updated - $results[1] and Skipped - $results[2] of $results[0] Total Clients from the DHIS2 Covax instance. Duration: $script_run_time");
        $this->info('command:ImportUpdatedDHIS2ClientDataPerFacilityDuration Command Run successfully!');

        return Command::SUCCESS;
    }
}
