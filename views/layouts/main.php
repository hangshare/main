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
<?= Alert::widget() ?>
<div class="main">
    <?= $content ?>
</div>
<?php
$this->beginContent('@app/views/layouts/footer.php');
$this->endContent();
?>
<?php $this->endPage() ?>
