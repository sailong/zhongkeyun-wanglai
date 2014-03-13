<?php

class DefaultController extends AdminController
{
	public function actionIndex()
	{
		
		//$this->redirect(array('/admin/model'));
		$this->render('index');
	}
}