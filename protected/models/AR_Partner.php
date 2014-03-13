<?php

/**
 * This is the model class for table "partner".
 *
 * The followings are the available columns in table 'partner':
 * @property string $id
 * @property integer $new_uid
 * @property integer $from_uid
 * @property integer $created_at
 */
class AR_Partner extends CActiveRecord
{
	/**
	 * 是否是新增的小伙伴
	 * @var unknown_type
	 */
	const NEW_NO  = 0;
	const NEW_YES = 1;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Partner the static model class
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
		return 'partner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('new_uid, from_uid, created_at', 'required'),
			array('new_uid, from_uid, created_at', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, new_uid, from_uid, created_at', 'safe', 'on'=>'search'),
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
			'new_uid' => 'New Uid',
			'from_uid' => 'From Uid',
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
		$criteria->compare('new_uid',$this->new_uid);
		$criteria->compare('from_uid',$this->from_uid);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 是否有新增的小伙伴
	 * @param int $mid 用户id
	 * @return bool
	 */
	public function hasNewPartner($mid=null)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT count(*) AS total FROM ".$this->tableName()." WHERE from_uid={$mid} AND is_new=".Partner::NEW_YES;
		$total = Yii::app()->db->createCommand($sql)->queryScalar();
		return $total>0 ? true : false;
	}
	
	/**
	 * 统计小伙伴数量
	 * @param int $mid 用户id
	 * @return int 
	 */
	public function calculateMyPartner($mid=null)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT count(*) AS total FROM ".$this->tableName()." WHERE from_uid={$mid}";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
}