<?php

/**
 * 统计用户登录
 * @author JZLJS00
 *
 */
class StatBehavior extends CBehavior
{
	
	public function events()
	{
		return array(
			'onBeginRequest' => 'begin'	
		);
	}
	
	/**
	 * 记录最后登录时间,统计活跃用户
	 */
	public function begin()
	{
		if(!Yii::app()->user->getIsGuest())
		{
			$flag = Util::getCookie('flag');
			if(empty($flag))
			{
				$uid = intval(Yii::app()->user->id);
				$stat = Stat::model()->findByAttributes(array('member_id' => $uid));
				if($stat === null)
				{
					$stat = new Stat();
					$stat->member_id = $uid;
				}
				$current = time();
				$stat->last_login_at = $current;
				$stat->save();
				Util::addCookie('flag', $current, $current + 7200);  //2小时过期
			}
		}
	}
	
}