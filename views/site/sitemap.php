<?php

use yii\widgets\ListView;

$this->title = Yii::t('app', 'جميع روابط موقع هانج شير');
$this->description = Yii::t('app', 'مقالات متنوعة من كافة انحاء العالم انشر مقالات واحصل على المال بشكل فوري.');
?>
<div class="container">
    <div class="white-box">
        <h1><?= $this->title; ?></h1>
        <div class="row">
            <div class="col-md-6">
                <h3><a href="<?php echo Yii::$app->urlManager->createUrl(['//users/']); ?>">المستخدمين</a></h3>
                <?=
                ListView::widget([
                    'dataProvider' => $userProvider,
                    'itemView' => '_sitemapuser',
                    'layout' => "<ul class='list-unstyled'>{items}\n</ul>",
                    'options' => [],
                    'itemOptions' => [
                        'tag' => false,
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-6">
                <h3><a href="<?php echo Yii::$app->urlManager->createUrl(['//explore/index']); ?>">مقالات</a></h3>
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