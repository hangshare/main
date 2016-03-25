<?php
use yii\helpers\Html;

$thump = Yii::$app->imageresize->thump($data->cover, 300, 250, 'crop');
?>
<li>
    <a onClick="ga('send', {
                hitType: 'event',
                eventCategory: 'Posts',
                eventAction: 'click',
                eventLabel: 'Most Viewed'
            });" href="<?= $data->url ?>">
        <?php echo Html::img($thump, ['class' => 'img-responsive']); ?>
        <h4 class="font16"><?php echo $data->title; ?></h4>
    </a>
</li>