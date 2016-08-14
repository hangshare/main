<?php

use \yii\helpers\Html;

$this->title = Yii::t('app', 'Success Payment');
?>

<div class="container">
    <h1><?= $this->title ?></h1>
    <p><?= Yii::t('app','Gold.user.success') ?></p>
    <p><?= Yii::t('app','Subscription period') ?></p>
    <p><?= Yii::t('app', 'From') ?> : <?= $start; ?></p>
    <p><?= Yii::t('app', 'To') ?> : <?= $end; ?></p>
    <div class="inlineblock">
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add Post'), ['//explore/post'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Profile Page'), ['//user', 'id' => Yii::$app->user->id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>