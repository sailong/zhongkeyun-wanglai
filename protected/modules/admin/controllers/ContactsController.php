<?php

class ContactsController extends AdminController
{
	
	public $nav='活动管理';
	
	public function actionIndex()
	{
		
		$dataProvider = new CActiveDataProvider('Contacts',array(
			'criteria' => array(
				'with' => 'creater',
				'alias' => 'cmr',
				'order' => 'cmr.id DESC'	
			),
			'pagination' => array(
				'pageSize' => 20		
					
			)	
				
		));
		$this->render('index',array('dataProvider'=>$dataProvider));
	}
}