<?php
use yii\helpers\Html;

$thump = Yii::$app->imageresize->thump($model->user->image, 80, 80, 'crop');
?>
<li style="border-radius: 3px;  margin-bottom: 0;
    margin-top: 20px; background: #fff none repeat scroll 0 0;
    border: 1px solid rgba(0, 0, 0, 0.09);
    border-radius: 3px;
    padding: 20px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);">
    <div style="margin-bottom: 15px;">
        <div style='display: inline-block; vertical-align: top;'>
            <a href="#">
                <?= Html::img($thump, ['class' => '', 'style' => 'border-radius: 100%;display: inline-block;vertical-align: middle;', 'width' => 40]); ?>
            </a>
        </div>
        <div style="display: inline-block; vertical-align: top; font-size: 14px; ">
            <a style="display: block;"><?= Html::encode($model->user->name) ?></a>
            <?= date('M d', strtotime($model->created_at)); ?>
        </div>
    </div>
    <p><?= nl2br(Html::encode($model->comment)) ?></p>
</li>