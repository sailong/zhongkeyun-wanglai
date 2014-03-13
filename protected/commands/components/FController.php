<?php
class FController extends Controller
{
	public  $params;            //传入参数
	public  $currentPage=1;
	private $debug = true;
	
	public function __construct($id,$module=null)
	{	/* 
		if (!Helper::is_mobile())
		{
			echo "error!";
			die;
		} *//* else 
		{
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if (strpos($user_agent, 'MicroMessenger') === false) {
				// 非微信浏览器禁止浏览
				echo "HTTP/1.1 401 Unauthorized";
				die;
			}
		} */
		
		
	 	
		error_reporting(0);
		parent::__construct($id,$module=null);
		header("Access-Control-Allow-Origin:*");
		header('content-type:text/html;charset=utf-8');
		
		if(isset($_REQUEST))
		{
			foreach($_REQUEST as $key=>$val)
			{
				$_REQUEST[$key] = !is_array($val) ? strip_tags(trim($val)) : $val;
			}
		}
		$this->params = $_REQUEST;
		
		if(isset($this->params['page'])) 
		{
			//$_GET['page'] 虚拟yii get 分页
			$_GET['page'] = $this->currentPage = intval($this->params['page']);
		}
		if($this->debug) $this->test();
	}
	/**
	 * 数据返回函数
	 * @param int    $resultCode
	 * @param string $resultMsg
	 * @param mix    $resultData
	 */
	public function returnData($resultCode,$resultMsg='',$resultData='')
	{
		$data['code']     = $resultCode;
		$data['message']  = $resultMsg;
		$data['data']     = Helper::resetDataIndex($resultData);
		echo json_encode($data);
		die;
	}
	
	/**
	 * 参数判断
	 */
	public function checkParam($params)
	{
		if(!is_array($params)) die('checkParam 参数错误，应该是一个数组');
		$requestParams = $this->params;
		foreach($params as $key=>$p)
		{
			if(!isset($requestParams[$p])) $this->returnData(0,'缺少业务参数：'.$p);
			if($requestParams[$p]==='') $this->returnData(0,'参数：'.$p.' 不能为空');
			if( $key && strtolower($key) === 'int' )
			{
				if(!is_numeric($requestParams[$p])) $this->returnData(0,'业务参数：'.$p.' 传值错误，必须是个整数');
			}
		}
	}
	/**
	 * 测试 调试函数
	 */
	public function test()
	{
		$file = Yii::getPathOfAlias('webroot').'/temp/log.txt';
        if (file_exists($file) && is_writeable($file))
        {
        	$str='';
        	if($_REQUEST)
        	{
        		if(isset($_REQUEST['r']))
        		{
        			$str='请求接口： '.'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].' 发送参数：';
        			unset($_REQUEST['r']);
        		}
        		foreach ($_REQUEST as $key=>$val)
        		{
        			$str.=$key.'='.$val.'&';
        		}
        		$str = trim($str,'&');
        	}
        	if(date('i')%10 == 0) file_put_contents($file,'');
            file_put_contents($file, date('Y-m-d H:i:s').'  '.Helper::get_real_ip().'  '.$str."\r\n",FILE_APPEND);
        }
	}
	
	public function showMessage($status,$message,$url='')
	{
		if(is_array($url))
		{
			$route=isset($url[0]) ? $url[0] : '';
			$url=$this->createUrl($route,array_splice($url,1));
		}
		$data['message'] = $message;
		$data['status'] = $status;
		$data['url'] = $url;
		$this->render('../layouts/message',$data);
		die;
	}
	/**
	 *设置openid
	 * @return Ambigous <string, unknown>
	 */
	public function getOpenid()
	{
		return isset($_COOKIE['openid']) ? $_COOKIE['openid'] : '';
	}
	
	/**
	 * 创建分享链接url
	 */
	 
	public function createShareUrl($id)
	{
		return Yii::app()->createUrl('/Card/Share',array('id'=>$id,'sign'=>Helper::createSign($id.'@#%&*')));
	}
}
