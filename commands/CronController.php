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

class CronController extends Controller
{



    public function actionFix()
    {
        $model = Yii::$app->db->createCommand("SELECT id, title FROM post WHERE urlTitle = '' OR urlTitle is null LIMIT 10000")->queryAll();
        print 'Count ' . count($model) . chr(10);
        foreach ($model as $item) {
            print ' Processing id : ' . $item['id'] . chr(10);
            $url = Yii::$app->helper->urlTitle($item['title']);
            $exist = Yii::$app->db->createCommand("SELECT id FROM post WHERE urlTitle = '{$url}' LIMIT 1")->queryOne();
            if ($exist) {
                $url .= "-{$item['id']}";
            }
            $re = Yii::$app->db->createCommand("UPDATE post SET urlTitle='{$url}' WHERE id={$item['id']}")->query();
            echo chr(10);
        }
    }

}



