<?php

use \yii\helpers\Html;

$this->title = 'تمت عملية الدفع بنجاح';
?>

<div class="container">
    <h1><?= $this->title ?></h1>
    <p>أصبح لديك الآن ميزات العضو الذهبي</p>
    <p>مدة الاشتراك /</p>
    <p>من <?= $start; ?></p>
    <p>إلى <?= $end; ?></p>
    <div class="inlineblock">
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> ' . 'أضف موضوع', ['//explore/post'], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('الصفحة الشخصية', ['//user', 'id' => Yii::$app->user->id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>