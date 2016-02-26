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

            header("Location: {$post->url}");
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
