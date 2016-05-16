<?php

namespace app\controllers;

require_once __DIR__ . '/../vendor/facebook/php-sdk-v4/src/Facebook/autoload.php';

use app\components\AwsEmail;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\Post;
use app\models\ResetPasswordForm;
use app\models\User;
use app\models\UserPayment;
use Facebook;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $description;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'Success'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionFlush()
    {
        Yii::$app->cache->flush();
        $this->redirect('http://hangadmin.hangshare.com');
    }

    public function actionSitemapxml()
    {
        header("Content-type: text/xml");
        $posts = Yii::$app->db->createCommand("SELECT t.id, t.title, t.urlTitle, t.created_at FROM  post t WHERE t.deleted = 0 ORDER BY t.id DESC LIMIT 10000;")->queryAll();
        $sitemap = new \DomDocument('1.0', 'UTF-8');
        $sitemap->preserveWhiteSpace = false;
        $sitemap->formatOutput = true;
        $root = $sitemap->createElement("urlset");
        $sitemap->appendChild($root);
        $root_attr = $sitemap->createAttribute('xmlns');
        $root->appendChild($root_attr);
        $root_attr_text = $sitemap->createTextNode('http://www.sitemaps.org/schemas/sitemap/0.9');
        $root_attr->appendChild($root_attr_text);
        foreach ($posts as $post) {
            $link = "http://www.hangshare.com/{$post['urlTitle']}/";
            $url = $sitemap->createElement('url');
            $root->appendChild($url);
            $loc = $sitemap->createElement("loc");
            $lastmod = $sitemap->createElement("lastmod");
            $changefreq = $sitemap->createElement("changefreq");
            $url->appendChild($loc);
            $url_text = $sitemap->createTextNode($link);
            $loc->appendChild($url_text);
            $url->appendChild($lastmod);
            $lastmod_text = $sitemap->createTextNode(date("Y-m-d", strtotime($post['created_at'])));
            $lastmod->appendChild($lastmod_text);
            $url->appendChild($changefreq);
            $changefreq_text = $sitemap->createTextNode("weekly");
            $changefreq->appendChild($changefreq_text);
        }
        $users = Yii::$app->db->createCommand("SELECT t.id, t.username, t.created_at, t.name FROM user t WHERE 1 ORDER BY t.id DESC LIMIT 10000;")->queryAll();
        foreach ($users as $user) {
            $username = empty($user['username']) ? $user['id'] : $user['username'];
            $link = "http://www.hangshare.com/user/{$username}/";
            $url = $sitemap->createElement('url');
            $root->appendChild($url);

            $loc = $sitemap->createElement("loc");
            $lastmod = $sitemap->createElement("lastmod");
            $changefreq = $sitemap->createElement("changefreq");

            $url->appendChild($loc);
            $url_text = $sitemap->createTextNode($link);
            $loc->appendChild($url_text);

            $url->appendChild($lastmod);
            $lastmod_text = $sitemap->createTextNode(date("Y-m-d", strtotime($user['created_at'])));
            $lastmod->appendChild($lastmod_text);

            $url->appendChild($changefreq);
            $changefreq_text = $sitemap->createTextNode("weekly");
            $changefreq->appendChild($changefreq_text);
        }
        echo $sitemap->saveXML();
        exit;
    }

    public function actionSitemap()
    {
        $userProvider = new ActiveDataProvider([
            'query' => User::find()->orderBy('id desc'),
            'pagination' => array('pageSize' => 100),
        ]);
        $postProvider = new ActiveDataProvider([
            'query' => Post::find()->orderBy('id desc'),
            'pagination' => array('pageSize' => 100),
        ]);
        return $this->render('sitemap', [
            'userProvider' => $userProvider,
            'postProvider' => $postProvider
        ]);
    }

    public function actionIndex()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $pageSize = 16;
        $newpost = Yii::$app->cache->get('home-new-posts');
        if ($newpost === false) {
            $querypost = Post::find()
                ->where("post.deleted=0 AND post.cover <> ''")
                ->joinWith(['user'])
                ->select('post.id,post.title , post.cover ,user.id as userId, post.urlTitle')
                ->orderBy('id desc');
            $newpost = new ActiveDataProvider([
                'query' => $querypost,
                'pagination' => array(
                    'defaultPageSize' => $pageSize,
                    'pageSize' => $pageSize,
//                    'route' => $currentPage
                ),
            ]);
            Yii::$app->cache->set('home-new-posts', $newpost, 200);
        }
        if (Yii::$app->request->isAjax && isset($_GET['page'])) {
            $this->layout = false;
            $html = '';
            foreach ($newpost->getModels() as $data) {
                $html .= $this->render('//explore/_view', ['model' => $data]);
            }
            echo json_encode(['html' => $html,
                'total' => $newpost->getTotalCount(),
                'PageSize' => $pageSize
            ]);
            Yii::$app->end();
        } else {
            $this->layout = 'homepage';
            $this->description = 'أسرع طريقة للحصول على المال عن طريق الانترنت والفيسبوك وتحقيق ارباح مالية سريعة';
            $featured = Yii::$app->cache->get('home-featured');
            if ($featured === false) {
                $queryfeatured = Post::find()
                    ->where("type=0 AND cover <> '' AND featured = 1")
                    ->select('id, cover, title, urlTitle')
                    ->limit(21)
                    ->orderBy('rand();');
                $featured = new ActiveDataProvider([
                    'query' => $queryfeatured,
                    'pagination' => array('pageSize' => 21),
                ]);
                Yii::$app->cache->set('home-featured', $featured, 1500);
            }
            $mostviewd = Post::featured(4);

            return $this->render('index', [
                'featured' => $featured,
                'mostviewd' => $mostviewd,
                'newpost' => $newpost
            ]);
        }
    }

    public function actionThump($id)
    {

        $model = Post::find()
            ->where(['>=', 'id', $id])
            ->andWhere(['<>', 'cover', "''"])
            ->andWhere(['<>', 'cover', "0"])
            ->limit(50)->all();
        foreach ($model as $data) {
            if (!empty($data->cover)) {
                try {
                    $json = json_decode($data->cover);
                    Yii::$app->imageresize->PatchResize('hangshare.media', $json->image, 'post');
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

    }

    public function actionRes($id)
    {
        $model = User::find()
            ->where(['>=', 'id', $id])
            ->andWhere(['<>', 'image', "''"])
            ->andWhere(['<>', 'image', "0"])
            ->limit(50)->all();
        foreach ($model as $data) {
            if (!empty($data->image)) {
                try {
                    Yii::$app->imageresize->PatchResize('hangshare.media', $data->image, 'user');
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    public function actionFacebook()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['state'])) {
            $_SESSION['FBRLH_state'] = $_GET['state'];
        }
        $fb = new Facebook\Facebook([
            'app_id' => '1024611190883720',
            'app_secret' => '0df74c464dc8e58424481fb4cb3bb13c',
            'default_graph_version' => 'v2.4',
//            'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : '1024611190883720|0df74c464dc8e58424481fb4cb3bb13c',
            'persistent_data_handler' => 'session'
        ]);
        $helper = $fb->getRedirectLoginHelper();
//      $_SESSION['facebook_access_token'] = (string)$accessToken;
        try {
            $accessToken = $helper->getAccessToken();
            $fb->setDefaultAccessToken($accessToken);
            $response = $fb->get('/me?fields=id,name,email,about,age_range,bio,birthday,gender,hometown,location');
            $user_profile = $response->getGraphUser();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            Yii::$app->getSession()->setFlash('error', 'نعتذر حصل خطأ سوف نقوم بحل هذه المشكلة في أقرب وقت. ');
            AwsEmail::SendMail('hasania.khaled@gmail.com', 'HangShare FB  Err 1', $e->getMessage());
            return $this->redirect(['//login']);
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            Yii::$app->getSession()->setFlash('error', 'نعتذر حصل خطأ سوف نقوم بحل هذه المشكلة في أقرب وقت. ');
            AwsEmail::SendMail('hasania.khaled@gmail.com', 'HangShare FB  Err 2', $e->getMessage());
            return $this->redirect(['//login']);
        }
        $loemail = strtolower($user_profile->getEmail());
        $user = Yii::$app->db->createCommand("SELECT scId FROM user WHERE email = '{$loemail}' OR scId = '{$user_profile->getId()}'  LIMIT 1;")->queryOne();
        if ($user === false) {
            $model = new User;
            $model->name = $user_profile->getName();
            $model->scId = $user_profile->getId();
            $model->password = $user_profile->getId();
            $model->email = strtolower($user_profile->getEmail());
            $model->gender = $user_profile->getGender() == 'male' ? 1 : 2;
            $birth = $user_profile->getBirthday();
            if (isset($birth))
                $model->dob = $birth->format('Y-m-d');
            $model->bio = empty($user_profile->getProperty('bio')) ? '' : $user_profile->getProperty('bio');
            $image = preg_replace('/(\d{4})-(\d{2})-(\d{2})$/', '', $model->name) . '.jpg';
            $model->image = 'user/' . $image;
            $model->username = $user_profile->getUsername();
            if (!$model->save(false)) {
                AwsEmail::SendMail('hasania.khaled@gmail.com', 'Fb Bug', json_encode($model->getErrors()) . json_encode($model->attributes));
                Yii::$app->getSession()->setFlash('success', "يوجد بريد الكتروني مسجل على الموقع باستخدام البريد الاكتروني التالي {$user['email']} ، يرجى تسجيل الدخول باستخدام البريد الاكتروني المذكور وكلمة المرور.");
                return $this->redirect(['//login', 'stat' => 'user']);
            }
        } else {
            $model = User::find()->where('scId = :scId OR email = :email', [
                ':scId' => $user_profile->getId(),
                ':email' => strtolower($user_profile->getEmail())
            ])->one();
            if ($model) {
                $model->scId = $user_profile->getId();
                $model->save(false);
            }
        }
        $login = new LoginForm();
        $login->rememberMe = true;
        $login->username = strtolower($user_profile->getEmail());  //$user_profile->getId();
        $login->password = $user_profile->getId(); // 'Fb91khaled';
        if ($status = $login->login()) {
            if (isset($model) && ($model->created_at + 100 > time())) {
                $url = "http://graph.facebook.com/{$user_profile->getId()}/picture?type=large";
                $imagecontent = file_get_contents($url);
                $imageFile = Yii::$app->basePath . '/media/' . $model->image;
                @file_put_contents($imageFile, $imagecontent);
                if (file_exists($imageFile)) {
                    Yii::$app->customs3->uploadFromPath($imageFile, 'hangshare.media', 'fa/' . $model->image);
                    Yii::$app->imageresize->PatchResize('hangshare.media', 'fa/' . $model->image, 'user');
                } else {
                    $model->image = '';
                    $model->save(false);
                }
                Yii::$app->getSession()->setFlash('success', '، يرجى منك اكمال تعبئة المعلومات في الأسفل لكي نستطيع تحويل لك النقود في المستقبل. <strong>تمت عملية التسجيل بنجاح</strong>');
                return $this->redirect(['//site/welcome']);
            } else {
                return $this->redirect(['//site/loginr', ['id' => $user_profile->getId()]]);
            }
        } else {
            Yii::$app->getSession()->setFlash('error', 'نعتذر حصل خطأ سوف نقوم بحل هذه المشكلة في أقرب وقت. ');
            AwsEmail::SendMail('hasania.khaled@gmail.com', 'HangShare FB  Err 4',
                json_encode(['user info db' => $user,
                    'id' => $user_profile->getId(),
                ]));
            return $this->redirect(['//login']);
        }
    }

    public function actionLoginr($id)
    {
        $user = User::find()->select(['id', 'scId', 'email', 'name', 'password_hash'])->where('scId LIKE :scId', [':scId' => $id])->one();
        var_dump($user);
        die();
        //var_dump(Yii::$app->user->id);die();
        //localhost/main/web/site/loginr/?id=643482198
        $login = new LoginForm();
        $login->rememberMe = true;
        $login->username = $id;
        $login->password = 'Fb91khaled';
        $status = $login->login();
        var_dump(Yii::$app->user->id);
        die();

        return $this->goHome();
    }

    public function actionLogin()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
            $message = '<table>'
                . '<tr><td><b>Name : </b></td><td>' . $model->name . '</td></tr>'
                . '<tr><td><b>Email : </b></td><td>' . $model->email . '</td></tr>'
                . '</table>' . $model->body;

            AwsEmail::SendMail('info@hangshare.com', $model->subject, $message, $model->email);
            AwsEmail::SendMail('hasania.khaled@gmail.com', $model->subject, $message, $model->email);
            Yii::$app->session->setFlash('success', 'شكرا لك لتواصلك عنا سوف يقوم فريقنا بالرد على رسالتكم خلال 24 ساعة القادمة.');
            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionPlan()
    {
        return $this->render('plan');
    }

    public function actionPrivacy()
    {
        return $this->render('privacy');
    }

    public function actionCancel()
    {
        return $this->render('cancel');
    }

    public function actionSuccess()
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect($this->goBack());

        $model = UserPayment::find()->where('userId = ' . Yii::$app->user->id)->one();
        if (!isset($model))
            return $this->redirect($this->goBack());

        $start = date('Y-m-d h:i:s', $model->start_date);
        $end = date('Y-m-d h:i:s', $model->end_date);

        return $this->render('success', ['start' => $start, 'end' => $end]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionPostback()
    {
        AwsEmail::SendMail('hasania.khaled@gmail.com', 'Start paypal', 'Start ' . date('Y-m-d h:i::s'));
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }
        $ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        if (!($res = curl_exec($ch))) {
            curl_close($ch);
            exit;
        }
        curl_close($ch);

        if (strcmp($res, "VERIFIED") == 0) {
            $item_id = isset($_POST['item_number']) ? $_POST['item_number'] : '-1';
            $payment_currency = isset($_POST['mc_currency']) ? $_POST['mc_currency'] : 'USD';
            $txn_id = isset($_POST['txn_id']) ? $_POST['txn_id'] : '-1';
            $receiver_email = isset($_POST['receiver_email']) ? $_POST['receiver_email'] : '-1';
            $payer_email = isset($_POST['payer_email']) ? $_POST['payer_email'] : '-1';
            $payment_amount = isset($_POST['mc_gross']) ? $_POST['mc_gross'] : '-1';
            $payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : 'fa';
            $custom = isset($_POST['custom']) ? $_POST['custom'] : 'Error';


            AwsEmail::SendMail('hasania.khaled@gmail.com', 'paypal', 'Status ' .
                $item_id . ' ' .
                $payment_currency . ' ' .
                $txn_id . ' ' .
                $receiver_email . ' ' .
                $payer_email . ' ' .
                $payment_amount . ' ' .
                $payment_status . ' ' .
                $custom);

            if ($payment_status == 'Completed' && $receiver_email == 'info@hangshare.com') {
                $custom = json_decode($custom);
                switch ($custom->type) {
                    case 'gold_1':
                        $endTime = time() + 1460555157; // 30 days
                        break;
                    case 'gold_3':
                        $endTime = time() + 4381665471; // 90 days
                        break;
                }

                $model = User::findOne($custom->userId);
                $model->plan = 1;
                $model->save(false);

                $payment = new UserPayment;
                $payment->userId = $custom->userId;
                $payment->price = $payment_amount;
                $payment->active = 1;
                $payment->payer_email = $payer_email;
                $payment->start_date = time();
                $payment->end_date = $endTime;
                $payment->planId = $custom->type;
                $payment->transactionId = $txn_id;
                $payment->save(false);
            } else {
                AwsEmail::SendMail('hasania.khaled@gmail.com', 'paypal Error', $payment_status);
            }
        } else {
            AwsEmail::SendMail('hasania.khaled@gmail.com', 'paypal Error', '1111');
        }
        AwsEmail::SendMail('hasania.khaled@gmail.com', 'paypal END', 'END ' . date('Y-m-d h:i:s'));
    }

    public function actionPlangold()
    {
        return $this->redirect(['//site/paypal', 'id' => Yii::$app->user->id, 'plan' => $_POST['plan']]);
    }

    public function actionPaypal($id, $plan)
    {
        $option = [
            'b' => [
                'price' => '10.00',
                'txt' => 'عضوية الحساب الذهبي لمدة شهر واحد',
                'key' => 'gold_1'
            ],
            'c' => [
                'price' => '25.00',
                'txt' => 'عضوية الحساب الذهبي لمدة 3 اشهر',
                'key' => 'gold_3'
            ],
        ];
        $query = array();
        $query['cmd'] = '_xclick';
        $query['lc'] = 'ae';
        $query['charset'] = 'utf-8';
        $query['business'] = 'info@hangshare.com';
        $query['notify_url'] = Yii::$app->urlManager->createAbsoluteUrl('//site/postback');
        $query['return'] = Yii::$app->urlManager->createAbsoluteUrl('//site/success');
        $query['cancel_return'] = Yii::$app->urlManager->createAbsoluteUrl('//site/cancel');
        $query['amount'] = $option[$plan]['price'];
        $query['currency_code'] = 'USD';
        $query['rm'] = '2';
        $query['item_name'] = html_entity_decode($option[$plan]['txt'], ENT_COMPAT, 'UTF-8');
        $query['custom'] = json_encode(array(
            'type' => $option[$plan]['key'],
            'userId' => $id
        ));
        $query_string = http_build_query($query);
        return $this->redirect("https://www.paypal.com/ae/cgi-bin/webscr?{$query_string}");
    }

    public function actionWelcome()
    {
        return $this->render('welcome');
    }

    public function actionTest($e, $p)
    {
        $lo = new LoginForm();
        $lo->rememberMe = true;
        $lo->username = $e;
        $lo->password = $p;
        $lo->login();
        return $this->goHome();
    }

    public function actionSignup($id = '')
    {
        if (!empty($id)) {
            $model = User::findModel($id);
        } else {
            $model = new User;
        }
        $model->setScenario('signup');
        $plan = isset($_POST['plan']) ? $_POST['plan'] : 'a';
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST['image']) && !empty($_POST['image'])) {
                $model->image = str_replace('key', 'image', $_POST['image']);
            }
            if (isset($_POST['User']['year']) && isset($_POST['User']['month']) && isset($_POST['User']['day']) && $_POST['User']['day'] != 0 && $_POST['User']['month'] != 0 && $_POST['User']['day'] != 0) {
                $model->dob = $_POST['User']['year'] . '-' . $_POST['User']['month'] . '-' . $_POST['User']['day'];
            }
            if ($model->save()) {
                $login = new LoginForm();
                $login->rememberMe = true;
                $login->username = $model->email;
                $login->password = $_POST['User']['password'];

                if ($plan == 'a' && $login->login()) {
                    return $this->redirect(['/site/welcome']);
                } else if ($login->login()) {
                    return $this->redirect(['/site/paypal', 'id' => $model->id, 'plan' => $plan]);
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
            'plan' => $plan
        ]);
    }

    public function actionReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'تم ارسال طريقة اعادة تعيين كلمة المرور الى البريد الاكتروني الخاص بك.');
            } else {
                Yii::$app->getSession()->setFlash('error', 'نعتذر حصل خطأ ما يرجى مراسلة ادارة الموقع.');
            }
        }
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetpassword($token)
    {
        $token = substr($token, 0, -1);
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'تم تغير كلمة السر بنجاح.');
            return $this->redirect(['//login']);
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}



