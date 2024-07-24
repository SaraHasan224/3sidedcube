<?php


namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Lcobucci\JWT\Parser as JwtParser;

class Helper
{
    public static function assets($path, $secure = null) {
        return app('url')->asset($path.'/asset', $secure);
    }

    public static function clean($string) {
        if(empty($string))
            return $string;

        $string = trim($string); // Replaces all spaces with no s.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    static function formatPhoneNumber($phone)
    {
        $phone = str_replace('+', '', $phone);
        if (strlen($phone) > 2)
        {
            $phone = substr($phone, 2);
        }
        return $phone;
    }

    static function dated_by($name, $date)
    {
        $text = '';
        if(!empty($name))
        {
            $text .= '<b>' . $name . '</b><br/>';
        }
        if (!empty($date)) {
            $text .= self::dateTime($date);
        }
        return $text;
    }

    static function dateTime($timestamp)
    {
        if (empty($timestamp)) return '';
        $localTimeZone = self::getUserLocalTime();
        $timestampFormat = 'Y-m-d H:i:s';
        return Carbon::createFromFormat($timestampFormat, $timestamp, 'UTC')
            ->setTimezone($localTimeZone)
            ->format('d M Y h:i  A');
    }

    public static function generateSlugReference($name) {
        $randString = substr( time().mt_rand(111, 999) , 8);
        $slug = self::clean(str_replace(' ', '-', $name));
        return (string) $randString."_".strtolower($slug);
    }

    public static function getUserIP($request)
    {
        $ip = null;
        if ($_SERVER && isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $request->getClientIp();
        }

        return explode(',', $ip)[0];
    }

    public static function obfuscateString($str)
    {
        return substr($str, 0, min(5, strlen($str) - 5)) .
          str_repeat('*', max(6, strlen($str) - 6)) .
          substr($str, strlen($str) - 2, min(2, strlen($str) - 2));
    }

    public static function randomDigits()
    {
        return mt_rand(100000, 999999);
    }


    public static function getUserLocalTime()
    {
        return Constant::timezone;
    }

    public static function utcToUserLocalTimeZone($timestamp)
    {
        if (empty($timestamp)) return '';
        $localTimeZone = Helper::getUserLocalTime();
        $timestampFormat = 'Y-m-d H:i:s';
        return Carbon::createFromFormat($timestampFormat, $timestamp, 'UTC')
          ->setTimezone($localTimeZone)
          ->format('F d, Y');
//        ->format('d M Y h:i  A');
    }

    public static function moveToTop(&$array, $key) {
//        $temp = array($key => $array[$key]);
//        unset($array[$key]);
//        $array = $temp + $array;
//        return (array) $array;

        $last_key     = key($array);
        $last_value   = array_pop($array);
        return array_merge(array($last_key => $last_value), $array);
    }

    public static function moveToBottom(&$array, $key) {
        $value = $array[$key];
        unset($array[$key]);
        $array[$key] = $value;
        return $array;
    }

    public static function generateAccessToken($request, $store) {
        $scheme = (strtolower(config('app.APP_ENV')) == "local") ? "http" : "https";
        $url = "{$scheme}://".config('app.API_DOMAIN')."/v1/passport/token";

        $tokenRequest = Request::create($url, 'POST', $request->all());
        $response = app()->handle($tokenRequest);

        if ($response->getStatusCode() == Http::$Codes[Http::SUCCESS]) {
            $responseBody = json_decode($response->getContent(), true);

            $token = $responseBody['access_token'];
            $id = app(JwtParser::class)->parse($token)->claims()->get('jti');

            $storeSlug = optional($store)->store_slug;
            AccessToken::addIdentifierToAccessToken($id, $storeSlug );

            return [
                'status' => Http::$Codes[Http::SUCCESS],
                'result' => $responseBody,
                'message' => ''
            ];
        }
        return [
            'status' => Http::$Codes[Http::BAD_REQUEST],
            'result' => '',
            'message' => __('messages.general.failed')
        ];
    }

    public static function formatNumber($number, $decimals = 2)
    {
        return (float)number_format((float)$number, $decimals, '.', '');
    }

    static function validationErrors($request, $rules, $messages = [])
    {
        if (is_array($request))
        {
            $formData = $request;
        }
        else
        {
            $formData = $request->all();
        }

        $validator = Validator::make($formData, $rules, $messages);

        if ($validator->fails())
        {
            return $validator->errors();
        }
        else
        {
            return false;
        }
    }
}
