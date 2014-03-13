<?php

/**
 * This is the model class for table "stat".
 *
 * The followings are the available columns in table 'stat':
 * @property string $id
 * @property string $member_id
 * @property integer $pv_counts
 * @property integer $favorite_counts
 * @property integer $last_follow_at
 * @property integer $last_partner_at
 * @property integer $last_update_at
 */
class AR_Stat extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Stat the static model class
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
		return 'stat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id', 'required'),
			array('pv_counts, favorite_counts, last_follow_at, last_partner_at, last_update_at', 'numerical', 'integerOnly'=>true),
			array('member_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, member_id, pv_counts, favorite_counts, last_follow_at, last_partner_at, last_update_at', 'safe', 'on'=>'search'),
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
			'pv_counts' => 'Pv Counts',
			'favorite_counts' => 'Favorite Counts',
			'last_follow_at' => 'Last Follow At',
			'last_partner_at' => 'Last Partner At',
			'last_update_at' => 'Last Update At',
			'last_login_at'  => '最后登录时间'
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
		$criteria->compare('member_id',$this->member_id,true);
		$criteria->compare('pv_counts',$this->pv_counts);
		$criteria->compare('favorite_counts',$this->favorite_counts);
		$criteria->compare('last_follow_at',$this->last_follow_at);
		$criteria->compare('last_partner_at',$this->last_partner_at);
		$criteria->compare('last_update_at',$this->last_update_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}