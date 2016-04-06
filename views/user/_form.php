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
                <span>اختر صورة</span>
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
            <p class="row">يرجى كتابة الرقم كامل مع الرقم الخاص بالدولة ، و يرجى العلم بأنه لن نقوم بنشر هذا الرقم باي
                مكان على الموقع.</p>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ التغيرات'), ['class' => 'btn btn-primary res-full']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<from id="uploadform" method="POST" enctype="multipart/form-data" style="display: none;">
    <input id="files3" type="file"/>
    <input id="type" value="user"/>
</from>
