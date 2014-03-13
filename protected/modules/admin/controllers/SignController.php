<?php

/**
 * 后台签名
 * @author JZLJS00
 *
 */
class SignController extends AdminController
{
	
	public $nav = '签名活动';
	/**
	 * 
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('SignActivity',array(
				'criteria' => array(
					'order'  => 'id desc'
				),
				'pagination' => array(
					'pageSize' => 10,
				)
		));
		
		$this->render('index',array('dataProvider'=>$dataProvider));
	}
	
	
	public function actionCreate()
	{
		$model = new SignActivity();
		if(isset($_POST['SignActivity']))
		{
			$model->attributes = $_POST['SignActivity'];
			$file = CUploadedFile::getInstance($model,'img');   //获得一个CUploadedFile的实例
			if(is_object($file) && get_class($file) === 'CUploadedFile')
			{
				$model->img = 'static/images/sign/'.time().rand(0,100).'.'.$file->extensionName;
			}
			$model->create_time = time();
			if($model->save())
			{
				if(is_object($file) && get_class($file) === 'CUploadedFile'){  
			 		$file->saveAs($model->img);    // 上传图片  
                }
                $this->redirect($this->createUrl('index'));  
			}else{
				print_r($model->getErrors());
			}
		}
		$this->render('create',array('model'=>$model));
		
	}
	
	public function actionUpdate()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		$model = SignActivity::model()->findByPk($id);
		if(isset($_POST['SignActivity']))
		{
			$param = $_POST['SignActivity'];
			$file = CUploadedFile::getInstance($model,'img');   //获得一个CUploadedFile的实例
			if(is_object($file) && get_class($file) === 'CUploadedFile')
			{
				$param['img'] = 'static/images/sign/'.time().rand(0,100).'.'.$file->extensionName;
			}else{
				unset($param['img']);
			}
			$model->attributes = $param;
			$model->update_time = time();
			if($model->save())
			{
				if(is_object($file) && get_class($file) === 'CUploadedFile'){
					$file->saveAs($model->img);    // 上传图片
				}
				$this->redirect($this->createUrl('index'));
			}else{
				print_r($model->getErrors());
			}
		}
		$this->render('update',array('model'=>$model));
	}
	
	
}