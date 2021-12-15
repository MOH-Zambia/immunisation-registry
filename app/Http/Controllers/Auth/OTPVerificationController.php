<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Client;
use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class OTPVerificationController extends Controller
{
    private $API_KEY = 'API_KEY';
    private $SENDER_ID = "VERIFY";
    private $ROUTE_NO = 4;
    private $RESPONSE_TYPE = 'json';

    public function sendMail($OTP, $email_address){
        Mail::send('auth.otp_email', ['OTP' => $OTP], function(Message $message) use ($email_address){
            $message->subject("Verification Code");
            $message->to($email_address);
        });

        return true;
    }


    public function sendSMS($OTP, $mobile_number){
        $isError = 0;
        $errorMessage = true;

        //Your message to send, Adding URL encoding.
        $message = urlencode("Ministry of Health Immunisation Registry , Your OPT is : $OTP");

        //Preparing post parameters
        $postData = array(
            'authkey' => $this->API_KEY,
            'mobiles' => $mobile_number,
            'message' => $message,
            'sender' => $this->SENDER_ID,
            'route' => $this->ROUTE_NO,
            'response' => $this->RESPONSE_TYPE
        );

        $url = "https://control.msg91.com/sendhttp.php";

        /** @var TYPE_NAME $ch */
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //get response
        $output = curl_exec($ch);

        //Print error if any
        if (curl_errno($ch)) {
            $isError = true;
            $errorMessage = curl_error($ch);
        }
        curl_close($ch);
        if($isError){
            return array('error' => 1 , 'message' => $errorMessage);
        }else{
            return array('error' => 0 );
        }
    }

    /**
     * Sending the OTP.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOTP(Request $request){
        $client = null;
        $response = array();
        $input = $request->all();


        if (array_key_exists('email', $input)) {
            if (array_key_exists('nrc', $input)) {
                $client = Client::where([
                    ['NRC', '=', $input['nrc']],
                    ['contact_email_address', '=', $input['email']],
                ])->first();
            }

            if (array_key_exists('passport', $input)) {
                $client = Client::where([
                    ['passport', '=', $input['nrc']],
                    ['contact_email_address', '=', $input['email']],
                ])->first();
            }

            if (array_key_exists('drivers_license', $input)) {
                $client = Client::where([
                    ['drivers_license', '=', $input['nrc']],
                    ['contact_email_address', '=', $input['email']],
                ])->first();
            }

            if(empty($client)){
                $response["message"] = "Unsuccessful";
            } else {
                $OTP = mt_rand(1000,9999);
                if($this->sendMail($OTP, $input['email'])){
                    Session::put('OTP', $OTP);
                    $response["message"] = "Successful";
                } else
                    $response["message"] = "Unsuccessful";
            }
        }

        return response()->json($response);
    }

    /**
     * Function to verify OTP.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOTP(Request $request){
        $response = array();
        $enteredOTP = $request->input('OTP');
        $input = $request->all();
        $client = null;

        $OTP = $request->session()->get('OTP');

        if($OTP == $enteredOTP){
            if (array_key_exists('email', $input)) {
                if (array_key_exists('nrc', $input)) {
                    $client = Client::where([
                        ['NRC', '=', $input['nrc']],
                        ['contact_email_address', '=', $input['email']],
                    ])->first();
                }

                if (array_key_exists('passport', $input)) {
                    $client = Client::where([
                        ['passport', '=', $input['nrc']],
                        ['contact_email_address', '=', $input['email']],
                    ])->first();
                }

                if (array_key_exists('drivers_license', $input)) {
                    $client = Client::where([
                        ['drivers_license', '=', $input['nrc']],
                        ['contact_email_address', '=', $input['email']],
                    ])->first();
                }

                if(empty($client) || empty($client['certificate'])){
                    Flash::error('Certificate not found');
                    return abort(404, 'Certificate not found!');
                } else {
                    return view('certificate')->with('certificate', $client['certificate']);
                }
            }
            //Removing Session variable
            Session::forget('OTP');
        }else{
            $response['message'] = "Wrong OTP";
        }

        return response()->json($response);
    }
}
