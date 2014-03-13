<?php

class SmsController extends AdminController
{
	
	public $nav='发送短信';
	
	public function actionSend()
	{
		
		$this->render('send');
	}
	
	public function actionSendDo()
	{
		$mobile = trim(Yii::app()->request->getParam('mobile'),'#');
		$content = trim(Yii::app()->request->getParam('content'));
		if(!$mobile || !$content)
		{
			$this->ajaxJsonReturn(0,'手机号码与短信内容都不能为空');
		}
		$mobileArr = explode('#', $mobile);
		$mobileArr = array_unique($mobileArr);
		$mobile = implode(',', $mobileArr);
		//if(!Helper::checkMobile($mobile)) $this->ajaxJsonReturn(0,'手机号码格式不正确');
		Yii::import('ext.Sms.sms');
		$sms = new sms();
		$data = $sms->send($mobile,$content);
		$code = isset($data[1]) && trim($data[1])==0 ? 1 : 0;
		$this->ajaxJsonReturn($code,$code ? '发送成功':'发送失败，请稍后再试！');
	}
	
	
}