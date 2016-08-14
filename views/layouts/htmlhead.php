<?php

use yii\helpers\Html;
use app\assets\AppAsset;

Yii::$app->F->jsTranslations("app");

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$this->description = htmlentities($this->description);
if (empty($this->ogImage)) {
    $this->ogImage = 'https://s3.amazonaws.com/hangshare.static/Screenshot+from+2016-02-05+03%3A38%3A33.png';
}
$canonical = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $this->description ?>"/>
    <?= Html::csrfMetaTags() ?>
    <!-- Facebook meta tags -->
    <meta property="og:title" content="<?= Html::encode($this->title) ?>"/>
    <meta property="og:site_name" content="hangshare"/>
    <meta property="og:url" content="<?= $canonical ?>"/>
    <meta property="og:description" content="<?= $this->description; ?>"/>
    <meta property="fb:app_id" content="1024611190883720"/>
    <meta property="og:image" content="<?= $this->ogImage; ?>"/>
    <meta property="og:type" content="article"/>
    <meta property="article:author" content="hangshare"/>
    <meta property="article:publisher" content="http://www.hangshare.com"/>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-68983967-1', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq)
                return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq)
                f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', '//connect.facebook.net/en_US/fbevents.js');

        fbq('init', '1713055775580604');
        fbq('track', "PageView");</script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=1713055775580604&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
</head>
<body>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php //$message['message']; ?>
<?php endforeach; ?>
<?php $this->beginBody() ?>
