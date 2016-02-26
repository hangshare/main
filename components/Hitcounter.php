<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Hitcounter extends Component
{

    const HIT_OLD_AFTER_SECONDS = 260000; // 3 days.
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
        setcookie($co_key, $country_code, time() + self::HIT_OLD_AFTER_SECONDS * 150, "/");
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
        if (self::HONOR_DO_NOT_TRACK && isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == "1") {
            return false;
        }
        if ($id_details = self::ip_details()) {
            if (!in_array($id_details, self::$COUNTRY_CODE)) {
                return false;
            }
        } else {
            return false;
        }

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
        $pricear = self::VIEW_PRICE;
        if (!isset($pricear[$plan])) {
            $pricear[0];
        } else {
            $price = $pricear[$plan];
        }
        if (isset($_COOKIE[$hashId]) || isset($_COOKIE["hangView-$id"])) {
            return false;
        }
        $vie = Yii::$app->db->createCommand("SELECT 1 FROM `post_view` WHERE hash = '{$hashId}'")->queryScalar();
        if ($vie) {
            return false;
        }

        if (Yii::$app->user->isGuest || $post_user_id != Yii::$app->user->identity->id) {
            Yii::$app->db->createCommand("UPDATE `post_stats` SET `views`=`views`+1 WHERE `postId`= {$id}")->query();
            Yii::$app->db->createCommand("UPDATE `user_stats` SET `post_total_views`=`post_total_views`+1, `post_views`=`post_views`+1, `available_amount`=`available_amount`+{$price}, `total_amount`=`total_amount`+ {$price} WHERE `userId`= {$post_user_id}")->query();
            self::logView($id, $price);
            setcookie($hashId, true, time() + self::HIT_OLD_AFTER_SECONDS, "/");
            return true;
        } else {
            return false;
        }

        return true;
    }

    private static function logView($id, $price)
    {
        $ip = self::getRemoteIPAddress();
        $hashId = self::UniqueHit($id);
        $userId = 0;
        $userAgent = json_encode($_SERVER);
        if (!Yii::$app->user->isGuest)
            $userId = Yii::$app->user->identity->id;
        Yii::$app->db->createCommand("
            INSERT INTO `hangshare`.`post_view` (`price` , `userId` , `postId` , `ip` ,`ip_info` , `hash` , `user_agent`)
                    VALUES ({$price}, {$userId}, {$id}, '{$ip}',:ipinfo, '{$hashId}', '{$userAgent}');
                ", [
            ':ipinfo' => self::$ip_info
        ])->query();
        return true;
    }

}
