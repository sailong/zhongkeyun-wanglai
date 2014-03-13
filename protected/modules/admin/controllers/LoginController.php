<?php
/**
 * @author wangyujie
 * 2013-3-21
 */
class LoginController extends AdminController
{
	
	public function actionIndex()
	{
		if(Yii::app()->user->getIsGuest())
		{
			$this->renderPartial('login');
		}else{
			$this->redirect(array('/admin/default'));
		}
	}

    /**
     * 登录
     */
	public function actionLoginDo()
	{
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		if(!$username || !$password) die('username or password is empty!');
	
		$model=new LoginForm;
	
		$model->username = $username;
	
		$model->password = $password;
	
		if($model->login())
		{
			$this->redirect(array('/admin/Default'));
		}else
		{
			header('content-type:text/html;charset=utf-8');
			//echo $model->getErrorMsg();
			$this->showMessage($model->getErrorMsg(),0);
			die;
		}
	}
	
	/**
	 * 退出
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('/admin'));
	}
}