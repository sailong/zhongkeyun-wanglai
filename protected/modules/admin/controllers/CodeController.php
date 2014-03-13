<?php

/**
 * 根据短信号码搜索验证码
 * @author JZLJS00
 *
 */
class CodeController extends AdminController
{
	
	protected $nav = '验证码查询';
	
	
	public function actionIndex()
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$result = array('status'=>0,'msg'=>'不存在');
			$mobile = Yii::app()->request->getParam('mobile');
			$time = time()-900;
			$sql = "select content from message_log where mobile={$mobile} and created_at>{$time} order by created_at desc limit 1";
			$code = Yii::app()->db->createCommand($sql)->queryScalar();
			if(!empty($code))
			{
				$result = array('status'=>1,'msg'=>$code);
			}
			echo json_encode($result);
			Yii::app()->end();
		}
		$this->render('index');
	}
	
	
	
}