<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Home Title');
$this->description = Yii::t('app', 'Homepage.Description');
$dif = (time() - 1473885430) / 60 / 3;
$articlesurl = Yii::t('app', 'articles-url');
?>
<div id="page" class="animsition equal">
    <section id="home-section" class="home-section full-screen">
        <div class="hs-content">
            <div class="overlay">
                <div class="wow animated fadeInRightBig" data-wow-duration="1s">
                    <h1 class="header-index"><?= Yii::t('app', 'welcome 1') ?></h1>
                </div>
                <div class="col-md-4 col-md-offset-4 m-t-25 m-b-20 wow animated fadeInLeftBig" data-wow-duration="1s"
                     data-wow-delay=".5s">
                    <img width="100" class="pull-left"
                         src="https://s3-eu-west-1.amazonaws.com/hangshare-media/paypal_verified.png"/>
                    <h3 class="header-index" style="line-height: 31px;margin-right: 107px;margin-top: 4px;">
                        <?= Yii::t('app', 'Certified By Paypal') ?>
                    </h3>
                </div>
                <div class="clearfix"></div>
                <?= Html::a(Yii::t('app', 'Signup Now'), Yii::$app->urlManager->createUrl(['plan']), ['class' => 'btn btn-primary btn-lg']); ?>
            </div>
            <?php if (!Yii::$app->helper->isMobile()): ?>
                <div class="fullscreen-bg">
                    <video class="fullscreen-bg__video" width="100%" muted="" loop="" autoplay=""
                           poster="https://s3-eu-west-1.amazonaws.com/hangshare-media/aff-intro.jpg">
                        <source type="video/mp4"
                                src="https://d193ic1ku09dlu.cloudfront.net/contribute_banner.mp4">
                        <source type="video/webm"
                                src="https://d193ic1ku09dlu.cloudfront.net/contribute_banner.webm">
                    </video>
                </div>
            <?php endif ?>
        </div>
    </section>
    <hr>
    <div id="page-2">
        <section id="process-section" class="process-section section">
            <div class="container">
                <div class="section-title">
                    <div class="section-title-more animated wow fadeInDown" data-wow-duration="1s">
                        <?= Yii::t('app', 'how is our work'); ?>
                    </div>
                    <h3 class="section-title-heading animated wow fadeInDown" data-wow-duration="1s"
                    ><?= Yii::t('app', 'process') ?>
                        <span> <?= Yii::t('app', 'flow') ?></span></h3>
                    <div class="row margin-top-40">
                        <div class="col-md-4 text-center animated wow flipInX" data-wow-duration="1s"
                             data-wow-delay=".5s">
                            <img width="120" src="/svg/edit.svg" alt="FreeLance writing"/>
                            <h4 class="text-center margin-top-30"
                                style="font-weight: bold;"><?= Yii::t('app', 'Write a post') ?></h4>
                            <p class="text-center"><?= Yii::t('app', 'Write a post info') ?></p>
                        </div>
                        <div class="col-md-4 text-center animated wow flipInX" data-wow-duration="1s"
                             data-wow-delay="1s">
                            <img width="120" src="/svg/share.svg" alt="FreeLance writing"/>
                            <h4 class="text-center margin-top-30"
                                style="font-weight: bold;"><?= Yii::t('app', 'Share your post') ?></h4>
                            <p class="text-center"><?= Yii::t('app', 'Share your post info') ?></p>
                        </div>
                        <div class="col-md-4 text-center animated wow flipInX" data-wow-duration="1s"
                             data-wow-delay="1.5s">
                            <img width="120" src="/svg/get-money.svg" alt="Gain Profit, Get Money"/>
                            <h4 class="text-center margin-top-30"
                                style="font-weight: bold;"><?= Yii::t('app', 'Gain Profit') ?></h4>
                            <p class="text-center"><?= Yii::t('app', 'Gain Profit info') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <hr>
        <div id="facts-section" class="facts-section text-center font-second small-section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 fact-item col-xs-6 margin-bottom-xs-40 animated wow fadeInDown"
                         data-wow-duration="5s">
                        <div class="fact-number font-second">
                            <p class="counter text-center" data-content="<?= $dif + 20000 ?>">0</p>
                        </div>
                        <div class="fact-text">
                            <p class="text-center"><?= Yii::t('app', 'Number of Users') ?></p>
                        </div>
                    </div>
                    <div class="fact-item col-sm-4 col-xs-6 margin-bottom-xs-40 animated wow fadeInUp"
                         data-wow-duration="2s" data-wow-delay="1s">
                        <div class="fact-number font-second">
                            <p class="counter text-center" data-content="<?= ($dif * 2) + 55000 ?>">0</p>
                        </div>
                        <div class="fact-text">
                            <p class="text-center"><?= Yii::t('app', 'Blog Posts') ?></p>
                        </div>
                    </div>
                    <div class="fact-item col-sm-4 col-xs-6 animated wow fadeInRight" data-wow-duration="2s"
                         data-wow-delay="2s">
                        <div class="fact-number font-second">
                            <p class="text-center"><span style="font-size: 55px;">$</span><span class="counter"
                                                                                                data-content="<?= $dif * 0.5 + 9300 ?>">0</span>
                            </p>
                        </div>
                        <div class="fact-text">
                            <p class="text-center"><?= Yii::t('app', 'Total Earning') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <section id="about-section" class="about-section section">
            <div class="container">
                <div class="section-title">
                    <h2 class="section-title-heading animated fadeInDown" data-wow-duration="1s" data-wow-delay="2s"
                        style="margin: 0px auto; display: table; margin-bottom: 30px;">
                        <?= Yii::t('app', 'How calculating') ?>
                        <span><?= Yii::t('app', 'Profits') ?></span>
                    </h2>
                    <div class="col-sm-6">
                        <div class="animated wow fadeInRight" data-wow-duration="2s">
                            <h4 style="font-weight: bold;"><?= Yii::t('app', 'Post Rating') ?></h4>
                            <p><?= Yii::t('app', 'post rating info') ?></p>
                        </div>
                        <div class="animated wow fadeInRight" data-wow-duration="3s">
                            <h4 style="font-weight: bold;"><?= Yii::t('app', 'Traffic Source') ?></h4>
                            <p><?= Yii::t('app', 'Traffic Source info') ?></p>
                        </div>
                        <div class="animated wow fadeInRight" data-wow-duration="4s">
                            <h4 style="font-weight: bold;"><?= Yii::t('app', 'Traffic Quality') ?></h4>
                            <p><?= Yii::t('app', 'Traffic Quality info') ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6 animated wow fadeInUpRightScale homepeie" data-wow-delay="1s"
                         data-wow-duration="2s">
                        <div id="pi1"
                            <?php if (Yii::$app->helper->isMobile()): ?>
                                style="width: 100vw; height: 40vh;"
                            <?php else : ?>
                                style="width: 450px; height: 350px;"
                            <?php endif; ?>
                        ></div>
                    </div>
                </div>
            </div>
        </section>
        <hr>
        <section id="client-testimonials" class="client-testimonials section overlay">
            <div class="container">
                <div class="section-title padding-bottom-40">
                    <div class=" section-title-more">
                        <?= Yii::t('app', 'hear them out') ?>
                    </div>
                    <div class="text-center">
                        <h3 class="section-title-heading"><?= Yii::t('app', 'What People Say About Us') ?></h3>
                    </div>
                </div>
            </div>
            <div id="testimonials" class="owl-carousel">
                <?php foreach ($testonimoial as $testonimoial): ?>
                    <div class="item">
                        <div class="container">
                            <p class="text-center">"<?= $testonimoial->bodyText ?>"</p>
                            <div class="testimonial_item">
                                <div class="author_thumb">
                                    <?php $thump = Yii::$app->imageresize->thump($testonimoial->user->image, 80, 80, 'crop'); ?>
                                    <img alt="<?= $testonimoial->user->name ?>" src="<?= $thump ?>"/>
                                </div>
                                <div class="testimonial_nav_info text-center">
                                    <h5 class="text-center"><?= $testonimoial->user->name ?></h5>
                                    <span
                                        style="font-style:italic; font-size:15px;"><?= $testonimoial->userTitle ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <hr>
        <section id="blog-section" class="blog-section section" style="padding-bottom: 90px;">
            <div class="container">
                <div class="section-title">
                    <div class=" section-title-more">
                        <?= Yii::t('app', 'posts from'); ?>
                    </div>
                    <h3 class="section-title-heading"><?= Yii::t('app', 'our users') ?></h3>
                </div>
                <div class="row">
                    <?php
                    echo ListView::widget([
                        'dataProvider' => $newpost,
                        'itemView' => '//explore/_onepage',
                        'layout' => "{items}",
                        'itemOptions' => [
                            'tag' => false
                        ],
                    ]);
                    ?>
                </div>
                <div class="padding-top-60 text-center">
                    <a class="btn btn-default btn-lg"
                       href="<?= Yii::$app->urlManager->createUrl(["//{$articlesurl}"]) ?>"><?= Yii::t('app', 'go to the blog') ?></a>
                </div>
            </div>
        </section>
        <hr>
        <section id="pricing-section" class="pricing-section section"
                 style="background-color: #f5f5f5; padding-bottom: 80px">
            <div class="container">
                <div class="section-title">
                    <h3 class="section-title-heading text-center"><?= Yii::t('app', 'Register') ?>
                        <span><?= Yii::t('app', 'Now') ?></span></h3>
                </div>
                <div class="row padding-top-20">
                    <div class="col-sm-4 col-sm-offset-2 margin-bottom-xs-40 wow fadeInUpLeftScale animated">
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
                    <div class="popular col-sm-4 margin-bottom-xs-40 wow fadeInUpScale animated">
                        <div class="pricing-table">
                            <div class="pricing-header font-second">
                                <h3 class="text-center"><?= Yii::t('app', 'Popular') ?></h3>
                                <div class="price">
                                    <span style="line-height: 50px;"><?= Yii::t('app', 'Free Account') ?></span>
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
        <hr>
    </div>
</div>


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