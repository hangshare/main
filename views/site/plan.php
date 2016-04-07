<?php
$this->title = 'تسجيل حساب جديد | موقع هانج شير';

Use yii\helpers\Html;
?>
<div class="container">
    <div class="white-box">
        <div class="row">
            <div style="margin: 5px;">
                <?php if (Yii::$app->user->isGuest): ?>
                    <div class="col-md-6">
                        <div class="plana">
                            <h3>الحساب المجاني</h3>
                            <span class="price">مجاني</span>
                            <ul class="list-group-item-heading">
                                <li>نسبة ربح متفاوتة بين 0.5 الى  2 دولار حسب نوع الزيارات.</li>
                                <li>إرفاق مقالاتك في النشرة الشهرية للموقع في حال كانت مقالاتك مميزة.</li>
                                <li>إمكانية سحب النقود عن وصولها لـ 100 دولار</li>
                                <li class="nodecoration">&nbsp;</li>
                                <li class="nodecoration">&nbsp;</li>
                                <li class="nodecoration">&nbsp;</li>
                            </ul>
                            <?=
                            Html::a('تسجيل مجاني', ['//register/'], [
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
                        <h3>الحساب الذهبي</h3>
                        <span class="pricegold">
                            <select id="goldtime">
                                <option value="b">$10 دولار / لشهر واحد</option>
                                <option value="c">$25 دولار / لثلاث أشهر</option>
                            </select>
                            عرض لمدة محدودة
                        </span>
                        
                        <ul class="list-group-item-heading">
                            <?php echo $this->render('//plan/gold'); ?>
                        </ul>
                        <div id="planb">
                            <?=
                            Html::a('تسجيل ذهبي', [Yii::$app->user->isGuest ? '//register/' : 'site/plangold'], [
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
                            Html::a('تسجيل ذهبي', [Yii::$app->user->isGuest ? '//register/' : 'site/plangold'], [
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