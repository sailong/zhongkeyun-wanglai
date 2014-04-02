<?php

class AdminController extends CController
{
	public $layout='main';
	public $uid;

	public function init()
	{
		Yii::app()->user->loginUrl = Yii::app()->createUrl('/admin/login/index');
		Yii::app()->setHomeUrl('/adminwl');
	}
    
    public function filters()
    {
    	return array('accessControl');
    }

    public function accessRules()
    {
        return array(
        	array('allow','controllers'=>array('login'),'actions'=>array('index','loginDo'),'users'=>array('*')),
            array('allow', 'expression'=>array($this, 'isAdmin')),
            array('deny', 'users'=>array('*')),
        );
    }

    protected function isAdmin()
    {
  		$this->uid = Yii::app()->user->getState('uid');
        $isAdmin = Yii::app()->user->getState('role') ? true : false;
        if($isAdmin)
        {
        	if(!$this->checkPurview($this->getId()))$this->showMessage('没有操作权限',0);
        }
        
        return $isAdmin;
    }
	
    
    public function ajaxReturn($msg)
    {
    	header('content-type:text/html;charset=utf-8');
    	echo $msg;
    	die;
    }
    
    public function ajaxJsonReturn($code,$msg)
    {
    	header('content-type:text/html;charset=utf-8');
    	echo json_encode(array('code' => $code,'msg'=>$msg));
    	die;
    }
    
    /**
     * 主菜单列表
     * @param number $isShowInfoCate
     */
    public function getMenuList($isShowSubate=0)
    {
    	$menu[]=array('id'=>'Card/index','name'=>'名片管理');
    	$menu[]=array('id'=>'qiye/index','name'=>'企业用户');
    	$menu[]=array('id'=>'Stat/index','name'=>'数据统计');
    	$menu[]=array('id'=>'Stat/GetActiveMember','name'=>'活跃用户');
    	//$menu[]=array('id'=>'Feedback/index','name'=>'意见反馈');
    	$menu[]=array('id'=>'Sms/send','name'=>'发送短信');
    	$menu[]=array('id'=>'Activity/index','name'=>'活动管理');
    	//$menu[]=array('id'=>'Code/index','name'=>'查询验证码');
    	$menu[]=array('id'=>'Contacts/index','name'=>'微群');
    	$menu[]=array('id'=>'Sign/index','name'=>'签名');
    	$menu[]=array('id'=>'Article/index','name'=>'文章管理');
    	$menu[]=array('id'=>'Sendmail/index','name'=>'发送邮件');
    	
    	//$menu[]=array('id'=>'User','name'=>'系统用户管理');
    	
    	return $menu;
    }
    /**
     * 权限判断
     */
    public function checkPurview($controller_id,$info_cate_id=0)
    {
    	$controller_id =ucfirst($controller_id);
    	if($controller_id=='Default' || $this->checkIsManage()) return true;
    	$purview = Yii::app()->user->getState('purview');
    	if(!in_array($controller_id,$purview['menu'])) return false;
    	return true;
    }

    /**
     * 判断是否是总管理员
     * @return boolean
     */
    public function checkIsManage()
    {
    	if(Yii::app()->user->getState('role')==1) return true;
    	return false;
    }
    
    public function showMessage($message='成功', $status='1',$url=false,$time=2)
    {
    
    	$message = ($status==1 ? 'SUCCESS:' : 'ERROR:').$message;
    	$image = $status==1 ? 'success.png' : 'error.png';
    	header('content-type:text/html;charset=utf-8');
    	$back_color ='#ff0000';
    	if($status =='1')
    	{
    		$back_color= 'blue';
    	}
    	if(is_array($url))
    	{
    		$route=isset($url[0]) ? $url[0] : '';
    		$url=$this->createUrl($route,array_splice($url,1));
    	}
    	if ($url)
    	{
    		$url = "window.location.href='{$url}'";
    	}
    	else
    	{
    		$url = "history.back();";
    	}
    	echo <<<HTML
<div>
    		<div style="background:#C9F1FF; margin:0 auto; height:100px; width:600px; text-align:center;">
    		<div style="margin-top:50px;">
    		<h5 style="color:{$back_color};font-size:14px; padding-top:20px;" >{$message}</h5>
    		页面正在跳转请等待<span id="sec" style="color:blue;">{$time}</span>秒
    		</div>
    		</div>
    		</div>
    		<script type="text/javascript">
    		function run()
    		{
    		    var s = document.getElementById("sec");
    			if(s.innerHTML == 0)
    			{
	    			{$url}
	    			return false;
                }
    			s.innerHTML = s.innerHTML * 1 - 1;
            }
    		window.setInterval("run();", 1000);
    		</script>
HTML;
    	    			die;
    }
    
}