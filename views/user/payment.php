<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Money Request');
$cantak = 100;
if ($model->plan == '1') {
    $cantak = 50;
}
?>
<div class="container">
    <div class="white-box">
        <h1><?= $this->title ?></h1>
        <?php if ($chkTransaction) : ?>
            <?= Yii::t('app', 'You have requested the money please wait') ?>
        <?php else : ?>
            <?php if ($model->userStats->cantake_amount > 0) : ?>

                <p class="red"><?= Yii::t('app', 'Payment Instruction 4', ['amount' => number_format($model->userStats->cantake_amount, 2)]) ?></p>
                <p><?= Yii::t('app', 'Payment Instruction 5') ?></p>
                <h3><?= Yii::t('app', 'Please check your account information') ?></h3>

                <?php
                if ($model->transfer_type == 0) {
                    echo Html::a(Yii::t('app', 'Update your payment method'), ['//u/transfer'], ['class' => 'btn btn-primary']);
                } else {
                    $obj = json_decode($model->currentMethod->info);
                    ?>
                    <ul class="list-unstyled well">
                        <?php if ($model->transfer_type == 1): ?>
                            <li><?= Yii::t('app', 'PayPal Account') ?></li>
                            <li>
                                <ul>
                                    <li><?= $obj->email ?></li>
                                </ul>
                            </li>
                        <?php endif ?>
                        <?php if ($model->transfer_type == 2): ?>
                            <li><?= Yii::t('app', 'Bank Account') ?></li>
                            <li>
                                <ul>
                                    <li><b><?= Yii::t('app', 'Name') ?> : </b><?= $obj->name ?></li>
                                    <li><b><?= Yii::t('app', 'Account Number') ?> : </b> <?= $obj->account ?></li>
                                    <li><b><?= Yii::t('app', 'Bank Name') ?> : </b><?= $obj->bank_name ?></li>
                                    <li><b><?= Yii::t('app', 'Branch Name') ?> : </b><?= $obj->bank_branch ?></li>
                                    <li><b>IBAN : </b> <?= $obj->IBAN ?></li>
                                </ul>
                            </li>
                        <?php endif ?>
                        <?php if ($model->transfer_type == 3): ?>
                            <li><b><?= Yii::t('app', 'Transfer Type Cashier') ?> ?></b></li>
                            <li>
                                <ul class="list-unstyled">
                                    <li><b><?= Yii::t('app', 'Name') ?> : </b><?= $obj->name ?></li>
                                    <li><b><?= Yii::t('app', 'User.phone') ?> : </b> <?= $obj->phone ?></li>
                                    <li><b><?= Yii::t('app', 'Location') ?> : </b> <?= $obj->address ?></li>
                                    <li><b><?= Yii::t('app', 'Cashire.cashier_name') ?> : </b><?= $obj->cashier_name ?>
                                    </li>
                                </ul>
                            </li>
                        <?php endif ?>
                    </ul>
                <?php } ?>
                <?= Html::a(Yii::t('app', 'Request your money'), ['request'], ['class' => 'btn btn-primary']); ?>
            <?php else : ?>
                <p><?= Yii::t('app', 'Payment Instruction 3', ['amount' => number_format($model->userStats->available_amount, 2)]) ?></p>
                <p><?= Yii::t('app', 'Payment Instruction 2', ['cantak' => $cantak]) ?></p>
                <p><?= Yii::t('app', 'Payment Instruction 1') ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>