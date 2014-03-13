<?php
class TestController extends CController
{
	public function actions(){
		return array(
			 'test'=>'application.controllers.post.TestAction',
		);
	}
	
// 	public function actionTestlist(){
// 		if(isset($_GET['page'])){
// 			$page = (int)$_GET['page'];
// 		}else{
// 			throw new CHttpException(404,'invalid request');
// 		}
// 	}
	
	public function actionTestlist($page){
        $page = (int)$page;
        echo 333;die;
	}
}