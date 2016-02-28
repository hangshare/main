<?php
use yii\helpers\Html;

$this->title = $model->title;
$this->ogImage = Yii::$app->imageresize->thump($model->cover, 500, 500, 'resize');
?>
<div class="col-md-9">
    <div class="row">
        <article data-id="<?= $model->id; ?>" data-userId="<?= $model->userId ?>"
                 data-plan="<?= $model->user->plan; ?>">
            <div class="content-column white prview">
                <div class="container">
                    <div class="row">
                        <div class="pull-right">
                            <?php
                            if (!Yii::$app->user->isGuest) {
                                if (Yii::$app->user->identity->id == $model->userId) {
                                    echo Html::a('تعديل الموضوع ', ['//explore/post', 'id' => $model->id]);
                                    echo '<span> | </span>';
                                    echo Html::a(' احذف الموضوع', ['//explore/delete', 'id' => $model->id], [
                                        'data' => ['method' => 'post'],
                                        'style' => 'color:red;']);
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <h1 class="post-header"><?= Html::encode($this->title) ?></h1>

                        <div class="pull-left"> الكاتب : <a
                                href="<?= Yii::$app->urlManager->createUrl(['//user/view', 'id' => $model->user->id]) ?>"
                                title="<?= $model->user->name; ?>"><?= $model->user->name; ?></a> |
                        </div>
                        <p> تاريخ اﻹضافة : <?php echo date('Y-m-d', strtotime($model->created_at)); ?>
                            | المشاهدات : <?= number_format($model->postStats->views) ?>
                        </p>
                        <ul class="list-inline shareer" style="margin: 18px 7px 0;">
                            <li><a class="btn btn-primary js-share js-share-fasebook" href="javascript:void(0);"
                                   post-url="<?= $model->url; ?>"><i style="margin: 3px;"
                                                                     class="fa fa-fw fa-facebook"></i>انشر
                                    هذا الموضوع على صفحة الفيسبوك</a></li>
                            <li><a class="btn js-share js-share-twitter" post-url="<?= $model->url; ?>"
                                   href="javascript:void(0);" style="color: #fff; background-color: #3E4347;"><i
                                        style="margin: 3px;" class="fa fa-twitter"></i>غرد على موقع تويتر</a></li>
                            <li><a class="btn js-share js-share-gpuls" post-url="<?= $model->url; ?>"
                                   href="javascript:void(0);" style="color: #fff; background-color: #e51717;"><i
                                        style="margin: 3px;" class="fa fa-google-plus"></i> انشر على موقع جوجل</a></li>
                        </ul>
                        <div class="post-body">
                            <div class="row chebody">
                                <div class="col-md-12 m-b-20">
                                    <?php if (Yii::$app->helper->isMobile()) : ?>
                                        <script async
                                                src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                        <!-- Responsive - Mobile Post Upper -->
                                        <ins class="adsbygoogle"
                                             style="display:block"
                                             data-ad-client="ca-pub-6288640194310142"
                                             data-ad-slot="6756705310"
                                             data-ad-format="auto"></ins>
                                        <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script>
                                    <?php else : ?>
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
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-12" style="margin: 50px 0;">
                                    <?php
                                    if ($model->type) {
                                        echo $this->render('//explore/_external', ['data' => $model]);
                                    }
                                    ?>
                                    <?php
                                    $bodys = Yii::$app->cache->get('post-body-' . $model->id);
                                    if ($bodys == false) {
                                        $bodys = '';
                                        foreach ($model->postBodies as $data) {
                                            $bodys .= $data->body;
                                        }
                                        $bodys = Yii::$app->helper->replaceLinks($bodys);
                                        Yii::$app->cache->set('post-body-' . $model->id, $bodys, 3000);
                                    }
                                    $this->description = Yii::$app->helper->metabody($bodys);
                                    echo $bodys;
                                    ?>
                                    <div class="m-b-20">
                                        <?php if (Yii::$app->helper->isMobile()) : ?>
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
                                        <?php else : ?>
                                            <script async
                                                    src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                            <!-- Leaderboard - Post Bottom -->
                                            <ins class="adsbygoogle"
                                                 style="display:inline-block;width:728px;height:90px"
                                                 data-ad-client="ca-pub-6288640194310142"
                                                 data-ad-slot="1341443712"></ins>
                                            <script>
                                                (adsbygoogle = window.adsbygoogle || []).push({});
                                            </script>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <ul class="list-inline shareer" style="margin: 18px 7px 20px;">
                                    <li><a class="btn btn-primary js-share js-share-fasebook" href="javascript:void(0);"
                                           post-url="<?= $model->url; ?>"><i style="margin: 3px;"
                                                                             class="fa fa-fw fa-facebook"></i>انشر هذا
                                            الموضوع
                                            على صفحة الفيسبوك</a></li>
                                    <li><a class="btn js-share js-share-twitter" post-url="<?= $model->url; ?>"
                                           href="javascript:void(0);" style="color: #fff; background-color: #3E4347;"><i
                                                style="margin: 3px;" class="fa fa-twitter"></i>غرد على موقع تويتر</a>
                                    </li>
                                    <li><a class="btn js-share js-share-gpuls" post-url="<?= $model->url; ?>"
                                           href="javascript:void(0);" style="color: #fff; background-color: #e51717;"><i
                                                style="margin: 3px;" class="fa fa-google-plus"></i> انشر على موقع
                                            جوجل</a></li>
                                </ul>
                                <div id="related-posts" data-id="<?= $model->id; ?>" class="col-md-12 m-t-20">
                                    <h3>اقرأ ايضا</h3>
                                    <hr/>
                                </div>
                                <div class="col-md-12">
                                    <h4>الأقسام</h4>
                                    <ul class="list-inline tags">
                                        <?php
                                        $category = $model->type == 1 ? 'مقاطع-فيديو' : 'مقالات-متنوعة';
                                        foreach ($model->postTags as $tags) :
                                            if (isset($tags->tags)):
                                                ?>
                                                <li>
                                                    <?php $ttaUrl = Yii::$app->helper->urlTitle($tags->tags->name); ?>
                                                    <a href="<?= Yii::$app->urlManager->createUrl(["مقالات/{$category}/{$ttaUrl}"]) ?>"><label
                                                            class="label label-default"><?php echo $tags->tags->name; ?></label></a>
                                                </li>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <hr class="row">
                            <div class="row">
                                <div class="pull-left m-l-30">
                                    <?php
                                    if (isset($this->params['next'])) : ?>
                                        <a rel="next" href="<?= $this->params['next']->url ?>">
                                            <h4>
                                                <i class="glyphicon glyphicon-arrow-right"></i> <?php echo $this->params['next']->title; ?>
                                            </h4>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="pull-right  m-r-30">
                                    <?php
                                    if (isset($this->params['prev'])) : ?>
                                        <a href="<?= $this->params['prev']->url ?>">
                                            <h4><?php echo $this->params['prev']->title; ?> <i
                                                    class="glyphicon glyphicon-arrow-left"></i></h4>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
    <div class="row m-b-20 m-t-25">
        <div class="container">
            <h3 class="m-t-25">التعليقات على موقع الفيسبوك</h3>

            <div id="fb-root"></div>
            <script>(function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id))
                        return;
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=1024611190883720";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
            <div class="fb-comments" data-href="<?= $model->url ?>" data-numposts="5" data-width="100%"></div>
        </div>
    </div>
</div>
<div class="col-md-3 re-hidden">
    <div class="col-md-12">
        <?php if (Yii::$app->user->isGuest) : ?>
            <div class="row m-b-20">
                <div class="white text-center">
                    <div class="col-md-12">
                        <h4 class="text-center">انشر مقالتك واحصل على المال مقابل كل مشاهدة</h4>
                        <a href="<?= Yii::$app->urlManager->createUrl('//site/facebook'); ?>"
                           class="btn btn-primary btn-block"
                           style="background-color: #3b5998;height: 30px;margin-top: 30px; padding: 4px 10px 10px;">
                            <i class="fa fa-fw fa-facebook pull-left" style="  border-left: 1px solid;
                               font-size: 15px;
                               margin-top: 3px;
                               padding-left: 10px;"></i>
                            <span class="pull-left"> سجل باستخدام الفيسبوك</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!Yii::$app->helper->isMobile()): ?>

            <div class="row m-t-25 m-b-20">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- MPU - Post Upper -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-6288640194310142"
                     data-ad-slot="2818176918"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <div class="row m-t-25">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Wide Skyscraper - Post Upper -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:160px;height:600px"
                     data-ad-client="ca-pub-6288640194310142"
                     data-ad-slot="4294910115"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <div class="row m-t-25">
                <div id="fb-root"></div>
                <script>(function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id))
                            return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1390171834574276&version=v2.0";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-like-box m-t-20" data-href="https://www.facebook.com/Hangshare" data-colorscheme="light"
                     data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
            </div>

        <?php endif; ?>
        <div style="position: relative">
            <div id="fixad" class="row m-t-25" rel="2318">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- MPU - Post Bottom -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-6288640194310142"
                     data-ad-slot="5771643312"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </div>
        <div class="row m-t-25">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Wide Skyscraper - Post Bottom -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:160px;height:600px"
                 data-ad-client="ca-pub-6288640194310142"
                 data-ad-slot="7248376513"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?php
if (!Yii::$app->user->isGuest):
    if ((time() - strtotime($model->created_at) < 40000) && Yii::$app->user->identity->id == $model->userId):
        ?>
        <div class="modal fade" id="sharenew" data-backdrop="show">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">انشر مقالتك على مواقع التواصل لتحصل على اكبر عدد ممكن
                            من المشاهدات.</h4>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center">شارك هذا الموضوع مع اصدقائك</h4>
                        <ul class="list-inline text-center shareer">
                            <li><a class="btn btn-primary js-share js-share-fasebook" href="javascript:void(0);"
                                   post-url="<?= $model->url; ?>"><i style="margin: 3px;"
                                                                     class="fa fa-fw fa-facebook"></i>انشر
                                    على صفحة الفيسبوك</a></li>
                            <li><a class="btn js-share js-share-twitter" post-url="<?= $model->url; ?>"
                                   href="javascript:void(0);" style="color: #fff; background-color: #3E4347;"><i
                                        style="margin: 3px;" class="fa fa-twitter"></i>غرد على موقع تويتر</a></li>
                            <li><a class="btn js-share js-share-gpuls" post-url="<?= $model->url; ?>"
                                   href="javascript:void(0);" style="color: #fff; background-color: #e51717;"><i
                                        style="margin: 3px;" class="fa fa-google-plus"></i> انشر على موقع google+</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endif;
endif;
?>
