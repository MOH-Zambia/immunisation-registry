<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Console\Command;
use GuzzleHttp;

use App\Models\Certificate;


class ExportDataToTrustedVaccinePortal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ExportDataToTrustedVaccinePortal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Data to Trusted Vaccine Portal';

    /**
     * Base URL for trackedEntityInstances
     */
    const TRUSTED_VACCINE_BOOKLET_URL = 'https://staging.panabios.org/api/external/vaccination/generate_booklet/';

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
        $number_of_booklets = 0;
        $script_start_time = microtime(true);
        $script_start_date_time = date('Y-m-d H:i:s');

        Log::info("$script_start_date_time: Generating and sending booklets");
        $this->getOutput()->writeln("<info>$script_start_date_time: Script started - Generating and sending booklets</info>");

//        $httpClient = new GuzzleHttp\Client([
//            // Base URI is used with relative requests
//            'base_url' => env('TRUSTED_VACCINE_BOOKLET_URL')
//        ]);

        $httpClient = new GuzzleHttp\Client();

//        $certificates = Certificate::all();
        $certificates = Certificate::whereNull('trusted_vaccine_code')->get();

        foreach ($certificates as $certificate){
            $startTime = microtime(true);

            $booklet = array();

            $currentDate = date("Y-m-d");
            $birthDate = $certificate->client->date_of_birth->format('Y-m-d');

            $age = date_diff(date_create($birthDate), date_create($currentDate));

            $booklet['card_number'] = $certificate->client->client_uid;

            $booklet['patient_info'] = array(
                'first_name' => $certificate->client->first_name,
                'last_name' => $certificate->client->last_name,
                'gender' => $certificate->client->sex,
                'date_of_birth' => $birthDate,
                'age' => $age->format("%y"),
                'age_unit' => '',
                'email' => $certificate->client->contact_email_address,
                'dail_code' => '+260',
                'phone_number' => ltrim($certificate->client->contact_number, '0'),
                'user_code' => '',
                'nationality' => $certificate->client->country->name,
                'county_of_residence' => $certificate->client->facility->district->province->name,
                'sub_county_of_residence' => $certificate->client->facility->district->name,
                'estate_of_residence' => $certificate->client->address_line1.' '.$certificate->client->address_line2,
                'occupation' => $certificate->client->occupation
            );

            $booklet['booklet_issuer'] = array(
                'email' => 'ir@moh.gov.zm',
                'dial_code' => '',
                'phone_number' => '',
                'user_code' => ''
            );

            $booklet['campaign_info'] =  array(
                'disease' => 'SARS-CoV-2',
                'country' => 'ZM'
            );

            foreach ($certificate->vaccinations as $vaccination){
                $booklet['vaccination_records'][] = array(
                    'vaccine_used' => array(
                        'name' => $vaccination->vaccine->vaccine_manufacturer
                    ),
                    'manufacturer' => $vaccination->vaccine->vaccine_manufacturer,
                    'dossage_type' => $vaccination->vaccine->commercial_formulation,
                    'dossage_quantity' => $vaccination->dose_number,
                    'date_of_vaccination' => $vaccination->date->format('Y-m-d'),
                    'vaccine_batch_number' => $vaccination->vaccine_batch_number
                );
            }

            $json = json_encode($booklet, JSON_PRETTY_PRINT);

            $headers = [
                'Authorization' => 'Token '.env('TRUSTED_VACCINE_TOKEN'),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json'
            ];
            try {
                $response = $httpClient->request('POST', env('TRUSTED_VACCINE_BOOKLET_URL'), [
                    'json' => $booklet,
                    'headers' => $headers
                ]);

                $body = json_decode($response->getBody()->getContents(), true);
                $certificate->trusted_vaccine_code = $body['data']['code'];
                $certificate->save();

                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                $time = date('Y-m-d H:i:s');
                Log::info( "$time Booklet saved: Client UID ".$certificate->client['client_uid'].", Certificate $certificate->certificate_uuid, Trusted Vaccine Code {$body['data']['code']} ({$runTime}ms)");
                $this->getOutput()->writeln("<info>$time Booklet saved:</info> Client UID ".$certificate->client['client_uid'].", Certificate $certificate->certificate_uuid, Trusted Vaccine Code {$body['data']['code']} ({$runTime}ms)");
                $this->getOutput()->writeln("<info>$json</info>");

                $number_of_booklets++;
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
                Log::error( "$json");

                $this->getOutput()->writeln("<error>$time RequestException: $message</error>");
                $this->getOutput()->writeln("<info>$json</info>");
            } catch (TransferException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( "$time TransferException: $message");
                $this->getOutput()->writeln("<error>$time TransferException: $message</error>");
            }catch (Exception $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( "$time Exception: $message");
                $this->getOutput()->writeln("<error>$time Exception: $message</error>");
            }
        }

        $script_end_time = date('Y-m-d H:i:s');
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);
        Log::info("$script_end_time Completed generating $number_of_booklets booklets: Duration: $script_run_time");
        $this->getOutput()->writeln("<info>$script_end_time Script completed:</info> Completed generating $number_of_booklets booklets. Duration: $script_run_time");

        return Command::SUCCESS;
    }
}
