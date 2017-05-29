<?php
use yii\helpers\Url;
use app\models\Category;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

if (empty($this->title)) {
    $this->title = Yii::t('app', 'General Page Title');;
}
$canonical = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$this->registerLinkTag(['rel' => 'canonical', 'href' => $canonical]);

$mainMenu = Yii::$app->cache->get('media-data-' . Yii::$app->language);
if ($mainMenu == false) {
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
    Yii::$app->cache->set($mainMenu, 'media-data-' . Yii::$app->language, 3600);
}
?>
<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<header>
    <?php if (!(Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index')) : ?>
        <div style="
                width: 100%;
                height: auto;
                min-height: 270px;
                background-size: cover;
                background: url(<?= $this->ogImage ?>);
                background-position: center center;
                ">
            <div class="container">
                <div class="adshead"
                     style="<?php if (Yii::$app->helper->isMobile()): ?> padding:50px 20px 20px; <?php else : ?> padding:10px; <?php endif; ?> width:100%; z-index: 10000; height:auto;min-height: 200px;">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-6288640194310142"
                         data-ad-slot="6189074110"
                         data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->helper->isMobile()): ?>
        <ul id="main-menu" class="mainmenu">
            <?php foreach ($mainMenu as $mData) : ?>
                <li>
                    <a href="<?= Yii::$app->urlManager->createUrl(["//{$articlesurl}/{$mData['url']}"]) ?>"><?= $mData['title'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (Yii::$app->user->isGuest) : ?>
            <ul id="non-user-menu" class="mainmenu">
                <li><?= Html::a(Yii::t('app', 'Login'), Yii::$app->urlManager->createUrl(['//login'])); ?></li>
                <li><?= Html::a(Yii::t('app', 'Register'), Yii::$app->urlManager->createUrl(['//register'])); ?></li>
                <li>
                    <hr/>
                </li>
                <?php if (Yii::$app->language == 'en'): ?>
                    <li><?= Html::a('عربي', 'javascript:void(0);', ['data-url' => '/', 'rel' => 'nofollow', 'id' => 'changeLang']) ?></li>
                <?php else: ?>
                    <li><?= Html::a('English', 'javascript:void(0);', ['data-url' => '/en/', 'rel' => 'nofollow', 'id' => 'changeLang']) ?></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
        <ul id="page-share" class="mainmenu">
            <li><a class="btn btn-primary js-share js-share-fasebook" href="javascript:void(0);"
                   post-url="<?= $actual_link; ?>">
                    <i style="margin: 3px;" class="fa fa-fw fa-facebook"></i>
                    <?= Yii::t('app', 'Facebook') ?>
                </a>
            <li><a class="btn js-share js-share-twitter" post-url="<?= $actual_link; ?>"
                   href="javascript:void(0);" style="color: #fff; background-color: #4099ff;">
                    <i style="margin: 3px;" class="fa fa-twitter"></i>
                    <?= Yii::t('app', 'twitter') ?>
                </a></li>
            <li>
                <a class="btn js-share js-share-gpuls" post-url="<?= $actual_link; ?>"
                   href="javascript:void(0);" style="color: #fff; background-color: #e51717;">
                    <i style="margin: 3px;" class="fa fa-google-plus"></i>
                    <?= Yii::t('app', 'Google+') ?>
                </a>
            </li>
            <li>
                <a class="btn" href="whatsapp://send?text=<?= $actual_link; ?>"
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
                <li><a href="<?= Yii::$app->urlManager->createUrl(['/user/view', 'id' => $username]); ?>"><i
                                class="fa fa-fw fa-user hidden-xs"></i>
                        <?= Yii::t('app', 'Profile') ?>
                    </a></li>
                <li><a href="<?= Yii::$app->urlManager->createUrl(['/u/manage']); ?>">
                        <i class="fa fa-fw fa-gear hidden-xs"></i>
                        <?= Yii::t('app', 'Settings') ?>
                    </a>
                </li>
                <li><a href="<?= Yii::$app->urlManager->createUrl(['/u/transfer']); ?>"><i
                                class="fa fa-fw fa-gear hidden-xs"></i>
                        <?= Yii::t('app', 'Money Transfer') ?>
                    </a></li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createUrl(['/u/report']); ?>">
                        <i class="fa fa-fw fa-gear hidden-xs"></i><?= Yii::t('app', 'Money Report') ?>
                    </a>
                </li>
                <li class="dropdown-header"></li>
                <li class="divider"></li>
                <li><a data-method="post" href="<?= Yii::$app->urlManager->createUrl(['/site/logout']); ?>"><i
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
            <div class="pull-left" style="padding: 10px 0;">
                <a data-element="search-header-mobile" class="showhide" href="javascript:void(0);"
                   style="color: #fff;"><i
                            class="glyphicon glyphicon-search"></i></a>
            </div>
            <div class="pull-right" style="padding: 4px 12px">
                <a title="Menu" class="topnav-localicons showhide" data-element="main-menu"
                   href="javascript:void(0);">☰</a>
            </div>
        </div>
    <?php else : ?>

    <div class="bottom-menu">
        <div class="container">
            <ul class="menu-category pull-left">
                <li>
                    <a href="<?= Yii::$app->homeUrl ?>">
                        <?php if (Yii::$app->language == 'ar') : ?>
                            <img width="135"
                                 src="https://s3-eu-west-1.amazonaws.com/hangshare-media/logo-ar.png"/>
                        <?php else : ?>
                            <img src="https://s3-eu-west-1.amazonaws.com/hangshare-media/logo-en.png"/>
                        <?php endif; ?>
                    </a>
                </li>
                <?php foreach ($mainMenu as $mData) : ?>
                    <li <?php if (isset($mData['sub'])): ?><?php endif; ?>>
                        <a href="<?= Yii::$app->urlManager->createUrl(["//{$articlesurl}/{$mData['url']}"]) ?>"><?= $mData['title'] ?></a>
                        <?php if (isset($mData['sub'])): ?>
                            <ul class="list-unstyled supdropdown">
                                <?php foreach ($mData['sub'] as $submenu) : ?>
                                    <?php $subu = Yii::$app->urlManager->createUrl("//{$articlesurl}/{$mData['url']}/{$submenu['url']}"); ?>
                                    <li><?php echo Html::a($submenu['title'], $subu, ['class' => 'nota']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                <?php if (Yii::$app->language == 'en'): ?>
                    <li><?= Html::a('عربي', 'javascript:void(0);', ['data-url' => '/', 'rel' => 'nofollow', 'id' => 'changeLang']) ?></li>
                <?php else: ?>
                    <li><?= Html::a('English', 'javascript:void(0);', ['data-url' => '/en/', 'rel' => 'nofollow', 'id' => 'changeLang']) ?></li>
                <?php endif; ?>
            </ul>
            <div class="pull-right w-280">
                <?= Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'Add a post'), ['//explore/post'], [
                    'class' => 'btn btn-primary pull-left',
                    'style' => 'margin-top:13px'
                ]); ?>
                <?php if (Yii::$app->user->isGuest) : ?>
                    <div class="menu-signup-login pull-left" style="margin: 13px 6px; ">
                        <?= Html::a(Yii::t('app', 'Become a Writer'), Yii::$app->urlManager->createUrl(['//login']), ['class' => 'btn btn-default', 'rel' => 'nofollow']); ?>
                    </div>
                <?php else :
                    $username = empty(Yii::$app->user->identity->username) ? Yii::$app->user->identity->id : Yii::$app->user->identity->username;
                    $imSm = Yii::$app->imageresize->thump(Yii::$app->user->identity->image, 50, 50, 'crop');
                    ?>
                    <div class="collapse navbar-collapse pull-left" id="w2-collapse">
                        <ul class="navbar-nav navbar-right nav" id="w4">
                            <li class="dropdown">
                                <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                    <img width="25" src="<?= $imSm; ?>">
                                    <span class="rela" style="top: 1px;">
                                        $<?= number_format(Yii::$app->user->identity->userStats->available_amount, 2) ?>
                                    </span>
                                    <b class="caret"></b></a>
                                <ul class="dropdown-menu" id="w5">
                                    <li><a class="nota" tabindex="-1"
                                           href="<?= Yii::$app->urlManager->createUrl(['/user/view', 'id' => $username]); ?>"><i
                                                    class="fa fa-fw fa-user hidden-xs"></i>
                                            <?= Yii::t('app', 'Profile') ?>
                                        </a></li>
                                    <li><a class="nota" tabindex="-1"
                                           href="<?= Yii::$app->urlManager->createUrl(['/u/manage']); ?>">
                                            <i class="fa fa-fw fa-gear hidden-xs"></i>
                                            <?= Yii::t('app', 'Settings') ?>
                                        </a>
                                    </li>
                                    <li><a class="nota" tabindex="-1"
                                           href="<?= Yii::$app->urlManager->createUrl(['/u/transfer']); ?>"><i
                                                    class="fa fa-fw fa-gear hidden-xs"></i>
                                            <?= Yii::t('app', 'Money Transfer') ?>
                                        </a></li>
                                    <li>
                                        <a class="nota" tabindex="-1"
                                           href="<?= Yii::$app->urlManager->createUrl(['/u/report']); ?>">
                                            <i class="fa fa-fw fa-gear hidden-xs"></i><?= Yii::t('app', 'Money Report') ?>
                                        </a>
                                    </li>

                                    <li><a class="nota" tabindex="-1" data-method="post"
                                           href="<?= Yii::$app->urlManager->createUrl(['/site/logout']); ?>"><i
                                                    class="fa fa-fw fa-sign-out hidden-xs"></i>
                                            <?= Yii::t('app', 'Logout') ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php if (!(Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index')) : ?>
        <div class="top-menu">
            <div class="container">
                <?php
                echo Breadcrumbs::widget([
                    'itemTemplate' => "<li>{link}</li>\n",
                    'options' => ['class' => 'brcr'],
                    'homeLink' => [
                        'label' => Yii::t('yii', 'Home'),
                        'url' => Yii::$app->homeUrl,
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
                ]);
                ?>
                <div class="pull-right" style="margin-top: -5px;">
                    <?= $this->render('//explore/_search', ['model' => new app\models\Post]) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</header>
<?php if (isset($this->params['prev'])) : ?>
    <div class="scroll-header">
        <div class="pull-left">
            <a href="/" class="navbar-brand">
                <?php if (Yii::$app->language == 'ar') : ?>
                    <img width="135" src="https://s3-eu-west-1.amazonaws.com/hangshare-media/hangshare-logo.png"/>
                <?php else : ?>
                    <img src="https://s3-eu-west-1.amazonaws.com/hangshare-media/logo-en.png"/>
                <?php endif; ?>
            </a>

            <h3><?= $this->title ?></h3>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>


