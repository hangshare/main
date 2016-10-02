<?php
use yii\helpers\Html;

?>


<footer class="footer-section section no-padding-bottom"
        style="position: relative;z-index: 2; height: 250px; padding: 20px 0; ">
    <div class="footer-logo" style="padding: 0px; text-align: center;">
        <a href="<?= Yii::$app->homeUrl ?>">
            <?php if (Yii::$app->language == 'ar') : ?>
                <img width="135"
                     src="https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo.png"/>
            <?php else : ?>
                <img width="135"
                     src="https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo-en.png"/>
            <?php endif; ?>
        </a>
    </div>
    <div class="footer-social text-center" style="height: 80px;margin-top: 30px;padding: 0;">
        <a target="_blank" style="visibility: visible; animation-delay: 0s; animation-name: fadeIn;"
           href="https://www.facebook.com/Hangshare"
           class="fa fa-facebook-square wow fadeIn animated" data-wow-offset="150" data-wow-delay="0s"></a>
        <a target="_blank" style="visibility: visible; animation-delay: 0.15s; animation-name: fadeIn;"
           href="https://twitter.com/hang_share"
           class="fa fa-twitter wow fadeIn animated" data-wow-offset="150" data-wow-delay=".15s"></a>
        <a target="_blank" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeIn;"
           href="https://www.youtube.com/channel/UC33wy618vyFr3iP3ywcabwg"
           class="fa fa-google-plus wow fadeIn animated" data-wow-offset="150" data-wow-delay=".3s"></a>
        <a target="_blank" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeIn;"
           href="https://www.youtube.com/channel/UC33wy618vyFr3iP3ywcabwg"
           class="fa fa-youtube-play wow fadeIn animated" data-wow-offset="150" data-wow-delay=".6s"></a>
    </div>
    <div class="footer-bottom">
        <span class="text-white"><?= Yii::t('app', 'All Right Reserved.'); ?>.</span>
        <a href="<?= Yii::$app->homeUrl ?>"
           class="footer-bottom-text font-second text-white"><?= Yii::t('app', 'Hangshare') ?></a>
        &copy; <?= date('Y') ?>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
