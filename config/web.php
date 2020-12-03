<?php
require __DIR__ . '/const.php';

$params = require __DIR__ . '/params.php';

if (file_exists(__DIR__ . '/../../db.php')) {
    $db = require(__DIR__ . '/../../db.php');
} else {
    $db = require __DIR__ . '/db.php';
}

$config = [
    'language' => 'ru-RU',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    ],
    'timeZone' => 'Europe/Moscow',
    'defaultRoute' => DEFAULT_ROUTE,
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'formatter' => [
            'defaultTimeZone'=>'Europe/Moscow',
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'd MMMM yyyy',
        ],
        'request' => [
            'cookieValidationKey' => '5DwWXaBGAkTnXrrAZLcvAN7SJvW5O7qy',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\user\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/login'],
            'authTimeout' => $params['time_auth'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            //'useFileTransport' => false, //посылать почту
            'useFileTransport' => true, //не посылать почту
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'page'=>'site/index',
                'page/<id:\d+>'=>'site/page',
            ],
        ],
    ],
    'params' => $params,
];

if (GII) {

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
