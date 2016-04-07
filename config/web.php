<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'hangshare-live',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'init'],
    'components' => [
//        'assetsAutoCompress' => [
//            'class' => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
//            'enabled' => true,
//            'jsCompress' => true,
//            'cssFileCompile' => true,
//            'jsFileCompile' => true,
//        ],


        'init' => [
            'class' => 'app\components\Init',
        ],
        'customs3' => [
            'class' => 'app\components\Customs3',
        ],
        'imageresize' => [
            'class' => 'app\components\Imageresize',
        ],
        'hitcounter' => [
            'class' => 'app\components\Hitcounter',
        ],
        'helper' => [
            'class' => 'app\components\Helper',
        ],
        'AwsEmail' => [
            'class' => 'app\components\AwsEmail',
        ],


        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\YiiAsset' => [
                    'js' => [
                        'yii.min.js'
                    ]
                ],
                'yii\web\JqueryAsset' => [
                    'js' => [
                        'jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        'js/bootstrap.min.js',
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        'css/bootstrap.min.css',
                    ]
                ],
            ]
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'rules' => [
                'مقالات/مقالات-متنوعة/<tag:[^*]+>' => 'explore/index',
                'مقالات/مقاطع-فيديو/<tag:[^*]+>' => 'explore/video',
                '/' => 'site/index',
                'مقالات' => 'explore/all',
                'users' => 'user/index',
                'register' => 'site/signup',
                'user/<id:[^*]+>' => 'user/view',
                'u/manage' => 'user/manage',
                'u/transfer' => 'user/transfer',
                'u/payment' => 'user/payment',
                'u/verify' => 'user/verify',
                'explore/<id:\d+>' => 'explore/red',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<id:\d+>/<title:[^*]+>' => '<controller>/view',
                '<controller:\w+>s' => '<controller>/index',
                'مقالات/مقاطع-فيديو' => 'explore/video',
                'مقالات/مقالات-متنوعة' => 'explore/index',
                'الأسئلة-الشائعة/<category:[^*]+>' => 'faq/index',
                'الأسئلة-الشائعة' => 'faq/index',
                'خريطة-الموقع' => 'site/sitemap',
                'تواصل-معنا' => 'site/contact',
                'شروط-الموقع' => 'site/privacy',
                'نبذة-عنا' => 'site/privacy',
                'request-password-reset' => 'site/reset',
                '<slug:.*?>' => 'explore/view',

            ],
        ],
        'request' => [
            'cookieValidationKey' => 'adsaew2343a',
        ],
        'cache' => array(
            'class' => 'yii\caching\MemCache',
            'servers' => array(
                array('host' => 'hangshare.jhis0g.cfg.use1.cache.amazonaws.com', 'port' => 11211, 'weight' => 60),
                array('host' => 'hangshare.jhis0g.cfg.use1.cache.amazonaws.com', 'port' => 11211, 'weight' => 40),
//                array('host' => 'localhost', 'port' => 11211, 'weight' => 60),
//                array('host' => 'localhost', 'port' => 11211, 'weight' => 40),
            ),
        ),
        'session' => [
            'class' => 'yii\web\Session',
            'cookieParams' => ['httponly' => true, 'lifetime' => 5184000],
            'timeout' => 5184000,
            'useCookies' => true,
            'name' => 'POSssjkOPj'
        ],
        'user' => [
            'identityClass' => 'app\models\User',
//            'enableAutoLogin' => true,
            'enableSession' => true,
            'authTimeout' => 2592000,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=main.cdb3bm2h7j5j.us-east-1.rds.amazonaws.com;port=3306;dbname=hangshare',
            'username' => 'hangshare',
            'password' => 'Khaled!23',
//            'dsn' => 'mysql:host=127.0.0.1;dbname=hangshare',
//            'username' => 'root',
//            'password' => '123456',
            'enableSchemaCache' => true,
            'schemaCache' => 'cache',
            'schemaCacheDuration' => 3600,
            'charset' => 'utf8',
        ],
    ],
    'params' => $params,
];


// configuration adjustments for 'dev' environment
//$config['bootstrap'][] = 'debug';
//$config['modules']['debug'] = [
//    'class' => 'yii\debug\Module',
//    'allowedIPs' => ['127.0.0.1', $_SERVER['REMOTE_ADDR']]
//];
//
//$config['bootstrap'][] = 'gii';
//$config['modules']['gii'] = [
//    'class' => 'yii\gii\Module',
//    'allowedIPs' => ['127.0.0.1', $_SERVER['REMOTE_ADDR']]
//];


return $config;
