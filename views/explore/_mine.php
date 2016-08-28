<?php
use yii\helpers\Html;

$width = 500;
$height = 350;
//if (isset($model) && isset($model->post)) {
//    $model = $model->post;
//}
if (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') {
    $home = true;
    $width = 400;
    $height = 250;
}
$thump = Yii::$app->imageresize->thump($model->cover, $width, $height, 'crop');
?>
<li>
    <a href="<?= $model->url ?>" title="<?= Html::encode($model->title) ?>"
       style="display: inline-block; border-bottom: 1px solid #eee; background-color: #fff; padding: 0 0 15px 0;
       text-decoration: none; border-radius: 3px;">
        <div class="col-md-3">
            <div class="row">
                <?php echo Html::img($thump, ['class' => 'img-responsive zoom-tilt', 'style'=>'padding:25px;']); ?>
            </div>
        </div>
        <div class="col-md-9">
            <h3 style="font-size: 20px;color: #4e83a2; font-weight: bold;"><?php echo $model->title; ?></h3>

            <span style="color: #999;">
                 <?php
            $totalViews = $model->postStats->views;
            echo number_format($totalViews + 1);
            ?> <?= Yii::t('app', 'Views') ?> </b></span>
            <?php if($model->published) : ?>
                <span class="label label-success"><?= Yii::t('app','Published') ?></span>
            <?php else  : ?>
                <span class="label label-danger"><?= Yii::t('app','Pending') ?></span>
            <?php endif; ?>

        </div>
    </a>
</li>