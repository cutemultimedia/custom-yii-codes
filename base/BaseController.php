<?php
namespace app\base;

use Yii;
use app\models\Settings;

/**
 * BaseController is a base class to inherate in all child controller
 *
 * @author Erson Puyos <erson.puyos@gmail.com>
 * @since October 4, 2015
 */
abstract class BaseController extends \yii\web\Controller
{
	/**
	 * Setting the layout to display
	 *
	 * @param $layout
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;
	}

	/**
	 * Return the system epoch time, value will be using in the following:
	 * - date_created
	 * - date_updated
	 * - date_activated
	 * @return system date
	 */
	public function epoch()
	{
		return (string)time();
	}

	/**
	 * Generate random characters
	 * @param  $min
	 * @param  $max
	 * @param  [$charset='']
	 * @return random characters
	 */
	public function randomCharacters($min, $max, $charset = '')
	{
		if(empty($charset)) {
			$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		}
		$char = '';
		$end = mt_rand($min, $max);
		for ($start=0; $start < $end; $start++) {
			$char .= $charset[(mt_rand(0, (strlen($charset) -1)))];
		}
		return $char;
	}

	/**
	 *	Responsible for cleaning the users input before we put into database
	 *
	 * @param $input
	 * @return clean input
	 */
	public function cleanInput($input)
	{
		return addslashes(trim($input));
	}

	/**
	 *	Get the user agent of the visitor
	 */
	public function userAgent()
	{
		$agent = 'No-Agent';

		if(isset($_SERVER['HTTP_USER_AGENT'])) {
			$agent = $_SERVER['HTTP_USER_AGENT'];
		}

		return $agent;
	}

	/**
	 *	Get the user ip of the visitor
	 */
	public function ip()
	{
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$exploded = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			return $exploded[0];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	/**
	 * Filter HTTPS
	 *
	 * @param array $actions
	 */
	public function filterHttps($actions)
	{
		if(is_array($actions)) {
			if($actions[0] == '*' && isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] == 80) {
				$url = 'https://' . $_SERVER['SERVER_NAME'] . Yii::$app->request->url;
				$this->redirect($url);
			} else if(in_array(Yii::$app->controller->action->id, $actions) && isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] == 80) {
				$url = 'https://' . $_SERVER['SERVER_NAME'] . Yii::$app->request->url;
				$this->redirect($url);
			}
		}
	}

	/**
	 * Detect if the visitor is using mobile
	 *
	 * @return bool
	 */
	public function isMobile()
	{
		return Yii::$app->mobile->CheckMobile();
	}

	/**
	 * Method Description : Standard json response to web/mobile
	 *
	 * @param bool status
	 * @param epoch dateRequest
	 * @param array data
	 *
	 * @return array response
	 */
	public function jsonResponse($status, $dateRequest, $data = [])
	{
		$jsonResponse = [
			'status' => $status,
			'dateRequest' => $dateRequest,
			'payload' => $data,
		];

		return $jsonResponse;
	}

	/**
	 * Generate the users hash
	 *
	 * @return hash
	 */
	public function hash()
	{
		return  substr(sha1(time()), 12, 32);
	}

	/**
	 * Return the number formating of the system with decimal value
	 * 
	 * @param $number
	 * @param $decimal
	 * @return number format
	 */
	public function numberFormat($number, $decimal = 2)
	{
		return number_format($number, $decimal);
	}

	/**
	 * Get the settings by passing group
	 *
	 * @param $group array
	 * @return list of settings
	 */
	public function settings($group = [])
	{
		$settingsTable = new Settings;

		$settingsList = $settingsTable->byGroup($group);

		return $settingsList;
	}

	/**
	 * Check if input is number
	 *
	 * @param $input
	 * @return bool
	 */
	public function isNumber($input)
	{
		if(is_numeric($input)) {
			return true;
		}

		return false;
	}

	/**
	 * A function that will elemenate the long lines of coding to get the parameter.
	 * Yii::$app->params['user']['securityKey'];
	 *
	 * If there will be a nested parameter we defined, we can pull the data by calling this function, and
	 * used the character "." (period), example below
	 *
	 * 1. $this->params('data')
	 * 2. $this->params('data.mysample');
	 *
	 * @param parameter $attribute to pull
	 * @return value of params
	 */
	public function params($attribute)
	{
		$s = explode(".", $attribute);
		$param = Yii::$app->params;

		while(count($s)) {
			$param = $param[array_shift($s)];
		}

		return $param;
	}

	/**
	 * user security key
	 * @return settings value
	 */
	public function userSecurityKey()
	{
		return $this->params('user.securityKey');
	}
}