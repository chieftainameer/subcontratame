<?php
namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
class UtilsService {

    static function deleteFile($path) {
        try {
            if(Storage::disk('public')->exists($path)){
                Storage::disk('public')->delete($path);
                return true;
            }
            return true;
         } catch(\Exception $e) {
            return false;
        }
    }

    static function setLocationLang($lang="en") {
        session(['applocale' => $lang]);
    }

    public function sendPushNotification($id, $title, $message, $data=null) {
        $config = Setting::first();
        try {
            // Select channel for OneSignal
            $app = $config->onesignal_dev==0?$config->onesignal_app_customer_id:$config->onesignal_dev_app_customer_id;
            $key = $config->onesignal_dev==0?$config->onesignal_app_customer_token:$config->onesignal_dev_app_customer_token;
            $content = ["en" => $message];
            $head 	 = ["en" => $title];
            $fields = array(
            'app_id' => $app,
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
            'contents' => $content,
            'headings' => $head);
            $fields = json_encode($fields);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, env('ONESIGNAL_API'));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$key));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        } catch(\Exception $e) {
            echo $e->getMessage();
            echo $e->getLine();
        }
    }

    public static function overWriteEnvFile($var, $value) {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$var}=");
        $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        $str = str_replace($oldLine, "{$var}={$value}", $str);
        $str = substr($str, 0, -1);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);

        \App\Helpers\DynamicEnvironment::set($var,$value);
    }

    public static function number_kmb($count, $precision = 2) {
        if($count < 1000) {
            $n_format = $count;
        } else if ($count < 1000000) {
            // Anything less than a million
            $n_format = number_format($count / 1000) . 'K';
        } else if ($count < 1000000000) {
            // Anything less than a billion
            $n_format = number_format($count / 1000000, $precision) . 'M';
        } else {
            // At least a billion
            $n_format = number_format($count / 1000000000, $precision) . 'B';
        }
        return $n_format;
    }

    public static function get_customer_new_code($data = null) {
        if($data) {
            $names = explode(' ',$data['name']);
            $code = '';
            foreach($names as $key => $name) {
                $code .= $name.'.';
            }
            // remove las character when this us a point
            if(substr($code,strlen($code)-1,1) == '.') {
                $code = substr($code,0,strlen($code)-1);
            }
            //check if already existe 
            $user = User::where('code',$code)->first();
            if($user) {
                $code .= $user->id++;
            }

            if($data['code'] && $data['id'] & $data['type']){
                $code_owner = $data['code'];
            }
            else{
                $code_owner = $data['code']  ? $data['code'] : User::first()->code;
            }
        } else {
            $names = explode(' ',request('name'));
            $code = '';
            foreach($names as $key => $name) {
                $code .= $name.'.';
            }
            // remove las character when this us a point
            if(substr($code,strlen($code)-1,1) == '.') {
                $code = substr($code,0,strlen($code)-1);
            }
            //check if already existe 
            $user = User::where('code',$code)->first();
            if($user) {
                $code .= $user->id++;
            }

            if(request()->get('code') && request()->get('id') && request()->get('type')){
                $code_owner = request()->get('code');
            }
            else{
                $code_owner = session()->has('code') ? session()->get('code') : User::first()->code;
            }
        }
        return [
            'code' => $code,
            'code_owner' => $code_owner
        ];
    }

    static function updateSettingsCache() {
        $settings = \App\Models\Setting::first();
        Cache::set('settings', $settings);
    }

    static function getSettingsCache() {
        return Cache::get('settings');
    }

    static function setCache($name, $value) {
        Cache::set($name, $value);
    }

    static function getCache($name) {
        return Cache::get($name);
    }

    static function removeCache($name) {
        Cache::forget($name);
    }
    static function clearCache() {
        Cache::flush();
    }
}
