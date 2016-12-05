<?php
use yii\bootstrap\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Money Report');
?>
<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>


    <table class="table">
        <thead>
        <tr>
            <th>#<?= Yii::t('app', 'ID') ?></th>
            <th><?= Yii::t('app', 'Receipt'); ?></th>
            <th><?= Yii::t('app', 'Amount'); ?></th>
            <th><?= Yii::t('app', 'Status'); ?></th>
            <th><?= Yii::t('app', 'Date'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?=
        ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_transactions',
            'layout' => "<ul class='list-unstyled'>{items}\n</ul>{pager}",
            'emptyText' => '',
            'options' => [],
            'itemOptions' => [
                'tag' => false,
            ]
        ]);
        ?>
        </tbody>
    </table>
</div>