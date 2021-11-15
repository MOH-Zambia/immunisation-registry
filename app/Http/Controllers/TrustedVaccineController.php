<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use App\Repositories\CertificateRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp;

class TrustedVaccineController extends Controller
{
    /** @var  CertificateRepository */
    private $certificateRepository;

    public function __construct(CertificateRepository $certificateRepo)
    {
        $this->certificateRepository = $certificateRepo;
    }

    /**
     * Create a new controller instance.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxRequestPost(Request $request)
    {
        $script_start_date_time = date('Y-m-d H:i:s');
        $startTime = microtime(true);

        Log::info("$script_start_date_time: Generating and sending booklets");

        $input = $request->all();

        $certificate = $this->certificateRepository->find($input['id']);

        if(is_null($certificate->trusted_vaccine_code)){
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
                'dial_code' => '+260',
                'phone_number' => ltrim($certificate->client->contact_number, '0'),
                'user_code' => '',
                'nationality' => $certificate->client->country->name,
                'county_of_residence' => $certificate->client->facility->district->province->name,
                'sub_county_of_residence' => $certificate->client->facility->district->name,
                'estate_of_residence' => $certificate->client->address_line1.' '.$certificate->client->address_line2,
                'occupation' => $certificate->client->occupation
            );

            $booklet['booklet_issuer'] = array(
                'email' => 'ir@moh.gov.zm', //phazambia@pana.org
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

            $httpClient = new GuzzleHttp\Client();

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

                return response()->json(['success'=>'Certificate sent']);
            } catch (ConnectException $e) {
                // Connection exceptions are not caught by RequestException
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( " $time ConnectException: $message");

                return response()->json(["error"=>" $time ConnectException: $message"]);
            } catch (RequestException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');

                Log::error( "$time RequestException - $message");
                Log::error( "$json");

                return response()->json(["error"=>" $time RequestException: $message"]);
            } catch (TransferException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( "$time TransferException: $message");

                return response()->json(["error"=>" $time TransferException: $message"]);
            }catch (Exception $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( "$time Exception: $message");

                return response()->json(["error"=>" $time Exception: $message"]);
            }
        } else {
            return response()->json(['success'=>'Certificate already sent']);
        }


    }
}
