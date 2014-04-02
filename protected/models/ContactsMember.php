<?php

/**
 * This is the model class for table "contacts_member_rel".
 *
 * The followings are the available columns in table 'contacts_member_rel':
 * @property string $id
 * @property string $member_id
 * @property string $contacts_id
 * @property integer $privacy
 * @property integer $state
 * @property string $apply_time
 * @property string $refuse_time
 */

/**
 * 用户与群之间的关系
 * @author JZLJS00
 *
 */
class ContactsMember extends CActiveRecord
{
	
	/**
	 * 申请状态,1申请中,2通过,3打回,4移除,5退出
	 */
	const STATE_APPLY  = 1;
	const STATE_PASS   = 2;
	const STATE_REFUSE = 3;
	const STATE_REMOVE = 4;
	const STATE_QUIT   = 5;
	
	public $error = NULL;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContactsMember the static model class
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
		return 'contacts_member_rel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, contacts_id, apply_time', 'required'),
			array('state', 'numerical', 'integerOnly'=>true),
			array('member_id, contacts_id, apply_time, update_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, member_id, contacts_id, state, apply_time, update_time', 'safe', 'on'=>'search'),
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
			'contacts' => array(self::BELONGS_TO, 'Contacts', 'contacts_id'),
			'member'   => array(self::BELONGS_TO, 'Member', 'member_id')
		);
	}

	public function scopes()
	{
		return array(
			'pass' => array(
				'alias' => 'cm',
				'condition' => "state=".self::STATE_PASS,
				'order' => 'cm.id desc'	
			),
			'applying' => array(
				'alias' => 'cm',
				'condition' => "state=".self::STATE_APPLY,
				'order' => 'cm.id desc'
			),
			'joined' => array(
				'condition' => "state=".self::STATE_PASS." AND member_id='".Yii::app()->user->id."'"
			)
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
			'contacts_id' => 'Contacts',
			'state' => 'State',
			'apply_time' => 'Apply Time',
			'update_time' => '审核时间',
		);
	}
	
	/**
	 * 统计某通讯录未处理的申请数量
	 */
	public function calculateApplyCount($contactsId)
	{
		return $this->countByAttributes(array('contacts_id' => ':contacts_id'),'',array(':contacts_id'=>$contactsId));
	}
	
	/**
	 * 申请加入某群
	 * @param int $id 群id
	 */
	public function apply($id)
	{
		$model = Contacts::model()->findByPk($id);
		if(empty($model)) return false;
		$uid = Yii::app()->user->id;
		$contactMember = ContactsMember::model()->findByAttributes(array('member_id'=>$uid,'contacts_id'=>$id));
		if(!empty($contactMember) && ($contactMember->state == self::STATE_APPLY || $contactMember->state == self::STATE_PASS))
			return false;
		if(empty($contactMember))
		{
			$contactMember = new ContactsMember();
			$contactMember->attributes = array(
				'member_id'   => $uid,
				'contacts_id' => $id,
			);
		}
		$contactMember->apply_time = time();
		$contactMember->state = ContactsMember::STATE_APPLY;
		return $contactMember->save() ? true : false;
	}		
		
	
	/**
	 * 申请审核,通过或拒绝
	 * @param int $id 申请id
	 * @param tinyint $state 审核状态(通过或拒绝,只能其中1个)
	 */
	public function verify($id,$state=self::STATE_PASS)
	{
		$model = $this->findByPk($id);
		if(empty($model) || $model->state != self::STATE_APPLY) return false;
		$uid = Yii::app()->user->id;
		$contact = Contacts::model()->findByPk($model->contacts_id);
		if(empty($contact) || $contact->create_mid != $uid) return false;
		$model->state = $state;
		$model->update_time = time();
		return $model->save() ? true : false;
	}
	
	/**
	 * 管理员将用户移除
	 * @param int $contactsId 通讯录id
	 * @param int $memberId   被移除的用户id
	 */
	public function remove($id)
	{
		$model = $this->findByPk($id);
		if(empty($model)) return false;
		$uid = !empty($uid) ? $uid : Yii::app()->user->id;
		$contact = Contacts::model()->findByPk($model->contacts_id);
		if(empty($contact) || $contact->create_mid != $uid) return false;
		if($model->state != self::STATE_PASS) return false;
		$model->state = self::STATE_REMOVE;
		$model->update_time = time();
		return $model->save() ? true : false;
	}
	
	/**
	 * 退出群
	 */
	public function quit($id)
	{
		$model = $this->findByPk($id);
		if(empty($model)) return false;
		$uid = Yii::app()->user->id;
		$contact = Contacts::model()->findByPk($model->contacts_id);
		if(empty($contact) || $model->member_id != $uid) return false;
		if($model->state != self::STATE_PASS) return false;
		$model->state = self::STATE_QUIT;
		$model->update_time = time();
		return $model->save() ? true : false;
	}
	
	/**
	 * 检测是否已加入了群
	 * @param $contactsId int 群id
	 * @param $memberId int 用户id
	 * @return bool 已加入返回true 否则false
	 */
	public function checkHasApply($contactsId,$memberId)
	{
		$model = $this->findByAttributes(array('member_id'=>$memberId,'contacts_id'=>$contactsId));
		return $model;
	}
	
	// 统计多少人申请
	public function countApply($contactsId)
	{
		$sql = "SELECT count(*) AS total,state FROM contacts_member_rel WHERE contacts_id={$contactsId} AND state=".ContactsMember::STATE_APPLY;
		$total = Yii::app()->db->createCommand($sql)->queryScalar();
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	public function countPass($contactsId)
	{
		$sql = "SELECT count(*) AS total,state FROM contacts_member_rel WHERE contacts_id={$contactsId} AND state=".ContactsMember::STATE_PASS;
		$total = Yii::app()->db->createCommand($sql)->queryScalar();
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
}