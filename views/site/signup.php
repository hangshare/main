<style>
    .field-user-username > .help-block-error {
        display: none;
    }
</style>
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Country;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

$this->title = Yii::t('app', 'New Account');
$this->params['breadcrumbs'][] = $this->title;

$thump = Yii::$app->imageresize->thump($model->image, 100, 80, 'crop');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$fb = new Facebook\Facebook([
    'app_id' => '1024611190883720',
    'app_secret' => '0df74c464dc8e58424481fb4cb3bb13c',
    'default_graph_version' => 'v2.4',
    'persistent_data_handler' => 'session'
]);
$helper = $fb->getRedirectLoginHelper();

$params = ['scope' => 'email,user_about_me'];
$fUrl = $helper->getLoginUrl('https://www.hangshare.com/site/facebook/', $params);


?>
<div class="container m-t-25">
    <div class="center w-600">
        <div class="white-box">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <?php if (!isset($_GET['id'])) : ?>
                <div class="center text-center">
                    <a onClick="ga('send', {
                                    hitType: 'event',
                                    eventCategory: 'Sign Up',
                                    eventAction: 'Facebook',
                                    eventLabel: 'Sign Up Page'
                                });" href="<?= $fUrl; ?>" rel="nofollow"
                       class="btn btn-primary btn-block fb-btn">
                        <i class="fa fa-fw fa-facebook pull-left fb-icon"></i>
                        <span class="pull-left"><?= Yii::t('app', 'Grab your data from your Facebook account') ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="site-signup white-box">
            <div class="row">
                <div class="col-lg-12">
                    <?php
                    $form = ActiveForm::begin(['id' => 'form-signup',
                        'enableClientValidation' => true
                    ]);
                    ?>
                    <h3><?= Yii::t('app', 'Account Info') ?></h3>
                    <hr>
                    <?= $form->field($model, 'name') ?>
                    <?= $form->field($model, 'email') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <h3><?= Yii::t('app', 'Profile Page') ?></h3>
                    <hr>
                    <label for="user-image" class="control-label"
                           style="display:block;"><?= Yii::t('app', 'User.image') ?></label>

                    <div class="col-xs-2" style="background-color: #f8f8f8; text-align: center; padding: 0;">
                        <?php echo Html::img($thump, ['id' => 'coveri']); ?>
                        <div id="prev"
                             style="background-color: rgba(0, 0, 0, 0.4);padding: 40px 38px;position: absolute;text-align: center;top: 0; display: none;">
                            <i class="fa fa-spin fa-spinner fa-2x"
                               style="position: relative; top: -10px; color: #fff;"></i>
                        </div>
                        <button class="btn btn-primary btn-block" id="uploadtos3" style="border-radius: 0;">
                            <span><?= Yii::t('app', 'Choose a picture') ?></span>
                        </button>
                        <input id="cover_input" name="image" type="hidden" value=""/>
                    </div>
                    <div class="clearfix"></div>
                    <hr/>

                    <div class="row">
                        <div class="rela">
                    <span class="username-url">www.hangshare.com/user/</span>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        echo $form->field($model, 'username')->textInput([
                            'class' => 'col-sm-3 text-left',
                            'placeholder' => 'Ahmad-Adel'
                        ])->label(null, ['class' => 'col-sm-4']); ?>
                    </div>
                    <div class="row"
                         style="font-size: 12px; line-height: 10px; background-color: #ffdd88; padding: 10px;border-radius:4px; margin: 10px 0;">
                        <p><?= Yii::t('app', 'username.note.1') ?></p>

                        <p><?= Yii::t('app', 'username.note.2') ?></p>

                        <p><?= Yii::t('app', 'username.note.3') ?></p>
                    </div>
                    <hr/>
                    <?= $form->field($model, 'bio')->textarea(); ?>
                    <hr/>
                    <?php
                    if ($model->isNewRecord) {
                        $model->gender = 1;
                    }
                    echo $form->field($model, 'gender')->radioList(['1' => Yii::t('app', 'Male'), 2 => Yii::t('app', 'Female')]);
                    ?>
                    <hr/>
                    <label style="margin-bottom: 10px;"><?= Yii::t('app', 'User.dob') ?></label>
                    <div id="containerdate">
                        <?php
                        $monthArr = [
                            1 => Yii::t('app', 'January'),
                            2 => Yii::t('app', 'February'),
                            3 => Yii::t('app', 'March'),
                            4 => Yii::t('app', 'April'),
                            5 => Yii::t('app', 'May'),
                            6 => Yii::t('app', 'June'),
                            7 => Yii::t('app', 'July'),
                            8 => Yii::t('app', 'August'),
                            9 => Yii::t('app', 'September'),
                            10 => Yii::t('app', 'October'),
                            11 => Yii::t('app', 'November'),
                            12 => Yii::t('app', 'December'),
                        ];
                        $dayArr = range(1, 31);
                        $yearArr = range(1950, date('Y') - 18);
                        $yearArr = array_combine($yearArr, $yearArr);
                        ?>
                        <ul class="list-inline">
                            <li><?= $form->field($model, 'day')->dropDownList($dayArr, ['prompt' => Yii::t('app', 'User.day'), 'class' => ''])->label(false); ?></li>
                            <li><?= $form->field($model, 'month')->dropDownList($monthArr, ['prompt' => Yii::t('app', 'User.month'), 'class' => ''])->label(false); ?></li>
                            <li><?= $form->field($model, 'year')->dropDownList($yearArr, ['prompt' => Yii::t('app', 'User.year'), 'class' => ''])->label(false); ?></li>
                        </ul>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <lable style="font-weight: bold; margin-bottom: 10px;display: block;" for="user-country"><?= Yii::t('app', 'Location') ?></lable>
                        <?= $form->field($model, 'country')->dropDownList(ArrayHelper::map(Country:: find()->where("published = 1 AND lang = '" . Yii::$app->language . "'")->all(), 'id', 'name')
                            , ['prompt' => Yii::t('app', 'Location'), 'class' => '', 'rel' => 'autoload'])->label(false);
                        ?>
                    </div>
                    <hr/>
                    <?= $form->field($model, 'phone') ?>
                    <p><?= Yii::t('app', 'user.phone.note') ?></p>

                    <div class="form-group">
                        <?=
                        Html::submitButton(Yii::t('app', 'Register'), [
                            'class' => 'btn btn-primary btn-block m-t-25',
                            'onClick' => "ga('send', {
                            hitType: 'event',
                            eventCategory: 'Sign Up',
                            eventAction: 'Form',
                            eventLabel: 'Sign Up Page'
                        });",
                            'name' => 'signup-button'
                        ])
                        ?>
                        <?php if ($plan == 'b') : ?>
                            <p class="normal m-t-8"><?= Yii::t('app','PayPal redirect1') ?></p>
                        <?php elseif ($plan == 'c'): ?>
                            <p class="normal m-t-8"><?= Yii::t('app','PayPal redirect2') ?></p>
                        <?php endif; ?>
                    </div>
                    <input name="plan" type="hidden" value="<?= $plan; ?>"/>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<from id="uploadform" method="POST" enctype="multipart/form-data" style="display: none;">
    <input id="files3" type="file"/>
    <input id="type" value="user"/>
</from>