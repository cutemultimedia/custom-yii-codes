<?php
namespace app\components;

use Yii;

/**
 * Redis class
 *
 * @author Erson Puyos <erson.puyos@gmail.com>
 * @since October 4, 2015
 */
final class Redis
{
	/**
	 *  Add a new record into redis database with expiration
	 *
	 * @param $key string key
	 * @param $value string | array
	 * @param $expiration in seconds (default 30 days)
	 * @return server response
	 */
	public static function add($key, $value, $expiration = 2592000)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		if(is_array($value)) {
			$value = json_encode($value);
		}

		return Yii::$app->redis->executeCommand('SETEX', [$key, $expiration, $value]);
	}

	/**
	 *  Add a new record into redis database
	 *
	 * @param $key string key
	 * @param $value string | array
	 * @return server response
	 */
	public static function addNoExpire($key, $value)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		if(is_array($value)) {
			$value = json_encode($value);
		}

		return Yii::$app->redis->executeCommand('SET', [$key, $value]);
	}

	/**
	 *  Update record into redis database with expiration
	 *
	 * @param $key string key
	 * @param $value string | array
	 * @param $expiration in seconds (default 30 days)
	 * @return server response
	 */
	public static function update($key, $value, $expiration = 2592000)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		return self::add($key, $value, $expiration);
	}

	/**
	 *  Update record into redis database
	 *
	 * @param $key string key
	 * @param $value string | array
	 * @return server response
	 */
	public static function updateNoExpire($key, $value)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		return self::addNoExpire($key, $value);
	}

	/**
	 * Delete a record into redis
	 *
	 * @param $key string key
	 * @return server response
	 */
	public static function delete($key)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		return Yii::$app->redis->executeCommand('DEL', [$key]);
	}

	/**
	 * Get a record
	 *
	 * @param $key string key
	 * @return server response
	 */
	public static function getValue($key)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		$response = Yii::$app->redis->executeCommand('GET', [$key]);

		if($response !== '' || !empty($response)) {
			$response = json_decode($response, true);
		}

		return $response;
	} 

	/**
	 * Update the key
	 *
	 * @param $oldKey old string key
	 * @param $newKey new string key
	 * @param $expiration in seconds (default 30 days)
	 * @return server response
	 */
	public static function updatekey($oldKey, $newKey, $expiration = 604800)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		self::setExpire($oldKey, $expiration);

		return Yii::$app->redis->executeCommand('RENAME', [$oldKey, $newKey]);
	}

	/**
	 * Update the key
	 *
	 * @param $oldKey old string key
	 * @param $newKey new string key
	 * @param $expiration in seconds (default 30 days)
	 * @return server response
	 */
	public static function updatekeyNoExpire($oldKey, $newKey)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		return Yii::$app->redis->executeCommand('RENAME', [$oldKey, $newKey]);
	}

	/**
	 * PING the server, check if the server is still online
	 *
	 * @return server response
	 */
	public static function ping()
	{
		return Yii::$app->redis->executeCommand('PING');
	}

	/**
	 * Check if the key exist
	 *
	 * @param $key string key
	 * @return server response
	 */
	public static function isKeyExists($key)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		return Yii::$app->redis->executeCommand('EXISTS', [$key]);
	}

	/**
	 * set the key expiration
	 *
	 * @param $key string key
	 * @param $expiration in seconds (default 7 days)
	 * @return server response
	 */
	public static function setExpire($key, $expiration = 604800)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		if($expiration == 0) {
			$expiration = 86400 * 100; //100 years to expire
		}

		return Yii::$app->redis->executeCommand('EXPIRE', [$key, $expiration]);
	}

	public static function getValuesByKeys($keys)
	{
		if(self::ping() != 1) {
			throw new Exception("Redis server is down", 1);
		}

		return Yii::$app->redis->executeCommand('MGET', $keys);
	}
}