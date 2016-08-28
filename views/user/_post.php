<?php

use yii\helpers\Html;


if (isset($model->post)) {
    $model = $model->post;
}

$thump = Yii::$app->imageresize->thump($model->cover, 400, 290, 'crop');
?>
<li>
    <div class="ex-all">
        <a href="<?= $model->url ?>">
            <?php echo Html::img($thump, ['class' => 'img-responsive zoom-tilt']); ?>
        </a>

        <div class="shareblogsimi">
            <ul class="list-inline">
                <li><a class="btn btn-primary js-share js-share-fasebook" post-url="<?= $model->url ?>"><i
                            style="margin: 3px;" class="fa fa-fw fa-facebook"></i>شارك</a></li>
                <li><a class="btn js-share js-share-twitter" post-url="<?= $model->url ?>"
                       style="color: #fff; background-color: #4099ff;"><i style="margin: 3px;"
                                                                          class="fa fa-twitter"></i>
                        <?= Yii::t('app', 'Tweet') ?>
                    </a></li>
                <li><a class="btn js-share js-share-gpuls" post-url="<?= $model->url ?>"
                       style="color: #fff; background-color: #e51717;"><i style="margin: 3px;"
                                                                          class="fa fa-google-plus"></i>
                        <?= Yii::t('app', 'Share') ?>
                    </a></li>
            </ul>
        </div>
    </div>
    <a href="<?= $model->url ?>">
        <div class="col-md-12 titlein">
            <h4 style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;"><?php echo $model->title; ?></h4>
        </div>
    </a>
</li>