<?php

use app\models\Post;
use app\models\Tags;
use yii\helpers\Html;
use yii\widgets\ListView;

if (isset($cat)) {
    $this->title = Yii::t('app', $cat->title);
    $this->description = Yii::t('app', $cat->title);
} else {
    if (isset($tags)) { // BUG
        $this->title = $tags;
        $this->description = $tags;
    }
}
if (empty($this->title)) {
    $this->title = Yii::t('app', 'post.header.title');
    $this->description = Yii::t('app', 'post.header.description');
}
?>
<div class="container m-t-25">
    <div class="row">
        <?php if (Yii::$app->helper->isMobile()) : ?>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- left right responsive categery -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-6288640194310142"
                 data-ad-slot="8394999319"
                 data-ad-format="auto"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        <?php else : ?>
            <div style="margin: 0 auto; display: table;">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Leaderboard - Category Upper -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:728px;height:90px"
                     data-ad-client="ca-pub-6288640194310142"
                     data-ad-slot="6969174910"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        <?php endif ?>
    </div>
    <div class="col-md-9">
        <?php if (isset($cat)) : //bug ?>
            <div class="row">
                <h1 class="normal"><?= $cat->title ?></h1>
            </div>
        <?php endif; ?>
        <hr class="nomargin m-b-20">
        <div class="row">
            <div class="col-md-12">
                <ul class='list-unstyled inifi'>
                    <?php
                    $models = $dataProvider->getModels();
                    $i = 1;
                    foreach ($models as $data) {
                        echo $this->render('_view', ['model' => $data]);
                        $i++;
                        if ($i == 5)
                            echo $this->render('_innerads');
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3 res-hidden">
        <div class="row" style="margin-top: 52px;">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- MPU - Category Right -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:300px;height:250px"
                 data-ad-client="ca-pub-6288640194310142"
                 data-ad-slot="9922641319"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
        <div class="row m-t-20">
            <div id="hot-posts" data-id="1000">
                <h3 class="underlined"><?= Yii::t('app', 'Whats Hot') ?></h3>
            </div>
        </div>
    </div>
</div>