<?php

use yii\helpers\Html;
$this->title='مرحبا بكم موقع هانج شير';
?>
<div class="container">
    <div class="white-box">
        <div class="text-center">
            <h1 class="text-center m-b-20">مرحبا بكم في موقع هانج شير</h1>
            <?= Html::a('اضف أول موضوع', ['explore/post'], ['class' => 'btn btn-primary btn-lg']) ?>
            <br>
            <br>
            <br>
            <p class="text-center">تأكد من تحديث معلوماتك الشخصية واضافة الطريقة التي تناسبك لتحويل النقود لك في حال استحقاقها</p>
            <?= Html::a('تعديل معلومات طرق الدفع', ['//u/transfer'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>