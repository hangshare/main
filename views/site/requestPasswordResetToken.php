<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\PasswordResetRequestForm */

$this->title = 'طلب اعادة تعيين كلمة المرور';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="white-box">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>لطلب اعادة تعيين كلمة المرور يرجى وضع البريد الاكتروني الخاص بحسابك على موقع هانج شير ، سوف نقوم بارسال لك بريد الكتروتي يحتوي على رابط يرجى الضغط عليه.</p>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <?= $form->field($model, 'email') ?>
                <div class="form-group">
                    <?= Html::submitButton('ارسل', ['class' => 'btn btn-primary res-full']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
