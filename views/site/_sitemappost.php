<?php
use yii\helpers\Html;
?>
<li>
    <a href="<?php echo Yii::$app->urlManager->createUrl(['//explore/view', 'id' => $model->id, 'title' => $model->title]); ?>"><?php echo $model->title; ?></a>
</li>
