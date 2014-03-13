<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $uid
 * @property string $nickname
 * @property string $password
 * @property string $register_time
 * @property string $register_ip
 * @property string $lastlogin_time
 * @property string $lastlogin_ip
 * @property integer $account_status
 * @property integer $role_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $purview
 */
class AR_User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('password, register_time, lastlogin_time, created_at', 'required'),
			array('account_status, role_id, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('nickname', 'length', 'max'=>20),
			array('password, register_ip, lastlogin_ip', 'length', 'max'=>32),
			array('purview', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('uid, nickname, password, register_time, register_ip, lastlogin_time, lastlogin_ip, account_status, role_id, created_at, updated_at, purview', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'nickname' => 'Nickname',
			'password' => 'Password',
			'register_time' => 'Register Time',
			'register_ip' => 'Register Ip',
			'lastlogin_time' => 'Lastlogin Time',
			'lastlogin_ip' => 'Lastlogin Ip',
			'account_status' => 'Account Status',
			'role_id' => 'Role',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'purview' => 'Purview',
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

		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('register_time',$this->register_time,true);
		$criteria->compare('register_ip',$this->register_ip,true);
		$criteria->compare('lastlogin_time',$this->lastlogin_time,true);
		$criteria->compare('lastlogin_ip',$this->lastlogin_ip,true);
		$criteria->compare('account_status',$this->account_status);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);
		$criteria->compare('purview',$this->purview,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}