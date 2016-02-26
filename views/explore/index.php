<?php

use app\models\Post;
use app\models\Tags;
use yii\helpers\Html;
use yii\widgets\ListView;

$q = '';
$title = 'مقالات متنوعة';
if (Yii::$app->controller->action->id == 'video') {
    $title = 'مقالات مرئية';
} else if (Yii::$app->controller->action->id == 'fun') {
    $title = 'مقالات ترفيهية ومضحكة و مسلية';
} else {
    if (isset($_GET['tag'])) {
        $q = Html::encode($_GET['tag']);
    } else if (isset($_GET['q'])) {
        $q = Html::encode($_GET['q']);
    }
    if (empty($q)) {
        $title = 'مقالات متنوعة';
    } else {
        $title = " مقالات عن :  " . $q;
    }
}
$this->title = Yii::t('app', 'انشر مقالاتك واحصل على المال -  ' . $title);
$this->description = Yii::t('app', "مقالات متنوعة $q ، انشر مقالات مواضيع تصور واحصل على المال مقابل كل مشاهدة عن طريق الباي بال ومواقغ التواصل الاجتماعي الفيسبوك والتويتر والجوجل بلس.");
?>
<div class="container m-t-25">
    <div class="row">
        <div class="col-md-3  res-hidden">
            <h3 class="m-t-25">البحث</h3>
            <hr class="nomargin"> 
            <div class="row m-b-20">
                <?= $this->render('_search', ['model' => new Post]) ?>
            </div>
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
            <div class="m-t-20">
                <div style="position: relative">
                    <div class="row m-t-25 fixad" rel="2800">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- Wide Skyscraper - Category Right -->
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:160px;height:600px"
                             data-ad-client="ca-pub-6288640194310142"
                             data-ad-slot="2399374518"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?php if (Yii::$app->helper->isMobile()) : ?>
                <script async src = "//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
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
                <div class="white text-center">
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
            <div class="row">
                <h1 class="normal"><?= $title ?></h1>
            </div>
            <hr class="nomargin">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_view',
                        'layout' => "<ul class='list-inline postindex inpage inifi'>{items}\n</ul>",
                        'itemOptions' => [
                            'tag' => false
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 res-hidden">
            <h3 class="m-t-25">التصنيفات</h3>
            <hr class="nomargin">
            <ul class="list-unstyled list-styled m-t-25">
                <?php
                $tags = Tags::find()->where('type=1')->all();
                $category = Yii::$app->controller->action->id == 'video' ? 'مقاطع-فيديو' : 'مقالات-متنوعة';
                foreach ($tags as $data) :
                    ?>
                    <li>
                        <a href="<?php echo Yii::$app->urlManager->createUrl(["//مقالات/$category/{$data->name}"]); ?>">
                            <?= $data->name ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="row m-t-25">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- MPU - Category Left -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-6288640194310142"
                     data-ad-slot="3876107710"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <div class="m-b-20">
                <div style="position: relative">
                    <div class="row m-t-25 fixad" rel="2800">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- Wide Skyscraper - Category Left -->
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:160px;height:600px"
                             data-ad-client="ca-pub-6288640194310142"
                             data-ad-slot="5352840915"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>