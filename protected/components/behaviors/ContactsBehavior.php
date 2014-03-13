<?php

/**
 * 通讯录相关行为
 * @author JZLJS00
 *
 */
class ContactsBehavior extends CBehavior
{
	
	public function events()
	{
		return array(
			'onAfterCreate' => 'afterCreate',
			'onAfterView'   => 'afterView',
			'onAfterApply'  => 'afterApply'
		);
	}
	
	/**
	 * 创建完后,非企业用户创建人参加群友通讯录里
	 */
	public function afterCreate($event)
	{
		$member = $this->getOwner()->getMember();
		if($member->is_qiye == Member::QIYE_NO)
		{
			$model = $event->params['model'];// 通讯录model
			
			$contactsMember = new ContactsMember();
			$contactsMember->attributes = array(
					'member_id' => $model->create_mid,
					'contacts_id' => $model->id,
					'state' => ContactsMember::STATE_PASS,
					'apply_time' => time(),
			);
			$contactsMember->save();
		}
	}
	
	/**
	 * 通讯录查看计数
	 * @param unknown_type $event
	 */
	public function afterView($event)
	{
		$model = $event->params['model'];// 通讯录model
		$model->updateByPk($model->id,array('pv_counts'=>$model->pv_counts+1));
	}
	
	
	public function afterApply($event)
	{
		$contacts_id = $event->params['contacts_id'];
		$model = Contacts::model()->with('creater')->findByPk($contacts_id);
		$extend = $model->creater->extend;
		if(!empty($extend))
		{
			$openid = $extend->weixin_openid;
			if(!empty($openid))
			{
				$member = $event->params['model'];// 当前用户
				$fromUrl = Yii::app()->createAbsoluteUrl('member/view',array('id'=>$member->id));
				$toUrl = Yii::app()->createAbsoluteUrl('contacts/view',array('id'=>Util::encode($contacts_id)));
				$message = '<a href="'.$fromUrl.'">'.urlencode($member->name).'</a>'.urlencode('申请加入你创建的群').'<a href="'.$toUrl.'">'.urlencode($model->title).'</a>';
				WeixinBridge::sendServiceMessage($openid, $message);
			}
		}
	}
	
	
}