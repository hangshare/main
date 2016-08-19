<?php

namespace app\controllers;

use app\components\AwsEmail;
use app\models\Category;
use app\models\Post;
use app\models\PostSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * PostController implements the CRUD actions for Post model.
 */
class ExploreController extends Controller
{
    public $next;
    public $enableCsrfValidation = false;

    public function actionHot()
    {
        $this->layout = false;
        $model = Post::featured(6);
        echo '<ul class="list-inline">';
        foreach ($model as $data) {
            echo $this->render('_hot', ['model' => $data]);
        }
        echo '</ul>';
    }

    public function actionRelated()
    {
        $request = Yii::$app->request->post();
        if (!isset($request['id'])) {
            $id = 333;
        } else {
            $id = $request['id'];
        }
        $this->layout = false;
        $model = Post::related($id, 6);
        echo '<ul class="list-inline releated">';
        foreach ($model as $data) {
            echo $this->render('_related', ['model' => $data]);
        }
        echo '</ul>';
    }

    public function actionCountcheck()
    {
        session_write_close();
        if (isset($_POST['id']) && isset($_POST['userid']) && isset($_POST['plan'])) {
            Yii::$app->hitcounter->addHit($_POST['id'], $_POST['userid'], $_POST['plan']);
        } else {
//            AwsEmail::SendMail('hasania.khaled@gmail.com', '61', json_encode($_SERVER));
        }
    }

    public function actionUpload()
    {

        if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
            $file_path = date('Ydm');
            if (!is_dir(Yii::$app->basePath . '/web/media/' . $file_path)) {
                mkdir(Yii::$app->basePath . '/web/media/' . $file_path, 0777, true);
            }
            $filebase_name = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_BASENAME));
            $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            $filebase_name = str_replace('.' . $file_ext, '', $filebase_name);
            $filename = rand(1, 100) . '-' . preg_replace("/[^A-Za-z0-9?!]/", '-', $filebase_name) . '.' . $file_ext;
            $r3 = move_uploaded_file($_FILES['file']['tmp_name'], Yii::$app->basePath . '/web/media/' . $file_path . '/' . $filename);

            if ($r3) {

                Yii::$app->imageresize->s3Resize(Yii::$app->basePath . '/web/media/' . $file_path . '/' . $filename,
                    1000, 1000, 'resize');
                Yii::$app->customs3->uploadFromPath(Yii::$app->basePath . '/web/media/' . $file_path . '/' . $filename,
                    'hangshare-media', $file_path . '/' . $filename);

                $file_url = Yii::$app->imageresize->thump($file_path . '/' . $filename, 1000, 1000, 'resize');
                header('Content-Type: application/json');
                echo json_encode(array('link' => $file_url));
                Yii::$app->end();
            } else {
                header('Content-Type: application/json');
                echo json_encode(array('error' => $r3));
                Yii::$app->end();
            }
        }
    }

    public function actionSearch($q)
    {
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
                $html .= $this->render('_view', ['model' => $data]);
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

    public function actionTags($tags)
    {
        $pageSize = 10;
        $tags = str_replace('-', ' ', $tags);
        $query = Post::find();
        $query->joinWith(['postTags', 'postTags.tags']);
        $query->orderBy('post.created_at DESC');
        $query->where("post.deleted=0 AND post.lang = '" . Yii::$app->language . "'");
        $query->andWhere(['<>', 'post.cover', '']);
        $query->andFilterWhere(['like', 'tags.name', $tags]);

        $currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPage = preg_replace("/tag=[^&]+/", "", $currentPage);
        $currentPage = preg_replace("/&{2,}/", "&", $currentPage);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'defaultPageSize' => $pageSize,
                'pageSize' => $pageSize,
                'route' => $currentPage),
        ]);
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
            $html = '';
            $models = $dataProvider->getModels();
            foreach ($models as $data) {
                $html .= $this->render('_view', ['model' => $data]);
            }
            echo json_encode([
                'html' => $html,
                'total' => 40000,
                'PageSize' => $pageSize
            ]);
        } else {
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'tags' => $tags
            ]);
        }
    }


    public function actionCategory($category)
    {
        $cat = Category::find()->where(['url_link' => $category])->one();
        $pageSize = 10;
        $query = Post::find();
        $query->joinWith(['postCategories']);
        $query->orderBy('post.created_at DESC');
        $query->where("post.deleted=0 AND post.lang = '" . Yii::$app->language . "'");
        $query->andWhere(['<>', 'post.cover', '']);
        $query->andWhere(['=', 'post_category.categoryId', $cat->id]);

        $currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPage = preg_replace("/tag=[^&]+/", "", $currentPage);
        $currentPage = preg_replace("/&{2,}/", "&", $currentPage);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'defaultPageSize' => $pageSize,
                'pageSize' => $pageSize,
                'route' => $currentPage),
        ]);
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
            $html = '';
            $models = $dataProvider->getModels();
            foreach ($models as $data) {
                $html .= $this->render('_view', ['model' => $data]);
            }
            echo json_encode([
                'html' => $html,
                'total' => 40000,
                'PageSize' => $pageSize
            ]);
        } else {
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'cat' => $cat
            ]);
        }
    }

    public function actionAll()
    {
        $pageSize = 8;
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
                $html .= $this->render('_view', ['model' => $data]);
            }
            echo json_encode([
                'html' => $html,
                //'total' => $dataProvider->getTotalCount(),
                'total' => 40000,
                'PageSize' => $pageSize
            ]);
        } else {
            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionResize()
    {
        Yii::$app->imageresize->PatchResize($_POST['bucket'], $_POST['key'], $_POST['type']);
    }

    public function actionS3crd()
    {
        $s3Bucket = 'hangshare-media';
        $region = 'eu-west-1';
        $acl = 'public-read';
        $awsKey = 'AKIAIXXCGXOS77W753RQ';
        $awsSecret = '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';
        $algorithm = "AWS4-HMAC-SHA256";
        $service = "s3";
        $date = gmdate("Ymd\THis\Z");
        $shortDate = gmdate("Ymd");
        $requestType = "aws4_request";
        $expires = "6400";
        $successStatus = "201";
        //$url = "https://{$s3Bucket}.s3.amazonaws.com/";
        $url = "https://hangshare-media.s3-eu-west-1.amazonaws.com/";
        $scope = [
            $awsKey,
            $shortDate,
            $region,
            $service,
            $requestType
        ];
        $credentials = implode('/', $scope);
        $policy = [
            'expiration' => gmdate('Y-m-d\TG:i:s\Z', strtotime('+6 hours')),
            'conditions' => [
                ['bucket' => $s3Bucket],
                ['acl' => $acl],
                ['starts-with', '$key', ''],
                ['starts-with', '$Content-Type', ''],
                ['success_action_status' => $successStatus],
                ['x-amz-credential' => $credentials],
                ['x-amz-algorithm' => $algorithm],
                ['x-amz-date' => $date],
                ['x-amz-expires' => $expires],
            ]
        ];
        $base64Policy = base64_encode(json_encode($policy));
        $dateKey = hash_hmac('sha256', $shortDate, 'AWS4' . $awsSecret, true);
        $dateRegionKey = hash_hmac('sha256', $region, $dateKey, true);
        $dateRegionServiceKey = hash_hmac('sha256', $service, $dateRegionKey, true);
        $signingKey = hash_hmac('sha256', $requestType, $dateRegionServiceKey, true);
        $signature = hash_hmac('sha256', $base64Policy, $signingKey);
        $inputs = [
            'ContentType' => '',
            'acl' => $acl,
            'success_action_status' => $successStatus,
            'policy' => $base64Policy,
            'X_amz_credential' => $credentials,
            'X_amz_algorithm' => $algorithm,
            'X_amz_date' => $date,
            'X_amz_expires' => $expires,
            'X_amz_signature' => $signature
        ];
        echo json_encode(['url' => $url, 'inputs' => $inputs]);
    }

    public function actionRed($id)
    {
        $post = Post::findOne(['id' => $id]);
        if (!isset($post)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: {$post->url}");
        exit(0);
    }

    public function actionView($slug)
    {
        $model = $this->findModel($slug);
        $mostviewd = Post::mostViewed();
        $this->view->params['next'] = Yii::$app->cache->get('next-' . $model->id);
        if ($this->view->params['next'] == false) {
            $this->view->params['next'] = Post::find()
                ->where("id > $model->id AND lang = '" . Yii::$app->language . "'")
                ->select('id,title, urlTitle')
                ->orderBy('id desc')
                ->one();

            Yii::$app->cache->set('next-' . $model->id, $this->view->params['next'], 300);
        }
        $this->view->params['prev'] = Yii::$app->cache->get('prev-' . $model->id);
        if ($this->view->params['prev'] == false) {
            $this->view->params['prev'] = Post::find()
                ->where("id < $model->id AND '" . Yii::$app->language . "'")
                ->select('id,title, urlTitle')
                ->orderBy('id desc')
                ->one();
            Yii::$app->cache->set('prev-' . $model->id, $this->view->params['prev'], 300);
        }
        return $this->render('view', [
            'model' => $model,
            'mostviewd' => $mostviewd
        ]);
    }

    protected function findModel($id)
    {

        $lang = Yii::$app->language;
        $userId = Yii::$app->user->identity->id;
        $model = Yii::$app->cache->get($id);
        if ($model === false) {
            $qu = Post::find()->joinWith(['user', 'postTags', 'postTags.tags']);
            if (!Yii::$app->user->isGuest)
                $model = $qu->where("post.urlTitle = '{$id}' AND (post.lang = '{$lang}' OR post.userId = '{$userId}')")->one();
            else
                $model = $qu->where(['post.urlTitle' => $id, 'post.lang' => Yii::$app->language])->one();
            Yii::$app->cache->set($id, $model, 3000);
        }

        if (!isset($model)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }

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
            if (!empty($model->ylink)) {
                $model->type = 1;
            }
            $model->saveExternal();
            if (!$model->save()) {
                var_dump($model->getErrors());
                die();
            }


            return $this->redirect(["/{$model->urlTitle}"]);
        } else {
            return $this->render('post', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->cache->delete('postView-' . $id);
            Yii::$app->cache->delete('post-body-' . $id);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = Post::findOne(['id' => $id]);
        if (Yii::$app->user->id != $model->userId) {
            throw new Exception(Yii::t('app', 'you are not allwoed to access this page'), '403');
        }
        Yii::$app->db->createCommand("UPDATE post SET deleted=1 WHERE id=$id")->query();
        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Post has been deleted successfully'));
        $username = empty($model->user->username) ? $model->user->id : $model->user->username;
        return $this->redirect(['/user/' . $username . '/']);
    }

}
