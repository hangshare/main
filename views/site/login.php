<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
$this->title = Yii::t('app', 'Sign in to your account');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="col-md-6 col-md-offset-3">
        <div class="white-box row padding-20" style="margin-top: 30px;">
            <div class="row">
                <h4><?= Yii::t('app', 'Signup using social networks') ?></h4>
                <a onClick="ga('send', {
                            hitType: 'event',
                            eventCategory: 'Login',
                            eventAction: 'Facebook',
                            eventLabel: 'Login Page'
                        });" href="javascript:void(0);" rel="nofollow" class="btn btn-primary btn-block fb-login"
                   style="background-color: #3b5998;
    font-size: 16px;
    text-align: center;
    padding: 10px;
    margin-bottom: 15px;
">
                    <i class="fa fa-fw fa-facebook fb-icon"></i>
                      <?= Yii::t('app', 'Signin using your Facebook account') ?>
                </a>

                <?= Html::a(Yii::t('app', 'Free Registrations'), ['//register/'], [
                    'class' => 'btn btn-default btn-block',
                    'style'=>'font-size: 16px;',
                    'data' => [
                        'method' => 'post',
                        'params' => ['plan' => 'a'],
                    ]
                ])
                ?>
            </div>
            <hr>
            <div class="row">
                <h1 class="font-18"><?= Html::encode($this->title) ?></h1>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->input('email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div class="pull-right">
                    <?= Html::a(Yii::t('app', 'Forget your username or password?'), ['//request-password-reset']) ?>
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
    </div>
</div>