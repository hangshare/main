<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\Post;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UserTransactions;
use app\models\UserPayment;
use app\models\TransferMethod;
use yii\filters\AccessControl;
use app\components\AwsEmail;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::classname(),
                'only' => ['transfer'],
                'rules' => [[
                'allow' => true,
                'roles' => ['@']
                    ]]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ],
            ],
        ];
    }

    public function actionChangepass() {
        if (isset($_POST['password']) && isset($_POST['old'])) {
            $old = $_POST['old'];
            $newPass = $_POST['password'];
            $model = $this->findModel(Yii::$app->user->id);
            if ($model->password_hash != sha1($old)) {
                $responce['status'] = false;
            } else {
                $model->password_hash = sha1($newPass);
                $model->save(false);
                $responce['status'] = true;
            }
        }
        echo json_encode($responce);
    }

    public function actionVerify($key) {
        $userSettings = \app\models\UserSettings::find()->where(['key' => $key])->one();
        if (isset($userSettings)) {
            $userSettings->verified_email = 1;
            $userSettings->save(false);
            Yii::$app->getSession()->setFlash('success', [
                'message' => 'تم تفعيل حسابك بنجاح ',
            ]);
            return $this->redirect(["//user/{$userSettings->userId}"]);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'message' => 'نعتذر حصل خطأ يرجى التواصل معنا.',
            ]);
        }
        return $this->redirect(['//تواصل-معنا']);
    }

    public function actionTransfer() {
        $this->layout = 'usermanage';
        $this->view->params['user'] = $this->findModel(Yii::$app->user->identity->id);
        if ($data = Yii::$app->request->post()) {
            if (isset($data['PayPal'])) {
                $model = TransferMethod::find()->where(
                                'userId = ' . Yii::$app->user->identity->id . " AND 
                            type = 1 ")->one();
                if (!isset($model)) {
                    $model = new TransferMethod;
                    $model->userId = Yii::$app->user->identity->id;
                    $model->type = 1;
                }
                $e['email'] = $data['PayPal']['email'];
                $model->info = json_encode($e);
                $model->save();
            }
            if (isset($data['BankTransfer'])) {
                $model = TransferMethod::find()->where(
                                'userId = ' . Yii::$app->user->identity->id . " AND 
                            type = 2 ")->one();
                if (!isset($model)) {
                    $model = new TransferMethod;
                    $model->userId = Yii::$app->user->identity->id;
                    $model->type = 2;
                }
                $model->info = json_encode($data['BankTransfer']);
                $model->save();
            }
            if (isset($data['CashierTransfer'])) {
                $model = TransferMethod::find()->where(
                                'userId = ' . Yii::$app->user->identity->id . " AND 
                            type = 3 ")->one();
                if (!isset($model)) {
                    $model = new TransferMethod;
                    $model->userId = Yii::$app->user->identity->id;
                    $model->type = 3;
                }
                $model->info = json_encode($data['CashierTransfer']);
                $model->save();
            }
            if (isset($data['Vodafone'])) {
                $model = TransferMethod::find()->where(
                                'userId = ' . Yii::$app->user->identity->id . " AND 
                            type = 4 ")->one();
                if (!isset($model)) {
                    $model = new TransferMethod;
                    $model->userId = Yii::$app->user->identity->id;
                    $model->type = 4;
                }
                $model->info = json_encode($data['Vodafone']);
                $model->save();
            }
            $this->view->params['user']->transfer_type = $model->type;
            $this->view->params['user']->save(false);
            return $this->refresh();
        }
        $model = TransferMethod::find()->where('userId = ' . Yii::$app->user->identity->id)->all();
        return $this->render('//transfer/transfer', ['model' => $model, 'user' => $this->user]);
    }

    public function actionGold() {
        $this->layout = 'usermanage';
        $this->view->params['user'] = $this->findModel(Yii::$app->user->identity->id);
        $model = UserPayment::find()->where('userId = ' . Yii::$app->user->id)->one();
        if (!isset($model))
            throw new NotFoundHttpException('The requested page does not exist.');

        return $this->render('gold', ['model' => $model]);
    }

    public function actionImage() {
        $re = [];
        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            if (Yii::$app->user->isGuest) {
                $image = 'user/' . time() . '-' . strtolower($_FILES['image']['name']);
            } else {
                $image = 'user/' . time() . '-' . Yii::$app->user->identity->name . '-' . strtolower($_FILES['image']['name']);
            }
            move_uploaded_file($_FILES['image']['tmp_name'], Yii::$app->basePath . '/../../media/' . $image);
            $re['url'] = Yii::$app->imageresize->thump($image, 50, 50, 'crop');
            $re['name'] = $image;
            echo json_encode($re);
        }
    }

    public function actionSuccess($id) {
        return $this->render('success');
    }

    public function actionRequest() {

        $model = $this->findModel(Yii::$app->user->identity->id);
        $transaction = new UserTransactions;
        $transaction->userId = Yii::$app->user->identity->id;
        $transaction->amount = $model->userStats->cantake_amount;


        AwsEmail::queueUser($transaction->userId, 5, [
            '__price__' => "{$model->userStats->cantake_amount}$"
        ]);

        if (!$transaction->save()) {
            var_dump($transaction->getErrors());
        }
        Yii::$app->db->createCommand("UPDATE `user_stats` SET `cantake_amount`=0 WHERE `userId`={$transaction->userId}")->execute();
        AwsEmail::queueUser($transaction->userId, 5, [
            '__price__' => "{$model->userStats->cantake_amount}$"
        ]);
        AwsEmail::SendMail('info@hangshare.com', 'Money Request', "
            User Id : {$transaction->userId} , 
                Amount : {$model->userStats->cantake_amount} , 
            Transaction id : {$transaction->id}");
        AwsEmail::SendMail('hasania.khaled@gmail.com', 'Money Request', " 
            User Id : {$transaction->userId} , 
                Amount : {$model->userStats->cantake_amount} , 
            Transaction id : {$transaction->id}");
        return $this->redirect(['success', 'id' => $transaction->id]);
    }

    public function actionPayment() {
        $chkTransaction = Yii::$app->db->createCommand('SELECT 1 FROM `user_transactions` WHERE status = 0 AND userId = ' . Yii::$app->user->identity->id)->queryScalar();
        $model = $this->findModel(Yii::$app->user->identity->id);
        return $this->render('payment', ['model' => $model, 'chkTransaction' => $chkTransaction]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {

        if (!isset($_COOKIE["hangprofile-$id"])) {
            Yii::$app->db->createCommand('UPDATE `user_stats` SET `profile_views`=`profile_views`+1 WHERE `userId`=' . $id)->query();
            setcookie("hangprofile-$id", true, time() + (86400), "/");
        }

        $query = Post::find()->orderBy('id desc');
        $query->where("post.deleted = 0");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => 6),
        ]);
        $query->andFilterWhere([
            'userId' => $id,
        ]);
        $model = $this->findModel($id);
        $view = 'guest';
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->id == $model->id) {
            $view = 'view';
        }
        return $this->render($view, [
                    'model' => $model,
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionManage() {
        $this->layout = 'usermanage';
        $model = $this->view->params['user'] = $this->findModel(Yii::$app->user->identity->id);

        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST['User']['year']) && isset($_POST['User']['month']) && isset($_POST['User']['day']) && $_POST['User']['day'] != 0 && $_POST['User']['month'] != 0 && $_POST['User']['day'] != 0) {
                $model->dob = $_POST['User']['year'] . '-' . $_POST['User']['month'] . '-' . $_POST['User']['day'];
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (!empty($model->getErrors())) {
            var_dump($model->getErrors());
            die();
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
