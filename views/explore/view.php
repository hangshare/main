<?php
use yii\helpers\Html;

$this->title = $model->title;
$this->ogImage = Yii::$app->imageresize->thump($model->cover, 500, 500, 'resize');
if (Yii::$app->user->isGuest && session_status() == PHP_SESSION_NONE) {
    session_start();
}
$fb = new Facebook\Facebook([
    'app_id' => '1024611190883720',
    'app_secret' => '0df74c464dc8e58424481fb4cb3bb13c',
    'default_graph_version' => 'v2.4',
    'persistent_data_handler' => 'session'
]);
$helper = $fb->getRedirectLoginHelper();
$params = ['scope' => 'email,user_about_me'];
$fUrl = $helper->getLoginUrl('https://www.hangshare.com/site/facebook/', $params);
?>
<?php if (Yii::$app->helper->isMobile()) : ?>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- responsive - new mobile upper post -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-6288640194310142"
         data-ad-slot="9020008518"
         data-ad-format="auto"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
<?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-9 res-nopadding" style="background-color: #fff;">
                <div class="ads1">
                    <script async
                            src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- Leaderboard - Post Upper -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:728px;height:90px"
                         data-ad-client="ca-pub-6288640194310142"
                         data-ad-slot="1011333310"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>

                <article data-id="<?= $model->id; ?>" data-userId="<?= $model->userId ?>"
                         data-plan="<?= $model->user->plan; ?>" style="margin-bottom: 100px;">
                    <div class="container">
                        <?php if (!Yii::$app->helper->isMobile()) : ?>
                            <span class="pull-left"
                                  style="font-weight: bold; color: #999; font-size: 18px; margin-top: 3px;"><?= Yii::t('app', 'Share this Article') ?></span>
                            <div class="pull-left" style="margin: 0 15px;">
                                <ul class="list-inline shareer">
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
                                    <?php if (Yii::$app->helper->isMobile()) : ?>
                                        <li>
                                            <a class="btn" href="whatsapp://send?text=<?= $model->url; ?>"
                                               style="color: #fff; background-color: #34af23;"
                                               data-action="share/whatsapp/share">
                                                <i style="margin: 3px;" class="fa fa-whatsapp"></i>
                                                <?= Yii::t('app', 'Whatsapp') ?> </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        <?php endif; ?>

                        <h1 class="post-header" style="margin: 10px 0 0px 0"><?= Html::encode($this->title) ?></h1>
                        <?php
                        $username = empty($model->user->username) ? $model->user->id : $model->user->username;
                        ?>
                        <ul class="list-inline">
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
                            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->id == $model->userId) : ?>
                                <li class="divider"></li>
                                <li><?= Html::a(Yii::t('app', 'Edit Post'), ['//explore/post', 'id' => $model->id]); ?></li>
                                <li class="divider"></li>
                                <li><?= Html::a(Yii::t('app', 'Delete Post'), ['//explore/delete', 'id' => $model->id], [
                                        'data' => ['method' => 'post'],
                                        'style' => 'color:red;']); ?></li>
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
                                $bodys = Yii::$app->cache->get('mmpost-body-' . $model->id);
                                if ($bodys == false) {
                                    $bodys = '';
                                    foreach ($model->postBodies as $data) {
                                        $bodys .= $data->body;
                                    }
                                    $bodys = Yii::$app->helper->replaceLinks($bodys);
                                    $bodys = Yii::$app->helper->str_insert($bodys, '</p>', '<div style="float: left; margin: 0 20px 20px 0;"> <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- MPU - Post page right Upper  -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:300px;height:250px; text-align: left;"
                         data-ad-client="ca-pub-6288640194310142"
                         data-ad-slot="1962272113"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script></div>');
                                    Yii::$app->cache->set('post-body-' . $model->id, $bodys, 3000);
                                }
                                $this->description = Yii::$app->helper->metabody($bodys);
                                echo $bodys;
                                ?>
                                <?php if (Yii::$app->helper->isMobile()): ?>
                                    <div class="col-md-12">
                                        <script async
                                                src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                        <!-- Responsive - Mobile Post Bottom -->
                                        <ins class="adsbygoogle"
                                             style="display:block"
                                             data-ad-client="ca-pub-6288640194310142"
                                             data-ad-slot="9710171716"
                                             data-ad-format="auto"></ins>
                                        <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script>
                                    </div>
                                <?php endif; ?>
                                <div class="clearfix"></div>
                                <div id="related-posts" data-id="<?= $model->id; ?>">
                                    <h3 class="underlined"><?= Yii::t('app', 'Related Posts') ?></h3>
                                </div>
                                <?php $tags_all = $model->postTags;
                                if(count($tags_all) > 0) : ?>
                                <div class="col-md-12">
                                    <div class="row">
                                        <h4 class="underlined"><?= Yii::t('app', 'Tags') ?></h4>
                                        <ul class="list-inline tags">
                                            <?php
                                            foreach ( $tags_all as $tags) :
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
                        <div style="float: left; margin: 0 20px 20px 0;"> <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <!-- MPU - Post page right Upper  -->
                            <ins class="adsbygoogle"
                                 style="display:inline-block;width:300px;height:250px; text-align: left;"
                                 data-ad-client="ca-pub-6288640194310142"
                                 data-ad-slot="1962272113"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script></div>
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
                            <?php if (Yii::$app->helper->isMobile()) : ?>
                                <li>
                                    <a class="btn" href="whatsapp://send?text=<?= $model->url; ?>"
                                       style="color: #fff; background-color: #34af23;"
                                       data-action="share/whatsapp/share">
                                        <i style="margin: 3px;" class="fa fa-whatsapp"></i>
                                        <?= Yii::t('app', 'Whatsapp') ?> </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endif;
endif; ?>
<?php if (Yii::$app->user->isGuest) : ?>
    <div id="slide-signup"
         style=" width: 560px; margin-right: 28%; position: fixed; bottom:-250px; transition: bottom 2s; z-index: 1000; border: 3px solid #3d8eb9; border-bottom: 0;">
        <div class="white text-center" style="height: 150px;">
                <a href="javascript:void(0);" onclick="$(this).parent().parent().hide();" rel="nofollow"
                   style="color: #ccc; position: relative; top: -10px"><i
                        class="glyphicon glyphicon-remove-sign"></i></a>
                <h4 class="text-center margin-0"><?= Yii::t('app', 'Share Posts and get instant profit') ?></h4>
                <a href="<?= $fUrl; ?>" class="btn btn-primary"
                   style="background-color: #3b5998;height: 30px;margin: 30px auto;
    width: 50%;">
                    <i class="fa fa-fw fa-facebook pull-left signup-slide-fb"></i>
                    <span class="pull-left"><?= Yii::t('app', 'Sign up NOW! Using your Facebook Account') ?></span>
                </a>

        </div>
    </div>
<?php endif; ?>