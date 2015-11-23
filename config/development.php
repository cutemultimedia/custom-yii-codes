<?php
$params = require(__DIR__ . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'params.php');

$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'modules' => require(__DIR__ . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'modules.php'),
	'components' => [
		'request' => [
			'cookieValidationKey' => require(__DIR__ . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'cookie.php'),
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'urlManager' => [
			'class' => 'yii\web\UrlManager',
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => require(__DIR__ . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'urlManager.php')
		],
		'user' => [
			'identityClass' => 'app\components\UserIdentity',
			'enableAutoLogin' => true,
			'loginUrl' => ['user/login/index'],
		],
		'errorHandler' => [
			'errorAction' => 'errorhandler/default/error',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
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
			'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=databasename',
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
			'attributes' => [
				1014 => 'DHE-RSA-AES256-SHA:AES128-SHA',
			],
			'emulatePrepare' => true,
		],
		'redis' => [
			'class' => 'yii\redis\Connection',
			'hostname' => '127.0.0.1',
			'port' => 6379,
			'database' => 0,
		],
		'mobile' => [
			'class' => 'yii\ismobile\IsMobile',
		],
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = 'yii\debug\Module';

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;