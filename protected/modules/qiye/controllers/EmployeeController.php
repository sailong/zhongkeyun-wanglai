<?php

class EmployeeController extends QiyeController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/qiye/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//note 默认一个企业账号当前只有一个默认系统群(员工群)
		$contacts = Contacts::model()->findByAttributes(array('create_mid'=>Yii::app()->user->mid,'default'=>Contacts::DEFAULT_YES));
		
		$dataProvider = new CActiveDataProvider('Member',array(
			'criteria' => array(
				'alias' => 'm',
				'condition' => "cmr.contacts_id='{$contacts->id}' and state=".ContactsMember::STATE_PASS,
				'join' => 'right join contacts_member_rel as cmr on cmr.member_id=m.id'		
			),
			'sort' => array(
				'defaultOrder' => 'id desc',
				'attributes' => array('id','name','mobile')
			)
				
		));
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Member::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}
