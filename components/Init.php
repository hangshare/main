<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Post;
use yii\helpers\Url;

class Init extends Component
{

    public function init()
    {

        $this->language();

//        if (!Yii::$app->user->isGuest) {
//            if(strpos(Yii::$app->request->url, 'missing/') === false) {
//                if (!filter_var(Yii::$app->user->identity->email, FILTER_VALIDATE_EMAIL)) {
//                    header("Location: https://www.hangshare.com/u/missing/");
//                    exit(0);
//                }
//            }
//        }
        if (strpos(Yii::$app->request->url, 'explore/') !== false && strpos(Yii::$app->request->url, 'title') !== false) {
            $id = $this->get_string_between(Yii::$app->request->url, 'id=', '&');
            if (!is_numeric($id)) {
                $id = $this->get_string_between(Yii::$app->request->url, 'id=', '?title=');
            }
            if (!is_numeric($id)) {
                $id = $this->get_string_between(Yii::$app->request->url, 'explore/', '/?');
            }
            if (!is_numeric($id)) {
                $id = $this->get_string_between(Yii::$app->request->url, 'explore/', '?title');
            }
            $post = Post::findOne(['id' => $id]);

            if (!isset($post)) {
                header("Location: https://www.hangshare.com/%D9%85%D9%82%D8%A7%D9%84%D8%A7%D8%AA/");
                exit(0);
            }
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$post->url}");
            exit(0);
        }

        if (strpos(Yii::$app->request->url, '//') !== false) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: https://www.hangshare.com/");
            exit(0);
        }

        if (strpos(Yii::$app->request->url, 'explore/view') !== false) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: https://www.hangshare.com/%D9%85%D9%82%D8%A7%D9%84%D8%A7%D8%AA/");
            exit(0);
        }
        if (strpos(Yii::$app->request->url, 'site/index') !== false) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: https://www.hangshare.com/");
            exit(0);
        }
        parent::init();
    }

    protected function language()
    {
        if (strpos(Yii::$app->request->url, 'en') !== false || strpos(Yii::$app->request->url, '/web/en') !== false) {
            Yii::$app->language = 'en';
            Yii::$app->homeUrl = Yii::getAlias('@web') . '/en/';
            //setcookie('userlanghangshare', 'en', time() + 999999, "/");
            $_GET['language'] = 'en';
        } elseif (isset($_COOKIE['userlanghangshare']) && $_COOKIE['userlanghangshare'] == 'en' && $this->isHome()) {
            $_GET['language'] = 'en';
            Yii::$app->homeUrl = Yii::getAlias('@web') . '/en/';
            //header("HTTP/1.1 302 Moved Permanently");
            //header("Location: http://localhost/hangshare/web/en/");
            //header("Location: https://www.hangshare.com/en/");
            //exit(0);
        } else {
            //setcookie('userlanghangshare', 'ar', time() + 999999, "/");
        }
    }

    protected function isHome()
    {
        $controller = Yii::$app->controller;
        $default_controller = Yii::$app->defaultRoute;
        $isHome = (($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction)) ? true : false;
        return true;
        return $isHome;
    }

    public function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

}
