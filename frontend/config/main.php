<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */

	   'i18n' => [
	       'translations' => [
	           'ticketinputform' => [
            		'class' => 'yii\i18n\PhpMessageSource',
			         'sourceLanguage' => 'en',
			         'fileMap' => [
	                   'ticketinputform' => 'TicketInputForm.php',
		              ],
		      ],
	       ],
        ],


    ],
    //---Application modules, vpr,190627
    'modules' => [
        'facilityeq' => [
            'class' => 'frontend\modules\facilityeq\Module',
        ],
        'employeeeq' => [
            'class' => 'frontend\modules\employeeeq\Module',
        ],
        'meter' => [
            'class' => 'frontend\modules\meter\MeterModule',    
        ],
        'gridview' => ['class' => 'kartik\grid\Module'],
    ],
    'params' => $params,
];
