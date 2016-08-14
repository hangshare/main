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
        if (Yii::$app->language == 'en')
            $params['language'] = Yii::$app->language;

        $url = parent::createUrl($params);
        if (strpos($url, 'language=en') !== false) {
            $url = str_replace('.com', '.com/en', $url);
            $url = str_replace('hangshare/web', 'hangshare/web/en', $url);
            $url = str_replace('?language=en', '', $url);
        }
        return $url;
    }
}