<?php

/**
 * 名片相关行为
 * @author JZLJS00
 *
 */
class MemberBehavior extends CBehavior
{
	
	public function events()
	{
		return array(
			'onAfterView' => 'afterView',
			'onAfterFollow' => 'afterFollow'	
		);
	}

	/**
	 * 查看名片后做相应的统计
	 * @param unknown_type $event
	 */
	public function afterView($event)
	{
		$model = $event->params['model'];// 名片model
		$uid = Yii::app()->user->id;
		$stat = $model->stat;
		if(Yii::app()->user->getIsGuest() || $uid != $model->id)  
		{
			// 更新PV
			if(empty($stat))
			{
				$stat = new Stat();
				$stat->member_id = $model->id;
			}
			$stat->pv_counts = $stat->pv_counts+1;
			$stat->last_update_at = time();
			$stat->save();
		}
		
		if(!Yii::app()->user->getIsGuest() && $uid != $model->id)
		{
			// 记录浏览日志
			$viewLog = ViewLog::model()->findByAttributes(array('member_id'=>$uid,'viewd_member_id'=>$model->id));
			if(empty($viewLog))
			{
				$viewLog = new ViewLog();
				$viewLog->member_id = $uid;
				$viewLog->viewd_member_id = $model->id;
				$viewLog->created_at = time();
				$model->updateByPk($model->id,array('views'=>$model->views+1));
				$model->views += 1;
			}
			$viewLog->last_viewd_at = time();
			$viewLog->save();
		}
	}
	
	// 关注后通过微信公众号发送消息
	public function afterFollow($event)
	{
		$model = $event->params['model'];// 名片model
		$extend = $model->extend;
		if(!empty($extend))
		{
			$openid = $extend->weixin_openid;
			if(!empty($openid))
			{
				$fromModel = $event->params['fromModel'];
				$fromUrl = Yii::app()->createAbsoluteUrl('member/view',array('id'=>$fromModel->id));
				$toUrl = Yii::app()->createAbsoluteUrl('member/view',array('id'=>$model->id));
				$message = '<a href="'.$fromUrl.'">'.urlencode($fromModel->name) . '</a>'.urlencode('关注了').'<a href="'.$toUrl.'">'.urlencode('你').'</a>';
				WeixinBridge::sendServiceMessage($openid, $message);
			}
		}
	}
	
}