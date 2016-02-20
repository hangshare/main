<?php
$thump = Yii::$app->imageresize->thump($model->image, 80, 80, 'crop');
?>
<li>
    <a class="user-post down text-center" href="<?= Yii::$app->urlManager->createUrl(['//user/view', 'id' => $model->id]) ?>">
        <div class="col-md-2">
            <img src="<?= $thump; ?>" class="img-circle ursprdown" />
            <span class="user-post-name"><?= $model->name; ?></span>
        </div>
        <div class="col-md-10">
            <div class="row">
                <span class="user-post-bio"><?= Yii::$app->helper->limit_text($model->bio, 100); ?></span>
            </div>
        </div>
    </a>
</li>