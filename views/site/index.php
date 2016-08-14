<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Home Title');
$this->description = Yii::t('app', 'Homepage.Description');
?>
<section id="quick-signup-post">
    <div class="container">
        <div class="spacer"></div>
        <h1 class="header-index">
            <?= Yii::t('app', 'welcome 1') ?>
            <aside>
                <?= Yii::t('app', 'welcome 2') ?>
            </aside>
        </h1>
        <div class="col-md-4 col-md-offset-4 m-t-25 m-b-20">
            <img width="100" class="pull-left"
                 src="https://s3-eu-west-1.amazonaws.com/hangshare-media/paypal_verified.png"/>

            <h3 class="header-index" style="line-height: 31px;margin-right: 107px;margin-top: 4px;">
                <?= Yii::t('app', 'Certified By Paypal') ?>
            </h3>
        </div>
        <div class="clearfix"></div>
        <h4 style="color: #fff; text-align: center; margin-bottom: 75px;">
            <?= Yii::t('app', 'Create your post > Share on social media > Get money for every view') ?>
        </h4>
    </div>
</section>
<section>
    <div class="home-header">
        <h3><?= Yii::t('app', 'Featured Posts') ?></h3>
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
            <div class="col-md-8">
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
                <h3 class="underlined"><?= Yii::t('app', 'New Post') ?></h3>
                <?php
                echo ListView::widget([
                    'dataProvider' => $newpost,
                    'itemView' => '//explore/_home',
                    'layout' => "<ul class='list-inline  homepost inifi'>{items}\n</ul>",
                    'itemOptions' => [
                        'tag' => false
                    ],
                ]);
                ?>
            </div>
            <?php if (!Yii::$app->helper->isMobile()) : ?>
                <div class="col-md-4">
                    <div class="m-t-25 text-center">
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
                                    <h3><?= Yii::t('app', 'Gold Account') ?></h3>

                                    <p><?= Yii::t('app', 'Limited Time Offer') ?></p>
                                    <select id="goldtime">
                                        <option
                                            value="b"><?= Yii::t('app', 'one month offer', ['price' => '10']) ?></option>
                                        <option
                                            value="c"><?= Yii::t('app', 'three month offer', ['price' => '25']) ?></option>
                                    </select>
                                    <ul class="list-unstyled">
                                        <?php echo $this->render('//plan/gold'); ?>
                                    </ul>
                                    <div id="planb">
                                        <?=
                                        Html::a(Yii::t('app', 'Register'), [Yii::$app->user->isGuest ? '//register/' : '//site/plangold'], [
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
                                        Html::a(Yii::t('app', 'Register'), [Yii::$app->user->isGuest ? '//register/' : '//site/plangold'], [
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
                        <h3 class="underlined"><?= Yii::t('app', 'Most Visited') ?></h3>
                        <ul class="list-unstyled">
                            <?php foreach ($mostviewd as $postData) : ?>
                                <?php echo $this->render('//explore/_hot', ['model' => $postData]); ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="rela">
                        <div class="row m-t-25 text-center">
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