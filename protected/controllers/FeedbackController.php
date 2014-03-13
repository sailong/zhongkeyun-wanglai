<?php

/**
 * 意见反馈控制类
 * @author JZLJS00
 *
 */
class FeedbackController extends FrontController
{
	
	/**
	 * 添加意见反馈
	 */
// 	public function actionCreate()
// 	{
// 		$openid = $this->getOpenid();
// 		if(empty($openid))
// 			$this->showMessage(0, '非法请求');
// 		$model = Member::model()->getMember($openid);
// 		if(empty($model))
// 			$this->showMessage(0, '用户不存在');
// 		if($_POST) 
// 		{
// 			$content = trim($_POST['feedback']);
// 			if(empty($content))
// 				$this->showMessage(0, '内容不可为空');
// 			$feedback = new Feedback();
// 			$feedback->mid=$model->id;
// 			$feedback->member_name = $model->name;
// 			$feedback->content = htmlentities($content,ENT_QUOTES,'UTF-8');
// 			$feedback->create_time = time();
// 			if($feedback->save())
// 			{
// 				$url = $this->createShareUrl($model->id);
// 				$this->showMessage(0,'反馈成功',$url);
// 			}
// 		}
// 		$this->render('create');
		
// 	}
	
	/**
	 * 人工客服
	 */
	public function actionService()
	{
		$this->render('service');
	}
	
}