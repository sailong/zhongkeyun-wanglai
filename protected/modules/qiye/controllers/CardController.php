<?php

class CardController extends QiyeController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/qiye/column2';
	
	//public $defaultAction = 'index';

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('view','update','photo','index'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * 名片主页
	 */
	public function actionIndex()
	{
		$this->actionView(Util::encode(Yii::app()->user->mid));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$this->render('view',array(
			'model'=>$model
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$this->performAjaxValidation($model);
		if(isset($_POST['Member']))
		{
			$model->attributes=$_POST['Member'];
			if($model->save())
				$this->redirect(array('view','id'=>Util::encode($model->id)));
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionPhoto($id)
	{
		$model=$this->loadModel($id);
		if(isset($_FILES['avatar']))
		{
			$image = Image::upload('avatar','avatar',array('s'=>'200,200'),0);
			if($image && $image['filePath'])
			{
				$model->saveAttributes(array('avatar'=>$image['filePath']));
				Yii::app()->user->setFlash('photoMessage','上传成功');
			}else{
				Yii::app()->user->setFlash('photoMessage','上传失败');
			}
		}
		$this->render('photo',array(
			'model'=>$model		
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$id = intval(Util::decode($id));
		if($id == Yii::app()->user->mid)
		{
			$model=Member::model()->findByPk($id);
			if($model===null)
				throw new CHttpException(404,'The requested page does not exist.');
			return $model;
		}else{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='member-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
}
