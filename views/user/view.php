<?php

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = $model->name;
$thump = Yii::$app->imageresize->thump($model->image, 80, 80, 'crop');
?>
<div class="container m-t-25">
    <div class="row">
        <div class="col-md-4">
            <div class="clr-gray-dark">
                <a href="<?php echo Yii::$app->urlManager->createUrl(['user/manage']); ?>">
                    <div style="padding: 0 20px;">
                        <img src="<?= $thump; ?>" class="img-circle"
                             style="margin: 0 auto;display: table;margin-top: 20px;border: 1px solid #aaa; padding: 4px;"/>

                        <h1><?php echo $model->name; ?></h1>
                    </div>
                    <div class="panel-footer">
                        <div style="display: table; margin: 0 auto;">
                            <span>إعدادات الحساب <i class="fa fa-gear pull-left m-t-3"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-8">
            <a class="dsp--b" href="<?php echo Yii::$app->urlManager->createUrl(['/user/payment']); ?>">
                <div class="panel panel-default text-center clr-gray-dark">
                    <div class="carda__body pdn--as text-center">
                        <div class="row no-gutter">
                            <?php
                            $dollar_available = floor($model->userStats->available_amount);
                            $cent_available = $model->userStats->available_amount - $dollar_available;
                            $cent_available = floatval(str_replace(',', '.', str_replace('.', '', $cent_available)));

                            $dollar_cantake_amount = floor($model->userStats->cantake_amount);
                            $cent_cantake_amount = $model->userStats->cantake_amount - $dollar_cantake_amount;
                            $cent_cantake_amount = floatval(str_replace(',', '.', str_replace('.', '', $cent_cantake_amount)));
                            ?>

                            <div class="col-sm-6 clr-greenflat text-center " data-toggle="tooltip"
                                 title="الرصيد الكلي : <?= $dollar_available ?>  دولار و <?= $cent_available ?>  سنت">
                                <h4 class="mrg--vt text-center">
                                    الرصيد الكلي
                                </h4>
                                <span class="text-mega text-center"><span
                                        dir="rtl">$<?= number_format($model->userStats->available_amount, 3); ?></span></span>
                            </div>
                            <div class="col-sm-6 text-center" data-toggle="tooltip"
                                 title="الرصيد  القابل للسحب: <?= $dollar_cantake_amount ?>  دولار و <?= $cent_cantake_amount ?>  سنت"
                            ">
                                <h4 class="mrg--vt text-center">
                                    الرصيد القابل للسحب
                                </h4>
                                <span class="clr-bluewood text-mega text-center"><span
                                        dir="rtl">$<?= number_format($model->userStats->cantake_amount, 3); ?></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer bg-white text-meta text-zeta text-right-xs">
                        <div class="row no-gutter">
                            <div class="col-xs-12">
                                <div class="col-xs-6"><span class="btn btn-primary btn-block">طلب تحول المبلغ</span>
                                </div>
                                <div class="font-18 pull-left m-t-8 col-xs-6 text-center"><span><i
                                            class="fa f-progress"></i></span>
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
                        <i class="fa fa-fw fa-file-text"></i> معلومات عن : <?= $model->name; ?>
                    </h4>
                </div>
                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                    <div data-ui="slimScroll" class="panel" style="overflow: hidden; width: auto;">
                        <div class="col-md-12" style="padding: 20px;">
                            <ul class="list-unstyled">

                                <li><b>تاريخ الميلاد : </b><?php echo $model->dob; ?></li>
                                <li><b> الجنس : </b><?php echo $model->gender == 1 ? 'ذكر' : 'أنثى'; ?></li>
                                <li><b> عدد المشاهدات الكلي
                                        : </b><?php echo number_format($model->userStats->post_total_views); ?></li>
                                <li><b> عدد المقالات : </b><?php echo number_format($model->userStats->post_count); ?>
                                </li>
                                <li><b> تاريخ التسجيل : </b><?php echo date('d-m-Y', strtotime($model->created_at)); ?>
                                </li>
                                <li><b> مكان الإقامة : </b>
                                    <?php
                                    if (isset($model->country) && $model->country != 0 && isset($model->location))
                                        echo $model->location->name_ar;
                                    else
                                        echo 'غير محدد';
                                    ?></li>
                            </ul>
                            <b>نبذة عامة: </b>

                            <p>
                                <?php
                                if (empty($model->bio)) {
                                    echo 'لا يوجد معلومات';
                                } else {
                                    echo Html::encode($model->bio);
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <h3 class="pull-left nomargin">مقالاتي</h3>
            <?= Html::a('<i class="glyphicon glyphicon-plus "></i>' . ' أضف موضوع ', ['/explore/post'], ['class' => 'btn btn-primary pull-right']) ?>
            <div class="clearfix"></div>
            <hr class="m-t-8"/>
            <?=
            ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_post',
                'layout' => "<ul class='list-inline postindex inpage userposts'>{items}</ul>\n{pager}",
                'options' => [
//                    'tag' => 'ul',
//                    'class' => 'list-inline postindex inpage'
                ],
                'itemOptions' => [
                    'tag' => false,
                ],
            ]);
            ?>
        </div>
    </div>
</div>