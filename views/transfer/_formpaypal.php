<?php

use app\models\PayPal;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$model = new PayPal;
if (isset($data)) {
    $obj = json_decode($data->info);
    $model->email = $obj->email;
}

$form = ActiveForm::begin(['id' => 'PayPal-form']);
?>
<?= $form->field($model, 'email') ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
</div>
<?php ActiveForm::end(); ?>