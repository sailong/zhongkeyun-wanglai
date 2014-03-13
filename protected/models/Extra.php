<?php

/**
 * This is the model class for table "extra".
 *
 * The followings are the available columns in table 'extra':
 * @property string $id
 * @property integer $type
 * @property string $object_id
 * @property string $email
 * @property string $create_time
 */
class Extra extends CActiveRecord
{
	
	/**
	 * 类型区别,1活动增加邮箱,2群增加邮箱
	 * @var unknown_type
	 */
	const TYPE_ACTIVITY_EMAIL = 1;
	const TYPE_CONTACTS_EMAIL = 2;
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Extra the static model class
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
		return 'extra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, object_id, email, create_time', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('object_id, create_time', 'length', 'max'=>10),
			array('email', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, object_id, email, create_time', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'object_id' => 'Object',
			'email' => 'Email',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 更加对象id和类型id获取邮箱
	 * @param unknown_type $object_id
	 * @param unknown_type $type
	 */
	public function getEmail($object_id,$type)
	{
		$emails = array();
		$model = $this->findByAttributes(array('object_id'=>$object_id,'type'=>$type));
		if(!empty($model))
		{
			foreach (explode(' ', $model->email) as $email)
			{
				!empty($email)&&array_push($emails, $email);
			}
		}
		return $emails;
	}
}