<?php

class ContactsController extends FrontController
{

	/**
	 * 需要加签名的视图文件
	 * @var unknown_type
	 */
	//protected $_include_views = array('create','update');
	
	/**
	 * 
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'ajaxOnly + apply,pass,refuse,remove,quit,sendMail'	
		);
	}
	
	public function accessRules()
	{
		return array(
			array('deny',
				'actions' => array('index','myJoined','myCreated','create','update','pass','refuse','apply','quit','remove'),
				'users'   => array('?'),
				'deniedCallback' => array($this,'deny')
			)	
		);
	}
	
	public function deny()
	{
		$actionId = $this->getAction()->getId();
		$refer = $actionId == 'apply' ? Yii::app()->request->getUrlReferrer() : Yii::app()->request->getRequestUri();
		Util::addCookie('refer', $refer);
		$this->goAuth();
	}
	
	public function behaviors()
	{
		return array(
			'contacts' => 'application.components.behaviors.ContactsBehavior'	
		);
	}
	/**
	 * 首页,默认我发起的微群
	 */
	public function actionIndex()
	{
		$this->actionMyCreated();
	}

	/**
	 * 创建群友通讯录
	 */
	public function actionCreate()
	{
		$model = new Contacts();
		if(isset($_POST['contacts']))
		{
			//if($this->checkSign())
			//{
				if(!isset($_SESSION['already']))
				{
					$_SESSION['already'] = 'already';
					$param = $_POST['contacts'];
					$param['create_mid'] = Yii::app()->user->id;
					$param['create_time'] = time();
					$model->attributes = $param;
					if($model->save())
					{
						unset($_SESSION['already']);
						$this->onAfterCreate(new CEvent($this,array('model'=>$model)));		
						$url = $this->createUrl('contacts/view',array('id'=>Util::encode($model->id))); 
						$this->redirect($url);
					}else 
					{
						unset($_SESSION['already']);
						$errors = $model->getErrors();
						$error = array_pop($errors);
						$this->showMessage(0, $error[0]);
					}
				}
			//}else{
			//	$this->showMessage(0, '签名失败');
			//}
		}
		//Util::addSign();
		if(isset($_SESSION['already'])) unset($_SESSION['already']);
		$this->render('create',array('model'=>$model));
	}
	
	public function onAfterCreate($event)
	{
		$this->raiseEvent('onAfterCreate', $event);
	}
	
	/**
	 * 编辑
	 */
	public function actionUpdate()
	{
		$id = Util::decode(Yii::app()->request->getParam('id'));
		$model = $this->loadModel($id);
		if($model->create_mid != Yii::app()->user->id)
		{
			$this->showMessage(0, '非法请求');
		}
		if(isset($_POST['contacts']))
		{
			//if($this->checkSign())
		//	{
				$param = $_POST['contacts'];
				$param['update_time'] = time();
				$model->attributes = $param;
				if($model->save())
				{
					$url = $this->createUrl('contacts/view',array('id'=>Util::encode($id)));
					$this->redirect($url);
				}else
				{
					$errors = $model->getErrors();
					$error = array_pop($errors);
					$this->showMessage(1, $error[0]);
				}
			}
		//}
		//Util::addSign();
		$this->render('update',array('model'=>$model));
	}
	
	/**
	 * 查看群
	 */
	public function actionView()
	{
		$id = Util::decode(Yii::app()->request->getParam('id'));
		$model = $this->loadModel($id);
		//$contacts = Contacts::model()->with('member')->findAll(array('alias'=>'c','condition'=>"c.id='".$id."'"));
		$members = ContactsMember::model()->pass()->with('member')->findAll(array('condition'=>"contacts_id='".$id."'"));
		$memberIds = array();
		//$members = $contacts[0]->member;
		if(!empty($members))
		{
			foreach ($members as $member)
			{
				array_push($memberIds, $member->member_id);
			}
		}
		$this->onAfterView(new CEvent($this, array('model'=>$model)));
		$followInfo = Yii::app()->user->getIsGuest() || empty($memberIds) ? array() : Follow::model()->checkMultiFollow2(Yii::app()->user->id, $memberIds);
		$this->render('view',compact('model','members','memberIds','followInfo'));
	}
	/**
	 * 群内搜索,类似查看群
	 * name mobile 
	 */
	public function actionSearch()
	{
		$id = Util::decode(Yii::app()->request->getParam('id'));
		$keyword = trim(Yii::app()->request->getParam('keyword'));
		$model = $this->loadModel($id);
		$members = ContactsMember::model()->pass()->with('member')->findAll(array('condition'=>"contacts_id='".$id."'"));
		$memberIds = array();
		$data = array();
		if(!empty($members) && !empty($keyword))
		{
			foreach ($members as $key => $member)
			{
				$contacts = $member->member;
				if(strpos($contacts->name, $keyword) !== false)
				{
					$data[] = $member;
				}elseif(strpos($contacts->mobile, $keyword) !== false)
				{
					$data[] = $member;
				}
				array_push($memberIds, $member->member_id);
			
			}
		}
		$html = $this->renderPartial('_list',array('model'=>$model,'members'=>$data,'memberIds'=>$memberIds),true);
		$this->returnData(1,$html);
	}
	
	public function onAfterView($event)
	{
		$this->raiseEvent('onAfterView', $event);
	}
	
	/**
	 * 我加入的
	 */
	public function actionMyJoined()
	{
		$contacts = ContactsMember::model()->joined()->with('contacts')->findAll();
		$stat = array();
		if(!empty($contacts))
		{
			$uid = Yii::app()->user->id;
			$contactIds = array();
			foreach ($contacts as $key => $value)
			{
				if($value->contacts->create_mid == $uid)
					unset($contacts[$key]);
				else 
					array_push($contactIds, $value->contacts->id);
			}
			if(!empty($contactIds))
			{
				$sql = "SELECT contacts_id,count(*) AS total FROM contacts_member_rel WHERE contacts_id IN(".join(',', $contactIds).") AND state=".ContactsMember::STATE_PASS." GROUP BY contacts_id";
				$all = Yii::app()->db->createCommand($sql)->queryAll();
				if(!empty($all))
				{
					foreach ($all as $value)
					{
						$stat[$value['contacts_id']] = $value['total'];
					}
				}
			}
		}
		$this->render('myJoined',array('data'=>$contacts,'stat'=>$stat));
	}
	
	/**
	 * 我发起的
	 */
	public function actionMyCreated()
	{
		$data = Contacts::model()->owner()->findAll();
		$stat = array();
		if(!empty($data))
		{
			foreach($data as $contact)
			{
				$sql = "SELECT count(*) AS total,state FROM contacts_member_rel WHERE contacts_id={$contact->id} AND (state=".ContactsMember::STATE_APPLY." OR state=".ContactsMember::STATE_PASS.") GROUP BY state";
				$result = Yii::app()->db->createCommand($sql)->queryAll();
				if(!empty($result))
					foreach ($result as $value)
					{
						if($value['state'] == ContactsMember::STATE_APPLY)
							$stat[$contact->id]['apply'] = $value['total'];
						if($value['state'] == ContactsMember::STATE_PASS)
							$stat[$contact->id]['pass'] = $value['total'];
					}
			}
		}
		$this->render('myCreated',array('data'=>$data,'stat'=>$stat));
	}
	
	/**
	 * 申请加入
	 */
	public function actionApply()
	{
		$id = Util::decode(Yii::app()->request->getParam('id'));
		if(ContactsMember::model()->apply($id))
		{
			$this->onAfterApply(new CEvent($this,array('model' => $this->_member,'contacts_id'=>$id)));
			$this->returnData(1,'已申请,等待审核');
		}else {
			$this->returnData(0,'申请失败');
		}
	}
	
	public function onAfterApply($event)
	{
		$this->raiseEvent('onAfterApply', $event);
	}
	
	
	/**
	 * 审核列表
	 */
	public function actionApplyList()
	{
		$id = Util::decode(Yii::app()->request->getParam('id'));
		$model = $this->loadModel($id);
		if(Yii::app()->user->id != $model->create_mid)
			$this->showMessage(0, '非法请求');
		$members = ContactsMember::model()->applying()->with('member')->findAll(array('condition'=>"contacts_id='".$id."'"));
		$this->render('applylist',array('members'=>$members));
	}
	/**
	 * 同意申请
	 */
	public function actionPass()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		return ContactsMember::model()->verify($id) ? $this->returnData(1,'同意') : $this->returnData(0,'Error');
	}
	
	/**
	 * 拒绝申请
	 */
	public function actionRefuse()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		ContactsMember::model()->verify($id,ContactsMember::STATE_REFUSE) ? $this->returnData(1,'拒绝') : $this->returnData(0,'Error');
	}
	
	/**
	 * 将某人移除群(管理员移除)
	 */
	public function actionRemove()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		ContactsMember::model()->remove($id) ? $this->returnData(1,'成功') : $this->returnData(0, '失败');
	}
	
	/**
	 * 用户主动退出群
	 */
	public function actionQuit()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		ContactsMember::model()->quit($id) ? $this->returnData(1,'成功') : $this->returnData(0, '失败');
	}
	
	/**
	 * 统计活动分享的次数
	 */
	public function actionCountShare()
	{
		if(Yii::app()->request->getIsAjaxRequest())
		{
			$id = Util::decode(Yii::app()->request->getParam('id'));
			$model = $this->loadModel($id);
			$model->updateByPk($model->id, array('share_counts' => $model->share_counts+1));
		}
	}
	
	/**
	 * 通讯录发送到邮箱
	 */
	public function actionSendMail()
	{
		$id = Util::decode(Yii::app()->request->getParam('id')); // 通讯录的id
		$model = $this->loadModel($id);
		$email = $this->_member->email;
		if(empty($email))
		{
			$email = trim(Yii::app()->request->getParam('email'));
			if(!Util::checkIsEmail($email))
			{
				$this->returnData(0, '邮箱格式错误');
			}
			Member::model()->updateByPk($this->_member->id, array('email'=>$email));
		}
		$members = ContactsMember::model()->pass()->with('member')->findAll(array('condition'=>"contacts_id='".$id."'"));
		if(!empty($members))
		{
			$data = array();
			$uid = Yii::app()->user->id;
			foreach ($members as $value)
			{
				$member = $value->member;
				if($member->id == $uid) continue;
				$data[] = array('mobile'=>$member->mobile,'name'=>$member->name,'position'=>$member->position,'company'=>$member->company,'email'=>$member->email,'address'=>$member->address);
			}
			$html = $this->renderPartial('sendmail',compact('data'),true);
			$emails = Extra::model()->getEmail($id, Extra::TYPE_CONTACTS_EMAIL);
			if(!empty($emails))
			{
				array_push($emails, $email);
				if(Util::sendMail($emails, $model->title . '群成员', $html))
					$this->returnData(1,'发送成功');
				else
					$this->returnData(0,'发送失败');
			}else{
				Util::sendMail($email, $model->title . '群成员', $html) ? $this->returnData(1,'发送成功') : $this->returnData(0,'发送失败'); 
			}
		}
		$this->returnData(0,'无可发送成员');
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Contacts::model()->with('creater')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
}