<?php
use yii\helpers\Html;

$this->title = $model->title;
$this->ogImage = Yii::$app->imageresize->thump($model->cover, 500, 500, 'resize');

if (Yii::$app->language == 'en') {
    $ua = '/en/articles';
} else {
    $ua = '//مواضيع';
}

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'articles-url'),
    'url' => Yii::$app->urlManager->createUrl($ua),
];
?>
<div <?php if (!Yii::$app->helper->isMobile()) : ?> class="row" <?php endif; ?>>
    <div class="col-md-12 res-nopadding">
        <div class="col-md-9" style="background-color: #fff;">
            <article data-id="<?= $model->id; ?>" data-userId="<?= $model->userId ?>"
                     data-plan="<?= $model->user->plan; ?>" style="margin-bottom: 100px;">
                <div class="container res-nopadding">
                    <h1 class="post-header"><?= Html::encode($this->title) ?></h1>
                    <?php
                    $username = empty($model->user->username) ? $model->user->id : $model->user->username;
                    ?>
                    <ul class="list-inline res-hidden">
                        <li><b><?= Yii::t('app', 'Author') ?></b> <a
                                    href="<?= Yii::$app->urlManager->createUrl(['//user/view', 'id' => $username]) ?>"
                                    title="<?= $model->user->name; ?>"><?= $model->user->name; ?></a></li>
                        <li class="divider"></li>
                        <li><b><?= Yii::t('app', 'created_at') ?> </b>
                            <span><?php echo date('Y-m-d', strtotime($model->created_at)); ?></span></li>
                        <li class="divider"></li>
                        <li><b><?= Yii::t('app', 'Views') ?> </b> <?php
                            $totalViews = $model->postStats->views;
                            echo number_format($totalViews + 1);
                            ?></li>
                        <?php
                        if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->id == $model->userId || Yii::$app->user->identity->type == 1)) : ?>
                            <li class="divider"></li>
                            <li><?= Html::a(Yii::t('app', 'Edit Post'), ['//explore/post', 'id' => $model->id]); ?></li>
                        <?php endif; ?>
                    </ul>
                    <div class="post-body">
                        <div class="chebody">
                            <?php
                            if ($model->type) {
                                echo $this->render('//explore/_external', ['data' => $model]);
                            }
                            ?>
                            <?php
                            $mo = Yii::$app->helper->isMobile() ? 'mob ' : 'desktop';
                            $bodys = Yii::$app->cache->get($mo . '-post-body-' . $model->id);
                            if ($bodys == false) {
                                $bodys = '';
                                foreach ($model->postBodies as $data) {
                                    $bodys .= $data->body;
                                }
                                $bodys = Yii::$app->helper->replaceLinks($bodys);
                                if (!Yii::$app->helper->isMobile()) {
                                    $bodys = '<div class="pull-left adsmargin"> <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- MPU - Post page right Upper  -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:300px;height:250px; text-align: left;"
                         data-ad-client="ca-pub-6288640194310142"
                         data-ad-slot="1962272113"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script></div>' . $bodys;
                                    Yii::$app->cache->set($mo . '-post-body-' . $model->id, $bodys, 3000);
                                } else {
                                    $bodys = Yii::$app->helper->str_insert($bodys, '</p>', '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-6288640194310142" data-ad-slot="9020008518" data-ad-format="auto"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>');
                                    Yii::$app->cache->set($mo . '-post-body-' . $model->id, $bodys, 3000);
                                }
                            }
                            $this->description = Yii::$app->helper->metabody($bodys);
                            if (empty($this->description)) {
                                $this->description = $this->title;
                            }
                            echo $bodys;
                            ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <span style="font-weight: bold; font-size: 18px; margin-top: 30px; display: block"><?= Yii::t('app', 'Share this Article') ?></span>
                                    <!-- Go to www.addthis.com/dashboard to customize your tools -->
                                    <div class="addthis_inline_share_toolbox"></div>
                                </div>
                            </div>

                            <?php if (Yii::$app->helper->isMobile()): ?>
                                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                <!-- MPU - Post page right Upper  -->
                                <ins class="adsbygoogle"
                                     style="display:inline-block;width:300px;height:250px; text-align: left;"
                                     data-ad-client="ca-pub-6288640194310142"
                                     data-ad-slot="1962272113"></ins>
                                <script>
                                    (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                            <?php endif; ?>
                            <div class="clearfix"></div>
                            <div id="related-posts" style="margin-top: 40px;" data-id="<?= $model->id; ?>">
                                <h3 class="underlined"><?= Yii::t('app', 'Related Posts') ?></h3>
                            </div>

                            <div class="row">
                                <div class="col-md-12 m-t-25">
                                    <h3 class="underlined"><?= Yii::t('app', 'Comments') ?></h3>
                                    <?php echo $this->render('//explore/_commentform', ['id' => $model->id]); ?>
                                    <div id="comments" data-id="<?= $model->id; ?>"></div>
                                </div>
                            </div>
                            <?php $tags_all = $model->postTags;
                            if (count($tags_all) > 0) : ?>
                                <div class="col-md-12">
                                    <div class="row">
                                        <h4 class="underlined"><?= Yii::t('app', 'Tags') ?></h4>
                                        <ul class="list-inline tags">
                                            <?php
                                            foreach ($tags_all as $tags) :
                                                if (isset($tags->tags)):
                                                    ?>
                                                    <li>
                                                        <?php $ttaUrl = Yii::$app->helper->urlTitle($tags->tags->name); ?>
                                                        <a href="<?= Yii::$app->urlManager->createUrl(["//tags/{$ttaUrl}"]) ?>"><label
                                                                    class="label label-default"><?php echo $tags->tags->name; ?></label></a>
                                                    </li>
                                                    <?php
                                                endif;
                                            endforeach;
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <?php if (!Yii::$app->helper->isMobile()): ?>
            <div class="col-md-3">
                <div class="m-t-8">
                    <div class="pull-left adsmargin">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- MPU - Post page right Upper  -->
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:300px;height:250px; text-align: left;"
                             data-ad-client="ca-pub-6288640194310142"
                             data-ad-slot="1962272113"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                    <div id="hot-posts" data-id="<?= $model->id; ?>">
                        <h3 class="underlined"><?= Yii::t('app', 'Whats Hot') ?></h3>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php if (!Yii::$app->user->isGuest):
    if ((time() - strtotime($model->created_at) < 40000) && Yii::$app->user->identity->id == $model->userId):
        ?>
        <div class="modal fade" id="sharenew" data-backdrop="show">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"
                            style="font-weight: bold;"><?= Yii::t('app', 'Share it message 2') ?></h4>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center"><?= Yii::t('app', 'Share this post with your friends') ?></h4>
                        <ul class="list-inline shareer text-center m-t-25">
                            <li><a class="btn btn-primary js-share js-share-fasebook" href="javascript:void(0);"
                                   post-url="<?= $model->url; ?>">
                                    <i style="margin: 3px;" class="fa fa-fw fa-facebook"></i>
                                    <?= Yii::t('app', 'Facebook') ?>
                                </a>
                            <li><a class="btn js-share js-share-twitter" post-url="<?= $model->url; ?>"
                                   href="javascript:void(0);" style="color: #fff; background-color: #4099ff;">
                                    <i style="margin: 3px;" class="fa fa-twitter"></i>
                                    <?= Yii::t('app', 'twitter') ?>
                                </a></li>
                            <li>
                                <a class="btn js-share js-share-gpuls" post-url="<?= $model->url; ?>"
                                   href="javascript:void(0);" style="color: #fff; background-color: #e51717;">
                                    <i style="margin: 3px;" class="fa fa-google-plus"></i>
                                    <?= Yii::t('app', 'Google+') ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endif;
endif; ?>

<?php $img = Yii::$app->imageresize->original($model->cover); ?>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "headline": "<?= $model->title ?>",
  "description": "<?= $this->description ?>",
  "url":"<?= $model->url ?>",
  "keywords":["abortion-access","lifestyle","abortion-rights","reproductive-rights","uk","isle-of-man","abortion","work-play"],
  "@type": "Article",
  "image": {
    "@type": "ImageObject",
    "url": "<?= 'https://s3-eu-west-1.amazonaws.com/hangshare-media/' . $img['url'] ?>",
    "height": <?= empty($img['height']) ? "200" : (string)$img['height'] ?>,
    "width":  <?= empty($img['width']) ? "200" : (string)$img['width'] ?>
  },
  "mainEntityOfPage":"True",
  "articleSection":"lifestyle",
  "datePublished": "<?= date('Y-m-d h:i', strtotime($model->created_at)); ?>",
  "dateModified": "<?= date('Y-m-d h:i', strtotime($model->created_at)); ?>",
  "author": {
    "@type": "Person",
    "name": "<?= $model->user->name; ?>"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Hangshare",
    "url":"https://www.hangshare.com/",
    "logo": {
      "@type": "ImageObject",
      "url": "https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo.png",
      "width": 135,
      "height": 32
    }
  }
}














</script>


