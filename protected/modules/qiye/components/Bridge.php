<?php

/**
 * 与模型有相同的操作,但由于用户变量的特殊性,不能直接使用model的方法
 * @author JZLJS00
 *
 */
class Bridge
{
	
	/**
	 * 通过活动报名申请
	 * @param unknown_type $id
	 * @return boolean|Ambigous <number, unknown>
	 */
	public static function passActivityApply($id)
	{
		$model = ActivityMember::model()->findByPk($id);
		if(empty($model) || $model->state != ActivityMember::VERIFY_STATE_APPLY)
			return false;
		$activity = Activity::model()->findByPk($model->activity_id);
		if(empty($activity) || $activity->create_mid != Yii::app()->user->mid)
			return false;
		return $model->updateByPk($id, array('state'=>ActivityMember::VERIFY_STATE_PASS));
	}
	
	/**
	 * 拒绝活动报名申请
	 * @param unknown_type $id
	 * @return boolean|Ambigous <number, unknown>
	 */
	public static function refuseActivityApply($id)
	{
		$model = ActivityMember::model()->findByPk($id);
		if(empty($model) || $model->state != ActivityMember::VERIFY_STATE_APPLY)
			return false;
		$activity = Activity::model()->findByPk($model->activity_id);
		if(empty($activity) || $activity->create_mid != Yii::app()->user->mid)
			return false;
		return $model->updateByPk($id, array('state'=>ActivityMember::VERIFY_STATE_PASS));
	}
	
	/**
	 * 通过加入群申请
	 * @param unknown_type $id
	 * @return boolean
	 */
	public static function passContactsApply($id)
	{
		$model = ContactsMember::model()->findByPk($id);
		if(empty($model) || $model->state != ContactsMember::STATE_APPLY) return false;
		$contact = Contacts::model()->findByPk($model->contacts_id);
		if(empty($contact) || $contact->create_mid != Yii::app()->user->mid) return false;
		$model->state = ContactsMember::STATE_PASS;
		$model->update_time = time();
		return $model->save();
	}
	
	/**
	 * 拒绝加入群申请
	 * @param unknown_type $id
	 * @return boolean
	 */
	public static function refuseContactsApply($id)
	{
		$model = ContactsMember::model()->findByPk($id);
		if(empty($model) || $model->state != ContactsMember::STATE_APPLY) return false;
		$contact = Contacts::model()->findByPk($model->contacts_id);
		if(empty($contact) || $contact->create_mid != Yii::app()->user->mid) return false;
		$model->state = ContactsMember::STATE_REFUSE;
		$model->update_time = time();
		return $model->save();
	}
	
}