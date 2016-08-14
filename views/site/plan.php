<?php
$this->title = Yii::t('app', 'New account hangshare');

Use yii\helpers\Html;

?>
<div class="container">
    <div class="white-box">
        <div class="row">
            <div style="margin: 5px;">
                <?php if (Yii::$app->user->isGuest): ?>
                    <div class="col-md-6">
                        <div class="plana">
                            <h3><?= Yii::t('app', 'Free Account') ?></h3>
                            <span class="price"><?= Yii::t('app', 'Free') ?></span>
                            <ul class="list-group-item-heading">
                                <li><?= Yii::t('app', 'freetips1') ?></li>
                                <li><?= Yii::t('app', 'freetips2') ?></li>
                                <li><?= Yii::t('app', 'freetips3') ?></li>
                                <li class="nodecoration">&nbsp;</li>
                                <li class="nodecoration">&nbsp;</li>
                                <li class="nodecoration">&nbsp;</li>
                            </ul>
                            <?=
                            Html::a(Yii::t('app', 'Free Registrations'), ['//register/'], [
                                'class' => 'btn free-btn btn-block',
                                'data' => [
                                    'method' => 'post',
                                    'params' => ['plan' => 'a'],
                                ]
                            ])
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-md-6">
                    <div class="planb">
                        <h3><?= Yii::t('app', 'Gold Account') ?></h3>
                        <span class="pricegold">
                            <select id="goldtime">
                                <option value="b"><?= Yii::t('app', 'one month offer', ['price' => '10']) ?></option>
                                <option value="c"><?= Yii::t('app', 'three month offer', ['price' => '25']) ?></option>
                            </select>
                            <?= Yii::t('app', 'Limited Time Offer') ?>
                        </span>
                        <ul class="list-group-item-heading">
                            <?php echo $this->render('//plan/gold'); ?>
                        </ul>
                        <div id="planb">
                            <?=
                            Html::a(Yii::t('app', 'Register'), [Yii::$app->user->isGuest ? '//register/' : 'site/plangold'], [
                                'class' => 'btn gold-btn btn-block',
                                'data' => [
                                    'method' => 'post',
                                    'params' => ['plan' => 'b'],
                                ]
                            ])
                            ?>
                        </div>
                        <div id="planc" style="display: none;">
                            <?=
                            Html::a(Yii::t('app', 'Register'), [Yii::$app->user->isGuest ? '//register/' : 'site/plangold'], [
                                'class' => 'btn gold-btn btn-block',
                                'data' => [
                                    'method' => 'post',
                                    'params' => ['plan' => 'c'],
                                ]
                            ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>