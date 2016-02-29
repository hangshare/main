<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Post;

class Init extends Component {

    public function init() {

        if (strpos(Yii::$app->request->url, 'explore/') !== false && strpos(Yii::$app->request->url, 'title') !== false) {
            $id = $this->get_string_between(Yii::$app->request->url, 'id=', '&');
            if(!is_numeric($id)){
                $id = $this->get_string_between(Yii::$app->request->url, 'id=', '?title=');
            }
            if(!is_numeric($id)){
                $id = $this->get_string_between(Yii::$app->request->url, 'explore/', '/?');
            }
            if(!is_numeric($id)){
                $id = $this->get_string_between(Yii::$app->request->url, 'explore/', '?title');
            }
            $post = Post::findOne(['id' => $id]);
            if(!isset($post)){
                header("Location: http://www.hangshare.com/%D9%85%D9%82%D8%A7%D9%84%D8%A7%D8%AA/");
            }
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$post->url}");
            exit(0);
        }

        if(strpos(Yii::$app->request->url,'explore/view') !== false){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: http://www.hangshare.com/%D9%85%D9%82%D8%A7%D9%84%D8%A7%D8%AA/");
            exit(0);
        }
        if(strpos(Yii::$app->request->url,'site/index') !== false){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: http://www.hangshare.com/");
            exit(0);
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
