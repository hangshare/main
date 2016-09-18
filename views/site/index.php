<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Home Title');
$this->description = Yii::t('app', 'Homepage.Description');
?>


<section id="quick-signup-post" <?php if (Yii::$app->helper->isMobile()): ?> style="background-image: url('https://s3-eu-west-1.amazonaws.com/hangshare-media/aff-intro.jpg');" <?php endif; ?>>
    <?php if (!Yii::$app->helper->isMobile()): ?>
        <div id="banner-video">
            <video width="100%" muted="" loop="" autoplay=""
                   poster="https://s3-eu-west-1.amazonaws.com/hangshare-media/aff-intro.jpg">
                <source type="video/mp4"
                        src="https://cdn-5.lifehack.org/wp-content/themes/lifehack-theme/videos/contribute_banner.mp4"></source>
                <source type="video/webm"
                        src="https://cdn-5.lifehack.org/wp-content/themes/lifehack-theme/videos/contribute_banner.webm"></source>
            </video>
        </div>
    <?php endif ?>
    <div class="overlay">
        <h1 class="header-index" style="margin-top: 70px;">
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
        <?= Html::a(Yii::t('app', 'Signup Now'), Yii::$app->urlManager->createUrl(['plan']), ['class' => 'btn btn-primary btn-lg']); ?>
    </div>
</section>
<section id="numbers">
    <div class="container">
        <div class="counter-row row text-center wow fadeInUp animated" style="visibility: visible;">
            <?php $dif = (time() - 1473885430) / 60; ?>
            <div class="col-md-4 col-sm-6 fact-container">
                <div class="fact">
                    <span class="counter highlight"><?= number_format(($dif * 1) + 25000) ?></span>
                    <h4><?= Yii::t('app', 'Number of Users') ?></h4>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 fact-container">
                <div class="fact">
                    <span class="counter highlight"><?= number_format(($dif * 2) + 55000) ?></span>
                    <h4><?= Yii::t('app', 'Blog Posts') ?></h4>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 fact-container">
                <div class="fact">
                    <span class="counter highlight">$<?= number_format(($dif * 0.5) + 9300) ?></span>
                    <h4><?= Yii::t('app', 'Total Earning') ?></h4>
                </div>
            </div>
        </div>
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
                    <?php if (Yii::$app->user->isGuest) : ?>
                        <div class="white-box" style="margin-top: 40px">
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
                    <?php endif; ?>
                    <div class="m-t-25">
                        <h3 class="underlined"><?= Yii::t('app', 'Most Visited') ?></h3>
                        <ul class="list-unstyled">
                            <?php foreach ($mostviewd as $postData) : ?>
                                <?php echo $this->render('//explore/_hot', ['model' => $postData]); ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
    window.$zopim||(function(d,s){var z=$zopim=function(c){
        z._.push(c)},$=z.s=
        d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
    _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
        $.src='//v2.zopim.com/?4Djyp0FocsEjBAeTYUm1jZQhm9JE1bH3';z.t=+new Date;$.
            type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->