<?php

/**
 * This is the model class for table "view_log".
 *
 * The followings are the available columns in table 'view_log':
 * @property string $id
 * @property integer $member_id
 * @property integer $viewd_member_id
 * @property integer $created_at
 * @property integer $last_viewd_at
 */
class AR_ViewLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_ViewLog the static model class
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
		return 'view_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, viewd_member_id, created_at', 'required'),
			array('member_id, viewd_member_id, created_at, last_viewd_at', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, member_id, viewd_member_id, created_at, last_viewd_at', 'safe', 'on'=>'search'),
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
			'viewd_member_id' => 'Viewd Member',
			'created_at' => 'Created At',
			'last_viewd_at' => 'Last Viewd At',
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
		$criteria->compare('viewd_member_id',$this->viewd_member_id);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('last_viewd_at',$this->last_viewd_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 统计多少人看过我名片
	 * @param int $mid
	 * @return int
	 */
	public function calculateMyViewer($mid=null)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT count(*) AS total FROM ".$this->tableName()." WHERE viewd_member_id={$mid}";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 获取最近一个访客
	 */
	public function getLatestViewer($mid)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT member.name FROM view_log AS log ,member WHERE log.viewd_member_id={$mid} AND log.member_id=member.id ORDER BY last_viewd_at DESC LIMIT 1";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
}