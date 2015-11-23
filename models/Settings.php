<?php
namespace app\models;

use Yii;
use app\components\Enum;

/**
 * This is the model class for table "settings".
 */
final class Settings extends \app\base\BaseModel
{
	/**
	 * get settings by group
	 *
	 * @param $group
	 * @return list of settings
	 */
	public function byGroup($group = [])
	{
		$response = [];
		$settings = [];

		if(is_array($group) && empty($group)) {
			$sql = 
			'
				SELECT
					`settings`.* 
				FROM
					`settings`
			';

			$response = $this->sql($sql, Enum::ACTIVE_RECORD_TYPE_ARRAY);
		} else {

			$thisGroup = '';
			foreach ($group as $key => $value) {
				$thisGroup .= '\'' .  $value  . '\',';
			}

			$thisGroup = substr($thisGroup, 0, -1);

			$sql = 
			'
				SELECT
					`settings`.* 
				FROM
					`settings`
				WHERE
					`settings`.`groups` IN (' . $thisGroup . ')
			';

			$response = $this->sql($sql, Enum::ACTIVE_RECORD_TYPE_ARRAY);
		}

		foreach ($response as $key => $value) {
			$settings[$value['groups']][$value['keyword']] = $value['value'];
		}

		return $settings;
	}
}