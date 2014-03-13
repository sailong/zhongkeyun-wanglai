<?php

class ActivityController extends QiyeController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/qiye/column2';

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
				'actions'=>array('create','update','getCity','index','applicants','view'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel(Util::decode($id)),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Activity;
		if(isset($_POST['Activity']))
		{
			$param = $_POST['Activity'];
			$model->attributes = $param;
			$this->performAjaxValidation($model);
			$param['begin_time'] = strtotime($param['begin_time']);
			$param['end_time'] = strtotime($param['end_time']);
			$param['create_mid'] = Yii::app()->user->mid;
			$param['create_time'] = time();
			$param['state'] =  Activity::VERIFY_STATE_WITHOUT;
// 			if(in_array($param['province'], array_keys(District::getSpecialCityId())))
// 			{
// 				$param['area'] = 0;
// 			}
			$model->attributes=$param;
			if($model->save())
			{
				Yii::app()->user->setFlash('activityMessage','创建成功');
				$this->redirect(array('view','id'=>Util::encode($model->id)));
			}else{
				Yii::app()->user->setFlash('activityMessage','创建失败');
			}
		}
		$model->begin_time = '';
		$model->end_time = '';
		$model->max='';
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel(Util::decode($id));
		if(isset($_POST['Activity']))
		{
			$param = $_POST['Activity'];
			$model->attributes = $param;
			$this->performAjaxValidation($model);
			$param['begin_time'] = strtotime($param['begin_time']);
			$param['end_time'] = strtotime($param['end_time']);
			$param['update_time'] = time();
			$model->attributes=$param;
			if($model->save())
			{
				Yii::app()->user->setFlash('activityMessage','编辑成功');
				$this->redirect(array('view','id'=>Util::encode($model->id)));
			}else{
				Yii::app()->user->setFlash('activityMessage','编辑失败');
			}
		}
		$model->begin_time = date('Y-m-d H:i:s', $model->begin_time);
		$model->end_time = date('Y-m-d H:i:s', $model->end_time);
		if($model->max == 0)
			$model->max = '';
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Activity',array(
			'criteria' => array(
				'condition' => "create_mid='".Yii::app()->user->mid."'",
			),
			'sort' => array(
				'defaultOrder' => 'id desc',
				'attributes' => array('create_time','begin_time','end_time')
			)
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionGetCity()
	{
		$provinceId = intval(Yii::app()->request->getParam('provinceId',1));
		if($provinceId>0)
		{
			$data = District::model()->getAreaList($provinceId);
			foreach ($data as $key => $value)
			{
				echo CHtml::tag('option', array('value'=>$key), CHtml::encode($value), true);
			}
		}
	}
	
	/**
	 * 查看报名成员
	 */
	public function actionApplicants($id)
	{
		$id = intval(Util::decode($id));
		$model = $this->loadModel($id);
		$dataProvider = new CActiveDataProvider('Member',array(
			'criteria' => array(
				'alias' => 'm',
				'condition' => "amr.activity_id='{$id}' and amr.canceled=".ActivityMember::CANCELED_NO." and amr.state in(".ActivityMember::VERIFY_STATE_PASS.",".ActivityMember::VERIFY_STATE_WITHOUT.")",
				'join' => 'right join activity_member_rel as amr on amr.member_id=m.id'
			),
			'sort' => array(
				'defaultOrder' => 'id desc',
				'attributes' => array('id','name','mobile')
			)
		));
		$this->render('applicants',array(
				'dataProvider'=>$dataProvider,
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
		$model=Activity::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='activity-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
