<?php
defined('WR') or define('WR', dirname(__FILE__));
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * Load the Environment
 */
require_once(WR . DS . '..' . DS . 'Environment.php');

/**
 * The environment to load
 *
 * @var $environment
 */
$environment = Environment::LOCALHOST;

if($environment === Environment::DEVELOPMENT || $environment === Environment::STAGING) {
	$users = array('my-project' => 'letmein');

	if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
		header('WWW-Authenticate: Basic realm="Restricted area"');
		header('HTTP/1.0 401 Unauthorized');

		die('');
	} else {
		if(!isset($users[$_SERVER['PHP_AUTH_USER']])) {
			header('WWW-Authenticate: Basic realm="Restricted area"');
			header('HTTP/1.0 401 Unauthorized');

			die('Wrong Credentials!');
		} else if($users[$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW']) {
			header('WWW-Authenticate: Basic realm="Restricted area"');
			header('HTTP/1.0 401 Unauthorized');

			die('Wrong Credentials!');
		}
	}
}

$debug = TRUE;
$env = 'dev';

if($environment === Environment::STAGING || $environment === Environment::PRODUCTION) {
	$debug = TRUE;
	$env = 'production';
}

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', $debug);
defined('YII_ENV') or define('YII_ENV', $env);

require_once(WR . DS . '..' . DS . 'vendor' . DS . 'autoload.php');
require_once(WR . DS . '..' . DS . 'vendor' . DS . 'yiisoft' . DS . 'yii2' . DS . 'Yii.php');

$config = require_once(__DIR__ . DS . '..' . DS . 'config' . DS . $environment . '.php');

(new yii\web\Application($config))->run();