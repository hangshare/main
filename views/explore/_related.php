<?php

use yii\helpers\Html;

$width = 300;
$height = 250;
$url = Yii::$app->urlManager->createAbsoluteUrl(['//explore/view', 'id' => $model->id, 'title' => $model->title]);
$thump = Yii::$app->imageresize->thump($model->cover, $width, $height, 'crop');
?>
<li>
    <a onClick="ga('send', {
                hitType: 'event',
                eventCategory: 'Posts',
                eventAction: 'click',
                eventLabel: 'Related Posts'
            });" href="<?= $url ?>"><?php echo Html::img($thump, ['class' => 'img-responsive']); ?>    </a>
    <a onClick="ga('send', {
                hitType: 'event',
                eventCategory: 'Posts',
                eventAction: 'click',
                eventLabel: 'Related Posts'
            });" href="<?= $url ?>">
        <h4 class="font16"><?php echo $model->title; ?></h4>
    </a>
</li>