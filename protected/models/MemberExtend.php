<?php

/**
 * This is the model class for table "member_extend".
 *
 * The followings are the available columns in table 'member_extend':
 * @property string $id
 * @property integer $member_id
 * @property string $number
 * @property integer $created_at
 */
class MemberExtend extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MemberExtend the static model class
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
		return 'member_extend';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, weixin_openid,nickname,add_time', 'required'),
			array('member_id, add_time,sex', 'numerical', 'integerOnly'=>true),
			array('province,city,country,headimgurl,sex','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
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
			'member_id' => '用户id',
			'weinxin_openid' => '微信openid',
			'nickname' => '用户昵称',
			'headimgurl' => '微信用户头像',
			'sex'  => '性别',
			'province' => '省',
			'city' => '市',
			'country' => '国家',
			'add_time' => '添加时间'
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
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}