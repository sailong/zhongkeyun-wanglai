<?php

/**
 * This is the model class for table "activity_apply_log".
 *
 * The followings are the available columns in table 'activity_apply_log':
 * @property string $id
 * @property string $activity_id
 * @property string $object_id
 * @property string $member_id
 * @property integer $source
 * @property integer $success
 * @property string $extra
 * @property string $create_time
 */
class ActivityJoinLog extends CActiveRecord
{
	
	/**
	 * 操作类型1报名2取消报名
	 * @var unknown
	 */
	const OPERATION_APPLY = 1;
	const OPERATION_CANCEL = 2;
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ActivityApplyLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity_join_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('activity_id, object_id, member_id, source, success, operation, create_time', 'required'),
			array('source, success', 'numerical', 'integerOnly'=>true),
			array('activity_id, object_id, member_id, create_time', 'length', 'max'=>10),
			array('extra', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, activity_id, object_id, member_id, source, success, extra, create_time', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'activity_id' => 'Activity',
			'object_id' => 'Object',
			'member_id' => 'Member',
			'source' => 'Source',
			'success' => 'Success',
			'extra' => 'Extra',
			'create_time' => 'Create Time',
			'operation' => 'operation'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('activity_id',$this->activity_id,true);
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('member_id',$this->member_id,true);
		$criteria->compare('source',$this->source);
		$criteria->compare('success',$this->success);
		$criteria->compare('extra',$this->extra,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}