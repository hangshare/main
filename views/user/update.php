<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'اعدادات الحساب :') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">
    <div class="col-md-12">
        <h1 class="res-update"><?= Html::encode($this->title) ?></h1>
        <hr>
    </div>
    <div class="row nomargin">
        <div class="col-md-12">
            <?= $this->render('_form', ['model' => $model,]) ?>
        </div>
    </div>
</div>
