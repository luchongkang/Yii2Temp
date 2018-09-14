<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'urlManager'=>[
           'class' => 'yii\web\UrlManager',
           'enablePrettyUrl' => true,
           'showScriptName' => false,
           'suffix' => '',//后缀   
           'rules'=>[

           ],
         ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['debug'],
                    'logFile' => '@app/runtime/logs/debug.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                    'logVars' => [],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['wxpay'],
                    'logFile' => '@app/runtime/logs/wxpay.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                    'logVars' => [],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['alipay'],
                    'logFile' => '@app/runtime/logs/alipay.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                    'logVars' => [],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'login/error',
        ],
    ],
    'params' => $params,
];
