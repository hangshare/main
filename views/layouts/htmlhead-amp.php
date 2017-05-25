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
    <link rel="canonical" href="__CANONICAL_URL__">
    <meta name="viewport"
          content="width=device-width,minimum-scale=1,initial-scale=1">
    <style>body {
            opacity: 0
        }</style>
    <noscript>
        <style>body {
                opacity: 1
            }</style>
    </noscript>
    <style amp-custom>
        <?php if(Yii::$app->language == 'ar') : ?>
        h1,h2,h3,h4,h5,o,a,span{
            text-align: right;
            direction: rtl;
        }
        <?php endif; ?>
        p,a,span{font-size: 16px}
    </style>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>

    <amp-analytics type="googleanalytics">
        <script type="application/json">
            {
                "vars": {
                    "account": "UA-68983967-1"
                },
                "triggers": {
                    "trackPageview": {
                        "on": "visible",
                        "request": "pageview"
                    }
                }
            }
        </script>
    </amp-analytics>

</head>
<body>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php //$message['message']; ?>
<?php endforeach; ?>
<?php $this->beginBody() ?>
