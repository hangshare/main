<?php
/**
 * Created by PhpStorm.
 * User: kaldm
 * Date: 2/26/2016
 * Time: 1:23 AM
 */

namespace app\commands;

use Yii;
use yii\console\Controller;

class FixController extends Controller
{
    public function actionS3post()
    {
        $time = time() - 4000;
        $lastId =2209;
        $posts = Yii::$app->db->createCommand("SELECT t.id, body.body FROM post t
            LEFT JOIN post_body body on(t.id = body.postId)
            WHERE UNIX_TIMESTAMP(t.created_at) < $time AND  t.id > $lastId  LIMIT 4000")->queryAll();

        print 'Total : '. count($posts) .chr(10);
        foreach ($posts as $post) {
            $body = str_replace('http://www.hangshare.com/media/','https://dw4xox9sj1rhd.cloudfront.net/',$post['body']);
            Yii::$app->db->createCommand("UPDATE post_body SET body = :body WHERE postId = {$post['id']} ",[':body'=>$body])->query();
            print $post['id'] . chr(10);
        }
    }
}



