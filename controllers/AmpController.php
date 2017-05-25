<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Post;

/**
 * PostController implements the CRUD actions for Post model.
 */
class AmpController extends Controller
{
    public $next;
    public $enableCsrfValidation = false;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],

            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['post'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionView($slug)
    {
        $this->layout = 'main-amp';

        $model = $this->findModel($slug, false);
        return $this->render('post/view', [
            'model' => $model
        ]);

    }

    protected function findModel($id, $er_404 = true)
    {
        $lang = Yii::$app->language;
        $userId = !Yii::$app->user->isGuest ? Yii::$app->user->identity->id : 0;
        $model = Yii::$app->cache->get($id . $lang);
        if ($model === false) {
            $qu = Post::find()->joinWith(['user', 'postTags', 'postTags.tags']);

            if (!Yii::$app->user->isGuest) {
                $model = $qu->where("post.urlTitle = '{$id}' AND (post.lang = '{$lang}' OR post.userId = {$userId})")->one();
            } else {
                $model = $qu->where(['post.urlTitle' => $id, 'post.lang' => Yii::$app->language])->one();
            }
            Yii::$app->cache->set($id . $lang, $model, 3000);
        }
        if (!isset($model) && $er_404) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }
}
