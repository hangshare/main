<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

$this->title = 'تعيين كلمة مرور جديدة';
?>
<div class="container">
    <div class="white-box">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>يرجى اختيار كلمة مرور مناسبة :</p>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('حفظ', ['class' => 'btn btn-primary res-full']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
