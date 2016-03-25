<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\bootstrap\Modal;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Country;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="col-sm-12 user-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="row">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'col-sm-6'])->label(null, ['class' => 'col-sm-4']); ?>
    </div>
    <hr>
    <div class="row">
        <?= $form->field($model, 'image')->hiddenInput()->label(null, ['class' => 'col-sm-4']) ?>
        <div class="col-sm-8">
            <div class="row">
                <div class="col-xs-10">
                    <div class="row">
                        <img id='coveri'width="60" src="<?= Yii::$app->imageresize->thump($model->image, 50, 50, 'crop'); ?>" />
                    </div>
                </div>
                <div class="col-xs-2 text-left">
                    <a id='js_edpix' class="btn btn-default text-right" href="javascript:void(0);">تعديل</a>
                </div>
            </div>
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
                    'header' => '<h4>تعديل كلمة المرور</h4>',
                    'toggleButton' => ['label' => 'تعديل', 'class' => 'btn btn-default'],
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
                        <a class="btn btn-primary btn-block pull-left js_passch res-full">حفظ التغيرات</a>
                    </div>
                </div>

                <?php Modal::end(); ?>
            </div>             
        </div>
        <hr>
    <?php endif; ?>
    <div class="row">
        <?= $form->field($model, 'gender')->radioList(['1' => 'ذكر', '2' => 'أنثى'])->label(null, ['class' => 'col-sm-4']); ?>
    </div>
    <hr>
    <div class="row">
        <label class="col-sm-4">تاريخ الميلاد</label>
        <div class="col-sm-6">
            <div class="row">
                <div id="containerdate">
                    <?php
                    $monthArr = [
                        1 => ' كانون الثاني',
                        2 => ' شباط',
                        3 => ' آذار',
                        4 => ' نيسان',
                        5 => ' أيار',
                        6 => ' حزيران',
                        7 => ' تموز',
                        8 => ' أب',
                        9 => ' أيلول',
                        10 => ' تشرين الأول',
                        11 => ' تشرين الثاني',
                        12 => ' كانون الأول',
                    ];
                    $dayArr = range(1, 31);
                    $dayArr = array_combine($dayArr, $dayArr);
                    $yearArr = range(1980, date('Y') - 18);
                    $yearArr = array_combine($yearArr, $yearArr);
                    ?>
                    <ul class="list-inline">
                        <li><?= $form->field($model, 'day')->dropDownList($dayArr, ['prompt' => 'اليوم', 'class' => ''])->label(false); ?></li>
                        <li><?= $form->field($model, 'month')->dropDownList($monthArr, ['prompt' => 'الشهر', 'class' => ''])->label(false); ?></li>
                        <li><?= $form->field($model, 'year')->dropDownList($yearArr, ['prompt' => 'السنة', 'class' => ''])->label(false); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <?=
        $form->field($model, 'country')->dropDownList(ArrayHelper::map(Country:: find()->all(), 'id', 'name_ar')
                , ['prompt' => 'مكان الإقامة', 'class' => ''])->label(null, ['class' => 'col-sm-4']);
        ?>
    </div>
    <hr>
    <div class="row">
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'col-sm-6'])->label(null, ['class' => 'col-sm-4']); ?>
    </div>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-8">
            <p class="row">يرجى كتابة الرقم كامل مع الرقم الخاص بالدولة ، و يرجى العلم بأنه لن نقوم بنشر هذا الرقم باي مكان على الموقع.</p>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ التغيرات'), ['class' => 'btn btn-primary res-full']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<form id="ProfileImage" method="POST" action="<?= Yii::$app->urlManager->createUrl('//user/image'); ?>" enctype="multipart/form-data" class="hidden">
    <input id="post-cover_file" type="file" name="image" />    
</form>

