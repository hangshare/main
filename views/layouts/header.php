<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;

?>
<?php
$this->registerLinkTag(['rel' => 'canonical', 'href' => Url::current(['src' => null], true)]);
$this->registerLinkTag(['rel' => 'alternate', 'hreflang' => 'ar', 'href' => Url::current(['src' => null], true)]);
NavBar::begin([
    'brandLabel' => '<img src="https://hangshare.media.s3.amazonaws.com/hangshare-logo.png" width="135" />',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse',
    ],
]);
$menuItems_left = [
    ['label' => '<span class="btn font-16">تصفح</span>', 'url' => ['//مقالات']],
//    ['label' => '<span class="btn font-16">عام</span>', 'url' => ['مقالات/مقالات-متنوعة']],
    ['label' => '<span class="btn font-16">مقاطع فيديو</span>', 'url' => ['مقالات/مقاطع-فيديو']],
    ['label' => '<span class="btn font-16">هل لديك استفسار ؟</span>', 'url' => ['//الأسئلة-الشائعة']],
];
if (Yii::$app->user->isGuest) {
    $menuItems_right[] = ['label' => '<span class="btn btn-primary">حساب جديد</span>', 'url' => ['//plan']];
    $menuItems_right[] = ['label' => '<span class="btn">تسجيل الدخول</span>', 'url' => ['//login']];
} else {

    $menuItems_right[] = [
        'label' => '<span class="addd"><i class="glyphicon glyphicon-plus"></i> أضف موضوع</span>',
        'url' => ['/explore/post'],
        'options' => ['class' => 'add-post']
    ];

    if (Yii::$app->user->identity->plan) {
        $menuItems_right[] = [
            'label' => '<span class="addd"><i class="glyphicon glyphicon-star userplan gold"></i>معلومات حسابك الذهبي </span>',
            'url' => ['//u/gold'],
            'options' => ['class' => 'planholder']
        ];
    } else {
        $menuItems_right[] = [
            'label' => '<span class="addd"><i class="glyphicon glyphicon-star userplan"></i> الترقية الى الحساب الذهبي</span>',
            'url' => ['//plan'],
            'options' => ['class' => 'planholder']
        ];
    }

    $username = empty(Yii::$app->user->identity->username) ? Yii::$app->user->identity->id : Yii::$app->user->identity->username;
    $menuItems_right[] = [
        'label' => '<span class="img-options"><img src="' . Yii::$app->imageresize->thump(Yii::$app->user->identity->image, 50, 50, 'crop') . '" /></span>',
        'items' => [
            ['label' => '<i class="fa fa-fw fa-user hidden-xs"></i> الصفحة الشخصية', 'url' => ['/user/view', 'id' => $username]],
            ['label' => '<i class="fa fa-fw fa-gear hidden-xs"></i> الإعدادات', 'url' => ['/u/manage']],
            ['label' => '<i class="fa fa-fw fa-gear hidden-xs"></i> طرق تحويل النقود', 'url' => ['/u/transfer']],
            ['label' => '<li class="divider">'],
            [
                'label' => '<i class="fa fa-fw fa-sign-out hidden-xs"></i> تسجيل الخروج',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']
            ],
        ]
    ];
}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'encodeLabels' => false,
    'items' => $menuItems_left,
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => $menuItems_right,
]);
NavBar::end();

if (empty($this->title)) {
    $this->title = 'انشر مقالات واحصل على المال - ربح اكيد عبر الانترنت';
}
?>

<?php if (isset($this->params['prev'])) : ?>
    <div class="scroll-header">
        <div class="pull-left">
            <a href="/" class="navbar-brand"><img width="135"
                                                  src="https://hangshare.media.s3.amazonaws.com/hangshare-logo.png"></a>

            <h3><?= $this->title ?></h3>
        </div>
        <div class="pull-right marg">
            <ul class="list-inline">
                <?php if (isset($this->params['next'])) : ?>
                    <li><a class="btn btn-primary"
                           href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['//explore/view', 'id' => $this->params['next']->id, 'title' => $this->params['next']->title]); ?>"><span
                                class="pull-left"> الموضوع التالي</span><i class="fa fa-chevron-left pull-left"></i></a>
                    </li>
                <?php endif; ?>
                <li><a class="btn btn-primary js-share js-share-fasebook"
                       post-url="<?php echo Url::current(['src' => null], true); ?>" href="javascript:void(0);"><i
                            class="fa fa-fw fa-facebook" style="margin: 3px;"></i></a></li>
                <li><a style="color: #fff; background-color: #3E4347;"
                       post-url="<?php echo Url::current(['src' => null], true); ?>" href="javascript:void(0);"
                       class="btn js-share js-share-twitter"><i class="fa fa-twitter" style="margin: 3px;"></i></a></li>
                <li><a style="color: #fff; background-color: #e51717;"
                       post-url="<?php echo Url::current(['src' => null], true); ?>" href="javascript:void(0);"
                       class="btn js-share js-share-gpuls"><i class="fa fa-google-plus" style="margin: 3px;"></i></a>
                </li>
            </ul>
        </div>
    </div>
<?php endif; ?>
