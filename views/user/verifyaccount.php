<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('app', 'Verify account');
?>
<div class="container">
    <div class="white-box">
        <div class="text-center">
            <?php if (!$sent): ?>
                <h1 class="text-center"><?= Yii::t('app', 'Please verify your email') ?></h1>
                <p class="text-center"><?= Yii::t('app', 'In order to add post and use full features of habgshare website') ?></p>
                <?php $form = ActiveForm::begin(['id' => 'act', 'class' => 'text-center']); ?>
                <?= Html::submitButton(Yii::t('app', 'Send verification key'), ['class' => 'btn btn-primary center', 'name' => 'save-button']) ?>
                <?php ActiveForm::end(); ?>
            <?php else: ?>
                <h1 class="text-danger text-center"><?= Yii::t('app', 'We have sent you an verification email') ?></h1>
            <?php endif; ?>
        </div>
    </div>
</div>