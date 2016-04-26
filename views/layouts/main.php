<?php
use kartik\alert\Alert;

?>
<?php
$this->beginContent('@app/views/layouts/htmlhead.php');
$this->endContent();
?>
<?php
$this->beginContent('@app/views/layouts/header.php');
$this->endContent();
?>
<?php
if (count(Yii::$app->getSession()->getAllFlashes()) > 0): ?>
    <?php foreach (Yii::$app->getSession()->getAllFlashes() as $key => $value) : ?>
        <div class="container">
            <div class="m-t-25">
                <div class="alert text-center alert-<?= $key ?>"> <?= Yii::$app->session->getFlash($key) ?></div>
            </div>
        </div>
        <?php
    endforeach;
endif;
?>
<div class="main">
    <?= $content ?>
</div>
<?php
$this->beginContent('@app/views/layouts/footer.php');
$this->endContent();
?>
<?php $this->endPage() ?>
