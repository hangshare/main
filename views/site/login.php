<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('app', 'Sign in to your account');
$this->params['breadcrumbs'][] = $this->title;

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
<div class="container">
    <div class="white-box row">
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="row">
                    <h1 class="font-18"><?= Html::encode($this->title) ?></h1>
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <?= $form->field($model, 'username') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    <div class="pull-right">
                        <?= Html::a('Forget your username or password?', ['//request-password-reset']) ?>
                    </div>

                    <div class="form-group">
                        <?=
                        Html::submitButton(Yii::t('app', 'Login'), [
                            'class' => 'btn btn-primary res-full',
                            'onClick' => "ga('send', {
                            hitType: 'event',
                            eventCategory: 'Login',
                            eventAction: 'Form',
                            eventLabel: 'Login Page'
                        });",
                            'name' => 'login-button'
                        ])
                        ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="col-md-2">

            </div>
            <div class="col-md-4">
                <div class="row">
                    <h3 class="m-b-20"><?= Yii::t('app', 'Signup using social networks') ?></h3>
                    <a onClick="ga('send', {
                            hitType: 'event',
                            eventCategory: 'Login',
                            eventAction: 'Facebook',
                            eventLabel: 'Login Page'
                        });" href="<?= $fUrl; ?>" class="btn btn-primary"
                       style="background-color: #3b5998;">
                        <i class="fa fa-fw fa-facebook pull-left fb-icon"></i>
                        <span class="pull-left"><?= Yii::t('app', 'Signin using your Facebook account') ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>