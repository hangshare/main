<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'action' => ['search'],
    'method' => 'GET',
]);
?>
<input id="q" action="<?= Yii::$app->urlManager->createAbsoluteUrl(['//explore/search']) ?>" class="serchq" value="<?= isset($_GET['q']) ? Html::encode($_GET['q']) : '' ?>" name="q"
       placeholder="<?= Yii::t('app','Search') ?>"/>
<?= Html::submitButton('<i class="glyphicon glyphicon-search"></i>', ['class' => 'btn btn-search']) ?>
<?php ActiveForm::end(); ?>
