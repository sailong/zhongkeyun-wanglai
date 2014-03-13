<?php

class QiyeController extends AdminController
{
	
	public $nav = '企业名片';
	
	public $defaultAction = 'create';
	/**
	 * 创建企业名片
	 * @see AdminController::actionCreate()
	 */
	public function actionCreate()
	{
		$member = new Member();
		if(isset($_POST['Member']))
		{
			$param = $_POST['Member'];
			if(empty($param['name']) || empty($param['wanglai_number']) || empty($param['mobile']))
			{
				$this->showMessage('请填写必要信息',0);
			}
			$wanglaiNumber = $param['wanglai_number'];
			$result = Member::model()->findByAttributes(array('wanglai_number'=>$wanglaiNumber));
			if(!empty($result))
			{
				$this->showMessage('修改失败:往来号 '.$wanglaiNumber.' 已经被其他人使用',0);
			}
			$numberModel = Number::model()->find('number = :a',array(':a'=>$wanglaiNumber));
			if(empty($numberModel)) $this->showMessage('修改失败:往来号 '.$wanglaiNumber.' 不存在',0);
			$member->name = $param['name'];
			$member->weixin_openid = 'temp_wanglai_'.md5(uniqid().mt_rand(1,999999));
			$member->mobile = $param['mobile'];
			$member->created_at = time();
			$member->initial = Util::getFirstLetter($param['name']);
			$member->wanglai_number = $wanglaiNumber;
			$member->is_qiye = Member::QIYE_YES;
			if($member->save())
			{
				$this->registerQiye($member);
				$this->showMessage('创建成功',1,array('/admin/Card/index'));
			}
		}
		$this->render('create',array('model'=>$member));
	}
	
	private function registerQiye($member)
	{
		Yii::import('application.modules.qiye.models.User');
		Yii::import('application.modules.qiye.QiyeModule');
		$user = new User();
		$attributes = array(
			'username' => $member->wanglai_number,
			'password' => QiyeModule::encrypting(7654321),
			'email'    => $member->email,
			'activkey' => QiyeModule::encrypting(microtime() . 7654321),
			'superuser'=> 0,
			'status'   => User::STATUS_ACTIVE,
			'mid'	   => $member->id
		);

		$user->setAttributes($attributes,false);
		$user->save();

		// 默认创建一个企业员工群 @todo 是否需要加特殊微群的标识?
		$contacts = new Contacts();
		$contacts->attributes = array(
			'title' => '员工通讯录',
			'description' => '员工通讯录',
			'type' => 2,
			'privacy' => Contacts::PRIVACY_PRIVATE,
			'create_mid' => $member->id,
			'create_time' => time(),
			'default' => Contacts::DEFAULT_YES
		);
		$contacts->save();
	}
	
	/**
	 * 企业账号
	 * @see QiyeController::actionIndex()
	 */
	public function actionIndex()
	{
		Yii::import('application.modules.qiye.models.User');
		$dataProvider = new CActiveDataProvider('User',array(
			'criteria'=>array(
				'condition'=>'superuser=0',
				'order'=>'id DESC'
			)	
		));
		$this->render('index',array('dataProvider'=>$dataProvider));
	}
	
	public function actionUpdate()
	{
		$uid = intval(Yii::app()->request->getParam('id'));
		Yii::import('application.modules.qiye.models.User');
		$model = User::model()->findByPk($uid);
		if(!empty($model))
		{
			if(isset($_POST['User']))
			{
				$param = $_POST['User'];
				if(empty($param['username']) && empty($param['password']))
				{
					$this->showMessage('请输入信息',0);
				}
				$data = array();
				if(!empty($param['username']))
					$data['username'] = $param['username'];
				if(!empty($param['password']))
				{
					if($param['password'] !== $param['repeatPassword'])
					{
						$this->showMessage('两次输入密码不同',0);
					}
					$length = strlen($param['password']);
					if($length<4 || $length>10)
					{
						$this->showMessage('密码长度在4到10个字符之间');
					}
					$data['password'] = md5(trim($param['password']));
				}
				$model->setAttributes($data,false);
				$model->save();
				$this->showMessage('修改成功',1,array('/admin/qiye/index'));
			}
		}
		$this->render('update',array('model'=>$model));
	}
}