<?php

use yii\helpers\Html;

$thump = Yii::$app->imageresize->thump($model->cover, 300, 250, 'crop');
?>
<li>
    <a href="<?= $model->url ?>">
        <div class="rgb"></div>
        <div class="im_home_posts">
            <h4><?php echo $model->title; ?></h4>
            <?php echo Html::img($thump); ?>
        </div>
    </a>
</li>