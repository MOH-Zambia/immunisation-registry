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
 * The script UpdateTrackedEntityInstances.php
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
use App\Models\ImportLog;
use App\Models\Record;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;

class UpdateTrackedEntityInstances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateTrackedEntityInstances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates clients details from DHIS2';

    /**
     * Base URL for trackedEntityInstances
     */
    const TRACKED_ENTITY_INSTANCES_URL = 'https://dhis2.moh.gov.zm/covax/api/trackedEntityInstances.json';

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
        $httpClient = new GuzzleHttp\Client();

        $clients = Client::all();

        foreach ($clients as $client){
            try {
                $response = $httpClient->request('GET', self::TRACKED_ENTITY_INSTANCES_URL . "?trackedEntityInstance={$client->client_uid}", [
                    'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                ]);

                if ($response->getStatusCode() == 200) {
                    $responce_body = json_decode($response->getBody(), true);
                    $trackedEntityInstance = $responce_body['trackedEntityInstances'][0];

                    $time = date('Y-m-d H:i:s');
                    $this->getOutput()->writeln("<info>$time Getting TRACKED_ENTITY_INSTANCE: </info>{$trackedEntityInstance['trackedEntityInstance']}");

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

                    //Save client record
                    $client->update();

                    //Store data in record table
                    $record = new Record([
                        'data_source' => 'MOH_DHIS2_COVAX',
                        'data_type' => 'TRACKED_ENTITY_INSTANCE_UPDATE',
                        'data' => json_encode($trackedEntityInstance)
                    ]);

                    $record->save();

                    // Create record in ImportLog table
                    ImportLog::create([
                        'hash' => sha1(json_encode($trackedEntityInstance)),
                    ]);

                    $time = date('Y-m-d H:i:s');
                    $this->getOutput()->writeln("<info>$time Client saved:</info> {$client->id}");
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
            }catch (Exception $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                $trackedEntityInstance = json_encode($trackedEntityInstance, JSON_UNESCAPED_SLASHES);

                Log::error( "$time: Exception: $message \n $trackedEntityInstance");
                $this->getOutput()->writeln("<error>$time Exception: $message \n $trackedEntityInstance</error>");
            }
        }

        return Command::SUCCESS;
    }
}
