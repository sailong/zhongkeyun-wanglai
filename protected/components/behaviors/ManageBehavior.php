<?php

/**
 * 个人中心页面的behavior
 * @author JZLJS00
 *
 */
class ManageBehavior extends CBehavior
{
	
	public function events()
	{
		return array(
			'onViewPartner' => 'viewPartner',
			'onViewFollow'  => 'viewFollow'
		);
	}
	
	/**
	 * 查看完我的小伙伴后,标记已查看
	 * @param unknown_type $event
	 */
	public function viewPartner($event)
	{
		$sql = "UPDATE partner SET is_new=".Partner::NEW_NO." WHERE from_uid=".Yii::app()->user->id;
		Yii::app()->db->createCommand($sql)->execute();
	}
	
	/**
	 * 查看名片夹后,标记新关注未已查看
	 * @param unknown_type $event
	 */
	public function viewFollow($event)
	{
		$sql = "UPDATE follow SET is_new=".Follow::NEW_NO." WHERE follow_mid=".Yii::app()->user->id;
		Yii::app()->db->createCommand($sql)->execute();
	}
	
	
}