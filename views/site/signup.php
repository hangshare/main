<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Country;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

$this->title = 'تسجيل حساب جديد';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container m-t-25">
    <div class="center w-600">
        <div class="white-box">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <?php if (!isset($_GET['id'])) : ?>
                <div class="center text-center">
                    <a onClick="
                                ga('send', {
                                    hitType: 'event',
                                    eventCategory: 'Sign Up',
                                    eventAction: 'Facebook',
                                    eventLabel: 'Sign Up Page'
                                });" href="<?= Yii::$app->urlManager->createUrl('//site/facebook'); ?>" rel="nofollow" class="btn btn-primary btn-block" style="background-color: #3b5998;height: 30px; margin-bottom: 20px; margin-top: 30px; width: 280px;padding: 4px 10px 10px;">
                        <i class="fa fa-fw fa-facebook pull-left" style="border-left: 1px solid;
                           font-size: 15px;
                           margin-top: 3px;
                           padding-left: 10px;"></i>
                        <span class="pull-left">املىء معلوماتك من خلال الفيسبوك</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="site-signup white-box">    
            <div class="row">
                <div class="col-lg-12">
                    <?php
                    $form = ActiveForm::begin(['id' => 'form-signup',
                                'enableClientValidation' => true
                    ]);
                    ?>
                    <h3>معلومات الحساب</h3>
                    <hr>
                    <?= $form->field($model, 'name') ?>
                    <?= $form->field($model, 'email') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <h3>معلومات الصفحة الشخصية</h3>
                    <hr>
                    <div class="row">
                        <?= $form->field($model, 'image')->hiddenInput()->label(null, ['class' => 'col-sm-4']) ?>
                    </div>
                    <div class="row">
                        <div class="col-xs-10">
                            <img id='coveri'width="60" src="<?= Yii::$app->imageresize->thump($model->image, 50, 50, 'crop'); ?>" />
                        </div>
                        <div class="col-xs-2 text-left">
                            <a id='js_edpix' class="btn btn-default text-right" href="javascript:void(0);">اضافة</a>
                        </div>
                    </div>
                    <?= $form->field($model, 'bio')->textarea(); ?>
                    <?php
                    if ($model->isNewRecord) {
                        $model->gender = 1;
                    }
                    echo $form->field($model, 'gender')->radioList(['1' => 'ذكر', 2 => 'أنثى']);
                    ?>
                    <label style="margin-bottom: 10px;">تاريخ الميلاد</label>
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
                        $yearArr = range(1950, date('Y') - 18);
                        $yearArr = array_combine($yearArr, $yearArr);
                        ?>
                        <ul class="list-inline">
                            <li><?= $form->field($model, 'day')->dropDownList($dayArr, ['prompt' => 'اليوم', 'class' => ''])->label(false); ?></li>
                            <li><?= $form->field($model, 'month')->dropDownList($monthArr, ['prompt' => 'الشهر', 'class' => ''])->label(false); ?></li>
                            <li><?= $form->field($model, 'year')->dropDownList($yearArr, ['prompt' => 'السنة', 'class' => ''])->label(false); ?></li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <lable style="font-weight: bold; margin-bottom: 10px;display: block;" for="user-country" >مكان الإقامة</lable>

                        <?= $form->field($model, 'country')->dropDownList(ArrayHelper::map(Country:: find()->where("published = 1")->all(), 'id', 'name_ar')
                                , ['prompt' => 'مكان الإقامة', 'class' => '', 'rel'=>'autoload'])->label(false);
                        ?>
                    </div>
                    <?= $form->field($model, 'phone') ?>
                    <p style="font-size: 12px; margin-top: -4px;">يرجى كتابة الرقم كامل مع الرقم الخاص بالدولة ، و يرجى العلم بأنه لن نقوم بنشر هذا الرقم باي مكان على الموقع.</p>

                    <div class="form-group">
                        <?=
                        Html::submitButton('تسجيل الحساب', [
                            'class' => 'btn btn-primary btn-block m-t-25',
                            'onClick' => "ga('send', {
                            hitType: 'event',
                            eventCategory: 'Sign Up',
                            eventAction: 'Form',
                            eventLabel: 'Sign Up Page'
                        });",
                            'name' => 'signup-button'
                        ])
                        ?>
                        <?php if ($plan == 'b') : ?>
                            <p class="normal m-t-8">سوف يتم تحويلك الى صفحة PayPal لكي تدفع $10 لاتمام عملية التسجيل.</p>
                        <?php elseif ($plan == 'c'): ?>
                            <p class="normal m-t-8">سوف يتم تحويلك الى صفحة PayPal لكي تدفع $25 لاتمام عملية التسجيل.</p>
                        <?php endif; ?>
                    </div>
                    <input name="plan" type="hidden" value="<?= $plan; ?>" />
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="ProfileImage" method="POST" action="<?= Yii::$app->urlManager->createUrl('//user/image'); ?>" enctype="multipart/form-data" class="hidden">
    <input id="post-cover_file" type="file" name="image" />
</form>
