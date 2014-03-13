<?php
/**
 * 往来默认主页
 * @author JZLJS00
 *
 */
class IndexController extends FrontController
{
	
    public function actionIndex()
    {   
    	$this->render('index');
    }
    
}
?>