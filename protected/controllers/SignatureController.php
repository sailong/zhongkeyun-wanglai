<?php

class SignatureController extends FrontController
{
	
	protected $object_id = 1;
	
	protected $objectIds = array(1,2,3,4);
	
	protected $map = array(
		1 => 'index',
		2 => 'yanhua',
		3 => 'lina',
		4 => 'liudonghua',
	);
	
	/**
	 *
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}
	
	
	public function accessRules()
	{
		return array(
			array('deny',
				'actions' => array('sign'),
				'users'   => array('?'),
				'deniedCallback' => array($this,'deny')
			)
		);
	}
	
	
	public function deny()
	{
		$actionId = $this->getAction()->getId();
		$objectId = Yii::app()->request->getParam('id');
		if(!empty($objectId))
			Util::addCookie('objectId', Util::decode($objectId));
		$refer = $actionId == 'sign' ? Yii::app()->request->getUrlReferrer() : Yii::app()->request->getRequestUri();
		Util::addCookie('refer', $refer);
		Util::addCookie('signature', 'signature');
		$this->goAuth();
	}
	
	public function actionIndex()
	{
		$signature = Util::getCookie('signature');
		$objectId = Yii::app()->request->getParam('id');
		if(!empty($objectId))
			$objectId = Util::decode($objectId);
		else{
			$objectId = Util::getCookie('objectId');
			if(empty($objectId))
				$objectId = 1;
			Util::removeCookie('objectId');
		}
		$model = SignActivity::model()->findByPk($objectId);
		if(empty($model))
		{
			$this->showMessage(0, '不存在');
		}
// 		if(!in_array($objectId, $this->objectIds))
// 			$this->showMessage(0, '不存在');
		
		// 登录后自动签名
		if($signature == 'signature')
		{
			$uid = Yii::app()->user->id;
			if(!empty($uid) && !Signature::model()->checkSignature($objectId))
			{
				$signature = new Signature();
				$signature->attributes = array(
					'object_id'	=> $objectId,
					'member_id' => $uid,
					'create_time' => time()
				);
				$signature->save();
			}
			Util::removeCookie('signature');
			$url = $this->createUrl('signature/index',array("#"=>"signature"));
			$this->redirect($url);
		}
		// 记录PV
		$sql = "UPDATE sign_activity set pv_counts=pv_counts+1 WHERE id={$objectId}";
		Yii::app()->db->createCommand($sql)->execute();
		$sql = "SELECT member.id,name,signature.create_time FROM member,signature WHERE signature.member_id=member.id AND object_id={$objectId}";
		$all = Yii::app()->db->createCommand($sql)->queryAll();
		$total = count($all);
		Util::addSign();
		$this->render('index',array('model'=>$model,'total'=>$total,'all'=>$all));
		//$this->render($this->map[$objectId],compact('all','total'));
	}
	
	public function actionTemp()
	{
		Util::removeCookie('objectId');
	}
	/**
	 * 签名
	 */
	public function actionSign()
	{
		if(Yii::app()->request->getIsAjaxRequest())
		{
			$objectId = Yii::app()->request->getParam('id');
			if(!empty($objectId))
				$objectId = Util::decode($objectId);
			else
				$objectId = 1;
			$model = SignActivity::model()->findByPk($objectId);
			if(empty($model))
			{
				$this->showMessage(0, '不存在');
			}
			if(!Signature::model()->checkSignature($objectId))
			{
				$uid = Yii::app()->user->id;
				$signature = new Signature();
				$signature->attributes = array(
					'object_id'	=> $objectId,
					'member_id' => $uid,
					'create_time' => time()
				);
				$signature->save();
				$member = Member::model()->findByPk($uid);
				$this->returnData(1,'成功',array('id'=>$uid,'name'=>$member->name,'create_time'=>date('Y/m/d H:i',$signature->create_time)));
			}
		}
	}
	
	/**
	 * 统计分享次数
	 */
	public function actionCountShare()
	{
		if(Yii::app()->request->getIsAjaxRequest())
		{
			if($this->checkSign())
			{
				$object_id = Yii::app()->request->getParam('id');
				if(!empty($object_id))
					$object_id = Util::decode($object_id);
				else
					$object_id = 1;
				$model = SignActivity::model()->findByPk($object_id);
				if(!empty($model))
				{
					$sql = "UPDATE sign_activity set share_counts=share_counts+1 WHERE id={$object_id}";
					Yii::app()->db->createCommand($sql)->execute();
				}
			}
		}
	}
	
}