<?php if (!Yii::$app->helper->isMobile()) : ?>
    <style>
        #w3, #w3, .navbar-inverse {
            background: none;
            position: relative;
        }

        .planholder {
            background: none;
        }

        .nav > li:hover {
            background: rgba(248, 248, 248, .3);
        }

        .navbar-nav > li {
            border-radius: 25px;
        }
    </style>
<?php endif; ?>
<?php

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'هانج - Share - شير مقالات واحصل على المال');
$this->description = Yii::t('app', 'احصل على المال مقابل كل مشاهدة على المقالات التي تقوم بنشرها من خلال الباي بال ومشاركة مقالاتك عبر الفيسبوك ومواقع التواصل الأخرى.');
?>
<section id="quick-signup-post">
    <div class="container">
        <div class="spacer"></div>
        <h1 class="header-index">
            انشر مقالاتك مجانا لدينا واحصل على المال
            <aside>
                اشترك بالحساب الذهبي للحصول على الدعم وزيادة في الربح
            </aside>
        </h1>
        <div class="col-md-4 col-md-offset-4 m-t-25 m-b-20">
            <img width="100" class="pull-left" src="http://hangshare.media.s3.amazonaws.com/paypal_verified.png"/>
            <h3 class="header-index" style="line-height: 31px;margin-right: 107px;margin-top: 4px;">موقع معتمد لدى
                PayPal</h3>
        </div>
        <div class="clearfix"></div>
        <h4 style="color: #fff; text-align: center; margin-bottom: 60px;"> انشر مواضيع > شاركها على
            مواقع التواصل > احصل على المال مقابل كل مشاهدة</h4>
    </div>
</section>
<section>
    <div class="home-header">
        <h3>مقالات متميزة</h3>
    </div>
    <div class="homepost_contaner">
        <div class="postsho">
            <?=
            ListView::widget([
                'dataProvider' => $featured,
                'itemView' => '_post',
                'layout' => "{items}",
                'options' => [
                    'tag' => 'ul',
                    'class' => 'homeposts list-unstyled'
                ],
                'itemOptions' => [
                    'tag' => false,
                ],
            ]);
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="container">
            <div class="col-md-9 nopadding">
                <div class="col-md-12 m-t-25 m-b-20">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- Responsive - Home Page Upper -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-6288640194310142"
                         data-ad-slot="7108775712"
                         data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
                <h3 class="m-t-25">مقالات جديدة</h3>
                <hr class="nomargin">
                <?php
                echo ListView::widget([
                    'dataProvider' => $newpost,
                    'itemView' => '//explore/_view',
                    'layout' => "<ul class='list-inline postindex homepost inifi'>{items}\n</ul>",
                    'itemOptions' => [
                        'tag' => false
                    ],
                ]);
                ?>
            </div>
            <?php if (!Yii::$app->helper->isMobile()) : ?>
                <div class="col-md-3" style="position: relative; z-index: 1;">
                    <div class="row m-t-25">
                        <div id="fb-root"></div>
                        <script>(function (d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (d.getElementById(id))
                                    return;
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1390171834574276&version=v2.0";
                                fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                        <div class="fb-like-box m-t-20" data-href="https://www.facebook.com/Hangshare"
                             data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false"
                             data-show-border="true"></div>
                    </div>
                    <div class="row m-t-25">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- Wide Skyscraper - Home Page Upper -->
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:160px;height:600px"
                             data-ad-client="ca-pub-6288640194310142"
                             data-ad-slot="8585508919"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                    <?php if (Yii::$app->user->isGuest) : ?>
                        <div class="row">
                            <div class="white text-center ressocal">
                                <div class="col-md-12">
                                    <h3>الحساب الذهبي</h3>

                                    <p>عرض لمدة محدودة</p>
                                    <select id="goldtime">
                                        <option value="b">$10 دولار / لشهر واحد</option>
                                        <option value="c">$25 دولار / لثلاث أشهر</option>
                                    </select>
                                    <ul class="list-unstyled">
                                        <?php echo $this->render('//plan/gold'); ?>
                                    </ul>
                                    <div id="planb">
                                        <?=
                                        Html::a('تسجيل ذهبي', [Yii::$app->user->isGuest ? '//register/' : '//site/plangold'], [
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
                                        Html::a('تسجيل ذهبي', [Yii::$app->user->isGuest ? '//register/' : '//site/plangold'], [
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
                    <?php endif; ?>
                    <div class="m-t-25">
                        <h3>الأكثر مشاهدة</h3>
                        <hr>
                        <ul class="list-unstyled list-res">
                            <?php foreach ($mostviewd as $postData) : ?>
                                <?php echo $this->render('//explore/_most', ['data' => $postData]); ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="row m-t-25">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- MPU - Home Page Bottom -->
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:300px;height:250px"
                             data-ad-client="ca-pub-6288640194310142"
                             data-ad-slot="1062242110"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                    <div style="position: relative">
                        <div id="fixad" class="row m-t-25" rel="5100">
                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <!-- faq ad -->
                            <ins class="adsbygoogle"
                                 style="display:inline-block;width:336px;height:280px"
                                 data-ad-client="ca-pub-6288640194310142"
                                 data-ad-slot="4022492110"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>