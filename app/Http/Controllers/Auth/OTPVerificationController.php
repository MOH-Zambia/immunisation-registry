<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppBaseController;
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
use Laracasts\Flash\Flash;


class OTPVerificationController extends AppBaseController
{
    public function sendSMS(Request $request){
        $input = $request->all();

        $OTP = mt_rand(1000,9999);
        $isError = 0;
        $errorMessage = true;

        $host = env('KANNEL_HOST');
        $port = env('KANNEL_PORT');
        $smsc = env('KANNEL_SMSC');
        $username = env('KANNEL_USERNAME');
        $password = env('KANNEL_PASSWORD');
        $recipient = $input['contact_number'];
        $sender = env('KANNEL_SENDER');

        //Your message to send, Adding URL encoding.
        $message = urlencode("Ministry of Health, \nCOVID-19 Immunisation Registry, \nYour One Time Password to access your COVID-19 Certificate is $OTP");

        $url = "http://{$host}:{$port}/cgi-bin/sendsms?username={$username}&password={$password}&smsc={$smsc}&from={$sender}&to={$recipient}&text={$message}";

        Log::info( "Sending OTP via SMS using URL: $url");

        /** @var TYPE_NAME $ch */
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => TRUE,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => TRUE,
//            CURLOPT_POST => true
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //Get response
        $output = curl_exec($ch);

        //Print error if any
        if (curl_errno($ch)) {
            $isError = true;
            $errorMessage = curl_error($ch);
        }

        curl_close($ch);

        if($isError){
            Log::error("Error sending OTP via SMS: $errorMessage");
            return $this->sendError($errorMessage);
        }else{
            Session::put('OTP', $OTP);
            Log::info("OPT sent via SMS: $output, OTP: $OTP");
            return $this->sendSuccess("OTP Sent!");
        }
    }

    /**
     * Sending the OTP.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request){
        $input = $request->all();
        $OTP = mt_rand(1000,9999);

        $contact_email_address = $input['contact_email_address'];

        try{
            Log::info( "Sending OTP via Email");

            Mail::send('auth.otp_email', ['OTP' => $OTP], function(Message $message) use ($contact_email_address){
                $message->subject("COVID-19 Immunisation Registry Verification Code");
                $message->to($contact_email_address);
            });

            Session::put('OTP', $OTP);

            Log::info( "OTP sent via email: $OTP");
            return $this->sendSuccess("OTP Sent!");
        } catch (\Exception $e){
            Log::error("Error sending OTP via Email: $e->getMessage()");
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Function to verify OTP.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOTP(Request $request){
        $input = $request->all();
        $enteredOTP = $input['OTP'];
        $client_id = $input['client_id'];

        $sessionOTP = $request->session()->get('OTP');

        if($sessionOTP == $enteredOTP){
            $certificate = Certificate::where('client_id', $client_id)->first();

            if(empty($certificate)){
                return $this->sendError('Certificate not found!');
            } else {
                return $this->sendSuccess($certificate->certificate_url);
            }

            //Removing Session variable
            Session::forget('OTP');
        } else {
            return $this->sendError("Wrong OTP");
        }
    }
}
