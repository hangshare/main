<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use app\models\Country;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$thump = Yii::$app->imageresize->thump($model->image, 100, 80, 'crop');
?>
<style>
    .field-user-username > .help-block {
        clear: both;
        float: right;
        margin-top: 25px;
    }
</style>
<div class="col-sm-12 user-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="row">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'col-sm-6'])->label(null, ['class' => 'col-sm-4']); ?>
    </div>
    <hr>
    <div class="row">
        <?= $form->field($model, 'image')->hiddenInput()->label(null, []) ?>
        <div class="col-xs-2">
            <?php echo Html::img($thump, ['id' => 'coveri']); ?>
            <div id="prev"
                 style="background-color: rgba(0, 0, 0, 0.4);padding: 40px 38px;position: absolute;text-align: center;top: 0; display: none;">
                <i class="fa fa-spin fa-spinner fa-2x" style="position: relative; top: -10px; color: #fff;"></i>
            </div>
            <button class="btn btn-primary btn-block" id="uploadtos3" style="border-radius: 0;">
                <span><?= Yii::t('app', 'Choose a picture') ?></span>
            </button>
            <input id="cover_input" name="image" type="hidden" value=""/>
        </div>
    </div>
    <hr>
    <div class="row">
        <?= $form->field($model, 'bio')->textarea(['class' => 'col-sm-7'])->label(null, ['class' => 'col-sm-4']); ?>
    </div>
    <hr>
    <div class="row">
        <?php
        if (!filter_var($model->email, FILTER_VALIDATE_EMAIL)) {
            $model->email = '';
        }
        $eoptions['maxlength'] = true;
        $eoptions['class'] = 'col-sm-6';
        echo $form->field($model, 'email')->textInput($eoptions)->label(null, ['class' => 'col-sm-4']);
        ?>
    </div>
    <hr>
    <?php if (empty($model->scId)): ?>
        <div class="row">
            <?php
            $model->password = '123456';
            echo $form->field($model, 'password')->passwordInput(['class' => 'col-sm-6', 'disabled' => 'disabled'])->label(null, ['class' => 'col-sm-4']);
            ?>
            <div class="col-sm-2 text-left" style="margin-top: -8px;">
                <?php
                Modal::begin([
                    'header' => '<h4>' . Yii::t('app', 'Edit Password') . '</h4>',
                    'toggleButton' => ['label' => Yii::t('app', 'Edit'), 'class' => 'btn btn-default'],
                ]);
                ?>
                <div class="row">
                    <?php
                    $model->password = '';
                    echo $form->field($model, 'password_old')->passwordInput(['class' => 'col-sm-6'])->label(null, ['class' => 'col-sm-4']);
                    ?>
                </div>
                <div class="row">
                    <?php
                    $model->password = '';
                    echo $form->field($model, 'password_new')->passwordInput(['class' => 'col-sm-6'])->label(null, ['class' => 'col-sm-4']);
                    ?>
                </div>
                <div class="row">
                    <?= $form->field($model, 'password_re')->passwordInput(['class' => 'col-sm-6'])->label(null, ['class' => 'col-sm-4']) ?>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <a class="btn btn-primary btn-block pull-left js_passch res-full"><?= Yii::t('app', 'Save') ?></a>
                    </div>
                </div>

                <?php Modal::end(); ?>
            </div>
        </div>
        <hr>
    <?php endif; ?>

    <div style="position: relative">
        <span style="    background-color: #d5d5d5;
    border: 1px solid #848d9c;
    direction: ltr;
    left: 57px;
    padding: 1px 12px;
    position: absolute;
    text-align: left;
    z-index: 3;">www.hangshare.com/user/</span>
    </div>
    <div class="row">
        <?php echo $form->field($model, 'username')->textInput(['class' => 'col-sm-4 text-left', 'placeholder' => 'Ahmad-Adel'])->label(null, ['class' => 'col-sm-4']); ?>
    </div>
    <hr/>
    <div class="row">
        <?= $form->field($model, 'gender')->radioList(['1' => Yii::t('app', 'Male'), '2' => Yii::t('app', 'Female')])->label(null, ['class' => 'col-sm-4']); ?>
    </div>
    <hr>
    <div class="row">
        <label class="col-sm-4"><?= Yii::t('app','User.dob') ?></label>

        <div class="col-sm-6">
            <div class="row">
                <div id="containerdate">
                    <?php
                    $monthArr = [
                        1 => Yii::t('app', 'January'),
                        2 => Yii::t('app', 'February'),
                        3 => Yii::t('app', 'March'),
                        4 => Yii::t('app', 'April'),
                        5 => Yii::t('app', 'May'),
                        6 => Yii::t('app', 'June'),
                        7 => Yii::t('app', 'July'),
                        8 => Yii::t('app', 'August'),
                        9 => Yii::t('app', 'September'),
                        10 => Yii::t('app', 'October'),
                        11 => Yii::t('app', 'November'),
                        12 => Yii::t('app', 'December'),
                    ];
                    $dayArr = range(1, 31);
                    $dayArr = array_combine($dayArr, $dayArr);
                    $yearArr = range(1950, date('Y') - 12);
                    $yearArr = array_combine($yearArr, $yearArr);
                    ?>
                    <ul class="list-inline">
                        <?php $model->day = str_replace('0', '', $model->day); ?>
                        <li><?= $form->field($model, 'day')->dropDownList($dayArr, ['prompt' => Yii::t('app', 'User.day'), 'class' => ''])->label(false); ?></li>
                        <li><?= $form->field($model, 'month')->dropDownList($monthArr, ['prompt' => Yii::t('app', 'User.month'), 'class' => ''])->label(false); ?></li>
                        <li><?= $form->field($model, 'year')->dropDownList($yearArr, ['prompt' => Yii::t('app', 'User.year'), 'class' => ''])->label(false); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <?=
        $form->field($model, 'country')->dropDownList(ArrayHelper::map(Country::find()->where("lang = '" . Yii::$app->language . "'")->all(), 'id', 'name')
            , ['prompt' => Yii::t('app', 'Location'), 'class' => ''])->label(null, ['class' => 'col-sm-4']);
        ?>
    </div>
    <hr>
    <div class="row">
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'col-sm-6'])->label(null, ['class' => 'col-sm-4']); ?>
    </div>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-8">
            <p class="row"><?= Yii::t('app', 'user.phone.note') ?></p>
        </div>
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
