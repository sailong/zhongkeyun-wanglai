<?php

/**
 * 活动与用户对应关系
 * @author zjj
 *
 */
class ActivityMember extends CActiveRecord
{
	
	/**
	 * 审核状态
	 * @var int
	 */
	const VERIFY_STATE_WITHOUT = 0;
	const VERIFY_STATE_APPLY   = 1;
	const VERIFY_STATE_PASS    = 2;
	const VERIFY_STATE_REFUSE  = 3;
	
	/**
	 * 是否已取消报名
	 */
	const CANCELED_NO = 1;
	const CANCELED_YES = 2;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Follow the static model class
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
		return 'activity_member_rel';
	}
	
	/**
	 * 关联
	 * @see CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'activity' => array(self::BELONGS_TO, 'Activity', 'activity_id'),
			'applicant'  => array(self::BELONGS_TO, 'Member', 'member_id')	
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::scopes()
	 */
	public function scopes()
	{
		return array(
			'recently' => array(
				'alias' => 'amr',
				'order' => 'amr.id DESC',
				'condition' => 'canceled='.self::CANCELED_NO		
			),
			// 已报名用户
			'applied' => array(
				'condition' => 'canceled='.self::CANCELED_NO.' AND (state='.self::VERIFY_STATE_PASS.' OR state='.self::VERIFY_STATE_WITHOUT.')',
			),
			// 正在申请中的
			'applying' => array(
				'condition' => 'amr.state='.self::VERIFY_STATE_APPLY.' AND amr.canceled='.self::CANCELED_NO,
				'alias' => 'amr',
				'order' => 'amr.id DESC',
			),
			// 我参加的
			'joined' => array(
				'alias' => 'join',
				'condition' => "join.member_id='".Yii::app()->user->id."' AND join.canceled=".self::CANCELED_NO." AND (join.state=".self::VERIFY_STATE_PASS." OR join.state=".self::VERIFY_STATE_WITHOUT.")",
			)
		);
	}
	/**
	 * 统计活动的报名人数
	 * @param unknown_type $activity_id
	 */
	public function countApplicants($activity_id)
	{
		$sql = 'SELECT count(*) AS total FROM '.$this->tableName().' WHERE activity_id='.intval($activity_id)." AND state IN(".self::VERIFY_STATE_PASS.','.self::VERIFY_STATE_WITHOUT.') AND canceled='.self::CANCELED_NO;
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 统计某活动有多少人正在申请
	 * @param unknown_type $activity_id
	 */
	public function countApplying($activity_id)
	{
		$sql = 'SELECT count(*) AS total FROM '.$this->tableName().' WHERE activity_id='.intval($activity_id).' AND state='.self::VERIFY_STATE_APPLY.' AND canceled='.self::CANCELED_NO;
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	/**
	 * 检测是否已经报名了活动
	 * @param int $memeber_id  用户id
	 * @param int $activity_id 活动id
	 * @return bool 存在返回true,or false
	 */
	public function checkApply($memeber_id,$activity_id)
	{
		$sql = "SELECT * FROM ".$this->tableName()." WHERE member_id=".intval($memeber_id)." AND activity_id=".intval($activity_id)." AND canceled=".self::CANCELED_NO;
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		return !empty($data) ? $data : false;
	}
	
	/**
	 * 取消活动报名
	 * @param int $activityId 活动ID
	 * @param int $uid 用户id
	 * @return bool 成功返回true 失败返回false
	 */
	public function cancelApply($activityId,$uid=null)
	{
		empty($uid) && $uid = Yii::app()->user->id;
		$model = self::model()->findByAttributes(array('activity_id'=>$activityId,'member_id'=>$uid));
		if(!empty($model))
		{
			$model->canceled = self::CANCELED_YES;
			$model->cancel_time = time();
			$model->save();
			return true;
		}
		return false;
	}
	
	/**
	 * 取消活动报名
	 * @param int $id 活动id
	 */
	public function cancle($id)
	{
		$model = $this->findByPk($id);
		$activity = Activity::model()->findByPk($model->activity_id);
		if(empty($model) || $model->member_id != Yii::app()->user->id || empty($activity) || $model->canceled == self::CANCELED_YES ||  $model->state == self::VERIFY_STATE_REFUSE)
			return false;
		//if($model->state == self::VERIFY_STATE_APPLY || $model->state == self::VERIFY_STATE_REFUSE || $model->canceled == self::CANCELED_YES)
		//	return false;
		$model->canceled = self::CANCELED_YES;
		$model->cancel_time = time();
		return $model->save() ? true : false;
	}
	
	/**
	 * 同意申请
	 * @param int $id
	 */
	public function pass($id)
	{
		return $this->verify($id, self::VERIFY_STATE_PASS);
	}
	
	/**
	 * 拒绝申请
	 * @param int $id
	 */
	public function refuse($id)
	{
		return $this->verify($id, self::VERIFY_STATE_REFUSE);
	}
	/**
	 * 审核
	 * @param unknown_type $id
	 * @param unknown_type $state
	 */
	private function verify($id, $state = self::VERIFY_STATE_PASS)
	{
		$model = $this->findByPk($id);
		if(empty($model) || $model->state != self::VERIFY_STATE_APPLY)
			return false;
		$activity = Activity::model()->findByPk($model->activity_id);
		if(empty($activity) || $activity->create_mid != Yii::app()->user->id)
			return false;
		return $model->updateByPk($id, array('state'=>$state));
	}
	
}