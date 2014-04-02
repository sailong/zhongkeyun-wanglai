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
			'onAfterView'   => 'afterView',
			'onAfterCancel' => 'afterCancel'
		);
	}
	
	/**
	 * 申请参加活动后,默认关注活动的发起人
	 */
	public function afterApply($event)
	{
		$model = $event->params['model'];// 活动对象
		if($model->source == Activity::SOURCE_SELE)
		{
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
		}elseif($model->source == Activity::SOURCE_TONGDAOHUI)
		{
			// 同道会,同步报名数据
			$member = $event->params['member'];// 当前用户
			$data = array(
				'r_id' => $model->cooperator->object_id,
				'name' => $member->name,
				'mobile' => $member->mobile,
				'company' => $member->company,
				'position' => $member->position,
				'sex' => $member->extend->sex == 1 ? '男' : '女',
				'remark' => '往来活动报名测试,',//$member->name,
				'email' => $member->email
			);
			$data['json'] = json_encode($data);
			$url = 'http://www.tongdaohui.com/wanglai/doApplyRoadshow/d2FuZ2xhaTp3YW5nbGFpMTIzNDU2';
			$curl = Yii::app()->curl;
			$curl->setOption(CURLOPT_TIMEOUT,1);
			$result = $curl->post($url,$data);
			$result = !empty($result) ? json_decode($result,true) : array();
			$log = new ActivityJoinLog();
			$attributes = array(
				'activity_id' => $model->id,
				'object_id' => $model->cooperator->object_id,
				'member_id' => $member->id,
				'source' => $model->source,
				'create_time' => time(),
				'operation' => ActivityJoinLog::OPERATION_APPLY
			);
			if(empty($result))
			{
				$attributes['success'] = 500;
				$attributes['extra'] = 'No response';
			}else{
				$attributes['success'] = $result['success'];
				$attributes['extra'] = $result['info'];
			}
			$log->attributes = $attributes;
			$log->save();
		}
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
	
	/**
	 * 活动报名取消后
	 * @param unknown $event
	 */
	public function afterCancel($event)
	{
		$id = $event->params['applyId'];// 活动对象
		$member = $event->params['member'];
		$activityMember = ActivityMember::model()->findByPk($id);
		$activity = $activityMember->activity;
		if($activity->source == Activity::SOURCE_TONGDAOHUI)
		{
			// 通道会活动,同步取消报名
			$object = ActivityMigrate::model()->findByAttributes(array('activity_id'=>$activity->id,'source'=>$activity->source));
			if(!empty($object))
			{
				$url = 'http://www.tongdaohui.com/wanglai/deleteApplyRoadshow?token=d2FuZ2xhaTp3YW5nbGFpMTIzNDU2';
				//$url = 'http://www.tongdaohui.com/index.php?app=home&mod=wanglai&act=deleteApplyRoadshow&token=d2FuZ2xhaTp3YW5nbGFpMTIzNDU2';
				$url .= '&r_id='.$object->object_id.'&mobile='.$member->mobile;
				$curl = Yii::app()->curl;
				$curl->setOption(CURLOPT_TIMEOUT,1);
				$result = Yii::app()->curl->get($url);
				$result = !empty($result) ? json_decode($result,true) : array();
				$log = new ActivityJoinLog();
				$attributes = array(
					'activity_id' => $activity->id,
					'object_id' => $object->object_id,
					'member_id' => $member->id,
					'source' => $activity->source,
					'create_time' => time(),
					'operation' => ActivityJoinLog::OPERATION_CANCEL
				);
				if(empty($result))
				{
					$attributes['success'] = 500;
					$attributes['extra'] = 'NO response';
				}else{
					$attributes['success'] = $result['success'];
					$attributes['extra'] = $result['info'];
				}
				$log->attributes = $attributes;
				$log->save();
			}
		}
	}
	
}