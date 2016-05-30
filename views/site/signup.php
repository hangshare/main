<style>
    .field-user-username > .help-block-error{display: none;}
</style>
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

$thump = Yii::$app->imageresize->thump($model->image, 100, 80, 'crop');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$fb = new Facebook\Facebook([
    'app_id' => '1024611190883720',
    'app_secret' => '0df74c464dc8e58424481fb4cb3bb13c',
    'default_graph_version' => 'v2.4',
    'persistent_data_handler'=>'session'
]);
$helper = $fb->getRedirectLoginHelper();

$params = ['scope' => 'email,user_about_me'];
$fUrl = $helper->getLoginUrl('https://www.hangshare.com/site/facebook/', $params);


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
                                });" href="<?= $fUrl; ?>" rel="nofollow"
                       class="btn btn-primary btn-block"
                       style="background-color: #3b5998;height: 30px; margin-bottom: 20px; margin-top: 30px; width: 280px;padding: 4px 10px 10px;">
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
                    <label for="user-image" class="control-label" style="display:block;">الصورة الشخصية</label>
                    <div class="col-xs-2" style="background-color: #f8f8f8; text-align: center; padding: 0;">
                        <?php echo Html::img($thump, ['id' => 'coveri']); ?>
                        <div id="prev"
                             style="background-color: rgba(0, 0, 0, 0.4);padding: 40px 38px;position: absolute;text-align: center;top: 0; display: none;">
                            <i class="fa fa-spin fa-spinner fa-2x"
                               style="position: relative; top: -10px; color: #fff;"></i>
                        </div>
                        <button class="btn btn-primary btn-block" id="uploadtos3" style="border-radius: 0;">
                            <span>اختر صورة</span>
                        </button>
                        <input id="cover_input" name="image" type="hidden" value=""/>
                    </div>
                    <div class="clearfix"></div>
                    <hr/>

                    <div class="row">
                        <div style="position: relative">
                    <span style="    background-color: #d5d5d5;
                    border: 1px solid #848d9c;
                    direction: ltr;
                    left: 47px;
                    padding: 1px 12px;
                    position: absolute;
                    text-align: left;
                    top: 0px;
                    z-index: 3;">www.hangshare.com/user/</span>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        echo $form->field($model, 'username')->textInput([
                            'class' => 'col-sm-3 text-left',
                            'placeholder' => 'Ahmad-Adel'
                        ])->label(null, ['class' => 'col-sm-4']); ?>
                    </div>
                    <div class="row" style="font-size: 12px; line-height: 10px; background-color: #ffdd88; padding: 10px;border-radius:4px; margin: 10px 0;">
                        <p>عنوان الصفحة هو الاسم الذي سوف يظهر في رابط صفحتك الشخصية كما هو موضح بالأعلى</p>
                        <p>  يجب ان يتكون من الأحرف الانجليزية فقط ولا يجب ان يحتوي على اي رموز مثل (%،$،@،...)</p>
                        <p>ويمنع ايضا وجود اي مسافات (فراغات) بين الأحرف</p>
                    </div>
                    <hr/>
                    <?= $form->field($model, 'bio')->textarea(); ?>
                    <hr/>
                    <?php
                    if ($model->isNewRecord) {
                        $model->gender = 1;
                    }
                    echo $form->field($model, 'gender')->radioList(['1' => 'ذكر', 2 => 'أنثى']);
                    ?>
                    <hr/>
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
                    <hr/>
                    <div class="form-group">
                        <lable style="font-weight: bold; margin-bottom: 10px;display: block;" for="user-country">مكان
                            الإقامة
                        </lable>

                        <?= $form->field($model, 'country')->dropDownList(ArrayHelper::map(Country:: find()->where("published = 1")->all(), 'id', 'name_ar')
                            , ['prompt' => 'مكان الإقامة', 'class' => '', 'rel' => 'autoload'])->label(false);
                        ?>
                    </div>
                    <hr/>
                    <?= $form->field($model, 'phone') ?>
                    <p style="font-size: 12px; margin-top: -4px;">يرجى كتابة الرقم كامل مع الرقم الخاص بالدولة ، و يرجى
                        العلم بأنه لن نقوم بنشر هذا الرقم باي مكان على الموقع.</p>

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
                            <p class="normal m-t-8">سوف يتم تحويلك الى صفحة PayPal لكي تدفع $10 لاتمام عملية
                                التسجيل.</p>
                        <?php elseif ($plan == 'c'): ?>
                            <p class="normal m-t-8">سوف يتم تحويلك الى صفحة PayPal لكي تدفع $25 لاتمام عملية
                                التسجيل.</p>
                        <?php endif; ?>
                    </div>
                    <input name="plan" type="hidden" value="<?= $plan; ?>"/>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<from id="uploadform" method="POST" enctype="multipart/form-data" style="display: none;">
    <input id="files3" type="file"/>
    <input id="type" value="user"/>
</from>