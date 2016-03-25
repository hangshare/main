<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FaqSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="faq-search">
    <?php
    $form = ActiveForm::begin([
                'action' => ['create'],
                'method' => 'post',
    ]);
    ?>
    <div class="row" style="margin-right: 8px;">
        <?= $form->field($model, 'question')->textInput(['class' => 'col-xs-10'])->error(false) ?>
        <div class="col-xs-2" style="margin: -16px;padding: 0;">
            <?= Html::submitButton('اضافة', ['class' => 'btn btn-primary btn-block','style'=>"
                  border-radius: 0 !important;
                  margin-top: 2px;
                  padding: 3.4px;
                "]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
