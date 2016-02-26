<?php
use yii\helpers\Html;
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
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<div class="main">
    <div class="container">
        <div class="white-box">
            <div class="row">
                <div class="col-xs-3">
                    <div class="list-group">
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createUrl(['//user/' . Yii::$app->user->identity->id]); ?>">
                            <i class="fa fa-user"></i> <?= Html::img(Yii::$app->imageresize->thump(Yii::$app->user->identity->image, 50, 50, 'crop')); ?> <?php echo $this->params['user']->name; ?>
                        </a>
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createUrl(['//user/manage']); ?>">
                            <i class="fa fa-info"></i> المعلومات الشخصية  
                        </a>
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createUrl(['//user/transfer']); ?>">
                            <i class="fa fa-money"></i>طرق تحويل النقود 
                        </a>
                    </div>
                </div>
                <div class="col-xs-9">
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
