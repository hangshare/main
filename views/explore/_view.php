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
$userthump = Yii::$app->imageresize->thump($model->user->image, 25, 25, 'crop');
?>
<li>
    <div class="ex-all" cover="<?= $model->cover; ?>">
        <a href="<?= $model->url ?>">
            <?php echo Html::img($thump, ['class' => 'img-responsive zoom-tilt']); ?>
        </a>
        <div class="shareblogsimi">
            <ul class="list-inline">
                <li><a class="btn btn-primary js-share js-share-fasebook" post-url="<?= $model->url ?>"><i
                            style="margin: 3px;" class="fa fa-fw fa-facebook"></i>شارك</a></li>
                <li><a class="btn js-share js-share-twitter" post-url="<?= $model->url ?>"
                       style="color: #fff; background-color: #3E4347;"><i style="margin: 3px;"
                                                                          class="fa fa-twitter"></i>غرد</a></li>
                <li><a class="btn js-share js-share-gpuls" post-url="<?= $model->url ?>"
                       style="color: #fff; background-color: #e51717;"><i style="margin: 3px;"
                                                                          class="fa fa-google-plus"></i> شارك</a></li>
            </ul>
        </div>
    </div>
    <a href="<?= $model->url ?>">
        <div class="col-md-12 titlein">
            <h4 style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;"><?php echo $model->title; ?></h4>
            <?php if (!isset($home)): ?>
                <p><?php
                    foreach ($model->postBodies as $data) {
                        echo Yii::$app->helper->limit_text($data->body, 300);
                    }
                    ?></p>
            <?php endif; ?>
            <img src = "<?= $userthump; ?>" />
            <span><?php echo $model->user->name;
            ?></span>
        </div>
    </a>
</li>