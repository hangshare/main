<?php

namespace app\controllers;

use app\models\Post;
use app\models\PostSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * PostController implements the CRUD actions for Post model.
 */
class ExploreController extends Controller {

    public $next;

    public function behaviors() {
        return [
            [
                'class' => 'yii\filters\HttpCache',
                'only' => ['index', 'view'],
                'cacheControlHeader' => function ($action, $params) {
            return 'public, max-age=3600';
        },
            ],
            'access' => [
                'class' => AccessControl::classname(),
                'only' => ['Post', 'Delete'],
                'rules' => [[
                'allow' => true,
                'roles' => ['@']
                    ]]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'checkhit' => ['post'],
                ],
            ],
        ];
    }

    public function actionRelated() {
        $this->layout = false;
//        $id = $_POST['id'];
//        $ids = Yii::$app->db->createCommand("
//                    SELECT `postId`
//                    FROM `post_tag`
//                    WHERE  `tag`
//                            IN (
//                                    SELECT `tag`
//                                    FROM `post_tag`
//                                    WHERE `postId` = {$id}
//                            ) AND `postId` != {$id}
//                    GROUP BY `postId`
//                    ORDER BY `postId` DESC
//                    LIMIT 8
//            ")->queryAll();
//
//        $ar = [];
//        foreach ($ids as $id) {
//            $ar[] = $id['postId'];
//        }
//        $sqp = implode(',', $ar);
//        $model = Post::find()
//                        ->groupBy('userId')
//                        ->where("id IN ({$sqp}) AND cover <> ''")->all();

        $model = Post::featured(8);
        echo '<ul class="list-inline releated">';
        foreach ($model as $data) {
            echo $this->render('_related', ['model' => $data]);
        }
        echo '</ul>';
    }

    public function actionCountcheck() {
        session_write_close();
        Yii::$app->hitcounter->addHit($_POST['id'], $_POST['userid'], $_POST['plan']);
    }

    public function actionUpload() {
        header('Content-Type: application/json');
        if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
            $file_path = date('Ydm');
            if (!is_dir(Yii::$app->basePath . '/../../media/' . $file_path)) {
                mkdir(Yii::$app->basePath . '/../../media/' . $file_path, 0777, true);
            }
            $filebase_name = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_BASENAME));
            $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            $filebase_name = str_replace('.' . $file_ext, '', $filebase_name);
            $filename = rand(1, 100) . '-' . preg_replace("/[^A-Za-z0-9?!]/", '-', $filebase_name) . '.' . $file_ext;
            move_uploaded_file($_FILES['file']['tmp_name'], Yii::$app->basePath . '/../../media/' . $file_path . '/' . $filename);

            $file_url = Yii::$app->imageresize->thump($file_path . '/' . $filename, 1000, 1000, 'resize');

            echo json_encode(array('link' => $file_url));
            Yii::$app->end();
        }
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionFun($tag = 'ترفيه') {

        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $tag);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionVideo($tag = '') {
        $pageSize = 14;
        $query = Post::find();
        $query->joinWith(['user', 'postBodies']);
        $query->orderBy('created_at DESC');
        $query->where('post.type = 1');
        $query->andWhere(['<>', 'cover', '']);
        if (!empty($tag)) {
            $query->joinWith(['postTags', 'postTags.tags']);
            $query->andFilterWhere(['like', 'tags.name', $tag]);
        }
        $currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPage = preg_replace("/tag=[^&]+/", "", $currentPage);
        $currentPage = preg_replace("/&{2,}/", "&", $currentPage);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'defaultPageSize' => $pageSize,
                'pageSize' => $pageSize,
                'route' => $currentPage
            ),
        ]);
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
            $html = '';
            $models = $dataProvider->getModels();
            foreach ($models as $data) {
                $html.= $this->render('_view', ['model' => $data]);
            }
            echo json_encode(['html' => $html,
                'total' => $dataProvider->getTotalCount(),
                'PageSize' => $pageSize
            ]);
            Yii::$app->end();
        } else {
            return $this->render('index', [
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionSearch($q) {
        $pageSize = 14;
        $search = filter_input(INPUT_POST | INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);

        $query = Post::find();
        $query->orderBy('created_at DESC');
        $query->where("post.deleted=0");
//        $query->andWhere(['<>', 'cover', '']);
        $query->joinWith(['postTags', 'postTags.tags']);
        $query->orFilterWhere(['like', 'tags.name', $search]);
        $query->andFilterWhere(['like', 'title', $search]);

        $currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPage = preg_replace("/tag=[^&]+/", "", $currentPage);
        $currentPage = preg_replace("/&{2,}/", "&", $currentPage);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'defaultPageSize' => $pageSize,
                'pageSize' => $pageSize,
                'route' => $currentPage
            ),
        ]);
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
            $html = '';
            $models = $dataProvider->getModels();
            foreach ($models as $data) {
                $html.= $this->render('_view', ['model' => $data]);
            }
            echo json_encode(['html' => $html,
                'total' => $dataProvider->getTotalCount(),
                'PageSize' => $pageSize
            ]);
            Yii::$app->end();
        } else {
            return $this->render('index', [
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionAll() {
        $pageSize = 14;
        $query = Post::find();
        $query->joinWith(['user', 'postBodies']);
        $query->orderBy('created_at DESC');
        $query->where("post.deleted=0");
        $query->andWhere(['<>', 'cover', '']);
        $currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPage = preg_replace("/tag=[^&]+/", "", $currentPage);
        $currentPage = preg_replace("/&{2,}/", "&", $currentPage);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'defaultPageSize' => $pageSize,
                'pageSize' => $pageSize,
                'route' => $currentPage
            ),
        ]);
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
            $html = '';
            $models = $dataProvider->getModels();
            foreach ($models as $data) {
                $html.= $this->render('_view', ['model' => $data]);
            }
            echo json_encode(['html' => $html,
                'total' => $dataProvider->getTotalCount(),
                'PageSize' => $pageSize
            ]);
            Yii::$app->end();
        } else {
            return $this->render('index', [
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex($tag = '') {
        $pageSize = 14;
        $query = Post::find();
        $query->orderBy('created_at DESC');
        $query->joinWith(['postTags', 'postTags.tags', 'user', 'PostBodies']);
        $query->where('post.type = 0 AND post.deleted=0');
        $query->andWhere(['<>', 'cover', '']);
        if (!empty($tag)) {
            $query->andFilterWhere(['like', 'tags.name', $tag]);
        }
        $currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPage = preg_replace("/tag=[^&]+/", "", $currentPage);
        $currentPage = preg_replace("/&{2,}/", "&", $currentPage);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'defaultPageSize' => $pageSize,
                'pageSize' => $pageSize,
                'route' => $currentPage
            ),
        ]);

        if (Yii::$app->request->isAjax) {
            $this->layout = false;
            $html = '';
            $models = $dataProvider->getModels();
            foreach ($models as $data) {
                $html.= $this->render('_view', ['model' => $data]);
            }
            echo json_encode(['html' => $html,
                'total' => $dataProvider->getTotalCount(),
                'PageSize' => $pageSize
            ]);
            Yii::$app->end();
        } else {
            return $this->render('index', [
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {

        $model = $this->findModel($id);
        $mostviewd = Post::mostViewed();
        $this->view->params['next'] = Yii::$app->cache->get('next-' . $id);
        if ($this->view->params['next'] == false) {
            $this->view->params['next'] = Post::find()
                    ->where(['and', "id>$id"])
                    ->select('id,title')
                    ->orderBy('id desc')
                    ->one();

            Yii::$app->cache->set('next-' . $id, $this->view->params['next'], 300);
        }
        $this->view->params['prev'] = Yii::$app->cache->get('prev-' . $id);
        if ($this->view->params['prev'] == false) {
            $this->view->params['prev'] = Post::find()
                    ->where(['and', "id<$id"])
                    ->select('id,title')
                    ->orderBy('id desc')
                    ->one();
            Yii::$app->cache->set('prev-' . $id, $this->view->params['prev'], 300);
        }
        return $this->render('view', [
                    'model' => $model,
                    'mostviewd' => $mostviewd
        ]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Yii::$app->cache->get('apostView-' . $id);
        if ($model === false) {
            $qu = Post::find()->joinWith(['user', 'postBodies', 'postTags', 'postTags.tags', 'postStats']);
            $model = $qu->where(['post.id' => $id])->one();
            Yii::$app->cache->set('apostView-' . $id, $model, 3000);
        }
        if (!isset($model)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPost($id = '')
    {

        if (empty($id)) {
            $model = new Post();
        } else {
            $model = Post::findOne(['id' => $id, 'userId' => Yii::$app->user->identity->id]);
            if (!isset($model)) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->cover_file = UploadedFile::getInstance($model, 'cover_file');
            if (!empty($model->ylink)) {
                $model->type = 1;
            }
            $upload_result = $model->upload();
            if ($upload_result && $model->type) { //get cover image from file
                $model->saveExternal(false);
            } else if ($model->type) { // get cover image from youtube or vimeo
                $model->saveExternal(true);
            }

            if (!$model->save(false)) {
                var_dump($model->getErrors());
                die();
            }
            return $this->redirect(['view', 'id' => $model->id, 'title' => $model->title]);
        } else {
            return $this->render('post', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Yii::$app->cache->delete('postView-' . $id);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->id != $model->userId) {
            throw new Exception('غير مسموح.', '403');
        }
        Yii::$app->db->createCommand("UPDATE post SET deleted=1 WHERE id=$id")->query();
        Yii::$app->getSession()->setFlash('success', [
            'message' => 'تم حذف الموضوع بنجاح.',
        ]);
        return $this->redirect('/user/' . $model->userId);
    }

}
