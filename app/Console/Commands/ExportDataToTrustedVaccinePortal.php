<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use Illuminate\Console\Command;
use GuzzleHttp;

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
//        $httpClient = new GuzzleHttp\Client([
//            // Base URI is used with relative requests
//            'base_url' => env('TRUSTED_VACCINE_BOOKLET_URL')
//        ]);

        $httpClient = new GuzzleHttp\Client();

//        $certificates = Certificate::all();
        $certificates = Certificate::whereNull('africa_cdc_trusted_vaccine_code')->get();

        foreach ($certificates as $certificate){
            $booklet = array();

            $currentDate = date("d-m-Y");
            $birthDate = $certificate->client->date_of_birth->format('Y-m-d');

            $age = date_diff(date_create($birthDate), date_create($currentDate));

            $booklet['card_number'] = $certificate->certificate_uuid;

            $booklet['patient_info'] = array(
                'first_name' => $certificate->client->first_name,
                'last_name' => $certificate->client->last_name,
                'gender' => $certificate->client->sex,
                'date_of_birth' => $birthDate,
                'age' => $age->format("%y"),
                'age_unit' => '',
                'email' => $certificate->client->contact_email_address,
                'dail_code' => '+260',
                'phone_number' => ltrim ($certificate->client->contact_number, '0'),
                'user_code' => '',
                'nationality' => $certificate->client->country->name,
                'county_of_residence' => $certificate->client->facility->district->province->name,
                'sub_county_of_residence' => $certificate->client->facility->district->name,
                'estate_of_residence' => $certificate->client->address_line1.' '.$certificate->client->address_line2,
                'occupation' => $certificate->client->occupation
            );

            $booklet['booklet_issuer'] = array(
                'first_name' => '',
                'last_name' => '',
                'email' => 'phazambia@pana.org',
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

            dd(json_encode($booklet));

            $headers = [
                'Authorization' => 'Token '.env('TRUSTED_VACCINE_TOKEN'),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json'
            ];

            $response = $httpClient->request('POST', env('TRUSTED_VACCINE_BOOKLET_URL'), [
                'json' => $booklet,
                'headers' => $headers
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            $certificate->africa_cdc_trusted_vaccine_code = $body['data']['code'];
            $certificate->save();

//            dd(json_decode($response->getBody()));
//            dd(json_decode($response->getBody()->getContents(), true)['data']['code']);
        }

        return Command::SUCCESS;
    }
}
