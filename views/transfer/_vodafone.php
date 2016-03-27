<?php

use app\models\Vodafone;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$model = new Vodafone;
if (isset($data)) {
    $obj = json_decode($data->info, true);
    $model->attributes = $obj;
}

$form = ActiveForm::begin(['id' => 'vodafone-form']);
?>
<?= $form->field($model, 'phone') ?>

<div class="form-group">
    <?= Html::submitButton('حفظ', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
</div>
<?php ActiveForm::end(); ?>
