<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'تسجيل الدخول ';
$this->params['breadcrumbs'][] = $this->title;
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
                        <?= Html::a('هل نسيت كلمة المرور ؟', ['site/request-password-reset']) ?>    
                    </div>

                    <div class="form-group">
                        <?=
                        Html::submitButton('تسجيل الدخول', [
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
                    <h3 class="m-b-20">التسجيل باستخدام مواقع التواصل</h3>
                    <a onClick="ga('send', {
                            hitType: 'event',
                            eventCategory: 'Login',
                            eventAction: 'Facebook',
                            eventLabel: 'Login Page'
                        });" href="<?= Yii::$app->urlManager->createUrl('//site/facebook'); ?>" class="btn btn-primary" style="background-color: #3b5998;">
                        <i class="fa fa-fw fa-facebook pull-left" style=" border-left: 1px solid;
                           margin: 3px 5px 0 10px;
                           padding-left: 10px;"></i>   
                        <span class="pull-left">سجل باستخدام حساب فيسبوك</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>