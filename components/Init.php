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
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->deleted) {
                if (strpos(Yii::$app->request->url, 'u/suspended') === false)
                    Yii::$app->getResponse()->redirect(['//u/suspended']);
            }
        }

        if (strpos(Yii::$app->request->url, 'tag=') !== false) {
            $url = Yii::$app->urlManager->createUrl(["//tags/{$_GET['tag']}"]);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$url}");
            exit(0);
        }

        if (strpos(Yii::$app->request->url, 'explore/index') !== false) {
            $url = Yii::$app->urlManager->createUrl(["//مواضيع"]);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$url}");
            exit(0);
        }


        $this->language();

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

        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if(strpos(Yii::$app->request->url, 'en/') === false && $lang == 'en' && Yii::$app->request->url == '/' && !isset($_COOKIE['userlanghangshare'])){
            header("Location: https://www.hangshare.com/en/");
            exit(0);
        }
        if (strpos(Yii::$app->request->url, 'en/') === false && isset($_COOKIE['userlanghangshare']) && $_COOKIE['userlanghangshare'] == 'en') {
//            header("Location: https://www.hangshare.com/en/");
//            exit(0);
        }
        $date_of_expiry = time() + 5184000;
        if (strpos(Yii::$app->request->url, 'en/') !== false || strpos(Yii::$app->request->url, '/web/en') !== false) {
            Yii::$app->language = 'en';
            Yii::$app->homeUrl = Yii::getAlias('@web') . '/en/';
            $_GET['language'] = 'en';
            setcookie("userlanghangshare", "en", $date_of_expiry, '/', '.hangshare.com');
        } else {
            Yii::$app->language = 'ar';
            Yii::$app->homeUrl = '/';
            $_GET['language'] = 'ar';
            setcookie("userlanghangshare", "ar", $date_of_expiry, '/', '.hangshare.com');
        }
    }

    protected function isHome()
    {
        $controller = Yii::$app->controller;
        $default_controller = Yii::$app->defaultRoute;
        $isHome = (($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction)) ? true : false;
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
