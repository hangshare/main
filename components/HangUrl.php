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

        if (!isset($params['language'])) {
            if (Yii::$app->session->has('language'))
                Yii::$app->language = Yii::$app->session->get('language');
            else if(isset(Yii::$app->request->cookies['language']))
                Yii::$app->language = Yii::$app->request->cookies['language']->value;
            $params['language']=Yii::$app->language;
        }
        return parent::createUrl($params);


//        $url = parent::createUrl($params);
//
//        if (Yii::$app->language == 'en') {
////            $params['language'] = Yii::$app->language;
////            $url = str_replace('.com', '.com/en', $url);
////            $url = str_replace('hangshare/web', 'hangshare/web/en', $url);
////            $url = str_replace('?language=en', '', $url);
//
//            $url = 'en'.$url;
////            $url = str_replace('/e/', '/en/', $url);
////            $url = str_replace('en/en', 'en', $url);
////            $url = str_replace('earticles', 'en/articles', $url);
////            $url = str_replace('een', 'en', $url);
//
//        }
//        $url = str_replace('en/en/en', 'en', $url);
//        $url = str_replace('en/en', 'en', $url);
//        return $url;
    }

    public function createAbsoluteUrl($params)
    {
        if (Yii::$app->language == 'en')
            $params['language'] = Yii::$app->language;

        $url = parent::createAbsoluteUrl($params);
        if (strpos($url, 'language=en') !== false) {
            $url = str_replace('.com', '.com/en', $url);
            $url = str_replace('hangshare/web', 'hangshare/web/en', $url);
            $url = str_replace('?language=en', '', $url);
        }
        $url = str_replace('/e/', '/en/', $url);
        $url = str_replace('en/en', 'en', $url);
        $url = str_replace('earticles', 'en/articles', $url);
        $url = str_replace('een', 'en', $url);
        return $url;
    }
}