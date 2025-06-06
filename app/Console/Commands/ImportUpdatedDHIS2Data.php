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
use App\Models\Client;
use App\Models\Vaccination;

class ImportUpdatedDHIS2Data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ImportUpdatedDHIS2Data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Updated DHIS2 Data from the COVAX Instance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function loadEvents($startDate, $endDate, $filename): array
    {
        $httpClient = new GuzzleHttp\Client();
        $utility = new Utilities();
        $persistClient = new PersistClient();
        $persistVaccination = new PersistVaccination();

        $facilities = Facility::all(); //Get all facilities from database
        // $facilities = Facility::inRandomOrder()->limit(5)->get(); //Get (n) random number of facilities from database FOR TESTING
        $total_number_of_events = 0; //Total events counter
        $number_of_saved_clients = 0; //Total clients counter
        $total_number_of_saved_events = 0; //Saved events counter
        $number_of_updated_clients = 0; //Updated clients counter
        $total_number_of_updated_events = 0; //Updated events counter

        $facilities_import_summaries = [];

        $total_facilities_with_updated_data = 0; //Total number of DHIS2 facilities with changes loaded into IR
        
        $import_summary_file = fopen($filename, "w");

        $heading = "Import Summary per Facility of Imported DHIS2 Data, Start Date: " . $startDate . ", End Date: " . $endDate . "\n\n";
        $process_details = "Fetching data from a total of : " . count($facilities) . " facilities\n";
        $intro = "Process started at : " . date('Y-m-d H:i:s') . "\n\n";
        fwrite($import_summary_file, $heading . $process_details . $intro );

        foreach($facilities as $facility) {
            $number_of_events = 0;

            $_total_number_of_events = 0; //Total events counter
            $_number_of_saved_clients = 0; //Total clients counter
            $_number_of_updated_clients = 0; //Updated clients counter
            $_total_number_of_saved_events = 0; //Saved events counter
            $_total_number_of_updated_events = 0; //Updated events counter

            if(!empty($facility->DHIS2_UID)) {
                
                $facility_import_summary = array (
                    "FacilityUID" => $facility->DHIS2_UID,
                    "FacilityName" => $facility->name,
                    "NewEvents" => $_total_number_of_saved_events,
                    "UpdatedEvents" => $_total_number_of_updated_events,
                    "NewClients" => $_number_of_saved_clients,
                    "UpdatedClients" => $_number_of_updated_clients
                ); //Array as a dictionary

                $time = date('Y-m-d H:i:s');
                $this->getOutput()->writeln("{$time} <info>Loading Updated DHIS2 Data between Start-Date:</info>{$startDate} <info>and End-Date:</info>{$endDate}<info> from:</info> {$facility->name}, <info>UID:</info> {$facility->DHIS2_UID}, <info>ID:</info> {$facility->id}");
                Log::info("$time Info: <info>Loading Updated DHIS2 Data between Start-Date:</info>{$startDate} <info>and End-Date:</info>{$endDate}<info> from:</info> {$facility->name}, <info>UID:</info> {$facility->DHIS2_UID}, <info>ID:</info> {$facility->id}");

                try {
                    $response = $httpClient->request('GET', env('DHIS2_BASE_URL')."events.json", [
                        'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                        'query' => [
                            'orgUnit' => $facility->DHIS2_UID,
                            'program' => 'yDuAzyqYABS',
                            'lastUpdatedStartDate' => $startDate,
                            'lastUpdatedEndDate' => $endDate,
                            'fields' => '*',
                            'skipPaging' => true,
                            'skipMeta' => true
                        ]
                    ]);

                    if ($response->getStatusCode() == 200) {
                        $response_body = json_decode($response->getBody(), true);
                        foreach ($response_body['events'] as $event) {
                            $number_of_events++;
                            
                            $_total_number_of_events++;

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
    
                                    $client = $persistClient->saveClient($tracked_entity_instance, $facility->id);

                                    $_number_of_saved_clients++;

                                } else {
                                    $created_at_timestamps_difference = $utility->getTimestampsDifferenceInSeconds($tracked_entity_instance['created'], $client->source_created_at);
                                    $updated_at_timestamps_difference = $utility->getTimestampsDifferenceInSeconds($client->source_updated_at, $tracked_entity_instance['lastUpdated']);

                                    if ($persistClient->shouldUpdate($client, $event['trackedEntityInstance'], $created_at_timestamps_difference, $updated_at_timestamps_difference)) {    
                                        //probably an if statement here
                                        $client = $persistClient->updateClient($client, $tracked_entity_instance, $facility->id);

                                        $_number_of_updated_clients++;
                                        
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
                                    if ($persistVaccination->shouldUpdate($vaccination, $client->id, $created_at_timestamps_difference, $updated_at_timestamps_difference)) {
                                        $vaccination = $persistVaccination->updateVaccination($vaccination, $event, $facility->id);

                                        $_total_number_of_updated_events++;

                                        $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                                        $time = date('Y-m-d H:i:s');
                                        $this->getOutput()->writeln("{$time} <info>UPDATING Event total:</info> {$total_number_of_events} ({$runTime}ms), <info>Event UID :</info> {$event_uid}");
                                    } else {
                                        $time = date('Y-m-d H:i:s');
                                        $this->getOutput()->writeln("{$time} <comment>SKIPPING Event UID:</comment> {$event_uid}, <comment>because event already exists in the DATABASE!</comment>");
                                    }
                                } else {
                                    //Store new client, record and event data in the vaccination table
                                    $vaccination = $persistVaccination->saveVaccination($event, $client->id, $facility->id);
                                    $_total_number_of_saved_events++;
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

                Log::error("$time Facility NOT FOUND. UID is Invalid or Does Not Exist!");
                $this->getOutput()->writeln("$time <error>Facility NOT FOUND. UID is Invalid or Does Not Exist!");
            }

            if ($_total_number_of_saved_events > 0 || $_total_number_of_updated_events > 0 || $_number_of_saved_clients > 0 || $_number_of_updated_clients > 0)
            {
                $total_facilities_with_updated_data++;
            }

            $total_number_of_saved_events += $_total_number_of_saved_events;
            $total_number_of_updated_events += $_total_number_of_updated_events;
            $number_of_saved_clients += $_number_of_saved_clients;
            $number_of_updated_clients += $_number_of_updated_clients;

            $facility_import_summary["NewEvents"] = $_total_number_of_saved_events;
            $facility_import_summary["UpdatedEvents"] = $_total_number_of_updated_events;
            $facility_import_summary["NewClients"] = $_number_of_saved_clients;
            $facility_import_summary["UpdatedClients"] = $_number_of_updated_clients;

            $facilities_import_summaries = array_push($facility_import_summary);

            fwrite($import_summary_file, json_encode($facility_import_summary) . "\n");

            $time = date('Y-m-d H:i:s');
            $this->getOutput()->writeln("{$time} <info>Finished loading updated DHIS2 Data between Start-Date:</info>{$startDate} <info>and End-Date:</info>{$endDate}<info> from:</info> {$facility->name}, <info>UID:</info> {$facility->DHIS2_UID}, <info>ID:</info> {$facility->id}, <info>Number of Events:</info> {$number_of_events}");
            Log::info("$time Info: <info>Finished loading updated DHIS2 Data between Start-Date:</info>{$startDate} <info>and End-Date:</info>{$endDate}<info> from:</info> {$facility->name}, <info>UID:</info> {$facility->DHIS2_UID}, <info>ID:</info> {$facility->id}, <info>Number of Events:</info> {$number_of_events}");
        } //End foreach($facilities as $facility)

        $process_details = "\nLoaded new and updated data from a total of : " . $total_facilities_with_updated_data . " facilities, out a total of : " . count($facilities);
        $outro = "\nProcess completed SUCCESSFULLY at: " . date('Y-m-d H:i:s');
        fwrite($import_summary_file, $process_details . $outro);

        fclose($import_summary_file);

        return array($total_number_of_saved_events, $total_number_of_events, $total_number_of_updated_events, $number_of_saved_clients, $number_of_updated_clients);

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

        Log::info("$script_start_date_time: Loading Updated Data from DHIS2 Covax Instance");
        $this->getOutput()->writeln("<info>$script_start_date_time Script started - Loading Updated Data from DHIS2 Covax Instance</info>");

        $startDate = date('Y-m-d',strtotime("yesterday"));
        $endDate = date('Y-m-d');

        $filename = 'storage/import_summaries/' . 'import_summary_' . $startDate . '_' . $endDate . '.txt';

        $results = self::loadEvents($startDate, $endDate, $filename);

        $script_end_time = date('Y-m-d H:i:s');
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);

        Log::info("$script_end_time Script completed loading Updated Data from DHIS2 Covax Instance Duration: $script_run_time Number of Events: Saved - $results[0], Updated - $results[2] of $results[1]");
        Log::info("$script_end_time Number of Clients Data: Saved - $results[3], Updated - $results[4]");
        $this->getOutput()->writeln("<info>$script_end_time Script completed:</info> Completed Updated Data from DHIS2 Covax Instance $results[0] and updated $results[2] of $results[1] events from the DHIS2 Covax instance. Duration: $script_run_time");
        $this->getOutput()->writeln("<info>$script_end_time Number of Clients Data:</info> Saved - $results[3], Updated - $results[4]");
        $this->info('command:ImportUpdatedDHIS2Data Command Run successfully!');

        return Command::SUCCESS;
    }
}
