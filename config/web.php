<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'app\bootstrap\AppBootstrap'],
    'defaultRoute' => 'flight/index',
    'name' => 'DAKS',
    'on beforeAction' => function ($event) {
        $locale = Yii::$app->session->get('language');
        if ($locale != null)
            Yii::$app->language = $locale;
        else
            Yii::$app->language = 'ru';
    },
    'controllerMap' => [
        'payment-type-client' => [
            'class' => 'app\controllers\BookController',
            'tableName' => 'payment_type_client',
            'headerLabel' => 'Тип оплаты клиента',
        ],
        'payment-type-driver' => [
            'class' => 'app\controllers\BookController',
            'tableName' => 'payment_type_driver',
            'headerLabel' => 'Тип оплаты водителя',
        ],
    ],
    'components' => [
    'i18n' => [
        'translations' => [
            'app*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                //'basePath' => '@app/messages',
                //'sourceLanguage' => 'en-US',
                'basePath' => '@app/messages',
            ],
        ],
    ],
    'request' => [
        // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        'cookieValidationKey' => 'GhAcZ2j2hHCv9-XMRK1mi0wYRu29SWwu',
    ],
    'myCache' => [
        'class' => 'app\components\MyCache',
    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'user' => [
        'identityClass' => 'app\models\User',
        'enableAutoLogin' => true,
    ],
    'urlManager' => [
        'class' => 'yii\web\UrlManager',
        'showScriptName' => false,
        'enablePrettyUrl' => true,
        'rules' => [
            'flight/upddoc/<id>' => 'flight/upd-doc',
            'sp' => 'site/sp',
            // 'flight/print-template/<id>/<template_id>/<signature>' => 'flight/print-template',
        ],
    ],
    'formatter' => [
        'dateFormat' => 'php:m/d/Y',
        'datetimeFormat' => 'php:m/d/Y H:i',
        'decimalSeparator' => ',',
        'thousandSeparator' => ' ',
        'currencyCode' => '$',
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        // send all mails to a file by default. You have to set
        // 'useFileTransport' to false and configure a transport
        // for the mailer to send real emails.
        'useFileTransport' => false,
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.mail.ru',
            'username' => 'info@daks-group.ru',
            'password' => '9PPWiVC0PkUUuzC4pu5f',
            'port' => '465',
            'encryption' => 'ssl',
        ],
        // 'transport' => [
        //     'class' => 'Swift_SmtpTransport',
        //     'host' => 'smtp.yandex.ru',
        //     // 'username' => 'hrsoft@mmbusiness.ru',
        //     // 'password' => 'mmbusiness95',
        //     // 'username' => 'hh.notify@yandex.ru',
        //     'username' => 'a.ntfier@yandex.ru',
        //     // 'password' => 'jw*in29nAlw',
        //     'password' => 'lwlofvqcvwlgrvmv',
        //     'port' => '465',
        //     'encryption' => 'ssl',
        // ],
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
                'categories' => ['_error'],
                'logFile' => '@app/runtime/logs/_error.log',
            ],
            [
                'class' => 'yii\log\FileTarget',
                'categories' => ['test'],
                'logFile' => '@app/runtime/logs/test.log',
            ],
        ],
    ],
    'db' => require(__DIR__ . '/db.php'),
    /*
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
        ],
    ],
    */
    ],
    'modules' => [
    'excel' => [
        'class'  => 'uranum\excel\Module',
        'params' => [
            'uploadPath' => 'uploads', // the path relative to the root
            'fileName'   => 'export',
            'extensions' => 'xls, xlsx',
        ],
    ],
    'gridview' =>  [
        'class' => '\kartik\grid\Module'
    ],
    'api' => [
        'class' => 'app\modules\api\Api',
    ],
    'mobile' => [
        'class' => 'app\modules\mobile\Mobile',
    ],
    'dynagrid' =>  [
        'class' => '\kartik\dynagrid\Module',
        // other settings (refer documentation)
    ],
],

    'language' => 'ru-RU',
    'params' => $params,
];

//if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
        //'allowedIPs' => ['212.20.41.22', '217.197.241.210'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'generators' => [
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => ['My' => '@app/vendor/yiisoft/yii2-gii/generators/crud/admincolor']
            ]
        ],
        'allowedIPs' => ['212.20.41.22', '217.197.241.210'],
    ];
//}

return $config;
