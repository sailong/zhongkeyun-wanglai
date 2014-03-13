<?php

class FeedbackController extends AdminController
{
	
	public $nav='意见反馈';
	
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Feedback',
				array(
					'criteria' => array(
						'order' => 'id DESC'	
					),
					'pagination' => array(
						'pageSize' => 20
					),
				)
		);
		$this->render('index',array('dataProvider'=>$dataProvider));
	}
	
	public function actionResolve()
	{
		$id = Yii::app()->request->getParam('id');
		$model = Feedback::model()->findByPk($id);
		if($model === null)
		{
			$this->ajaxJsonReturn(0,'不存在');
		}
		$model->resolved = Feedback::RESOLVED;
		$model->operator_id = Yii::app()->user->getState('uid');
		$model->operator_name = Yii::app()->user->getState('nickname');
		$model->save();
		$this->ajaxJsonReturn(1,'成功');
	}
	
	
}