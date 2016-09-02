<?php
use yii\helpers\Html;

?>
<?php if (!Yii::$app->helper->isMobile()): ?>
    <footer style="position: relative">
        <div class="container">
            <ul class="list-inline pull-left" style="width: 100%;padding-right: 70px;">
                <li><?= Html::a(Yii::t('app', 'Home page'), Yii::$app->homeUrl) ?></li>
                <li class="divider"></li>
                <li><?= Html::a(Yii::t('app', 'About Us'), ['//' . Yii::t('app', 'About-Us-url')]) ?></li>
                <li class="divider"></li>
                <li><?= Html::a(Yii::t('app', 'Faqs'), ['//' . Yii::t('app', 'Faqs-url')]) ?></li>
                <li class="divider"></li>
                <li><?= Html::a(Yii::t('app', 'Contact Us'), ['//' . Yii::t('app', 'ContactUs-url')]) ?></li>
                <li class="divider"></li>
                <li><?= Html::a(Yii::t('app', 'Privacy'), ['//' . Yii::t('app', 'privacy-url')]) ?></li>
                <li class="divider"></li>
                <li>
                    <?php if (Yii::$app->language == 'en') : ?>
                        <a class="changeLang" href="https://www.hangshare.com/">عربي</a>
                    <?php else : ?>
                        <a class="changeLang" href="https://www.hangshare.com/en/">English</a>
                    <?php endif; ?>
                </li>
                <li class="pull-right"><a class="btn btn-primary" href="https://www.facebook.com/Hangshare"
                                          target="_blank"><i
                            style="margin: 3px;" class="fa fa-fw fa-facebook"></i></a></li>
                <li class="pull-right"><a class="btn" href="https://www.twitter.com/hang_share" target="_blank"
                                          style="color: #fff; background-color: #4099ff;"><i style="margin: 3px;"
                                                                                             class="fa fa-twitter"></i></a>
                </li>
            </ul>
        </div>
    </footer>
<?php endif; ?>
<?php $this->endBody() ?>
</body>
</html>
