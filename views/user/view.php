<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = $model->name;
$thump = Yii::$app->imageresize->thump($model->image, 80, 80, 'crop');
$dollar_available = floor($model->userStats->available_amount);
$cent_available = $model->userStats->available_amount - $dollar_available;
$cent_available = number_format(floatval(str_replace(',', '.', str_replace('.', '', $cent_available))));

$dollar_cantake_amount = floor($model->userStats->cantake_amount);
$cent_cantake_amount = $model->userStats->cantake_amount - $dollar_cantake_amount;
$cent_cantake_amount = number_format(floatval(str_replace(',', '.', str_replace('.', '', $cent_cantake_amount))));
?>
<div class="container m-t-25">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="clr-gray-dark">
                    <div style="padding: 0 20px;">
                        <img src="<?= $thump; ?>" class="img-circle"
                             style="margin: 0 auto;display: table;margin-top: 20px;border: 1px solid #aaa; padding: 4px;"/>
                        <h1><?php echo $model->name; ?></h1>
                    </div>
                    <div class="panel-footer">
                        <ul class="list-inline" style="font-size: 15px; text-align: center;">
                            <li>
                                <a href="<?php echo Yii::$app->urlManager->createUrl(['//u/manage']); ?>">
                                    <span><?= Yii::t('app', 'Account Settings') ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Yii::$app->urlManager->createUrl(['//u/transfer']); ?>">
                                    <span><?= Yii::t('app', 'Transfer Methods') ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Yii::$app->urlManager->createUrl(['//u/report']); ?>">
                                    <span><?= Yii::t('app', 'Money Report') ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <a class="dsp--b" href="<?php echo Yii::$app->urlManager->createUrl(['/u/payment']); ?>">
                    <div class="panel panel-default text-center clr-gray-dark">
                        <div class="carda__body pdn--as text-center">
                            <div class="row no-gutter">
                                <div class="col-sm-6 clr-greenflat text-center " data-toggle="tooltip"
                                     title="<?= Yii::t('app', 'total.amount.title', ['dollar' => $dollar_available, '__cent__' => $cent_available]) ?>">
                                    <h4 class="mrg--vt text-center"><?= Yii::t('app', 'total amount') ?></h4>
                                    <span class="text-mega text-center"><span
                                            dir="rtl">$<?= number_format($model->userStats->available_amount, 2); ?></span></span>
                                </div>
                                <div class="col-sm-6 text-center" data-toggle="tooltip"
                                     title="<?= Yii::t('app', 'Balance.retractable.title', ['dollar' => $dollar_cantake_amount, '__cent__' => $cent_cantake_amount]) ?>">
                                    <h4 class="mrg--vt text-center"><?= Yii::t('app', 'Balance retractable') ?></h4>
                                    <span class=" clr-bluewood text-mega text-center
                                "><span
                                            dir="rtl">$<?= number_format($model->userStats->cantake_amount, 2); ?></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer bg-white text-meta text-zeta text-right-xs">
                            <div class="row no-gutter">
                                <div class="col-xs-12">
                                    <div class="col-xs-6 res-nopadding"><span
                                            class="btn btn-primary btn-block"><?= Yii::t('app', 'Request Money') ?></span>
                                    </div>
                                    <div class="font-18 pull-left m-t-8 col-xs-6 text-center">
                                        <span><i class="fa f-progress"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="clr-gray-dark">
                    <div class="heada brd--b">
                        <h4 class="heada__title pdn--an">
                            <i class="fa fa-fw fa-file-text"></i><?= Yii::t('app', 'Info About') ?>
                            : <?= $model->name; ?>
                        </h4>
                    </div>
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                        <div data-ui="slimScroll" class="panel" style="overflow: hidden; width: auto;">
                            <div class="col-md-12" style="padding: 20px;">
                                <ul class="list-unstyled">
                                    <li><b><?= Yii::t('app', 'User.dob') ?> : </b><?php echo $model->dob; ?></li>
                                    <li><b><?= Yii::t('app', 'User.gender') ?>
                                            : </b><?php echo $model->gender == 1 ? Yii::t('app', 'Male') : Yii::t('app', 'Female'); ?>
                                    </li>
                                    <li><b><?= Yii::t('app', 'Post Count') ?>
                                            : </b><?php echo number_format($model->userStats->post_count); ?></li>
                                    <li>
                                        <b><?= Yii::t('app', 'Registered at') ?>
                                            : </b><?php echo date('d-m-Y', strtotime($model->created_at)); ?>
                                    </li>
                                    <li><b><?= Yii::t('app', 'User.country') ?> : </b>
                                        <?php
                                        if (isset($model->country) && $model->country != 0 && isset($model->location))
                                            echo $model->location->name;
                                        else
                                            echo Yii::t('app', 'Not Specified');
                                        ?></li>
                                </ul>
                                <b><?= Yii::t('app', 'User.bio') ?> : </b>
                                <p><?php
                                    if (empty($model->bio)) {
                                        echo Yii::t('app', 'No info');
                                    } else {
                                        echo Html::encode($model->bio);
                                    }
                                    ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h3 class="pull-left nomargin"><?= Yii::t('app', 'My Posts') ?></h3>
                <?= Html::a('<i class="glyphicon glyphicon-plus "></i>' . Yii::t('app', 'Add Post'), ['/explore/post'], ['class' => 'btn btn-primary pull-right']) ?>
                <div class="clearfix"></div>
                <hr class="m-t-8"/>
                <?=
                ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '//explore/_mine',
                    'layout' => "<ul class='list-inline'>{items}</ul>\n{pager}",
                    'options' => [],
                    'itemOptions' => [
                        'tag' => false,
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
    window.$zopim || (function (d, s) {
        var z = $zopim = function (c) {
            z._.push(c)
        }, $ = z.s =
            d.createElement(s), e = d.getElementsByTagName(s)[0];
        z.set = function (o) {
            z.set._.push(o)
        };
        z._ = [];
        z.set._ = [];
        $.async = !0;
        $.setAttribute('charset', 'utf-8');
        $.src = '//v2.zopim.com/?4Djyp0FocsEjBAeTYUm1jZQhm9JE1bH3';
        z.t = +new Date;
        $.type = 'text/javascript';
        e.parentNode.insertBefore($, e)
    })(document, 'script');
</script>
<!--End of Zopim Live Chat Script-->