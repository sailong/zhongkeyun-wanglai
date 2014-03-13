<?php

/**
 * This is the model class for table "invite_stat".
 *
 * The followings are the available columns in table 'invite_stat':
 * @property string $id
 * @property integer $member_id
 * @property integer $all_counts
 * @property integer $left_counts
 * @property integer $created_at
 */
class InviteStat extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InviteStat the static model class
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
		return 'invite_stat';
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
			array('member_id, all_counts, left_counts, activity_id, created_at', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, member_id, all_counts, left_counts, activity_id, created_at', 'safe', 'on'=>'search'),
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
			'all_counts' => 'All Counts',
			'left_counts' => 'Left Counts',
			'created_at' => 'Created At',
			'activity_id'=>'activity id',
			'is_confirm_number'=>'is_confirm_number',
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
		$criteria->compare('all_counts',$this->all_counts);
		$criteria->compare('left_counts',$this->left_counts);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('activity_id',$this->activity_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 更新邀请数量
	 */
	public function updateStat($member_id,$fields=array())
	{
		if(!$member_id || !$fields) return;
		//判断活动是否在进行中
		if($this->checkIsCanJoin()!==true) return;
		$ac_id = $this->getActivityId();
		$ret = $this->updateCounters($fields,'member_id = '.$member_id.' AND activity_id = '.$ac_id);
		if(!$ret)
		{
			$this->setIsNewRecord(true);
			$this->member_id = $member_id;
			$this->activity_id = $ac_id;
			$this->created_at = time();
			foreach ($fields as $key=>$f)
			{
				$this->$key = 1;
			}
			$ret = $this->insert();
		}
	}
	/**
	 * 活动开始时间
	 * @return number
	 */
	public function getStartTime()
	{
		return strtotime('2013-11-27 00:38');
	}
	/**
	 * 活动结束时间
	 * @return number
	 */
	public function getEndTime()
	{
		return strtotime('2013-12-05 12:00:00');
	}
	
	public function getActivityId()
	{
		return 1;
	}
	public function checkIsCanJoin()
	{
		$time = time();
		if($this->getStartTime() > $time) return array(-1,'亲，活动还没开始呢，坐下来先喝杯茶吧！');
		if($this->getEndTime() < $time) return array(-2,'很抱歉，您来晚了，活动已经结束了');
		return true;
	}
	
	public function getInviteData($id)
	{
		return $this->find('activity_id = '.$this->getActivityId().' AND member_id = '.$id);
	}
}