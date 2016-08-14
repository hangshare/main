<?php
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Sitemap title');
$this->description = Yii::t('app', 'Sitemap description');
?>
<div class="container">
    <div class="white-box">
        <h1><?= $this->title; ?></h1>
        <div class="row">
            <div class="col-md-6">
                <h3><a href="<?php echo Yii::$app->urlManager->createUrl(['//users/']); ?>"><?= Yii::t('app', 'Users') ?></a></h3>
                <?=
                ListView::widget([
                    'dataProvider' => $userProvider,
                    'itemView' => '_sitemapuser',
                    'layout' => "<ul class='list-unstyled'>{items}\n</ul>",
                    'options' => [],
                    'itemOptions' => [
                        'tag' => false
                    ]]);
                ?>
            </div>
            <div class="col-md-6">
                <h3>
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['//explore/index']); ?>"><?= Yii::t('app', 'Posts') ?></a>
                </h3>
                <?=
                ListView::widget([
                    'dataProvider' => $postProvider,
                    'itemView' => '_sitemappost',
                    'layout' => "<ul class='list-unstyled'>{items}\n</ul>{pager}",
                    'options' => [],
                    'itemOptions' => [
                        'tag' => false,
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>