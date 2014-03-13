<?php

class QiyeModule extends CWebModule
{
	
	
	public $returnUrl = array("/qiye");
	
	public $returnLogoutUrl = array("/qiye/login");
	
	public $loginUrl = array('/qiye/login');
	
	/**
	 * @var int
	 * @desc Remember Me Time (seconds), defalt = 2592000 (30 days)
	 */
	public $rememberMeTime = 2592000; // 30 days
	
	
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		
		$this->setImport(array(
			'qiye.controllers.QiyeController',
			'qiye.models.*',
			'qiye.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		$controllerId = $controller->getId();
		if($controllerId != 'login')
		{
			if(Yii::app()->user->getIsGuest())
			{
				Yii::app()->controller->redirect($this->loginUrl);
			}
			
			if(parent::beforeControllerAction($controller, $action))
			{
				$member = Member::model()->findByPk(Yii::app()->user->mid);
				if(empty($member) || $member->is_qiye != Member::QIYE_YES)
						throw new CHttpException(403,'没有权限');
				return true;
			}
			else
				return false;
		}	
		return true;
	}
	
	/**
	 * @return hash string.
	 */
	public static function encrypting($string="") {
		$hash = 'md5';
		if ($hash=="md5")
			return md5($string);
		if ($hash=="sha1")
			return sha1($string);
		else
			return hash($hash,$string);
	}
	
}
