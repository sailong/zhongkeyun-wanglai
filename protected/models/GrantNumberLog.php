<?php

/**
 * This is the model class for table "grant_number_log".
 *
 * The followings are the available columns in table 'grant_number_log':
 * @property string $id
 * @property integer $member_id
 * @property string $number
 * @property integer $grant_way
 * @property integer $grant_admin_id
 * @property integer $created_at
 */
class GrantNumberLog extends CActiveRecord
{
	
	/**
	 * 发放方式1 系统 2 人工4 注册发放5没有往来号的初始化发放
	 * @var unknown_type
	 */
	const GRAND_WAY_SYSTEM = 1; 
	const GRAND_WAY_HUMAN = 2;
	
	const GRAND_WAY_SIGN = 4;
	const GRAND_WAY_INIT = 5;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GrantNumberLog the static model class
	 */
	const SYSTEM_GRANT = 1;
	const ADMIN_GRANT = 2;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grant_number_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, number, created_at', 'required'),
			array('member_id, grant_way, grant_admin_id, created_at', 'numerical', 'integerOnly'=>true),
			array('number', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, member_id, number, grant_way, grant_admin_id, created_at', 'safe', 'on'=>'search'),
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
			'member_id' => 'Member',
			'number' => 'Number',
			'grant_way' => 'Grant Way',
			'grant_admin_id' => 'Grant Admin',
			'created_at' => 'Created At',
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
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('grant_way',$this->grant_way);
		$criteria->compare('grant_admin_id',$this->grant_admin_id);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * 获取备选的往来账号数量
	 */
	public function getSpareCounts($member_id)
	{
		$ret = $this->count('member_id = :a AND grant_way = '.self::SYSTEM_GRANT,array(':a'=>$member_id));
		return $ret ? $ret : 0;
	}
	
	public function getMySpaceNumber($member_id)
	{
		$param['select']= 'number';
		$param['condition'] = 'member_id = :a AND grant_way = '.self::SYSTEM_GRANT;
		$param['params'] = array(':a'=>$member_id);
		$param['order'] = 'id desc';
		return $this->findAll($param);
	}
}