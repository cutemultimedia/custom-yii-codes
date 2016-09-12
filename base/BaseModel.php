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
	 * @param array $values
	 * @return $pk id
	 */
	public function addRecord($values)
	{
		$pk = 0;

		// insert the new record
		$insert = Yii::$app->db->createCommand()->insert($this->tableName(), $values)->execute();

		//check if success
		if($insert == true) {
			//grab the pk value
			$pk = Yii::$app->db->getLastInsertID();
		}

		return $pk;
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
	 * @param array $conditions
	 * @param array $values
	 * @return bool
	 */
	public function updateRecord($conditions, $values)
	{
		$update = Yii::$app->db->createCommand()->update($this->tableName(), $values, $conditions)->execute();

		return $update;
	}

	/**
	 * Delete a record to specific table
	 *
	 *  $thisObject = new \app\models\ThisObject;
	 *	$result = $thisObject->deleteRecord(
	 *		[
	 *			'id' => 1
	 *		]
	 *	);
	 *
	 * @param array $conditions
	 * @return bool
	 */
	public function deleteRecord($conditions)
	{
		$delete = Yii::$app->db->createCommand()->delete($this->tableName(), $conditions)->execute();

		return $delete;
	}

	/**
	 * Search for record with spefic table
	 *
	 *  $thisObject = new \app\models\ThisObject;
	 *	$result = $thisObject->getRecordBase(
	 *		[
	 *			'id' => 1
	 *		],
	 *		Enum::ACTIVE_RECORD_TYPE_OBJECT,
	 *	);
	 *
	 * @param array $condition
	 * @param string $type "array|object" @Enum::ACTIVE_RECORD_TYPE_OBJECT
	 * @return record
	 */
	public function getRecord($condition, $type = 'array')
	{
		if($type == Enum::ACTIVE_RECORD_TYPE_OBJECT) {
			return $this->find()->where($condition)->one();
		} else if($type == Enum::ACTIVE_RECORD_TYPE_ARRAY) {
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
	 *		Enum::ACTIVE_RECORD_TYPE_OBJECT,
	 *	);
	 *
	 * @param string $sql
	 * @param string $sql "array|object" @Enum::ACTIVE_RECORD_TYPE_OBJECT
	 * @return record
	 */
	public function sql($sql, $type = 'array')
	{
		if($type == Enum::ACTIVE_RECORD_TYPE_OBJECT) {
			return $this->findBySql($sql)->all();
		} else if($type == Enum::ACTIVE_RECORD_TYPE_ARRAY) {
			return $this->findBySql($sql)->asArray()->all();
		}

		return NULL;
	}
}