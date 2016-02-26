<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Post;

class Init extends Component {

    public function init() {
        if (strpos(Yii::$app->request->url, 'explore/') !== false && strpos(Yii::$app->request->url, 'title') !== false) {
            $id = $this->get_string_between(Yii::$app->request->url, 'explore/', '?title=');
            $post = Post::findOne(['id' => $id]);
            if(!isset($post)){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$post->url}");
            exit(0);
        }
        $need_to_add_trailing_slash = preg_match('~^http?://[^/]+$~', Yii::$app->request->url);
        var_dump($need_to_add_trailing_slash);die();
        if($need_to_add_trailing_slash){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$url}/");
        }

        parent::init();
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
