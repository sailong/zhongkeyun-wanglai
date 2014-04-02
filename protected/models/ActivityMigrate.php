<?php

/**
 * This is the model class for table "activity_migrate_log".
 *
 * The followings are the available columns in table 'activity_migrate_log':
 * @property string $id
 * @property integer $source
 * @property string $activity_id
 * @property string $object_id
 * @property string $extra
 * @property string $create_time
 */
class ActivityMigrate extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ActivityMigrate the static model class
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
		return 'activity_migrate_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('source, activity_id, object_id, extra, create_time', 'required'),
			array('source', 'numerical', 'integerOnly'=>true),
			array('activity_id, object_id, create_time', 'length', 'max'=>10),
			//array('extra', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, source, activity_id, object_id, extra, create_time', 'safe'),
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
			'activity' => array(self::BELONGS_TO, 'Activity', 'activity_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'source' => 'Source',
			'activity_id' => 'Activity',
			'object_id' => 'Object',
			'extra' => 'Extra',
			'create_time' => 'Create Time',
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
		$criteria->compare('source',$this->source);
		$criteria->compare('activity_id',$this->activity_id,true);
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('extra',$this->extra,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}