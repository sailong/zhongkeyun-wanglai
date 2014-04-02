<?php

/**
 * 活动
 * @author zjj
 *
 */
class ActivityController extends FrontController
{

	
	/**
	 * 
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl - list,detail,more',
			'ajaxOnly + pass,refuse,cancel,sendMail'	
		);
	}
	
	public function accessRules()
	{
		return array(
			array(
				'deny',
				'actions' => array('index','apply','applyList','pass','refuse','cancel','create','getApplicants','myCreated','myJoined','update'),
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
			'activity' => 'application.components.behaviors.ActivityBehavior'		
		);
	}
	
	/**
	 * 活动列表(全部活动)
	 */
	public function actionList()
	{
		$data = Activity::model()->filter()->current()->findAll();
		$count = count($data);
		if($count<100){
			$past = Activity::model()->filter()->past()->findAll(array('limit'=>100-$count));
			$data = array_merge($data,$past);
		}
		$calculation = Activity::model()->calculateTotalByCity();
		$this->render('list',array('data'=>$data,'calculation'=>$calculation));
	}
	
	/**
	 * 加载更多,以及按城市及标题搜索
	 */
	public function actionMore()
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$condition = '1=1';
			$cid = intval(Yii::app()->request->getParam('cid'));
			$keyword = trim(Yii::app()->request->getParam('keyword'));
			if(!empty($cid))
			{
				$specials = District::getSpecialCityId();
				if(key_exists($cid, $specials))
				{
					$condition = 'province='.$cid;
				}else{
					$condition = 'area='.$cid;
				}
			}
			
			if(!empty($keyword))
			{
				if(!empty($condition))
				{
					$condition .= ' AND title LIKE "%'.$keyword.'%"';
				}else{
					$condition = 'title LIKE "%'.$keyword.'%"';
				}
			}
			
			$current = strtotime(date('Y-m-d',time()));
			$total = Activity::model()->filter()->count($condition);
			$pagination = new CPagination($total);
			$pagination->setPageSize(100);
			$sql = "(SELECT * FROM activity WHERE ".$condition." AND end_time>={$current} AND (state=".Activity::VERIFY_STATE_WITHOUT." OR state=".Activity::VERIFY_STATE_PASS.") ORDER BY id DESC)
					UNION (SELECT * FROM activity WHERE ".$condition." AND end_time<{$current} AND (state=".Activity::VERIFY_STATE_WITHOUT." OR state=".Activity::VERIFY_STATE_PASS.") ORDER BY id DESC)
					LIMIT ".$pagination->getOffset().",".$pagination->getPageSize();
			$data = array();
			$result = Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($result))
			{
				foreach ($result as $value)
				{
					$data[] = (object)$value;
				}
			}
			$output = $this->renderPartial('_list',array('data'=>$data),true);
			$this->returnData(1,$output);
		}
	}
	/**
	 * 我的活动列表
	 */
	public function actionIndex()
	{
		$this->actionMyCreated();
	}
	
	/**
	 * 我参加的活动
	 */
	public function actionMyJoined()
	{
		$c = strtotime(date('Y-m-d',time()));
		$past = ActivityMember::model()->joined()->with(array('activity'=>array('order'=>'end_time DESC','condition'=>"end_time<$c")))->findAll();
		$current = ActivityMember::model()->joined()->with(array('activity'=>array('alias'=>'a','order'=>'a.id DESC','condition'=>"end_time>=$c")))->findAll();
		$data = array_merge($current, $past);
		if(!empty($data))
		{
			$uid = Yii::app()->user->id;
			foreach ($data as $key => $value)
			{
				if($value->activity->create_mid == $uid)
					unset($data[$key]);
			}
		}
		$this->render('myJoined',array('data'=>$data));
	}
	
	/**
	 * 我发起的活动(我创建的活动)
	 */
	public function actionMyCreated()
	{
		//$data = Activity::model()->owner()->recently()->findAll();
		$past = Activity::model()->owner()->past()->findAll();
		$current = Activity::model()->owner()->current()->findAll();
		$data = array_merge($current,$past);
		$this->render('myCreated',array('data'=>$data));
	}
	/**
	 * 创建活动
	 * VIP创建的活动部需要活动审核,普通用户创建的活动,需要审核
	 */
	public function actionCreate()
	{
		$member = Member::model()->findByPk(Yii::app()->user->id);
		$model = new Activity();
		if($_POST)
		{
			if(!isset($_SESSION['already']))
			{
				$_SESSION['already'] = Yii::app()->user->id;  //避免表单重复提交
				$param = $_POST;
				$param['begin_time'] = strtotime($param['begin_time']);
				$param['end_time'] = strtotime($param['end_time']);
				$param['create_mid'] = Yii::app()->user->id;
				$param['create_time'] = time();
				//$param['state'] = $member->is_vip == Member::TYPE_VIP ? Activity::VERIFY_STATE_WITHOUT : Activity::VERIFY_STATE_GOING;
				// 临时普通用户创建的活动也不需要审核
				$param['state'] =  Activity::VERIFY_STATE_WITHOUT;
				$param['verify'] = intval($param['verify']) == Activity::APPLY_VIRIFY_WITH ? Activity::APPLY_VIRIFY_WITH : Activity::APPLY_VIRIFY_WITHOUT;
				if(in_array($param['province'], array_keys(District::getSpecialCityId())))
				{
					$param['area'] = 0;
				}
				$model->attributes = $param;
				$model->setScenario('front');
				if($model->save())
				{
					unset($_SESSION['already']);
					$this->onAfterCreate(new CEvent($this,array('model'=>$model)));
					$this->redirect($this->createUrl('activity/detail',array('id'=>$model->id)));
				}else {
					unset($_SESSION['already']);
					$error = array_values($model->getErrors());
					$this->showMessage(0, $error[0][0]);
				}
			}
		}
		if(isset($_SESSION['already'])) unset($_SESSION['already']);
		$this->render('create',array('model'=>$model));
	}
	
	/**
	 * 创建完活动后
	 */
	public function onAfterCreate($event)
	{
		$this->raiseEvent('onAfterCreate', $event);
	}
	
	/**
	 * 编辑活动
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		if($model->create_mid != Yii::app()->user->id)
			$this->showMessage(0, '非法编辑');
		if($_POST)
		{
			$param = $_POST;
			$param['begin_time'] = strtotime($param['begin_time']);
			$param['end_time'] = strtotime($param['end_time']);
			$param['update_time'] = time();
			//$param['state'] = $model->creater->is_vip == Member::TYPE_VIP ? Activity::VERIFY_STATE_WITHOUT : Activity::VERIFY_STATE_GOING;
			$param['state'] = Activity::VERIFY_STATE_WITHOUT;
			$param['verify'] = intval($param['verify']) == Activity::APPLY_VIRIFY_WITH ? Activity::APPLY_VIRIFY_WITH : Activity::APPLY_VIRIFY_WITHOUT;
			if(in_array($param['province'], array_keys(District::getSpecialCityId())))
			{
				$param['area'] = 0;
			}
			$model->attributes = $param;
			$model->setScenario('front');
			if($model->save())
			{
				$this->redirect(Yii::app()->createUrl('activity/detail',array('id'=>$model->id)));
			}else
			{
				$error = array_values($model->getErrors());
				$this->showMessage(0, $error[0][0]);
			}
		}
		$this->render('update',array('model'=>$model));
	}
	
	/**
	 * 活动详情
	 */
	public function actionDetail($id)
	{
		$model = $this->loadModel($id);
		if($model->state == Activity::VERIFY_STATE_REFUSED)
			$this->showMessage(0, '该活动不存在');
		$this->onAfterView(new CEvent($this,array('model'=>$model)));
		$count = ActivityMember::model()->countApplicants($model->id); //活动已报名人数
		$this->render('detail',array('count'=>$count,'model'=>$model));
	}
	
	public function onAfterView($event)
	{
		$this->raiseEvent('onAfterView', $event);
	}
	
	/**
	 * 活动报名
	 */
	public function actionApply()
	{
		if(Yii::app()->request->getIsAjaxRequest())
		{
			$id = Yii::app()->getRequest()->getParam('activityId');
			$model = $this->loadModel(intval($id));
			$uid = Yii::app()->user->id;
			if($uid == $model->create_mid)
			{
				$this->returnData(0, '怎么能报名自己创建的活动呢！');
			}
			if(time() > $model->end_time && strtotime(date('Y-m-d',time())) > $model->end_time)
			{
				$this->returnData(0, '活动已结束了哦,下次抓紧时间');
			}
			if($model->state==Activity::VERIFY_STATE_GOING)
			{
				$this->returnData(0,'活动审核中,过一段时间再来报名');
			}
			if($model->state == Activity::VERIFY_STATE_REFUSED)
			{
				$this->returnData(0, '活动不可报名');
			}
			
			$max = intval($model->max);
			if($max > 0)
			{
				$count = ActivityMember::model()->countApplicants($model->id); //活动已报名人数
				if($count >= $max)
				{
					$this->returnData(0, '报名人数已满了');
				}
			}
			// 第三方报名要求
			if(!Activity::model()->checkCooperatApply($model, $this->_member))
			{
				$this->returnData(0,'请修改名片信息,确保邮箱,职位,公司信息不为空方可报名');
			}
			$activityMember = ActivityMember::model()->findByAttributes(array('activity_id'=>$id,'member_id'=>$uid));
			if(empty($activityMember))
			{
				$activityMember = new ActivityMember();
				$activityMember->activity_id = $id;
				$activityMember->member_id = Yii::app()->user->id;
				$activityMember->create_time = time();
			}else{
				if($activityMember->canceled == ActivityMember::CANCELED_NO)
				{
					$this->returnData(0, '您已经报名过了哦！');
				}
				$activityMember->canceled = ActivityMember::CANCELED_NO;
				$activityMember->create_time = time(); //更改报名时间
			}
			$activityMember->state = $model->verify == Activity::APPLY_VIRIFY_WITH ? ActivityMember::VERIFY_STATE_APPLY : ActivityMember::VERIFY_STATE_WITHOUT;
			$activityMember->save();
			$this->onAfterApply(new CEvent($this,array('model'=>$model,'member'=>$this->_member)));
			$message = $model->verify == Activity::APPLY_VIRIFY_WITH ? '报名成功,等待审核' : '报名成功';
			$this->returnData(1, $message, $activityMember->id);
		}
	}
	
	public function onAfterApply($event)
	{
		$this->raiseEvent('onAfterApply', $event);
	}
	
	/**
	 * 取消活动报名
	 */
	public function actionCancel()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		if(ActivityMember::model()->cancle($id))
		{
			$this->onAfterCancel(new CEvent($this,array('applyId'=>$id,'member'=>$this->_member)));
			$this->returnData(1,'取消成功');
		}
		else
			$this->returnData(0,'失败');
	}
	
	/**
	 * 取消后同步取消合作方的活动报名
	 * @param unknown $event
	 */
	public function onAfterCancel($event)
	{
		$this->raiseEvent('onAfterCancel', $event);
	}
	
	/**
	 * 查看活动参与人
	 */
	public function actionGetApplicants()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		$model = $this->loadModel($id);
		$data = ActivityMember::model()->applied()->with('applicant')->findAll(array('condition'=>"activity_id='{$id}'"));
		$memberIds = array();
		if(!empty($data))
		{
			foreach ($data as $member)
			{
				array_push($memberIds, $member->member_id);
			}
		}
		$followInfo = Follow::model()->checkMultiFollow2(Yii::app()->user->id, $memberIds);
		$this->render('applicants',compact('model','data', 'memberIds','followInfo'));
	}
	/**
	 * 活动参与者搜索
	 */
	public function actionSearch()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		$model = $this->loadModel($id);
		$result = ActivityMember::model()->applied()->with('applicant')->findAll(array('condition'=>"activity_id='{$id}'"));
		$memberIds = array();
		$keyword = trim(Yii::app()->request->getParam('keyword'));
		$data = array();
		if(!empty($result) && !empty($keyword))
		{
			foreach ($result as $value)
			{
				$member = $value->applicant;
				if(strpos($member->name, $keyword) !== false)
				{
					$data[] = $value;
				}elseif(strpos($member->mobile, $keyword) !== false)
				{
					$data[] = $value;
				}else{
					continue;
				}
				array_push($memberIds, $value->member_id);
			}
		}
		$html = $this->renderPartial('_list',compact('model','data', 'memberIds'),true);
		$this->returnData(1,$html);
	}
	
	/**
	 * 审核活动报名
	 */
	private function applyVerify($type=ActivityMember::VERIFY_STATE_REFUSE)
	{
		$id = Yii::app()->request->getParam('id');
		$model = ActivityMember::model()->with('activity')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if($model->state != ActivityMember::VERIFY_STATE_APPLY)
		{
			$this->returnData(0, '已审核过了');
		}
		if($model->activity->create_mid != Yii::app()->user->id)
		{
			$this->returnData(0, '越俎代庖了！');
		}
		$model->state=$type;
		$model->verify_time = time();
		if($model->save())
		{
			$this->returnData(1,'审核成功');
		}else
		{
			$this->returnData(0,'网络问题');
		}
	}
	
	/**
	 * 审核活动报名,通过
	 */
	public function actionPass()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		ActivityMember::model()->pass($id) ? $this->returnData(1,'成功') : $this->returnData(0,'失败');
	}
	
	/**
	 * 审核活动报名,拒绝
	 */
	public function actionRefuse()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		ActivityMember::model()->refuse($id) ? $this->returnData(1,'成功') : $this->returnData(0,'失败');
	}
	
	/**
	 * 查看申请列表
	 */
	public function actionApplyList()
	{
		$id = intval(Yii::app()->request->getParam('id'));  //活动id
		$model = $this->loadModel($id);
		if($model->create_mid != Yii::app()->user->id)
		{
			$this->showMessage(0, '非法访问');
		}
		// 不需要后天审核或者后台审核已经通过的活动
		if(in_array(intval($model->state), array(Activity::VERIFY_STATE_WITHOUT,Activity::VERIFY_STATE_PASS)))
		{
			$data = ActivityMember::model()->applying()->with('applicant')->findAll(array('condition' => 'activity_id='.$id));
		}else {
			$this->showMessage(0, '活动暂时不可执行该操作');
		}
		$this->render('verify',array('data'=>$data));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Activity::model()->with(array('provinceName','areaName'))->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if($model->state == Activity::VERIFY_STATE_REFUSED || $model->state == Activity::VERIFY_STATE_DELETED)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * 统计活动分享的次数
	 */
	public function actionCountShare()
	{
		if(Yii::app()->request->getIsAjaxRequest())
		{
			$id = intval(Yii::app()->request->getParam('id'));
			$model = $this->loadModel($id);
			$model->shares += 1;
			$model->save();
		}
		
	}
	
	/**
	 * 发送报名用户到邮箱
	 */
	public function actionSendMail()
	{
		$id = intval(Yii::app()->request->getParam('id')); // 活动id
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
		$members = ActivityMember::model()->applied()->with('applicant')->findAll(array('condition'=>"activity_id='".$id."'"));
		if(!empty($members))
		{
			$data = array();
			$uid = Yii::app()->user->id;
			foreach ($members as $value)
			{
				$member = $value->applicant;
				if($member->id == $uid) continue;
				$data[] = array('mobile'=>$member->mobile,'name'=>$member->name,'position'=>$member->position,'company'=>$member->company,'email'=>$member->email,'address'=>$member->address);
			}
			if(!empty($data))
			{
				$html = $this->renderPartial('/contacts/sendmail',compact('data'),true);
				// 特殊处理,临时的
				if($model->id == Activity::$special_id)
				{
					$emails = Activity::$emails;
					array_push($emails, $email);
					Util::sendMail($emails, $model->title . '群成员', $html);
				}else{
					$emails = Extra::model()->getEmail($id, Extra::TYPE_ACTIVITY_EMAIL);
					if(!empty($emails))
					{
						array_push($emails, $email);
						Util::sendMail($emails, $model->title . '群成员', $html);
					}else{
						Util::sendMail($email, $model->title . '群成员', $html);
					}
				}
				$this->returnData(1,'发送成功');
			}
		}
		$this->returnData(0,'无可发送成员');
	}
}