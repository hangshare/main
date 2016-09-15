<?php

use app\models\Post;
use app\models\Tags;
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = $model->title;
$bodys = '';
foreach ($model->postBodies as $data) {
    $bodys .= $data->body;
}
$this->description = Yii::$app->helper->metabody($bodys);
if (empty($this->description)) {
    $this->description = $this->title;
}

$this->description = Yii::t('app', 'description.deleted');

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
        <div class="row">
            <h1 class="normal"><?= $this->title ?></h1>
        </div>
        <hr class="nomargin m-b-20">
        <div class="row">
            <div class="col-md-12">
                <?php
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_view',
                    'layout' => "<ul class='list-unstyled'>{items}\n</ul>",
                    'itemOptions' => [
                        'tag' => false
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3  res-hidden">
        <div class="row m-b-20">
            <div class="col-md-12">
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
            </div>
        </div>
        <div class="row m-t-20">
            <div style="position: relative">
                <div class="m-t-25">
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
            </div>
        </div>
    </div>
</div>