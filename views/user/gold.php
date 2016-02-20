<?php
$this->title = 'حالة الحساب الذهبي';
$start = date('Y-m-d h:i:s', $model->start_date);
$end = date('Y-m-d h:i:s', $model->end_date);

$diff = $model->end_date - time();
$time = number_format($diff / ( 60 * 60 ));
if ($time > 24) {
    $time = number_format($diff / ( 60 * 60 * 24)) . ' يوم';
} else {
    $time.=' ساعة';
}
?>
<div class="container">
    <h1><?= $this->title ?></h1>
    <h4>مدة الاشتراك</h4>
    <p>من <?= $start; ?></p>
    <p>إلى <?= $end; ?></p>
    <h4>الوقت المتبقي لنهاية الإشتراك</h4>
    <p><?= $time ?></p>
</div>