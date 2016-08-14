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
       style="display: inline-block; border-bottom: 1px solid #eee; background-color: #fff; padding: 0 0 15px 0; max-width: 100%;
       text-decoration: none; border-radius: 3px;">
        <div class="col-md-3">
            <div class="row">
                <?php echo Html::img($thump, ['class' => 'img-responsive zoom-tilt', 'style'=>'padding:25px;']); ?>
            </div>
        </div>
        <div class="col-md-9" style="overflow: hidden;">
            <h3 style="font-size: 20px;color: #4e83a2; font-weight: bold;"><?php echo $model->title; ?></h3>
            <p><?php
                foreach ($model->postBodies as $data) {
                    echo Yii::$app->helper->limit_text($data->body, 300);
                }
                ?></p>
            <span style="color: #999;">
                 <?php
            $totalViews = $model->postStats->views;
            echo number_format($totalViews + 1);
            ?> <?= Yii::t('app', 'Views') ?> </b></span>
        </div>
    </a>
</li>