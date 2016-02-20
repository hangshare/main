<?php

use yii\helpers\Html;

$thump = Yii::$app->imageresize->thump($model->cover, 300, 250, 'crop');
?>
<li>
    <a href="<?php echo Yii::$app->urlManager->createUrl(['//explore/view', 'id' => $model->id, 'title' => $model->title]); ?>">
        <div class="rgb"></div>
        <div class="im_home_posts">
            <h4><?php echo $model->title; ?></h4>
            <?php echo Html::img($thump); ?>
        </div>
    </a>
</li>