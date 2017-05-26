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
        h1, h2, h3, h4, h5, o, a, span {
            text-align: right;
            direction: rtl;
        }

        <?php endif; ?>
        p, a, span {
            font-size: 18px
        }

    </style>

    <?php
    $assests = Yii::$app->assetManager;
    $assests->assetMap[] = "https://cdn.ampproject.org/v0.js";
    $assests->assetMap[] = "https://cdn.ampproject.org/v0/amp-ad-0.1.js";
    ?>
</head>
<body>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php //$message['message']; ?>
<?php endforeach; ?>
<?php $this->beginBody() ?>
