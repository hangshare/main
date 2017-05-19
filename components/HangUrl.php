<?php
/**
 * Created by PhpStorm.
 * User: kaldmax
 * Date: 6/14/2016
 * Time: 7:16 PM
 */

namespace app\components;

use Yii;


class HangUrl extends \yii\web\UrlManager
{
    public function createUrl($params)
    {
        if (!isset($params['language']) && Yii::$app->language == 'en') {
            $params['language'] = Yii::$app->language;
        }
        $url =parent::createUrl($params);
        if (strpos($url, 'language=en') !== false) {
            $url = str_replace('?language=en', '', $url);
            $url = '/en'.$url;
        }
        $url = str_replace('/e/', 'en', $url);
        $url = str_replace('en/en', 'en', $url);
        $url = str_replace('een/', 'en/', $url);
        $url = str_replace('enarticles', '/en/articles', $url);

        return $url;
    }

    public function createAbsoluteUrl($params, $scheme = NULL)
    {
        if (!isset($params['language']) && Yii::$app->language == 'en') {
            $params['language'] = Yii::$app->language;
        }
        $url =parent::createAbsoluteUrl($params);
        if (strpos($url, 'language=en') !== false) {
            $url = str_replace('?language=en', '', $url);
            $url = '/en'.$url;
        }
        $url = str_replace('/e/', 'en', $url);
        $url = str_replace('en/en', 'en', $url);
        $url = str_replace('enarticles', '/en/articles', $url);
        return $url;
    }
}