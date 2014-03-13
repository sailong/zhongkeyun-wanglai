<?php
class PasswordController extends FController
{
	private $action_id  = 2;
	public  $time_limit = 120;  //未收到验证码
	private $invalid_time = 900; //验证码失效时间
	/**
	 * 找回密码第一步
	 */
    public function actionStep1()
    {  
    	$sendSign = md5(time().'558897');
    	setcookie('sendSign',$sendSign);
    	$mobile = isset($_GET['mobile']) ? trim($_GET['mobile']) : '';
    	$this->render('step1',array('sendSign'=>$sendSign,'mobile'=>$mobile));
    }
    /**
     * 找回密码第二步
     */
    public function actionStep2()
    {
    	$this->checkParam(array('mobile','code'));
    	$mobile = $this->params['mobile'];
    	if(!Helper::checkMobile($mobile)) $this->showMessage(0,'手机号码格式不正确');
    	$member = Member::model()->getMemberBy('mobile',$mobile,'id');
    	if(!$member) $this->showMessage(0, '无效的手机号码');
    	//验证code
    /* 	$messageModel = $this->getNewestCode($mobile);
    	if(!$messageModel || $messageModel->content!=$this->params['code']) $this->showMessage(0, '无效的验证码');
    	if(time() - $messageModel->created_at > $this->time_limit) $this->showMessage(0, '验证码已经失效,请重新获取验证码'); */
    	$this->params['id'] = 	$member->id;
    	$this->params['sign'] = Helper::createSign($member->id.'findpwd');
    	$this->render('step2',$this->params);
    }
    
    public function actionCheckCode()
    {
    	$this->checkParam(array('mobile','code'));
    	$mobile = $this->params['mobile'];
    	if(!Helper::checkMobile($mobile)) $this->returnData(0,'手机号码格式不正确');
    	$member = Member::model()->getMemberBy('mobile',$mobile,'id');
    	if(!$member) $this->returnData(0, '无效的手机号码');
    	//验证code
    	$messageModel = $this->getNewestCode($mobile);
    	if(!$messageModel || $messageModel->content!=$this->params['code']) $this->returnData(0, '无效的验证码');
    	if(time() - $messageModel->created_at > $this->invalid_time) $this->returnData(0, '验证码已经失效,请重新获取验证码');
    	@setcookie('sendSign','',time()-3600);
    	$this->returnData(1);
    }
    /**
     * 更改密码
     */
    public function actionUpdatePassword()
    {
    	$this->checkParam(array('id','mobile','sign','password','password2'));
    	if($this->params['password'] != $this->params['password2']) $this->showMessage(0,'两次输入密码不一致'); 
    	$id = intval($this->params['id']);
    	$sign = $this->params['sign'];
    	if(!$id || !$sign || $sign!=Helper::createSign($id.'findpwd'))  $this->showMessage(0,'非法请求'); 
    	$length = strlen($this->params['password']);
    	if($length<6 || $length>16) $this->showMessage(0,'修改密码失败：密码长度在6~16个字符之间'); 
    	$member = Member::model()->getMemberBy('id',$id,'id,weixin_openid,password');
    	if(!$member) $this->showMessage(0,'非法请求：该用户不存在'); 
    	$updateArr['password'] = $member->setPassword($this->params['password']);
    	$ret = Member::model()->updateByPk($id, $updateArr);
    	//if(!$ret && $updateArr['password']!=$member->password) $this->showMessage(0, '密码修改失败'); 
    	setcookie("openid",$member->weixin_openid,time()+3600*24*300);
    	$this->showMessage(1, '密码修改成功',$this->createShareUrl($id)); 
    }
    
    /**
     * 发送验证码
     */
    public function actionSendCode()
    {
    	$this->checkParam(array('mobile','sign'));
    	$sign = isset($_COOKIE['sendSign']) ? $_COOKIE['sendSign']:'';
    	if(!$sign || $sign!= $this->params['sign']) $this->returnData(0,'非法请求'); 
    	$mobile = $this->params['mobile'];
    	if(!Helper::checkMobile($mobile)) $this->returnData(-1,'手机号码格式不正确');
    	//判断该手机号码是否存在
    	if(!Member::model()->checkExists('mobile', $mobile)) $this->returnData(-1, '手机号码不存在');
    	//判断是否发送过验证码
    	$param = array();
    	$param['select'] = 'id,created_at';
    	$param['condition'] = 'mobile = :a AND action = :b AND send_status = 1';
    	$param['params'] = array(':a'=>$mobile,':b'=>$this->action_id);
    	$param['order'] = 'id DESC';
    	$param['limit'] = 1;
    	$messageModel = MessageLog::model()->find($param);
    	if($messageModel && time() - $messageModel->created_at < $this->time_limit) $this->returnData(-2,'短信验证码已经发送，请不要重复请求！');
    	//验证发送次数
    	$start_time = strtotime(date('Y-m-d'));
    	$end_time = $start_time + 3600*24;
    	$today_send_counts = MessageLog::model()->count('created_at > :a AND created_at < :b AND mobile = :c AND action = 2 AND send_status = 1',array(':a'=>$start_time,':b'=>$end_time,':c'=>$mobile));
    	if($today_send_counts>=3) $this->returnData(-1,'您获取验证码的次数已超过3次，请明天再试！');
        //---开始发送短信--------------------------------------
    	$path = dirname(dirname(__FILE__)).'/extensions/Sms/';
    	include_once($path.'sms.php');
    	$sms = new sms();
    	$codeStr = $this->getNumber(6);
    	$return = $sms->send($mobile,'您正在申请修改往来微名片密码，验证码为:'.$codeStr.',关注往来微信公众号wanglairm获得更多功能,点击wanglai.cc登录您的微名片');
    	$data = array();
    	$data['send_status'] = isset($return[1]) && trim($return[1]==0) ? 1 : 0;
    	if(!$data['send_status']) $this->returnData(0,'短信发送失败，请稍后再试！');
    	$data['member_id'] = 0;
    	$data['mobile'] = $mobile;
    	$data['content'] = $codeStr;
    	$data['action'] = $this->action_id;
    	$data['created_at'] = time();
    	MessageLog::model()->saveData($data);
    	$this->returnData(1);
    }
    private function getNumber($length)
    {
    	$str='1234567890';
    	$rndstr='';
    	$i=0;
    	for($i;$i<$length;$i++)
    	{
    	$rndcode=rand(0,9);
    	$rndstr.=$str[$rndcode];
    	}
    	return $rndstr;
    }
    /**
     * 获取最新发送的验证码信息
     * @param string $mobile
     */
    private  function getNewestCode($mobile)
    {
    	$param = array();
    	$param['select'] = 'id,content,created_at';
    	$param['condition'] = 'mobile = :a AND action = :b AND send_status = 1';
    	$param['params'] = array(':a'=>$mobile,':b'=>$this->action_id);
    	$param['order'] = 'id DESC';
    	$param['limit'] = 1;
    	$messageModel = MessageLog::model()->find($param);
    	return $messageModel;
    }
}
?>