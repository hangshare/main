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
       style="display: inline-block; border-bottom: 1px solid #eee; background-color: #fff;
       text-decoration: none; border-radius: 3px; margin-bottom: 10px; border-radius: 3px;">
        <?php echo Html::img($thump, ['class' => 'img-responsive','style'=>'border-bottom: 1px solid #eeeeee;']); ?>
        <h3 style="padding: 0 10px; font-size: 15px;color: #4e83a2; font-weight: bold;"><?php echo $model->title; ?></h3>
    </a>
</li>