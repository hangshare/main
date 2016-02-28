﻿<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'hangshare-live',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','init'],
    'components' => [
        'init' => [
            'class' => 'app\components\Init',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'rules' => [
                'مقالات/مقالات-متنوعة/<tag:[^*]+>' => 'explore/index',
                'مقالات/مقاطع-فيديو/<tag:[^*]+>' => 'explore/video',
                '/' => 'site/index',
                'مقالات' => 'explore/all',
                'user/<id:\d+>' => 'user/view',
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

                '<slug:.*?>' => 'explore/view',

            ],
        ],
        'request' => [
            'cookieValidationKey' => 'adsaew2343a',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'cookieParams' => ['httponly' => true, 'lifetime' => 5184000],
            'timeout' => 5184000,
            'useCookies' => true,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
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
//            'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=hangshare',
//            'username' => 'root',
//            'password' => '',
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
