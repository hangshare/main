<?php

use yii\helpers\Html;

$cantak = 100;
if ($model->plan == '1') {
    $cantak = 50;
}
?>
<div class="container">
    <div class="white-box">
        <h1>سحب النقود</h1>
        <?php if ($chkTransaction) : ?>
            لقد قمت بطلب ارسال المبلغ يرجى الانتظار سوف نقوم بارسال المبلغ خلال 24 ساعة من وقت الطلب.
        <?php else : ?>
            <?php if ($model->userStats->cantake_amount > 0) : ?>
                <p class="red">لديك <?php echo number_format($model->userStats->cantake_amount, 2) . '$'; ?> دولار قابلة للسحب الآن.</p>
                <p>عند الضغط على طلب ارسال المبلغ الى حساب  سوف نقوم بتحويل المبلغ اليك باستخدام معلومات تحويل التقود المذكورة ادناه.   </p>

                <h3>يرجى مراجعة معلومات حسابك</h3>
                <?php
                if ($model->transfer_type == 0) {
                    echo Html::a('تحديث معلومات الدفع', ['//user/transfer'], ['class' => 'btn btn-primary']);
                } else {
                    $obj = json_decode($model->currentMethod->info);
                    echo '<ul class="list-unstyled well">';
                    if ($model->transfer_type == 1) {
                        echo '<li>حساب PayPal</li>';
                        echo '<li><ul><li>' . $obj->email . '</li></ul></li>';
                    }
                    if ($model->transfer_type == 2) {
                        echo '<li>حساب بنكي</li>';
                        echo '<li><ul>';
                        echo '<li><b>الاسم : </b>' . $obj->name . '</li>';
                        echo '<li><b>رقم الحساب : </b>' . $obj->account . '</li>';
                        echo '<li><b>اسم البنك : </b>' . $obj->bank_name . '</li>';
                        echo '<li><b>اسم الفرع : </b>' . $obj->bank_branch . '</li>';
                        echo '<li><b>IBAN : </b>' . $obj->IBAN . '</li>';
                        echo '</ul></li>';
                    }
                    if ($model->transfer_type == 3) {
                        echo '<li><b>نوع التحويل : صراف آلي</b></li>';
                        echo '<li><ul class="list-unstyled">';
                        echo '<li><b>اسم الشخص المراد التحويل له : </b>' . $obj->name . '</li>';
                        echo '<li><b>رقم الهاتف : </b>' . $obj->phone . '</li>';
                        echo '<li><b>العنوان : </b>' . $obj->address . '</li>';
                        echo '<li><b>اسم محل الصرافة : </b>' . $obj->cashier_name . '</li>';
                        echo '</ul></li>';
                    }
                    echo '</ul>';
                    echo Html::a('طلب ارسال المبلغ ', ['request'], ['class' => 'btn btn-primary']);
                }
                ?>

            <?php else : ?>
                <p>        يوجد في ريصيد الآن <b><?= number_format($model->userStats->available_amount, 3); ?></b> لاكن هذا المبلغ غير قابل للسحب الآن. </p>
                <p>حسب شروط الموقع يمكنك تحويل النقود من حسابك بعد ان يتم تجاوز حسابك مبلغ <?= $cantak ?> دولار .</p>
                <p>يقوم الموقع بإرسال النقود الى حسابكم خلال 5 ايام عمل من تاريخ طلب المبلغ .</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>