<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppBaseController;
use App\Models\Certificate;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class OTPVerificationController extends AppBaseController
{
    public function sendSMS(Request $request){
        $requestId = uniqid('OTP-SMS-', true);

        Log::channel('sms')->info("[$requestId] OTP SMS request initiated", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $request->validate([
            'contact_number' => 'required|string'
        ]);

        $input = $request->all();
        $contactNumber = $input['contact_number'];

        Log::channel('sms')->info("[$requestId] Validation passed for contact: " . substr($contactNumber, -4));

        $OTP = mt_rand(1000,9999);
        $isError = 0;
        $errorMessage = '';

        $host = env('KANNEL_HOST');
        $port = env('KANNEL_PORT');
        $smsc = env('KANNEL_SMSC');
        $username = env('KANNEL_USERNAME');
        $password = env('KANNEL_PASSWORD');
        $from = env('KANNEL_SENDER');

        Log::channel('sms')->info("[$requestId] SMS Gateway config loaded", [
            'host' => $host,
            'port' => $port,
            'smsc' => $smsc,
            'sender' => $from
        ]);

        $to = $contactNumber;


        //Your message to send, Adding URL encoding.
        $text = urlencode("COVID-19 Immunisation Registry, \nYour One Time Password to access your COVID-19 Certificate is {$OTP}");

        $url = "http://{$host}:{$port}/cgi-bin/sendsms?username={$username}&password={$password}&smsc={$smsc}&from={$from}&to={$to}&text={$text}";

        Log::channel('sms')->info("[$requestId] Sending OTP via SMS to: " . substr($to, -4));

        /** @var TYPE_NAME $ch */
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => TRUE,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => TRUE
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //Get response
        $startTime = microtime(true);
        $output = curl_exec($ch);
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        //Print error if any
        if (curl_errno($ch)) {
            $isError = true;
            $errorMessage = curl_error($ch);
            Log::channel('sms')->error("[$requestId] cURL error: $errorMessage", [
                'curl_errno' => curl_errno($ch),
                'execution_time_ms' => $executionTime
            ]);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        Log::channel('sms')->info("[$requestId] SMS gateway response", [
            'http_code' => $httpCode,
            'execution_time_ms' => $executionTime,
            'response_length' => strlen($output)
        ]);

        if($isError){
            Log::channel('sms')->error("[$requestId] Failed to send OTP via SMS", [
                'error' => $errorMessage,
                'contact_last4' => substr($to, -4)
            ]);
            return $this->sendError('Failed to send OTP. Please try again later.');
        }else{
            Session::put('OTP', $OTP);
            Session::put('OTP_REQUEST_ID', $requestId);
            Session::put('OTP_TIMESTAMP', now()->timestamp);

            Log::channel('sms')->info("[$requestId] OTP sent successfully via SMS", [
                'contact_last4' => substr($to, -4),
                'otp_stored_in_session' => true,
                'timestamp' => now()->toDateTimeString()
            ]);

            return $this->sendSuccess("OTP Sent!");
        }
    }

    public function sendSMSViaZamtelBulkSMS(Request $request)
    {
        $request->validate([
            'contact_number' => 'required|string'
        ]);

        $input = $request->all();

        $OTP = mt_rand(1000,9999);
        $isError = 0;
        $errorMessage = '';

        $apiKey = env('ZAMTEL_BULK_SMS_API_KEY');
        $senderId = env('ZAMTEL_SENDER_ID');

        $message = urlencode("COVID-19 Immunisation Registry, \nYour One Time Password to access your COVID-19 Certificate is {$OTP}");
        $formattedPhone = $this->formatPhone($input['contact_number']); // Ensure phone is in 260xxxxxxxxx format

        $url = "https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/{$apiKey}/contacts/{$formattedPhone}/senderId/{$senderId}/message/{$message}";

        Log::channel('sms')->info("[$requestId] Sending SMS via Zamtel API");

        /** @var TYPE_NAME $ch */
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => TRUE,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => TRUE
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //Get response
        $startTime = microtime(true);
        $response = curl_exec($ch);
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $responseBody = curl_getinfo($ch, CURLINFO_HEADER_OUT);

        //Print error if any
        if (curl_errno($ch)) {
            $isError = true;
            $errorMessage = curl_error($ch);
            Log::channel('sms')->error("[$requestId] cURL error", [
                'error' => $errorMessage,
                'curl_errno' => curl_errno($ch)
            ]);
        }

        curl_close($ch);

        Log::channel('sms')->info("[$requestId] Zamtel API response", [
            'http_code' => $httpcode,
            'execution_time_ms' => $executionTime,
            'response_preview' => substr($response, 0, 200)
        ]);


        if ($httpcode == 200) {
            // You can parse $response if needed
            Session::put('OTP', $OTP);
            Session::put('OTP_REQUEST_ID', $requestId);
            Session::put('OTP_TIMESTAMP', now()->timestamp);

            Log::channel('sms')->info("[$requestId] OTP sent successfully via Zamtel", [
                'phone' => $formattedPhone,
                'otp_stored_in_session' => true,
                'timestamp' => now()->toDateTimeString()
            ]);

            return $this->sendSuccess("OTP Sent!");
        } else {
            // Log error
            Log::channel('sms')->error("[$requestId] Failed to send OTP via Zamtel", [
                'phone' => $formattedPhone,
                'http_code' => $httpcode,
                'response_preview' => substr($response, 0, 500)
            ]);
            return $this->sendError('Failed to send OTP. Please try again later.');
        }
    }

    private function formatPhone($phone)
    {
        // Ensure phone starts with 260
        if (strpos($phone, '0') === 0) {
            return '260' . substr($phone, 1);
        }
        return $phone;
    }


    /**
     * Sending the OTP.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request){
        $requestId = uniqid('OTP-EMAIL-', true);

        Log::info("[$requestId] OTP Email request initiated", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $request->validate([
            'contact_email_address' => 'required|email'
        ]);

        $input = $request->all();
        $OTP = mt_rand(1000,9999);

        $contact_email_address = $input['contact_email_address'];
        $maskedEmail = substr($contact_email_address, 0, 3) . '***@' . explode('@', $contact_email_address)[1];

        Log::info("[$requestId] Sending OTP via Email to: $maskedEmail");

        try{

            Mail::send('auth.otp_email', ['OTP' => $OTP], function(Message $message) use ($contact_email_address){
                $message->subject("COVID-19 Immunisation Registry Verification Code");
                $message->to($contact_email_address);
            });

            Session::put('OTP', $OTP);
            Session::put('OTP_REQUEST_ID', $requestId);
            Session::put('OTP_TIMESTAMP', now()->timestamp);

            Log::info("[$requestId] OTP sent successfully via email", [
                'email_masked' => $maskedEmail,
                'otp_stored_in_session' => true,
                'timestamp' => now()->toDateTimeString()
            ]);

            return $this->sendSuccess("OTP Sent!");
        } catch (\Exception $e){
            Log::error("[$requestId] Error sending OTP via Email", [
                'error' => $e->getMessage(),
                'email_masked' => $maskedEmail,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->sendError('Failed to send OTP email. Please try again later.');
        }
    }

    /**
     * Function to verify OTP.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOTP(Request $request){
        $verificationId = uniqid('VERIFY-', true);
        $requestId = Session::get('OTP_REQUEST_ID', 'unknown');

        Log::info("[$verificationId] OTP verification attempt", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'original_request_id' => $requestId,
            'timestamp' => now()->toDateTimeString()
        ]);

        $request->validate([
            'OTP' => 'required|numeric',
            'client_id' => 'required|integer'
        ]);

        $input = $request->all();
        $enteredOTP = $input['OTP'];
        $client_id = $input['client_id'];

        $sessionOTP = $request->session()->get('OTP');
        $otpTimestamp = Session::get('OTP_TIMESTAMP');
        $otpAge = $otpTimestamp ? (now()->timestamp - $otpTimestamp) : null;

        Log::info("[$verificationId] OTP verification details", [
            'client_id' => $client_id,
            'otp_in_session' => !empty($sessionOTP),
            'otp_age_seconds' => $otpAge,
            'otp_match' => ($sessionOTP == $enteredOTP)
        ]);

        if($sessionOTP == $enteredOTP){
            // Clear OTP from session immediately
            Session::forget('OTP');
            Session::forget('OTP_REQUEST_ID');
            Session::forget('OTP_TIMESTAMP');

            Log::info("[$verificationId] OTP verification successful", [
                'client_id' => $client_id,
                'verification_time_seconds' => $otpAge
            ]);

            $certificate = Certificate::where('client_id', $client_id)->first();

            if(empty($certificate)){
                Log::warning("[$verificationId] Certificate not found for client", [
                    'client_id' => $client_id
                ]);
                return $this->sendError('Certificate not found!');
            } else {
                Log::info("[$verificationId] Certificate found and returned", [
                    'client_id' => $client_id,
                    'certificate_uuid' => $certificate->certificate_uuid
                ]);
                return $this->sendSuccess($certificate->certificate_url);
            }
        } else {
            Log::warning("[$verificationId] OTP verification failed - Invalid code", [
                'client_id' => $client_id,
                'otp_in_session' => !empty($sessionOTP),
                'otp_age_seconds' => $otpAge
            ]);
            return $this->sendError("Invalid verification code");
        }
    }
}
