<?php

class Employee
{
	
	
	
	/**
	 * 获取员工总数
	 * @param unknown_type $id
	 */
	public static function getTotalEmployee($id)
	{
		$total = 0;
		$contacts = Contacts::model()->findByAttributes(array('create_mid'=>$id,'default'=>Contacts::DEFAULT_YES));
		if(!empty($contacts))
		{
			$sql = "SELECT count(*) FROM contacts_member_rel WHERE contacts_id=".$contacts->id." AND state=".ContactsMember::STATE_PASS;
			$total = Yii::app()->db->createCommand($sql)->queryScalar();
		}
		return $total;
	}
	
	/**
	 * 检测用户是否是企业的员工
	 * @param int $qid 企业名片id
	 */
	public static function checkEmployee($qid)
	{
		$employee = false;
		$contacts = Contacts::model()->findByAttributes(array('create_mid'=>$qid,'default'=>Contacts::DEFAULT_YES));
		if(!empty($contacts))
		{
			$contactsMember = ContactsMember::model()->findByAttributes(array('contacts_id'=>$contacts->id,'member_id'=>Yii::app()->user->id));
			if(!empty($contactsMember) && $contactsMember->state == ContactsMember::STATE_PASS)
				$employee = true;
		}
		return $employee;
	}
	
	/**
	 * 获取员工状态
	 * @param unknown_type $qid
	 */
	public static function getEmployeeState($qid)
	{
		$state = false;
		$contacts = Contacts::model()->findByAttributes(array('create_mid'=>$qid,'default'=>Contacts::DEFAULT_YES));
		if(!empty($contacts))
		{
			$contactsMember = ContactsMember::model()->findByAttributes(array('contacts_id'=>$contacts->id,'member_id'=>Yii::app()->user->id));
			if(!empty($contactsMember))
				$state = $contactsMember->state;
		}
		return $state;
	}
	
}