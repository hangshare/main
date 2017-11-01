<?php

use yii\helpers\Html;
use app\assets\AppAsset;

$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];

$canonical = str_replace('amp/', '', "https://{$host}{$uri}");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html amp lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <link rel="canonical" href="<?= $canonical ?>">
    <meta name="viewport"
          content="width=device-width,minimum-scale=1,initial-scale=1">
    <style amp-custom>
        <?php if(Yii::$app->language == 'ar') : ?>
        h1, h2, h3, h4, h5, o, a, span,p {
            text-align: right;
            direction: rtl;
        }

        <?php endif; ?>
        p, a, span {
            font-size: 18px
        }
    </style>
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-auto-ads" src="https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js"></script>
</head>
<body>
<amp-auto-ads type="adsense" data-ad-client="ca-pub-6288640194310142"></amp-auto-ads>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php //$message['message']; ?>
<?php endforeach; ?>
<?php //$this->beginBody() ?>
