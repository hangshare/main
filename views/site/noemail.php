<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\models\User;

$model = new User;
?>
<div class="container">
    <div class="center">
        <div class="col-xs-6 col-xs-offset-3">
            <div class="white-box row">
                <h1 class="font-16"><?= Yii::t('app', 'no email title') ?></h1>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'email')->input('email') ?>
                <div class="form-group">
                    <?=
                    Html::submitButton(Yii::t('app', 'Add Email'), [
                        'class' => 'btn btn-primary res-full',
                        'name' => 'login-button'
                    ]) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
