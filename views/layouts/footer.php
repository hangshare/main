<?php
use yii\helpers\Html;

?>
<?php if (Yii::$app->helper->isMobile()): ?>
    <div class="footads">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Large mobile Banner - Mobile all pages -->
        <ins class="adsbygoogle"
             style="display:inline-block;width:320px;height:80px"
             data-ad-client="ca-pub-6288640194310142"
             data-ad-slot="5787356113"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
<?php endif; ?>
<?php if (!Yii::$app->helper->isMobile()): ?>
    <footer style="position: relative">
        <ul class="list-inline pull-left" style="width: 100%;padding-right: 70px;">
            <li><?= Html::a('الرئيسية', 'http://www.hangshare.com/') ?></li>
            <li class="divider"></li>
            <li><?= Html::a('تصفح', ['//مقالات/مقالات-متنوعة']) ?></li>
            <li class="divider"></li>
            <li><?= Html::a('شاهد', ['//مقالات/مقاطع-فيديو']) ?></li>
            <li class="divider"></li>
            <li><?= Html::a('نبذة عنا', ['//نبذة-عنا']) ?></li>
            <li class="divider"></li>
            <li><?= Html::a('هل لديك استفسار؟', ['//الأسئلة-الشائعة']) ?></li>
            <li class="divider"></li>
            <li><?= Html::a('شروط الاستخدام', ['//شروط-الموقع']) ?></li>
            <li class="divider"></li>
            <li><?= Html::a('اتصل بنا', ['//تواصل-معنا']) ?></li>
            <li class="divider"></li>
            <li><?= Html::a('خريطة الموقع', ['//خريطة-الموقع']) ?></li>
            <li class="pull-right"><a class="btn btn-primary" href="https://www.facebook.com/Hangshare" target="_blank"><i
                        style="margin: 3px;" class="fa fa-fw fa-facebook"></i></a></li>
            <li class="pull-right"><a class="btn" href="https://www.twitter.com/hang_share" target="_blank"
                                      style="color: #fff; background-color: #3E4347;"><i style="margin: 3px;"
                                                                                         class="fa fa-twitter"></i></a>
            </li>
            <li class="pull-right"><a class="btn" href="https://www.google.com/Hangshare" target="_blank"
                                      style="color: #fff; background-color: #e51717;"><i style="margin: 3px;"
                                                                                         class="fa fa-google-plus"></i></a>
            </li>
        </ul>
    </footer>
<?php endif; ?>
<?php $this->endBody() ?>
<?php if (!Yii::$app->user->isGuest): ?>
    <script type="text/javascript">
        mixpanel.identify("<?= Yii::$app->user->identity->id ?>");
    </script>
<?php endif; ?>
</body>
</html>
