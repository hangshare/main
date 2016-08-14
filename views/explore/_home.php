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
    <a href="<?= $model->url ?>" title="<?= Html::encode($model->title) ?>">
        <?php echo Html::img($thump, ['class' => 'img-responsive']); ?>
        <div style="padding: 10px">
            <h3 class="twolines"><?php echo $model->title; ?></h3>
            <span style="color: #999;">
                 <?php
                 $totalViews = $model->postStats->views;
                 echo number_format($totalViews + 1);
                 ?> <?= Yii::t('app', 'Views') ?> </b></span>
        </div>
    </a>
</li>