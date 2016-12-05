<?php
$this->title = Yii::t('app', 'New account hangshare');
$this->description = Yii::t('app', 'meta.plan.desc');

Use yii\helpers\Html;

?>

<?php if (Yii::$app->user->isGuest): ?>
    <section id="pricing-section" class="pricing-section section m-t-25">
        <div class="container">
            <div class="section-title">
                <h1 class="section-title-heading text-center"><?= Yii::t('app', 'Register') ?>
                    <span><?= Yii::t('app', 'Now') ?></span></h1>
            </div>
            <div class="row padding-top-20">
                <div
                    class="col-sm-4 col-sm-offset-2 margin-bottom-xs-40">
                    <div class="pricing-table">
                        <div class="pricing-header font-second golden">
                            <h3 class="text-center"><?= Yii::t('app', 'Gold Account') ?></h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="value">10</span>
                                <span class="duration"><?= Yii::t('app', 'mo') ?></span>
                            </div>
                        </div>
                        <!--/ .pricing-header -->
                        <div class="pricing-body">
                            <ul class="pricing-features">
                                <li><?= Yii::t('app', 'goldtips1') ?></li>
                                <li><?= Yii::t('app', 'goldtips2') ?></li>
                                <li><?= Yii::t('app', 'goldtips3') ?></li>
                                <li><?= Yii::t('app', 'goldtips4') ?></li>
                                <li><?= Yii::t('app', 'goldtips5') ?></li>
                                <li><?= Yii::t('app', 'goldtips6') ?></li>
                            </ul>
                        </div>
                        <div class="pricing-footer">
                            <?=
                            Html::a(Yii::t('app', 'Go Premium'), [Yii::$app->user->isGuest ? '//register/' : 'site/plangold'], [
                                'class' => 'btn btn-default',
                                'style' => 'background-color: #ffd700;border: 0 none;border-radius: 0;color: #fff;text-transform: uppercase;',
                                'rel' => 'nofollow',
                                'data' => [
                                    'method' => 'post',
                                    'params' => ['plan' => 'b'],
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="popular col-sm-4 margin-bottom-xs-40">
                    <div class="pricing-table">
                        <div class="pricing-header font-second">
                            <h3 class="text-center"><?= Yii::t('app', 'Popular') ?></h3>
                            <div class="price">
                                                <span
                                                    style="line-height: 50px;"><?= Yii::t('app', 'Free Account') ?></span>
                            </div>
                        </div>
                        <div class="pricing-body">
                            <ul class="pricing-features">
                                <li><?= Yii::t('app', 'freetips1') ?></li>
                                <li><?= Yii::t('app', 'freetips2') ?></li>
                                <li class="nodecoration"> -</li>
                                <li class="nodecoration"> -</li>
                                <li class="nodecoration"> -</li>
                                <li><?= Yii::t('app', 'freetips3') ?></li>
                            </ul>
                        </div>
                        <div class="pricing-footer">
                            <?= Html::a(Yii::t('app', 'Free Registrations'), ['//register/'], [
                                'class' => 'btn btn-default btn-block',
                                'style' => 'color:#fff;background-color:#757a86 ;border: 0 none;border-radius: 0;text-transform: uppercase;',
                                'data' => [
                                    'method' => 'post',
                                    'params' => ['plan' => 'a'],
                                ]
                            ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>

    <section id="pricing-section" class="pricing-section section section m-t-25">
        <div class="container">
            <div class="section-title">
                <h1 class="section-title-heading text-center"><?= Yii::t('app', 'Register') ?>
                    <span><?= Yii::t('app', 'Now') ?></span></h1>
            </div>
            <div class="row padding-top-20">
                <div
                    class="col-sm-4 col-sm-offset-4 margin-bottom-xs-40">
                    <div class="pricing-table">
                        <div class="pricing-header font-second golden">
                            <h3 class="text-center"><?= Yii::t('app', 'Gold Account') ?></h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="value">10</span>
                                <span class="duration"><?= Yii::t('app', 'mo') ?></span>
                            </div>
                        </div>
                        <!--/ .pricing-header -->
                        <div class="pricing-body">
                            <ul class="pricing-features">
                                <li><?= Yii::t('app', 'goldtips1') ?></li>
                                <li><?= Yii::t('app', 'goldtips2') ?></li>
                                <li><?= Yii::t('app', 'goldtips3') ?></li>
                                <li><?= Yii::t('app', 'goldtips4') ?></li>
                                <li><?= Yii::t('app', 'goldtips5') ?></li>
                                <li><?= Yii::t('app', 'goldtips6') ?></li>
                            </ul>
                        </div>
                        <div class="pricing-footer">
                            <?=
                            Html::a(Yii::t('app', 'Go Premium'), [Yii::$app->user->isGuest ? '//register/' : 'site/plangold'], [
                                'class' => 'btn btn-default',
                                'style' => 'background-color: #ffd700;border: 0 none;border-radius: 0;color: #fff;text-transform: uppercase;',
                                'rel' => 'nofollow',
                                'data' => [
                                    'method' => 'post',
                                    'params' => ['plan' => 'b'],
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>