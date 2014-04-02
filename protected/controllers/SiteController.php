<?php
/**
 * 登录及创建名片等控制器
 * @author JZLJS00
 *
 */
class SiteController extends FrontController
{
	//private $_invalid_time = 900; //失效时间900秒
	//private $_specialMobiles = array('4000-7370-88' => '4000737088');
	private $_error = '';
	
	
	public $defaultAction = 'login';
	
	/**
	 * 重载FrontController的init
	 * @see FrontController::init()
	 */
	public function init()
	{
		
		
	}
	
	/**
	 * 重载FrontController的beforeAction
	 * @see FrontController::beforeAction()
	 */
	public function beforeAction($action)
	{
		return true;
	}
	
	/**
	 * 在登录之前,如果当前已是普通登录账号,则需要先退出;如果企业已登录账号,则跳转到企业个人主页
	 */
	private function beforeLogin()
	{
		if(!Yii::app()->user->getIsGuest())
		{
			if(!Yii::app()->user->getState('qiye'))
			{
				Yii::app()->user->logout();
			}else{
				$this->redirect(array('/member/index'));
			}
		}
	}
	
	/**
	 * 企业登录
	 */
	public function actionLogin()
	{
		$this->beforeLogin();
		if(isset($_POST['Login']))
		{
			$userIdentity = new QiyeUserIdentity($_POST['Login']['username'], $_POST['Login']['password']);
			$userIdentity->authenticate();
			$error = NULL;
			switch($userIdentity->errorCode)
			{
				case QiyeUserIdentity::ERROR_NONE:
					Yii::app()->user->login($userIdentity,$this->duration);
					$this->redirect('/member/index');
					break;
				case QiyeUserIdentity::ERROR_USERNAME_INVALID:
					$error = '用户名错误';
					break;
				case QiyeUserIdentity::ERROR_PASSWORD_INVALID:
					$error = '密码错误';
					break;
				case QiyeUserIdentity::ERROR_CARD_NOTEXIST:
					$error = '企业名片不存在';
			}
			if($error !== NULL)
			{
				Yii::app()->user->setFlash('error',$error);
			}
		}
		$this->render('login');
	}
	
	/**
	 * 退出登录
	 */
	public function actionLogout()
	{
		setcookie('openid','',time()-3600);
		Yii::app()->user->logout();  //活动相关的用到
		$this->redirect('/');
	}
	
	/**
	 * 显示错误
	 */
	public function actionError()
	{
		$error = Yii::app()->errorHandler->error;
		if($error['code'] == '404')
		{
			$this->renderPartial('//system/error404');
		}else{
			if ($error) {
				echo $error['message'];
			}
		}
	}
	
	/**
	 * 登录后的页面跳转(暂未使用)
	 */
	private function go()
	{
		$refer = Util::getCookie('refer');
		if(empty($refer))
		{
			$refer = $this->createShareUrl(Yii::app()->user->id);
		}
		$msg = Util::getCookie('msg');
		$this->clearCookie();  //清除设定的cookie
		if(!empty($msg))
		{
			$this->showMessage(1, $msg,$refer);
		}else{
			$this->redirect($refer);
		}
	}
	/**
	 * 登录关联的操作(报名活动,关注名片,增加小伙伴等)(暂未使用)
	 */
	private function afterLogin()
	{
		$action = Util::getCookie('action');
		if(!empty($action))
		{
			$actions = array_fill_keys(explode('/', $action),1);
			extract($actions);
			// 关注名片
			if(isset($follow) && $follow === 1)
			{
				$this->addFollow();
			}
			// 报名参加活动
			if(isset($apply) && $apply === 1)
			{
				$this->addApply();
			}
			// 添加小伙伴
			if(isset($addPartner) && $addPartner === 1)
			{
				$this->addPartner();
			}
		}
	}
	
	/**
	 * 加关注
	 */
	private function addFollow()
	{
		$objectId = intval(Util::getCookie('objectId'));
		$uid = Yii::app()->user->id;
		if(!empty($objectId) && $uid != $objectId)
		{
			$model = Member::model()->findByPk($objectId);
			if($model !== null && $model->from ==1)
			{
				$follow = Follow::model()->findByAttributes(array('mid'=>$uid,'follow_mid'=>$objectId));
				if(!empty($follow))
				{
					if($follow->is_deleted == Follow::FOLLOW_OUT)
						$follow->is_deleted = Follow::FOLLOW_IN;
				}else {
					$follow = new Follow();
					$follow->mid = $uid;
					$follow->follow_mid = $objectId;
					$follow->follow_at  = time();
				}
				$follow->is_new = Follow::NEW_YES;
				$follow->save();
			}
		}
	}
	
	/**
	 * 报名参加活动
	 */
	private function addApply()
	{
		$objectId = intval(Util::getCookie('objectId'));
		if(!empty($objectId))
		{
			Activity::model()->apply($objectId,Yii::app()->user->id);
		}
	}
	
	/**
	 * 增加小伙伴
	 */
	private function addPartner()
	{
		$objectId = intval(Util::getCookie('objectId'));
		if(!empty($objectId) && $objectId != Yii::app()->user->id)
		{
			$partnerModel = new Partner;
			$partnerModel->new_uid = Yii::app()->user->id;
			$partnerModel->from_uid = $objectId;
			$partnerModel->created_at = time();
			$partnerModel->is_new = Partner::NEW_YES;
			$partnerModel->save();
		}
	}

}
