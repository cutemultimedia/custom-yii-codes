<?php
namespace app\base;

use Yii;
use app\components\Enum;

/**
 * BaseModel is a base class to manipulate data in database
 *
 * @author Erson Puyos <erson.puyos@gmail.com>
 * @since October 4, 2015
 */
abstract class BaseModel extends \yii\db\ActiveRecord
{
	/**
	 * Adding new record
	 *
	 *  $thisObject = new \app\models\ThisObject;
	 *	$result = $thisObject->addRecord(
	 *		[
	 *			'name' => 'John Due',
	 *			'email' => 'sample@domain.com',
	 *		]
	 *	);
	 *
	 * @param array $params
	 * @return pk id
	 */
	public function addRecord($params)
	{
		foreach ($params as $key => $value) {
			if($value != '') {
				$this->$key = $value;
			}
		}

		$this->insert();
		return $this->id;
	}

	/**
	 * Update the record of the table that represent the model object
	 *
	 *  $thisObject = new \app\models\ThisObject;
	 *	$result = $thisObject->updateRecord(
	 *		[
	 *			'id' => '3560048159',
	 *		],
	 *		[
	 *			'password' => 'new password',
	 *			'email' => 'new@email.com',
	 *		]
	 *	);
	 *
	 * @param array $condition
	 * @param array $params
	 * @return bool
	 */
	public function updateRecord($condition, $params)
	{
		$record = $this->find()->where($condition)->one();

		foreach ($params as $key => $value) {
			$record->$key = $value;
		}

		return $record->save();
	}

	/**
	 * Add a record to specific table
	 *
	 *
	 *  $thisObject = new \app\models\ThisObject;
	 *	$result = $thisObject->deleteRecord(
	 *		[
	 *			'id' => 1
	 *		]
	 *	);
	 *
	 * @param array $condition
	 * @return bool
	 */
	public function deleteRecord($condition)
	{
		$record = $this->find()->where($condition)->one();

		if($record !== NULL) {
			return $record->delete();
		}

		return false;
	}

	/**
	 * Search for record with spefic table
	 *
	 *  $thisObject = new \app\models\ThisObject;
	 *	$result = $thisObject->getRecordBase(
	 *		[
	 *			'id' => 1
	 *		],
	 *		Enum::ARTIVE_RECORD_TYPE_OBJECT,
	 *	);
	 *
	 * @param array $condition
	 * @param string $type "array|object" @Enum::ARTIVE_RECORD_TYPE_OBJECT
	 * @return record
	 */
	public function getRecord($condition, $type = 'array')
	{
		if($type == Enum::ARTIVE_RECORD_TYPE_OBJECT) {
			return $this->find()->where($condition)->one();
		} else if($type == Enum::ARTIVE_RECORD_TYPE_ARRAY) {
			return $this->find()->where($condition)->asArray()->one();
		}

		return NULL;
	}

	/**
	 * Search record with custom SQL 
	 *
	 *  $thisObject = new \app\models\ThisObject;
	 *	$result = $thisObject->sql(
	 *		$sql,
	 *		Enum::ARTIVE_RECORD_TYPE_OBJECT,
	 *	);
	 *
	 * @param string $sql
	 * @param string $sql "array|object" @Enum::ARTIVE_RECORD_TYPE_OBJECT
	 * @return record
	 */
	public function sql($sql, $type = 'array')
	{
		if($type == Enum::ARTIVE_RECORD_TYPE_OBJECT) {
			return $this->findBySql($sql)->all();
		} else if($type == Enum::ARTIVE_RECORD_TYPE_ARRAY) {
			return $this->findBySql($sql)->asArray()->all();
		}

		return NULL;
	}
}