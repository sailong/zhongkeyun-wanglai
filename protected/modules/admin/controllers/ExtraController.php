<?php

/**
 * 一些特殊的处理
 * @author JZLJS00
 *
 */
class ExtraController extends AdminController
{
	
	
	
	
	
	/**
	 * 活动或者群增加邮件
	 */
	public function actionIndex()
	{
		$object_id = Yii::app()->request->getParam('id');
		$type = Yii::app()->request->getParam('type');
		if(empty($object_id) || empty($type))
		{
			$this->showMessage('参数错误',0);
		}
		$model = Extra::model()->findByAttributes(array('object_id'=>$object_id,'type'=>$type));
		if(isset($_POST['email']))
		{
			$email = trim($_POST['email']);
			if(!empty($email))
			{
				if(empty($model))
				{
					$model = new Extra();
					$model->type = $type;
					$model->object_id = $object_id;
					$model->create_time = time();
				}
				$model->email = $email;
				if($model->save())
				{
					Yii::app()->user->setFlash('email','保存成功');
				}else{
					print_r($model->getErrors());
				}
			}else{
				Yii::app()->user->setFlash('email','请填写邮箱');
			}
		}
		$this->render('index',compact('object_id','type','model'));
	}
	
	
	
}