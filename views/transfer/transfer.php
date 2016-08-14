<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Money Transfer Methods');
?>

<?php if (isset($new) && $new == 1): ?>
    <ul class="list list-inline">
        <li><?php echo Html::a(Yii::t('app', 'Add Post'), ['//explore/post']); ?></li>
    </ul>
<?php endif; ?>
<h1><?= $this->title ?></h1>
<hr>
<div class="user-form">
    <p><?= Yii::t('app', 'Select transfer method') ?></p>
    <br>
    <ul class="list list-inline">
        <li><a id="paypal" href="javascript:void(0);"
               class="btn <?php echo ($this->params['user']->transfer_type == 1 || $this->params['user']->transfer_type == 0) ? 'btn-warning' : 'btn-default'; ?> showclick">PayPal</a>
        </li>
        <li>
            <a id="bank" href="javascript:void(0);"
               class="btn <?php echo $this->params['user']->transfer_type == 2 ? 'btn-warning' : 'btn-default'; ?> showclick">
                <?= Yii::t('app', 'Bank') ?>
            </a></li>
        <li><a id="moneychanger" href="javascript:void(0);"
               class="btn <?php echo $this->params['user']->transfer_type == 3 ? 'btn-warning' : 'btn-default'; ?> showclick">
                <?= Yii::t('app', 'Cashier') ?>
            </a></li>
    </ul>
    <br>
    <?php
    $paypal = null;
    $bank = null;
    $moneychanger = null;
    $vodafone = null;
    foreach ($model as $data) {
        if ($data->type == 1)
            $paypal = $data;
        if ($data->type == 2)
            $bank = $data;
        if ($data->type == 3)
            $moneychanger = $data;
        if ($data->type == 4)
            $vodafone = $data;
    }
    ?>
    <div
        id="paypal_form" <?php echo ($this->params['user']->transfer_type == 1 || $this->params['user']->transfer_type == 0) ? '' : 'style="display: none;"'; ?>>
        <?= $this->render('//transfer/_formpaypal', ['data' => $paypal]); ?>
    </div>
    <div id="bank_form" <?php echo $this->params['user']->transfer_type != 2 ? 'style="display: none;"' : ''; ?>>
        <?= $this->render('//transfer/_formbank', ['data' => $bank]); ?>
    </div>
    <div
        id="moneychanger_form" <?php echo $this->params['user']->transfer_type != 3 ? 'style="display: none;"' : ''; ?>>
        <?= $this->render('//transfer/_formchanger', ['data' => $moneychanger]); ?>
    </div>
    <div id="vodafone_form" <?php echo $this->params['user']->transfer_type != 4 ? 'style="display: none;"' : ''; ?>>
        <?= $this->render('//transfer/_vodafone', ['data' => $vodafone]); ?>
    </div>
</div>