<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'المستخدمين');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <?=
    ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
        'layout' => "<ul class='list-unstyled'>{items}\n</ul>{pager}",
        'options' => [],
        'itemOptions' => [
            'tag' => false,
        ]
    ]);
    ?>
</div>
