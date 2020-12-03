<?php
require __DIR__ . '/const.php';

$params = require __DIR__ . '/params.php';


if (file_exists(__DIR__ . '/../../db.php')) {
    $db = require(__DIR__ . '/../../db.php');
} else {
    $db = require __DIR__ . '/db.php';
}


$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    ],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'scriptUrl' => 'http://platforam.allnewhomes.ru',
        ],
        'formatter' => [
            'defaultTimeZone'=>'Europe/Moscow',
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'd MMMM yyyy',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            //'useFileTransport' => false, //посылать почту
            'useFileTransport' => true, //не посылать почту
        ],



        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,


    ],
    'params' => $params,


];

if (GII) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
