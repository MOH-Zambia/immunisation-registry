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
        $contactNumber = $this->formatPhone($input['contact_number']);

        Log::channel('sms')->info("[$requestId] Validation passed for contact: " . substr($contactNumber, -4));

        $OTP = mt_rand(1000,9999);
        $message = "COVID-19 Immunisation Registry, \nYour One Time Password to access your COVID-19 Certificate is {$OTP}";

        // Intelligently choose gateway - prefer Zamtel if configured, fallback to Kannel
        $zamtelConfigured = !empty(env('ZAMTEL_BULK_SMS_API_KEY')) && !empty(env('ZAMTEL_SENDER_ID'));
        $kannelConfigured = !empty(env('KANNEL_HOST')) && !empty(env('KANNEL_USERNAME'));

        Log::channel('sms')->info("[$requestId] Gateway availability", [
            'zamtel_configured' => $zamtelConfigured,
            'kannel_configured' => $kannelConfigured
        ]);

        // Try Zamtel first if configured, otherwise use Kannel
        if ($zamtelConfigured) {
            $result = $this->sendViaZamtel($contactNumber, $message, $requestId);
        } elseif ($kannelConfigured) {
            $result = $this->sendViaKannel($contactNumber, $message, $requestId);
        } else {
            Log::channel('sms')->error("[$requestId] No SMS gateway configured");
            return $this->sendError('SMS gateway not configured. Please contact support.');
        }

        if($result['success']){
            Session::put('OTP', $OTP);
            Session::put('OTP_REQUEST_ID', $requestId);
            Session::put('OTP_TIMESTAMP', now()->timestamp);

            Log::channel('sms')->info("[$requestId] OTP sent successfully via SMS", [
                'contact_last4' => substr($contactNumber, -4),
                'otp_stored_in_session' => true,
                'timestamp' => now()->toDateTimeString(),
                'http_code' => $result['http_code'],
                'execution_time_ms' => $result['execution_time']
            ]);

            return $this->sendSuccess("OTP Sent!");
        }else{
            Log::channel('sms')->error("[$requestId] Failed to send OTP via SMS", [
                'error' => $result['error'],
                'contact_last4' => substr($contactNumber, -4)
            ]);
            return $this->sendError('Failed to send OTP. Please try again later.');
        }
    }

    public function sendSMSViaZamtelBulkSMS(Request $request)
    {
        $requestId = uniqid('OTP-ZAMTEL-', true);

        Log::channel('sms')->info("[$requestId] OTP SMS request initiated (Zamtel Bulk SMS)", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $request->validate([
            'contact_number' => 'required|string'
        ]);

        $input = $request->all();
        $contactNumber = $this->formatPhone($input['contact_number']);

        Log::channel('sms')->info("[$requestId] Validation passed for contact: " . substr($contactNumber, -4));

        $OTP = mt_rand(1000,9999);
        $message = "COVID-19 Immunisation Registry, \nYour One Time Password to access your COVID-19 Certificate is {$OTP}";

        // Use the tested helper method
        $result = $this->sendViaZamtel($contactNumber, $message, $requestId);

        if($result['success']){
            Session::put('OTP', $OTP);
            Session::put('OTP_REQUEST_ID', $requestId);
            Session::put('OTP_TIMESTAMP', now()->timestamp);

            Log::channel('sms')->info("[$requestId] OTP sent successfully via Zamtel", [
                'phone_last4' => substr($contactNumber, -4),
                'otp_stored_in_session' => true,
                'timestamp' => now()->toDateTimeString(),
                'http_code' => $result['http_code'],
                'execution_time_ms' => $result['execution_time']
            ]);

            return $this->sendSuccess("OTP Sent!");
        }else{
            Log::channel('sms')->error("[$requestId] Failed to send OTP via Zamtel", [
                'phone_last4' => substr($contactNumber, -4),
                'error' => $result['error'],
                'http_code' => $result['http_code'] ?? null
            ]);
            return $this->sendError('Failed to send OTP. Please try again later.');
        }
    }

    private function formatPhone($phone)
    {
        // Remove any whitespace
        $phone = trim($phone);

        // Remove leading + if present
        if (strpos($phone, '+') === 0) {
            $phone = substr($phone, 1);
        }

        // If starts with 0 (local format like 0969928546), replace with 260
        if (strpos($phone, '0') === 0) {
            return '260' . substr($phone, 1);
        }

        // If starts with 9 (missing country code like 969928546), add 260
        if (strpos($phone, '9') === 0 && strlen($phone) === 9) {
            return '260' . $phone;
        }

        // If already starts with 260, return as is
        if (strpos($phone, '260') === 0) {
            return $phone;
        }

        // Default: assume it needs 260 prefix
        return '260' . $phone;
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

    /**
     * Display SMS testing interface for admins
     *
     * @return \Illuminate\View\View
     */
    public function showTestSmsInterface()
    {
        return view('admin.test_sms');
    }

    /**
     * Send test SMS via selected gateway (admin only)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTestSMS(Request $request)
    {
        $requestId = uniqid('TEST-SMS-', true);

        Log::channel('sms')->info("[$requestId] Test SMS request initiated", [
            'admin_user_id' => auth()->id(),
            'admin_email' => auth()->user()->email,
            'ip' => $request->ip(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $validated = $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string|max:160',
            'gateway' => 'required|in:kannel,zamtel'
        ]);

        $phoneNumber = $validated['phone_number'];
        $message = $validated['message'];
        $gateway = $validated['gateway'];

        Log::channel('sms')->info("[$requestId] Test SMS details", [
            'gateway' => $gateway,
            'phone_last4' => substr($phoneNumber, -4),
            'message_length' => strlen($message)
        ]);

        try {
            if ($gateway === 'zamtel') {
                $result = $this->sendViaZamtel($phoneNumber, $message, $requestId);
            } else {
                $result = $this->sendViaKannel($phoneNumber, $message, $requestId);
            }

            if ($result['success']) {
                Log::channel('sms')->info("[$requestId] Test SMS sent successfully", [
                    'gateway' => $gateway,
                    'execution_time_ms' => $result['execution_time']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Test SMS sent successfully!',
                    'details' => [
                        'gateway' => $gateway,
                        'phone' => substr($phoneNumber, 0, 3) . '***' . substr($phoneNumber, -4),
                        'http_code' => $result['http_code'],
                        'execution_time_ms' => $result['execution_time'],
                        'response_preview' => $result['response_preview']
                    ]
                ]);
            } else {
                Log::channel('sms')->error("[$requestId] Test SMS failed", [
                    'gateway' => $gateway,
                    'error' => $result['error']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test SMS',
                    'error' => $result['error'],
                    'details' => [
                        'gateway' => $gateway,
                        'http_code' => $result['http_code'] ?? null,
                        'response_preview' => $result['response_preview'] ?? null
                    ]
                ], 500);
            }
        } catch (\Exception $e) {
            Log::channel('sms')->error("[$requestId] Test SMS exception", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending test SMS',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send SMS via Zamtel API
     *
     * @param string $phoneNumber
     * @param string $message
     * @param string $requestId
     * @return array
     */
    private function sendViaZamtel($phoneNumber, $message, $requestId)
    {
        $apiKey = env('ZAMTEL_BULK_SMS_API_KEY');
        $senderId = env('ZAMTEL_SENDER_ID');

        if (empty($apiKey) || empty($senderId)) {
            return [
                'success' => false,
                'error' => 'Zamtel API credentials not configured'
            ];
        }

        $formattedPhone = $this->formatPhone($phoneNumber);
        $encodedMessage = urlencode($message);

        $url = "https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/{$apiKey}/contacts/{$formattedPhone}/senderId/{$senderId}/message/{$encodedMessage}";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);

        $startTime = microtime(true);
        $response = curl_exec($ch);
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if (!empty($curlError)) {
            return [
                'success' => false,
                'error' => $curlError,
                'http_code' => $httpCode,
                'execution_time' => $executionTime
            ];
        }

        return [
            'success' => $httpCode == 200,
            'http_code' => $httpCode,
            'execution_time' => $executionTime,
            'response_preview' => substr($response, 0, 200),
            'error' => $httpCode != 200 ? "HTTP $httpCode" : null
        ];
    }

    /**
     * Send SMS via Kannel gateway
     *
     * @param string $phoneNumber
     * @param string $message
     * @param string $requestId
     * @return array
     */
    private function sendViaKannel($phoneNumber, $message, $requestId)
    {
        $host = env('KANNEL_HOST');
        $port = env('KANNEL_PORT');
        $smsc = env('KANNEL_SMSC');
        $username = env('KANNEL_USERNAME');
        $password = env('KANNEL_PASSWORD');
        $from = env('KANNEL_SENDER');

        if (empty($host) || empty($port) || empty($username) || empty($password)) {
            return [
                'success' => false,
                'error' => 'Kannel gateway credentials not configured'
            ];
        }

        $encodedMessage = urlencode($message);
        $url = "http://{$host}:{$port}/cgi-bin/sendsms?username={$username}&password={$password}&smsc={$smsc}&from={$from}&to={$phoneNumber}&text={$encodedMessage}";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);

        $startTime = microtime(true);
        $response = curl_exec($ch);
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if (!empty($curlError)) {
            return [
                'success' => false,
                'error' => $curlError,
                'http_code' => $httpCode,
                'execution_time' => $executionTime
            ];
        }

        return [
            'success' => true, // Kannel typically returns 200 for queued messages
            'http_code' => $httpCode,
            'execution_time' => $executionTime,
            'response_preview' => substr($response, 0, 200),
            'error' => null
        ];
    }
}
