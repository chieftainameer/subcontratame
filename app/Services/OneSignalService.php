<?php
namespace App\Services;
use GuzzleHttp\Client;
class OneSignalService {
    //OneSignal
    static function sendPushNotification($id, $title, $message, $data=null) {
        try {
            $params = [
                'app_id' => cache('settings')->onesignal_id,
                'included_segments' => array('All'),
                'filters' => [
                    0 => [
                            'field' => 'tag',
                            'key' => 'id',
                            'relation' => '=',
                            'value' => $id
                    ]
                ],
                'data' => $data!=null?$data:array("foo" => "bar"),
                'contents' => ["en" => $message],
                'headings' => ["en" => $title]
            ];
            $fields = json_encode($params);
            $clientHTTP = new Client();
            $clientHTTP->post('https://onesignal.com/api/v1/notifications', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.cache('settings')->onesignal_token
                ],
                'body' => $fields
            ]);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
    // Twillio + OneSignal
    static function sendSMS(String $phone, String $message) {
        try {
            $params = [
                'app_id' => cache('settings')->onesignal_id,
                'name' => cache('settings')->site_name,
                'sms_from' => cache('settings')->twilio_phone,
                'contents' => [
                    'en' => $message
                ],
                'include_phone_numbers' => [
                    $phone
                ]
            ];
            $fields = json_encode($params);
            $clientHTTP = new Client();
            $clientHTTP->post('https://onesignal.com/api/v1/notifications', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.cache('settings')->onesignal_token
                ],
                'body' => $fields
            ]);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
    // SendBlue / sendingGrid
    static function sendEmail($email,$subject, $content) {
        try {
            $params = [
                'app_id' => cache('settings')->onesignal_id,
                'email_subject' => $subject,
                'sms_from' => cache('settings')->twilio_phone,
                'email_body' => $content,
                'included_segments' => \is_array($email)?$email:array($email)
            ];
            $fields = json_encode($params);
            $clientHTTP = new Client();
            $clientHTTP->post('https://onesignal.com/api/v1/notifications', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.cache('settings')->onesignal_token
                ],
                'body' => $fields
            ]);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
}