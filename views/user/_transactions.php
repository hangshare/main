<?php
use yii\bootstrap\Html; ?>

<tr>
    <th scope="row"><?= $model->id ?></th>
    <td><?= Html::a(Html::img($model->image, ['width' => 200]), $model->image, ['target' => '_blank']) ?></td>
    <td><?= $model->amount ?></td>
    <td><?= $model->status ? Yii::t('app', 'Received') : Yii::t('app', 'Processing') ?></td>
    <td style="direction: ltr;"><?= date('d/M/Y  h:i',strtotime($model->created_at)) ?></td>
</tr>
