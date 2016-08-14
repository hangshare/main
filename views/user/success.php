<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'user.success.payment');
?>
<div class="container">
    <div class="white-box">
        <h1 class="text-center"><?= Yii::t('app', 'money.sent') ?></h1>
        <br>
        <br>

        <div class="text-center">
            <?= Html::a(Yii::t('app', 'back.home'), 'http://www.hangshare.com/', ['class' => 'btn btn-default btn-lg']); ?>
        </div>
    </div>
</div>