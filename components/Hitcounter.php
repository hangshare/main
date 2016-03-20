<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use Memcached;

class Hitcounter extends Component
{

    const HIT_OLD_AFTER_SECONDS = 80000; // 1 day.
    const IGNORE_SEARCH_BOTS = true;
    const HONOR_DO_NOT_TRACK = false;
    const VIEW_PRICE = [
        0 => 0.0005,
        1 => 0.0015
    ];

    private static $ip_info = '';
    private static $IP_IGNORE_LIST = array(
        '127.0.0.1'
    );
    private static $COUNTRY_CODE = array(
        'JO', 'EG', 'KW', 'ISR', 'SA', 'BH', 'SY', 'AE', 'IR', 'LB', 'MA',
        'OM', 'QA', 'SD', 'YE', 'IL', 'MR', 'DJ', 'LB', 'DZ', 'TN', 'PS', 'TR', 'IQ'
    );

    public static function get_http_response_code($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    public static function ip_details()
    {
        $details = '';
        $reip = self::getRemoteIPAddress();
        $co_key = 'hangusercountrycode';
        if (isset($_COOKIE[$co_key])) {
            return $_COOKIE[$co_key];
        }
        $url = "http://ipinfo.io/{$reip}";
        if (self::get_http_response_code($url) != "200") {
            $url = "http://www.geoplugin.net/json.gp?ip={$reip}";
            if (self::get_http_response_code($url) != "200") {
                $url = "http://ip-api.com/json/{$reip}";
                if (self::get_http_response_code($url) != "200") {
                    $url = "https://freegeoip.net/json/{$reip}";
                }
            }
        }
        $json = @file_get_contents($url);
        self::$ip_info = $json;

        if (!isset($_COOKIE['ip_info'])) {
            setcookie('ip_info', $json, time() + 99999999, "/");
        }

        $details = json_decode($json);

        if (isset($details->countryCode)) {
            $country_code = $details->countryCode;
        }

        if (isset($details->country_code)) {
            $country_code = $details->country_code;
        }

        if (isset($details->geoplugin_countryCode)) {
            $country_code = $details->geoplugin_countryCode;
        }

        if (isset($details->country)) {
            $country_code = $details->country;
        }
        if (!isset($country_code)) {
            return false;
        }
        setcookie($co_key, $country_code, time() + 99999999, "/");
        return $country_code;
    }

    public static function getRemoteIPAddress()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return $_SERVER['REMOTE_ADDR'];
    }

    public function AddHit($pageID, $post_user_id, $plan)
    {
        if (self::IGNORE_SEARCH_BOTS && self::IsSearchBot())
            return false;
        if (in_array(self::getRemoteIPAddress(), self::$IP_IGNORE_LIST))
            return false;
        if (self::HONOR_DO_NOT_TRACK && isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == "1")
            return false;
        if (!Yii::$app->user->isGuest && Yii::$app->user->id == $post_user_id)
            return false;

        return self::CreateCountsIfNotPresent($pageID, $post_user_id, $plan);
    }

    private static function IsSearchBot()
    {
        $keywords = array(
            'bot', 'spider', 'spyder', 'crawlwer', 'walker', 'search', 'holmes',
            'htdig', 'archive', 'tineye', 'yacy', 'yeti',
            '008', 'accoona-ai-agent', 'yooglifetchagent', 'zao', 'zspider', 'zyBorg',
            'tineye', 'truwogps', 'updated', 'vagabondo', 'vortex', 'voyager', 'vyu2', 'webcollage', 'websquash.com', 'wf84', 'womlpefactory',
            'spider', 'yacy', 'yahoo',
        );
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        foreach ($keywords as $keyword) {
            if (strpos($agent, $keyword) !== false)
                return true;
        }
        return false;
    }

    private static function UniqueHit($pageID)
    {
        return hash("SHA256", $pageID . self::getRemoteIPAddress());
    }

    private static function CreateCountsIfNotPresent($id, $post_user_id, $plan)
    {
        $hashId = self::UniqueHit($id);
        if (isset($_COOKIE[$hashId]) || isset($_COOKIE["hangView-$id"])) {
            return false;
        }
        $memcached = new \Memcache();
        $memcached->addserver('127.0.0.1');
        $res = $memcached->get('hang_mem_views');
        $res[$id][$hashId] = [
            'country_code' => self::ip_details(),
            'ip' => self::getRemoteIPAddress(),
            'post_user_id' => $post_user_id,
            'plan' => $plan,
            'userAgent' => json_encode($_SERVER),
            'userId' => Yii::$app->user->isGuest ? 0 : Yii::$app->user->identity->id,
            'ip_info' => isset($_COOKIE['ip_info']) ? $_COOKIE['ip_info'] : ''
        ];
//        var_dump($_COOKIE);

        $memcached->set('hang_mem_views', $res);

        setcookie($hashId, true, time() + self::HIT_OLD_AFTER_SECONDS, "/");
        return true;
    }

}
