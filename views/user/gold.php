<?php
$this->title = Yii::t('app', 'Premium Status');
$start = date('Y-m-d h:i:s', $model->start_date);
$end = date('Y-m-d h:i:s', $model->end_date);

$diff = $model->end_date - time();
$time = number_format($diff / (60 * 60));
if ($time > 24) {
    $time = number_format($diff / (60 * 60 * 24)) . ' ' . Yii::t('app', 'User.day');
} else {
    $time .= ' ' . Yii::t('app', 'Hour');
}
?>
<div class="container">
    <h1><?= $this->title ?></h1>

    <p><?= Yii::t('app', 'Subscription period') ?></p>

    <p><?= Yii::t('app', 'From') ?> : <?= $start; ?></p>

    <p><?= Yii::t('app', 'To') ?> : <?= $end; ?></p>
    <h4><?= Yii::t('app', 'Reminding time') ?></h4>

    <p><?= $time ?></p>
</div>