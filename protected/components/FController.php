<?php
class FController extends CController
{
	
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	
	public  $params;            //传入参数
	public  $currentPage=1;
	private $debug = false;
	public  $domain = '.wanglai.cc';
	
	
	public function init()
	{
		
		Yii::import('ext.MobileDetect.MobileDetect');
		if(MobileDetect::instance()->isMobile())
		{
			$openid = Yii::app()->request->cookies->itemAt('openid')->value;
			if(!empty($openid))
			{
				$user = Member::model()->getMember($openid);
				if(!empty($user))
				{
					$extend = MemberExtend::model()->findByPk($user->id);
					if(empty($extend))
					{
						// oAuth获取用户信息
						$refer =Yii::app()->request->getRequestUri();
						!empty($refer) && Util::addCookie('refer', $refer);
						//Yii::app()->request->cookies->remove('openid');
						$this->redirect($this->createUrl('auth/index',array('from'=>Yii::app()->getSecurityManager()->hashData('auth'))));
					}
				}
			}
		}
		
	}
	
	
	
	public function __construct($id,$module=null)
	{	
		error_reporting(0);
		header('content-type:text/html;charset=utf-8');
		if(isset($_REQUEST['r']) && stristr($_REQUEST['r'],'card/create')!==false)
		{
			Yii::import('ext.MobileDetect.MobileDetect');
			$isMobile = MobileDetect::instance()->isMobile();
			if(!$isMobile) 
			{
				echo "感谢您的访问，我们暂时还未开通PC端服务！请关注微信公众帐号“往来”接受我们的服务!";
				die;
			}
		} 
		parent::__construct($id,$module=null);
		header("Access-Control-Allow-Origin:*");
		
		if(isset($_REQUEST))
		{
			foreach($_REQUEST as $key=>$val)
			{
				$_REQUEST[$key] = !is_array($val) ? strip_tags(trim($val)) : $val;
			}
		}
		$this->params = $_REQUEST;
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
	 *获取openid
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
		return Yii::app()->createUrl('/Card/Share',array('id'=>$id,'sign'=>Helper::createSign($id.'@#%&*'),'wx'=>'#mp.weixin.qq.com'));
	}
	
	public function setcookie($name,$value)
	{
		//setcookie($name,$value,time()+3600*24*300);
		$cookie = new CHttpCookie($name,$value);
		$cookie->expire = time()+3600*24*30;  //有限期30天
		Yii::app()->request->cookies[$name]=$cookie;
		
	}
	
	public function getCookie($name)
	{
		$cookie = Yii::app()->request->getCookies();
		return $cookie[$name]->value;
		//return isset($_COOKIE[$name]) ? $_COOKIE[$name] : '';
	}
	
	/**
	 * 清除cookie
	 */
	protected function clearCookie()
	{
		Util::removeCookie('action');
		Util::removeCookie('objectId');
		Util::removeCookie('message');
		Util::removeCookie('from');
		Util::removeCookie('mobile');
		Util::removeCookie('refer');
		Util::removeCookie('sign');
	}
}
