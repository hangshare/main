<?php

namespace app\controllers;

use app\components\AwsEmail;
use app\models\Category;
use app\models\Comments;
use app\models\Post;
use app\models\PostSearch;
use app\models\UserSettings;
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

    public function actionAddcomment()
    {
        $this->layout = false;
        $request = Yii::$app->request->post();
        $post = Post::find()->where(['id' => $request['id']])->one();
        $model = new Comments;
        $model->postId = $request['id'];
        $model->comment = $request['text'];
        $model->save(false);
//        if (Yii::$app->user->identity->id != $post->id) {
//            AwsEmail::queueUser($post->userId, 'post_comment', [
//                '__title__' => "{$post->title}",
//                '__url__' => $post->url . '#comments',
//                '__user__' => Yii::$app->user->identity->name,
//            ]);
//        }
        Yii::$app->db->createCommand("UPDATE `post_stats` SET `comments`=`comments`+1 WHERE `postId`= {$post->id}")->query();

        echo $this->render('_commentview', ['model' => $model]);
    }

    public function actionComments()
    {
        $request = Yii::$app->request->post();
        $id = $request['id'];
        $this->layout = false;
        $model = Comments::find()->where(['postId' => $id])->orderBy('id desc')->limit(20)->all();
        echo '<ul id="commentsCont" class="list-unstyled">';
        foreach ($model as $data) {
            if ($id != $data->id)
                echo $this->render('_commentview', ['model' => $data]);
        }
        echo '</ul>';
    }

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
        $id = $request['id'];

        $this->layout = false;
        $model = Post::related($id, 6);
        echo '<ul class="list-inline releated">';
        foreach ($model as $data) {
            if ($id != $data->id)
                echo $this->render('_related', ['model' => $data]);
        }
        echo '</ul>';
    }

    public function actionCountcheck()
    {
        session_write_close();
        if (isset($_POST['id']) && isset($_POST['userid']) && isset($_POST['plan'])) {
            Yii::$app->hitcounter->addHit($_POST['id'], $_POST['userid'], $_POST['plan']);
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
        $query->where("post.published=1 AND post.deleted=0");
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
        $query->where("post.published=1 AND post.deleted=0 AND post.lang = '" . Yii::$app->language . "'");
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

        $pageSize = 10;
        $query = Post::find();
        $query->joinWith(['postCategories']);
        $query->orderBy('post.created_at DESC');
        $query->where("post.published=1 AND post.deleted=0 AND post.lang = '" . Yii::$app->language . "'");
        $query->andWhere(['<>', 'post.cover', '']);


        if (strpos($category, '/') !== false) {
            //sub category

            $sub_cat = explode('/', $category);
            $category = $sub_cat[1];
            $cat = Yii::$app->db->createCommand("SELECT id,title FROM `category` WHERE url_link LIKE '{$category}%'")->queryOne();
            $query->andWhere(['=', 'post_category.categoryId', $cat['id']]);
        } else {
            //main category
            $cat = Yii::$app->db->createCommand("SELECT id,title FROM `category` WHERE url_link LIKE '{$category}%'")->queryOne();
            if (!$cat) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            $subcats = Yii::$app->db->createCommand("SELECT id FROM `category` WHERE parent = {$cat['id']}")->queryAll();
            $qa = [];
            foreach ($subcats as $subcats) {
                $qa[] = $subcats['id'];
            }
            $query->andWhere(['in', 'post_category.categoryId', $qa]);

        }


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
            $i = 1;
            foreach ($models as $data) {
                $html .= $this->render('_view', ['model' => $data]);
                $i++;
                if ($i == 10)
                    $html .= $this->render('_innerads');
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
        $pageSize = 10;
        $query = Post::find();
        $query->orderBy('id DESC');
        $query->where("post.deleted=0 AND post.published=1 AND lang = '" . Yii::$app->language . "'");
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
            $i = 1;
            foreach ($models as $data) {
                $html .= $this->render('_view', ['model' => $data]);
                $i++;
                if ($i == 10)
                    $html .= $this->render('_innerads');
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
        $awsKey = 'AKIAJRPDRPENTPSXRUGQ';
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
        $model = $this->findModel($slug, false);
        if (!isset($model) || $model->deleted == 1) {
            $pageSize = 8;
            $query = Post::find();
            $query->orderBy('created_at DESC');
            $query->where("post.featured = 1 AND post.deleted=0 AND post.published=1  AND lang = '" . Yii::$app->language . "'");
            $query->andWhere(['<>', 'cover', '']);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => array(
                    'defaultPageSize' => $pageSize,
                    'pageSize' => 10,
                    'route' => 1
                ),
            ]);

            return $this->render('deleted', [
                'dataProvider' => $dataProvider,
                'model' => $model,

            ]);
        } else {
            return $this->render('view', [
                'model' => $model
            ]);
        }
    }

    public function actionPost($id = '')
    {
        $user_setting = UserSettings::findOne(['userId' => Yii::$app->user->identity->id]);
        if(!$user_setting->verified_email){
            $lang = Yii::$app->language == 'en' ? 'en/' : '';
            return $this->redirect(["//{$lang}u/verifyaccount"]);
        }

        if (empty($id)) {
            $model = new Post();
        } else {
            if (Yii::$app->user->identity->type) {
                $model = Post::findOne(['id' => $id]);
            } else {
                $model = Post::findOne(['id' => $id, 'userId' => Yii::$app->user->identity->id]);
            }
            if (!isset($model)) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->ylink)) {
                //$model->type = 1;
            }
            $model->saveExternal();
            if (!$model->save()) {
            }
            $lang = Yii::$app->language == 'en' ? 'en/' : '';
            return $this->redirect(["//{$lang}{$model->urlTitle}"]);
        } else {
            return $this->render('post', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->id != $model->userId) {
            throw new Exception(Yii::t('app', 'you are not allwoed to access this page'), '403');
        }
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
        if (Yii::$app->user->id != $model->userId && Yii::$app->user->identity->type != 1) {
            throw new Exception(Yii::t('app', 'You are not allowed to access this page'), '403');
        }
        Yii::$app->db->createCommand("UPDATE post SET deleted=1 WHERE id=$id")->query();
        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Post has been deleted successfully'));
        $username = empty($model->user->username) ? $model->user->id : $model->user->username;
        return $this->redirect(['/user/' . $username . '/']);
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
