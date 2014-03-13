<?php

/**
 * 企业名片相关操作
 * @author JZLJS00
 *
 */
class EmployeeController extends FrontController
{
	
	/**
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('applyEmployee'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
				'deniedCallback' => array($this,'deny')
			),
		);
	}
	
	public function deny()
	{
		$actionId = $this->getAction()->getId();
		$refer = $actionId == 'applyEmployee' ? Yii::app()->request->getUrlReferrer() : Yii::app()->request->getRequestUri();
		Util::addCookie('refer', $refer);
		$this->goAuth();
	}
	
	/**
	 * 申请成为企业员工员工(企业用户不可申请成为企业员工)
	 */
	public function actionApplyEmployee()
	{
		// 员工群
		if($this->_member->is_qiye != Member::QIYE_YES)
		{
			$id = Yii::app()->request->getParam('id');
			$contacts = Contacts::model()->findByAttributes(array('create_mid'=>$id,'default'=>Contacts::DEFAULT_YES));
			if(!empty($contacts))
			{
				$contactsMemeber = ContactsMember::model()->findByAttributes(array('contacts_id'=>$contacts->id,'member_id'=>Yii::app()->user->id));
				if(empty($contactsMemeber))
				{
					$contactsMemeber = new ContactsMember();
					$contactsMemeber->contacts_id = $contacts->id;
					$contactsMemeber->member_id = Yii::app()->user->id;
					$contactsMemeber->state = ContactsMember::STATE_APPLY;
					$contactsMemeber->apply_time = time();
					$contactsMemeber->save();
					$this->returnData(1,'企业员工申请中');
				}
			}
		}
		$this->returnData(1,'申请失败');
	}
	
	
}