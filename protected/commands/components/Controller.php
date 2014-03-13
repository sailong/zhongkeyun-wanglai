<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	//-wyj add--2013-10-8---------------------------------------------------------------------
	public $dealer = array(1=>'上虞金昌宝顺',2=>'绍兴宝顺',3=>'诸暨宝顺');
	
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