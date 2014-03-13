<?php

/**
 * 与活动有关的行为
 * @author zjj
 *
 */
class ActivityBehavior extends CBehavior
{
	
	public function events()
	{
		return array(
			'onAfterApply'  => 'afterApply',	
			'onAfterCreate' => 'afterCreate',
			'onAfterView'   => 'afterView'
		);
		
	}
	
	/**
	 * 申请参加活动后,默认关注活动的发起人
	 */
	public function afterApply($event)
	{
		$model = $event->params['model'];// 活动对象
		$follow = Follow::model()->findByAttributes(array('mid'=>Yii::app()->user->id,'follow_mid'=>$model->create_mid));
		if(!empty($follow))
		{
			if($follow->is_deleted == Follow::FOLLOW_OUT)
				$follow->is_deleted = Follow::FOLLOW_IN;
		}else {
			$follow = new Follow();
			$follow->mid = Yii::app()->user->id;
			$follow->follow_mid = $model->create_mid;
			$follow->follow_at  = time();
		}
		$follow->save();
	}
	
	/**
	 * 创建完活动后,非企业用户活动创建人默认参加活动
	 */
	public function afterCreate($event)
	{
		$member = $this->getOwner()->getMember();
		if($member->is_qiye == Member::QIYE_NO)
		{
			$model = $event->params['model'];// 活动对象
			$activityMember = new ActivityMember();
			$activityMember->activity_id = $model->id;
			$activityMember->member_id = $model->create_mid;
			$activityMember->state = ActivityMember::VERIFY_STATE_WITHOUT;
			$activityMember->create_time = time();
			$activityMember->save();
		}
	}
	
	/**
	 * 活动详情页计数
	 * @param unknown_type $event
	 */
	public function afterView($event)
	{
		$model = $event->params['model'];// 活动对象
		$model->views += 1;
		$model->save();
	}
	
}