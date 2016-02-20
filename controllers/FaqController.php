<?php

namespace app\controllers;

use Yii;
use app\models\Faq;
use app\models\FaqSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AwsEmail;

/**
 * FaqController implements the CRUD actions for Faq model.
 */
class FaqController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Faq models.
     * @return mixed
     */
    public function actionIndex() {
        Yii::$app->assetManager->forceCopy = true;
        $searchModel = new FaqSearch();
        if (isset($_GET['category'])) {
            $tit = str_replace('-', ' ', $_GET['category']);
        } else {
            $tit = 'الأسئلة الشائعة';
        }
        $categoryId = array_search($tit, Faq::$CategoryStr);
        $dataProvider = $searchModel->search(['categoryId' => $categoryId]);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'tit' => $tit
        ]);
    }

    /**
     * Creates a new Faq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Faq();
        $model->load(Yii::$app->request->post());
        if (!Yii::$app->user->isGuest)
            $model->userId = Yii::$app->user->id;
        $model->save();
        
        AwsEmail::SendMail('info@hangshare.com', 'New Frequently Asked Questions', $model->question);
        AwsEmail::SendMail('hasania.khaled@gmail.com', 'New Frequently Asked Questions', $model->question);
        Yii::$app->getSession()->setFlash('success', [
            'message' => 'تمت اضافة سؤالك بنجاح ، سوف يقوم فريقنا بالرد على سؤالكم واعلامكم.',
        ]);
        return $this->redirect(['//الأسئلة-الشائعة']);
    }

    /**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Faq::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
