<?php

use yii\helpers\Html;
$this->title=Yii::t('app','Welcome in hangshare');
?>
<div class="container">
    <div class="white-box">
        <div class="text-center">
            <h1 class="text-center m-b-20"><?= $this->title ?></h1>
            <?= Html::a(Yii::t('app','Add your first post'), ['explore/post'], ['class' => 'btn btn-primary btn-lg']) ?>
            <br>
            <br>
            <br>
            <p class="text-center"><?=Yii::t('app','Welcome.message') ?></p>
            <?= Html::a(Yii::t('app','Edit your payment methods'), ['//u/transfer'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>