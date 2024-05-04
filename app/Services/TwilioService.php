<?php namespace App\Services;
//SMS Api
use Twilio\Rest\Client;
class TwilioService {

    static function sendSMS($phone,$message) {
        try {
            $twilioClient = new Client(env('TWILIO_KEY'),env('TWILIO_TOKEN'));
            $service = $twilioClient->messages->create(
                str_replace(' ','',$phone),//Clear empty spaces
                [
                    'from' => cache('settings')->twilio_phone,
                    'body' => $message
                ]
            );
        } catch(\Exception $e) {
            return false;
        }
    }

    static function sendSMSVerification($phone) {
        $data =  json_encode([
            'phone' => $phone,
            'service' => cache('settings')->twilio_service,
            'token' => cache('settings')->twilio_token,
            'sid' => cache('settings')->twilio_sid,
        ]);
        try {
            $twilioClient = new Client(cache('settings')->twilio_sid,cache('settings')->twilio_token);
            $twilioClient->verify->v2->services(cache('settings')->twilio_service)->verifications
                        ->create($phone,"sms");
        } catch(\Twilio\Exceptions\TwilioException $e) {
            echo json_encode([
                'data' => $data,
                'debug' => [
                    'code' => $e->getCode(),
                    'file'     => $e->getFile(),
                    'line'     => $e->getLine(),
                    'message'  => $e->getMessage(),
                    'trace'    => $e->getTraceAsString()],
            ]);
            return false;
        }
    }

    static function checkSMSVerification($phone,$code) {
        try {
            $config = \cache('settings');
            $key    = $config->twilio_sid;
            $token  = $config->twilio_token;
            $token_sms_verification = $config->twilio_service;
            $twilioClient = new Client($key,$token);
            $serviceCheck = $twilioClient->verify->v2->services($token_sms_verification)->verificationChecks
                            ->create($code,["to" => $phone]);
            return $serviceCheck->status;
        } catch(\Exception $e) {
            return false;
        }
    }

    static function aproveSMSVerification($phone) {
        try {
            $config = \cache('settings');
            $key    = $config->twilio_sid;
            $token  = $config->twilio_token;
            $token_sms_verification = $config->twilio_service;
            $twilioClient    = new Client($key,$token);
            $serviceApproved = $twilioClient->verify->v2->services($token_sms_verification)->verifications($phone)
                            ->update("approved");
        } catch(\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
}