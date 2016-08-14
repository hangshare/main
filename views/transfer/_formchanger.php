<?php

use app\models\CashierTransfer;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$model = new CashierTransfer;
if (isset($data)) {
    $obj = json_decode($data->info, true);
    $model->attributes = $obj;
}

$form = ActiveForm::begin(['id' => 'PayPal-form']);
?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'phone') ?>
<?= $form->field($model, 'address') ?>
<?= $form->field($model, 'cashier_name') ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
</div>
<?php ActiveForm::end(); ?>
