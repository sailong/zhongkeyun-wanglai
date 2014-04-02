<?php

/**
 * 前端基类控制器
 * @author JZLJS00
 */
class FrontController extends CController
{
	
	protected $duration = 15552000; //Cookie 持续时间,180天,86400 * 30 * 6;
	
	protected $_member = null;     //已登录用户model
	
	
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
	
	protected $_debug = false; //非微信客户端条件下用来测试
	
	/**
	 * 需要加sign的视图文件
	 * @var unknown_type
	 */
	protected $_include_views = array();

	/**
	 * 0 当前处于登录状态的用户通过openid初始化
	 * 1 只能微信访问
	 * (non-PHPdoc)
	 * @see CController::init()
	 */
	public function init()
	{
// 		$this->_debug = Yii::app()->request->getParam('debug',false);
// 		if(!$this->_debug)
// 		{
// 			if(!Yii::app()->mobileDetect->isMobile())
// 			{
// 				$this->renderPartial('/layouts/deny');
// 				Yii::app()->end();
// 			}
// 		}
		
		
		// 当前处于登录状态下的用户,需要先login
		
		if(Yii::app()->user->getIsGuest())
		{
			$openid = Yii::app()->request->cookies->itemAt('openid')->value;
			if(!empty($openid))
			{
				$user = Member::model()->findByAttributes(array('weixin_openid'=>$openid));
				if(!empty($user))
				{
					$this->login($user);
				}else
				{
					Yii::app()->request->cookies->remove('openid');
				}
			}
		}
		
	}
	
	/**
	 * 用户状态管理,匿名用户需要微信验证登录,子类调用,则需要parent::beforeAction()
	 * @see CController::beforeAction()
	 */
	public function beforeAction($action)
	{
		if(!Yii::app()->user->getIsGuest())
		{
			// 已是登录用户,但未进行过OAuth验证的
			$uid = intval(Yii::app()->user->id);
			$model = Member::model()->findByPk($uid);
			if(empty($model))
			{
				$this->logout();
			}
			
			// 非企业用户需要微信登录,企业用户不需要
			if($model->is_qiye == Member::QIYE_NO)
			{
				$extend = MemberExtend::model()->findByPk($uid);
				if(empty($extend))
				{
					$this->goAuth();
				}
			}
			
			$this->_member = $model;
		}
		return true;
	}
	
	/**
	 * ajax数据返回函数
	 * @param int    $resultCode
	 * @param string $resultMsg
	 * @param mix    $resultData
	 */
	public function returnData($resultCode,$resultMsg='',$resultData='')
	{
		$data['code']     = $resultCode;
		$data['message']  = $resultMsg;
		$data['data']     = $resultData;
		echo json_encode($data);
		die;
	}
	
	/**
	 * 返回错误消息
	 * @param unknown_type $status
	 * @param unknown_type $message
	 * @param unknown_type $url
	 */
	public function showMessage($status,$message,$url='')
	{
		$data['message'] = $message;
		$data['status'] = $status;
		$data['url'] = $url;
		$this->render('/layouts/message',$data);
		Yii::app()->end();
	}
	
	/**
	 * 清除cookie
	 */
	protected function clearCookie()
	{
		Util::removeCookie('refer');
		Util::removeCookie('sign');
		Util::removeCookie('weixin');
	}
	
	/**
	 * 创建个人名片页地址
	 * @param unknown_type $id
	 * @param boole $absolute 是否是绝对路径
	 */
	protected function createShareUrl($id,$absolute=false)
	{
		return $absolute ? $this->createAbsoluteUrl('member/view',array('id'=>$id,'#'=>'mp.weixin.qq.com')) : $this->createUrl('member/view',array('id'=>$id,'#'=>'mp.weixin.qq.com'));
	}
	
	/**
	 * 走微信OAuth授权流程,排序不需要进行验证的操作
	 */
	protected function goAuth()
	{
		$this->redirect($this->createUrl('auth/index',array('from'=>Yii::app()->getSecurityManager()->hashData('auth'))));
	}
	
	/**
	 * 登陆
	 * @param Member obj $user 合法的Member对象
	 */
	protected function login($user)
	{
		$userIdentity = new FrontUserIdentity($user);
		Yii::app()->user->login($userIdentity,$this->duration);
	}
	
	/**
	 * 退出
	 */
	protected function logout()
	{
		$this->destoryCookie('openid');
		Yii::app()->user->logout();  //活动相关的用到
		$url = Yii::app()->createUrl('/');
		$this->redirect($url);
	}
	
	/**
	 * 检测是否可以分享
	 */
	public function chekcShareAble()
	{
		$controllerId = $this->getId();
		$actionId = $this->getAction()->getId();
		$include = array(
			'member' => array('view'),
			'activity' => array('list','detail'),
			'index' => array('index'),
			'bless' => array('view')
		);
		if(key_exists($controllerId, $include) && in_array($actionId, $include[$controllerId]))
			return true;
		return false;
	}
	
	/**
	 * 在渲染视图前增加sign字符串
	 * @see CController::beforeRender()
	 */
	public function beforeRender($view)
	{
		if(in_array($view, $this->_include_views))Util::addSign();
		return true;
	}
	
	/**
	 * 检测sign是否合法
	 * @return boole
	 */
	protected function checkSign()
	{
		$sign = Yii::app()->request->getParam('sign');
		return $sign == Util::getSign() ? true : false;
	}
	
	public function getMember()
	{
		return $this->_member;
	}
	
	/**
	 * 方案一
	 * 防止表单重复提交
	 */
	public function autoCheckFormSubmit(){
	    $session = Yii::app()->session;
	    $user_id = Yii::app()->user->id;
	    $sessionKey = $user_id.'_is_sending';
	    
	    if(isset($session[$sessionKey])){
	        $first_submit_time = $session[$sessionKey];
	        $current_time = time();
	        if($current_time - $first_submit_time < 20){
	            $session[$sessionKey] = $current_time;
	            return false;//$this->response(array('status'=>1, 'msg'=>'不能在10秒钟内连续发送两次。'));
	        }else{
	            unset($session[$sessionKey]);//超过限制时间，释放session";
	        }
	    }
	    //第一次点击确认按钮时执行
	    if(!isset($session[$sessionKey])){
	        $session[$sessionKey] = time();
	    }
	    
	    return true;
	}
	
	/**
	 * 方案二
	 * 1、产生token，并存在session中
	 * 2、随页面生成
	 * 3、提交页面与session进行比对，成功后对session进行销毁
	 * 4、第二次提交则不存在这个值而报错
	 * @param type $uniqueid
	 * @return type
	 */
	public function createToken($uniqueid) 
	{
	    $session = Yii::app()->session;
	    if(is_object($uniqueid))
	    {
	        $uniqueid = $uniqueid->id.$uniqueid->action->id.Yii::app()->user->id;
	    }else{
	        $uniqueid = empty($uniqueid) ? Yii::app()->user->id . uniqid(): $uniqueid;
	    }
	    $token = md5("wang_lai" . $uniqueid);
	    $session["form_token"] = $token; 
	  
	    return $token;
	}
	
	public function checkToken($token) 
	{
	    $session = Yii::app()->session;
    	if (!isset($session['form_token']) || empty($session['form_token']) || $session['form_token'] != $token) 
    	{
	        return false;
    	} else {
    	    unset($session['form_token']);
    	    return true;
    	}
	}
}