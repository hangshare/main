<?php

use app\models\BankTransfer;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$model = new BankTransfer;
if (isset($data)) {
    $obj = json_decode($data->info, true);
    $model->attributes = $obj;
}

$form = ActiveForm::begin(['id' => 'PayPal-form']);
?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'account') ?>
<?= $form->field($model, 'bank_name') ?>
<?= $form->field($model, 'bank_branch') ?>
<?= $form->field($model, 'IBAN') ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
</div>
<?php ActiveForm::end(); ?>
