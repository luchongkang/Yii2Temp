<?php
/**开发环境配置 **/
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cacheFile' => [
            'class' => 'yii\caching\FileCache',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        
    ],
];
