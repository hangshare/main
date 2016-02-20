<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="post-search">
    <?php
    $form = ActiveForm::begin([
                'action' => ['search'],
                'method' => 'get',
    ]);
    ?>
    <div class="form-group">
        <input id="q" class="form-control serchq" value="<?= isset($_GET['q']) ? Html::encode($_GET['q']) : '' ?>" name="q" placeholder="البحث" />
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i>', ['class' => 'btn btn-primary btn-search']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>