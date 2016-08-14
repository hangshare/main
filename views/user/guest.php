<?php

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = $model->name;
$thump = Yii::$app->imageresize->thump($model->image, 80, 80, 'crop');
?>
<div class="container m-t-25">
    <div class="row">
        <div class="col-md-8">
            <div class="clr-gray-dark">

                <div style="padding: 0 20px;">
                    <img src="<?= $thump; ?>" class="img-circle"
                         style="margin: 0 auto;display: table;margin-top: 20px;border: 1px solid #aaa; padding: 4px;"/>

                    <h1><?php echo $model->name; ?></h1>
                </div>

                <p style="text-align: justify; padding: 20px;"><?php echo Html::encode($model->bio); ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="clr-gray-dark">
                <div class="heada brd--b">
                    <h4 class="heada__title pdn--an">
                        <i class="fa fa-fw fa-file-text"></i><?= Yii::t('app', 'Info About') ?>: <?= $model->name; ?>
                    </h4>
                </div>
                <div style="position: relative; overflow: hidden; width: auto;">
                    <div style="overflow: hidden; width: auto;">
                        <div class="col-md-12" style="padding: 20px;">

                            <ul class="list-unstyled">
                                <?php if ($model->dob != '0000-00-00'): ?>
                                    <li><b><?= Yii::t('app', 'User.dob') ?> : </b><?php echo $model->dob; ?></li>
                                <?php endif; ?>
                                <li><b><?= Yii::t('app', 'User.gender') ?>
                                        : </b><?php echo $model->gender == 1 ? Yii::t('app', 'Male') : Yii::t('app', 'Female'); ?>
                                </li>
                                <?php if (isset($model->userStats)) : ?>
                                    <li><b><?= Yii::t('app', 'Post Count') ?>
                                            : </b><?php echo number_format($model->userStats->post_count); ?></li>
                                <?php endif; ?>
                                <li><b><?= Yii::t('app', 'User.country') ?> : </b>
                                    <?php
                                    if (isset($model->country) && $model->country != 0 && isset($model->location))
                                        echo $model->location->name;
                                    else
                                        echo Yii::t('app', 'Not Specified');
                                    ?></li>

                                <li>
                                    <b><?= Yii::t('app', 'Registered at') ?></b><?php echo date('d-m-Y', strtotime($model->created_at)); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <h3 class="pull-left nomargin"><?= Yii::t('app','Posts Writen by') ?> : <?= $model->name; ?></h3>

            <div class="clearfix"></div>
            <hr class="m-t-8"/>
            <?=
            ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_post',
                'layout' => "<ul class='list-inline postindex inpage userposts'>{items}</ul>\n{pager}",
                'options' => [
                ],
                'itemOptions' => [
                    'tag' => false,
                ],
            ]);
            ?>
        </div>
    </div>
</div>