<?php
use yii\helpers\Html;
?>
<?php
$this->beginContent('@app/views/layouts/htmlhead.php');
$this->endContent();
?>
<?php
$this->beginContent('@app/views/layouts/header.php');
$this->endContent();
?>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<div class="main">
    <div class="container">
        <div class="white-box">
            <div class="row">
                <div class="col-xs-3 res-hidden">
                    <div class="list-group">
                        <?php
                        $username = empty(Yii::$app->user->identity->username) ? Yii::$app->user->identity->id : Yii::$app->user->identity->username;
                        ?>
                        <a class="list-group-item" href=
                        "<?= Yii::$app->urlManager->createUrl(['//user/' . $username]); ?>">
                            <i class="fa fa-user m-l-30"></i> <?php echo $this->params['user']->name; ?>
                        </a>
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createUrl(['//u/manage']); ?>">
                            <i class="fa fa-info m-l-30"></i> <?= Yii::t('app', 'Personal Info') ?>
                        </a>
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createUrl(['//u/transfer']); ?>">
                            <i class="fa fa-money m-l-30"></i><?= Yii::t('app', 'Transfer Methods') ?>
                        </a>
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createUrl(['//u/report']); ?>">
                            <i class="fa fa-money m-l-30"></i><?= Yii::t('app', 'Money Report') ?>
                        </a>
                    </div>
                </div>
                <div class="col-xs-9 res-full res-nopadding">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->beginContent('@app/views/layouts/footer.php');
$this->endContent();
?>
<?php $this->endPage() ?>
