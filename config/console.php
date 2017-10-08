<?php
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
$params = require(__DIR__ . '/params.php');
return [
    'id' => 'hangshare-live-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
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

        'cache'=>array(
            'class' => 'yii\caching\MemCache',
            'servers'=>array(
                array('host' => 'microcache.jhis0g.cfg.use1.cache.amazonaws.com', 'port' => 11211, 'weight' => 60),
                array('host' => 'microcache.jhis0g.cfg.use1.cache.amazonaws.com', 'port' => 11211, 'weight' => 40),

//                array('host'=>'127.0.0.1', 'port'=>11211, 'weight'=>60),
            ),
        ),

        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=main.cdb3bm2h7j5j.us-east-1.rds.amazonaws.com;port=3306;dbname=hangshare',
            'username' => 'hangshare',
            'password' => 'Khaled!23',
//            'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=hangshare',
//            'username' => 'root',
//            'password' => '123456',
            'enableSchemaCache' => true,
            'schemaCache' => 'cache',
            'schemaCacheDuration' => 3600,
            'charset' => 'utf8',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
