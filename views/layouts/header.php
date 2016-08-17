<?php
use yii\helpers\Url;
use app\models\Category;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

if (empty($this->title)) {
    $this->title = Yii::t('app', 'General Page Title');;
}
$canonical = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$this->registerLinkTag(['rel' => 'canonical', 'href' => $canonical]);
$menu = Category::find()->where(['lang' => Yii::$app->language])->all();

$articlesurl = Yii::t('app', 'articles-url');
$mainMenu = [];
foreach ($menu as $menuData) {
    if ($menuData->parent) {
        $mainMenu[$menuData->parent]['sub'][] = ['id' => $menuData->id, 'title' => $menuData->title, 'url' => $menuData->url_link];
    } else {
        $mainMenu[$menuData->id] = ['id' => $menuData->id, 'title' => $menuData->title, 'url' => $menuData->url_link];
    }
}
?>
    <header>
<?php if (Yii::$app->helper->isMobile()): ?>
    <ul id="main-menu" class="mainmenu">
        <?php foreach ($mainMenu as $mData) : ?>
            <li <?php if (isset($mData['sub'])): ?><?php endif; ?>>
                <a href="<?= Url::to(["//{$articlesurl}/{$mData['url']}"]) ?>"><?= $mData['title'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if (Yii::$app->user->isGuest) : ?>
        <ul id="non-user-menu" class="mainmenu">
            <li><?= Html::a(Yii::t('app', 'Login'), Url::to(['//login'])); ?></li>
            <li><?= Html::a(Yii::t('app', 'Register'), Url::to(['//register'])); ?></li>
        </ul>
    <?php endif; ?>


    <ul id="page-share" class="mainmenu">
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
        <li>
            <a class="btn" href="whatsapp://send?text=<?= $model->url; ?>"
               style="color: #fff; background-color: #34af23;"
               data-action="share/whatsapp/share">
                <i style="margin: 3px;" class="fa fa-whatsapp"></i>
                <?= Yii::t('app', 'Whatsapp') ?> </a>
        </li>
    </ul>

    <?php if (!Yii::$app->user->isGuest) : ?>
        <ul id="user-menu" class="mainmenu">
            <?php
            $username = empty(Yii::$app->user->identity->username) ? Yii::$app->user->identity->id : Yii::$app->user->identity->username;
            ?>
            <li><a href="<?= Url::to(['/user/view', 'id' => $username]); ?>"><i
                        class="fa fa-fw fa-user hidden-xs"></i>
                    <?= Yii::t('app', 'Profile') ?>
                </a></li>
            <li><a href="<?= Url::to(['/u/manage']); ?>">
                    <i class="fa fa-fw fa-gear hidden-xs"></i>
                    <?= Yii::t('app', 'Settings') ?>
                </a>
            </li>
            <li><a href="<?= Url::to(['/u/transfer']); ?>"><i
                        class="fa fa-fw fa-gear hidden-xs"></i>
                    <?= Yii::t('app', 'Money Transfer') ?>
                </a></li>
            <li class="dropdown-header"></li>
            <li class="divider"></li>
            <li><a data-method="post" href="<?= Url::to(['/site/logout']); ?>"><i
                        class="fa fa-fw fa-sign-out hidden-xs"></i>
                    <?= Yii::t('app', 'Logout') ?>
                </a>
            </li>
        </ul>
    <?php endif; ?>
    <div class="mobile-menu">
        <div id="search-header-mobile"
             style="border: 1px solid #ccc;left: 0;position: absolute;top: 37px;width: 100%;z-index: 50;
             display: none;">
            <?= $this->render('//explore/_search', ['model' => new app\models\Post]) ?>
        </div>

        <?php if (Yii::$app->user->isGuest) : ?>
            <div class="pull-left" style=" padding: 7px;
    text-align: center;
    width: 12%;">
                <a data-element="non-user-menu" href="#" class="showhide" style="color: #fff;"><i
                        class="glyphicon glyphicon-user"></i></a>
            </div>
        <?php else :
            $username = empty(Yii::$app->user->identity->username) ? Yii::$app->user->identity->id : Yii::$app->user->identity->username;
            $imSm = Yii::$app->imageresize->thump(Yii::$app->user->identity->image, 50, 50, 'crop');
            ?>
            <div class="pull-left" style=" padding: 7px;
    text-align: center;
    width: 12%;">
                <a class="showhide" data-element="user-menu" href="javascript:void(0);">
                    <img width="25" src="<?= $imSm; ?>">
                </a>
            </div>
        <?php endif; ?>
        <div class="pull-left" style="  padding: 7px;
    text-align: center;
    width: 12%;">
            <a data-element="search-header-mobile" class="showhide" href="javascript:void(0);"
               style="color: #fff; padding: 10px;"><i
                    class="glyphicon glyphicon-search"></i></a>
        </div>
        <div class="pull-left" style="text-align: center;width: 52%;">
            <div class="mobile-menu-logo">
                <a href="<?= Yii::$app->homeUrl ?>">
                    <?php if (Yii::$app->language == 'ar') : ?>
                        <img width="135"
                             src="https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo.png"/>
                    <?php else : ?>
                        <img width="135"
                             src="https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo-en.png"/>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        <div class="pull-left" style="padding: 5px;text-align: center;width: 12%;">
            <a style="color: #fff;font-size: 18px;" data-element="page-share" class="showhide" href="#"><i
                    class="fa fa-share-alt"></i></a>
        </div>
        <div class="pull-left" style="padding: 4px;">
            <a title="Menu" class="topnav-localicons showhide" data-element="main-menu" href="javascript:void(0);">☰</a>
        </div>
    </div>
<?php else : ?>
    <div class="top-menu">
        <div class="container">
            <div class="menu-logo">
                <a href="<?= Yii::$app->homeUrl ?>">
                    <?php if (Yii::$app->language == 'ar') : ?>
                        <img width="135" src="https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo.png"/>
                    <?php else : ?>
                        <img width="135"
                             src="https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo-en.png"/>
                    <?php endif; ?>
                </a>
            </div>
            <div class="pull-right">
                <div class="row">
                    <div class="links" style="color: #fff;">
                        <ul class="list-inline pull-right">
                            <li><?= Html::a(Yii::t('app', 'Home page'), Yii::$app->homeUrl) ?></li>
                            <li class="divider"></li>
                            <li><?= Html::a(Yii::t('app', 'About Us'), ['//' . Yii::t('app', 'About-Us-url')]) ?></li>
                            <li class="divider"></li>
                            <li><?= Html::a(Yii::t('app', 'Faqs'), ['//' . Yii::t('app', 'Faqs-url')]) ?></li>
                            <li class="divider"></li>
                            <li><?= Html::a(Yii::t('app', 'Contact Us'), ['//' . Yii::t('app', 'ContactUs-url')]) ?></li>
                            <li class="divider"></li>
                            <li><?= Html::a(Yii::t('app', 'Privacy'), ['//' . Yii::t('app', 'privacy-url')]) ?></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="menu-search">
                        <?= $this->render('//explore/_search', ['model' => new app\models\Post]) ?>
                    </div>
                    <ul class="list-inline">
                        <li class="pull-right"><a class="btn btn-primary" href="https://www.facebook.com/Hangshare"
                                                  target="_blank"><i
                                    style="margin: 3px;" class="fa fa-fw fa-facebook"></i></a></li>
                        <li class="pull-right"><a class="btn" href="https://www.twitter.com/hang_share" target="_blank"
                                                  style="color: #fff; background-color: #4099ff;"><i
                                    style="margin: 3px;"
                                    class="fa fa-twitter"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-menu">
        <div class="container">
            <div class="col-xs-7">
                <ul class="menu-category">
                    <?php foreach ($mainMenu as $mData) : ?>
                        <li <?php if (isset($mData['sub'])): ?><?php endif; ?>>
                            <a href="<?= Url::to(["//{$articlesurl}/{$mData['url']}"]) ?>"><?= $mData['title'] ?></a>
                            <?php if (isset($mData['sub'])): ?>
                                <ul class="supdropdown">
                                    <?php foreach ($mData['sub'] as $submenu) : ?>
                                        <li><?php echo Html::a($submenu['title'], Url::to(["//{$articlesurl}/{$mData['url']}/{$submenu['url']}"])); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if (Yii::$app->user->isGuest) : ?>
                <div class="menu-signup-login pull-right">
                    <?= Html::a(Yii::t('app', 'Login'), Url::to(['//login']), ['class' => 'btn btn-default']); ?>
                    <?= Html::a(Yii::t('app', 'Register'), Url::to(['//register']), ['class' => 'btn btn-primary']); ?>
                </div>
            <?php else :
                $username = empty(Yii::$app->user->identity->username) ? Yii::$app->user->identity->id : Yii::$app->user->identity->username;
                $imSm = Yii::$app->imageresize->thump(Yii::$app->user->identity->image, 50, 50, 'crop');
                ?>
                <div class="col-xs-5">
                    <div class="collapse navbar-collapse" id="w2-collapse">
                        <ul class="navbar-nav navbar-right nav" id="w4">
                            <li class="add-post">
                                <?= Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'Add a post'), Url::to(['//explore/post']), ['class' => '']); ?>
                            </li>
                            <li>
                                <a href="<?= Url::to(['//plan']); ?>"><span><i
                                            class="glyphicon glyphicon-star"></i></span>
                                    <?= Yii::t('app', 'Go Premium') ?>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a style="padding: 9px;" data-toggle="dropdown" href="#" class="dropdown-toggle">
                                    <img width="25" src="<?= $imSm; ?>">
                                    <span class="rela" style="top: 1px;">
                                        <?= Yii::$app->user->identity->name; ?>
                                        $<?= number_format(Yii::$app->user->identity->userStats->available_amount, 2) ?>
                                    </span>
                                    <b class="caret"></b></a>
                                <ul class="dropdown-menu" id="w5">
                                    <li><a tabindex="-1" href="<?= Url::to(['/user/view', 'id' => $username]); ?>"><i
                                                class="fa fa-fw fa-user hidden-xs"></i>
                                            <?= Yii::t('app', 'Profile') ?>
                                        </a></li>
                                    <li><a tabindex="-1" href="<?= Url::to(['/u/manage']); ?>">
                                            <i class="fa fa-fw fa-gear hidden-xs"></i>
                                            <?= Yii::t('app', 'Settings') ?>
                                        </a>
                                    </li>
                                    <li><a tabindex="-1" href="<?= Url::to(['/u/transfer']); ?>"><i
                                                class="fa fa-fw fa-gear hidden-xs"></i>
                                            <?= Yii::t('app', 'Money Transfer') ?>
                                        </a></li>
                                    <li class="dropdown-header"></li>
                                    <li class="divider"></li>
                                    <li><a tabindex="-1" data-method="post" href="<?= Url::to(['/site/logout']); ?>"><i
                                                class="fa fa-fw fa-sign-out hidden-xs"></i>
                                            <?= Yii::t('app', 'Logout') ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </header>
    <?php if (isset($this->params['prev'])) : ?>
        <div class="scroll-header">
            <div class="pull-left">
                <a href="/" class="navbar-brand">
                    <?php if (Yii::$app->language == 'ar') : ?>
                        <img width="135" src="https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo.png"/>
                    <?php else : ?>
                        <img width="135"
                             src="https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo-en.png"/>
                    <?php endif; ?>
                </a>

                <h3><?= $this->title ?></h3>
            </div>
            <div class="pull-right marg">
                <ul class="list-inline">
                    <?php if (isset($this->params['next'])) : ?>
                        <li><a class="btn btn-primary"
                               href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['//explore/view', 'id' => $this->params['next']->id, 'title' => $this->params['next']->title]); ?>"><span
                                    class="pull-left"><?= Yii::t('app', 'Next Post') ?></span><i
                                    class="fa fa-chevron-left pull-left"></i></a>
                        </li>
                    <?php endif; ?>
                    <li><a class="btn btn-primary js-share js-share-fasebook"
                           post-url="<?php echo Url::current(['src' => null], true); ?>" href="javascript:void(0);"><i
                                class="fa fa-fw fa-facebook" style="margin: 3px;"></i></a></li>
                    <li><a style="color: #fff; background-color: #4099ff;"
                           post-url="<?php echo Url::current(['src' => null], true); ?>" href="javascript:void(0);"
                           class="btn js-share js-share-twitter"><i class="fa fa-twitter" style="margin: 3px;"></i></a>
                    </li>
                    <li><a style="color: #fff; background-color: #e51717;"
                           post-url="<?php echo Url::current(['src' => null], true); ?>" href="javascript:void(0);"
                           class="btn js-share js-share-gpuls"><i class="fa fa-google-plus"
                                                                  style="margin: 3px;"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>