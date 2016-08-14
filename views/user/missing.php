<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'User Missing info');
?>
<div class="container">
    <div class="col-md-8 col-lg-offset-2">
        <div class="white-box">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <hr>
            <div class="row">
                <?php
                if (!filter_var($model->email, FILTER_VALIDATE_EMAIL)) {
                    $model->email = '';
                }
                $eoptions['maxlength'] = true;
                $eoptions['class'] = 'col-sm-6';
                $eoptions['type'] = 'email';
                echo $form->field($model, 'email')->textInput($eoptions)->label(null, ['class' => 'col-sm-4']);
                ?>
            </div>
            <hr>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary res-full']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <from id="uploadform" method="POST" enctype="multipart/form-data" style="display: none;">
            <input id="files3" type="file"/>
            <input id="type" value="user"/>
        </from>
    </div>
</div>