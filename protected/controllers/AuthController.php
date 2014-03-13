<?php
/**
 * 执行OAuth验证登陆过程
 * @author JZLJS00
 *
 */
class AuthController extends FrontController
{
	
	// 测试
	protected $_appId = 'wxc61d5668292f8a0d';
	protected $_appSecret = '1c2cc8e92058879cf15b55233593fd0c';
	
	//protected $_appId = 'wxac3940a5a72eda2a';
	//protected $_appSecret = 'eed0e73141d05e6c904da0d359ef49ab';
	
	public function init()
	{
		$curl = Yii::createComponent(array('class'=>'ext.ECurl','options'=>array()));
		Yii::app()->setComponent('curl', $curl);
	}
	
	/**
	 * 
	 * @see ApiController::actionIndex()
	 */
	public function actionIndex()
	{
		$from = Yii::app()->request->getParam('from');
		if(Yii::app()->getSecurityManager()->validateData($from) == 'auth')
		{
			unset($_SESSION['authcode']);
			$returnUrl = urlencode($this->createAbsoluteUrl('auth/return'));
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->_appId.'&redirect_uri='.$returnUrl.'&response_type=code&scope=snsapi_userinfo&state='.Yii::app()->getSecurityManager()->hashData('wanglai').'#wechat_redirect';
			Yii::app()->request->redirect($url);
		}
	}
	
	
	/**
	 * 回调页面
	 */
	public function actionReturn()
	{
		$state = Yii::app()->request->getParam('state');
		if(Yii::app()->getSecurityManager()->validateData($state) == 'wanglai')
		{
			$code = Yii::app()->request->getParam('code');
			if(!isset($_SESSION['authcode']))
			{
				$_SESSION['authcode'] = $code;
				$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->_appId.'&secret='.$this->_appSecret.'&code='.$code.'&grant_type=authorization_code';
				$result = Yii::app()->getComponent('curl')->get($url);
				$result = json_decode($result,true);
				if(isset($result['access_token'],$result['openid']))
				{
					$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$result['access_token'].'&openid='.$result['openid'];
					$return = Yii::app()->getComponent('curl')->get($url);
					$return = json_decode($return,true);
					if(isset($return['openid'],$return['nickname']))
					{
						$this->bind($return);
					}else{
						print_r($result);
						echo 'here';
					}
				}
			}
		}else{
			unset($_SESSION['authcode']);
			$this->render('refuse');
		}
	}
	
	/**
	 * 输入手机号
	 */
	public function actionMobile()
	{
		if(isset($_POST['mobile']))
		{
			$url = $this->createUrl('auth/mobile');
			if(!isset($_SESSION['already']))
			{
				$_SESSION['already'] = Yii::app()->getId();  //避免表单重复提交
				$sign = Yii::app()->request->getParam('sign');
				if($sign != Util:: getSign())
				{
					$this->showMessage(0, '非法请求',$url);
				}
				$mobile = trim(Yii::app()->request->getPost('mobile'));
				if(!Helper::checkMobile($mobile))
				{
					$this->showMessage(0, '手机格式错误',$url);
				}
				$model = Member::model()->findByAttributes(array('mobile'=>$mobile,'from'=>1));
				$param = json_decode(Util::getCookie('weixin'),true);
				if(empty($param))
				{
					$this->showMessage(0, '非法请求',$url);
				}
				if(empty($model))
				{
					// 新用户
					$member = new Member();
					$member->name = $param['nickname'];
					$member->weixin_openid = $param['openid'];
					$member->mobile = $mobile;
					$member->created_at = time();
					$member->initial = Util::getFirstLetter($param['nickname']);
					$member->wanglai_number = Number::model()->getNumber();
					if($member->save())
					{
						$this->login($member);
						$extend = new MemberExtend();
						$data = array(
							'member_id' => $member->id,'weixin_openid' => $param['openid'],'nickname'  => $param['nickname'],
							'headimgurl' => $this->saveWeixinImg($param['headimgurl']),'province' =>$param['province'],
							'sex' => $param['sex'],'city' => $param['city'],'country' =>$param['country'],'add_time' => time()
						);
						$extend->attributes = $data;
						$extend->save();
						// 记录往来号发放日志
						$grandLog = new GrantNumberLog();
						$grandLog->member_id = $member->id;
						$grandLog->number = $member->wanglai_number;
						$grandLog->grant_way = GrantNumberLog::GRAND_WAY_SIGN;
						$grandLog->created_at = time();
						$grandLog->save();
					}else{
						$errors = array_pop($member->getErrors());
						$this->showMessage(0, join(',',$errors),$url);
					}
				}else{
					// 老用户绑定(问题:有可能是已经绑定过的用户用了别人的微信号输入自己的手机号或者是2个往来号相互更改手机号)
					$extend = MemberExtend::model()->findByPk($model->id);
					if(empty($extend))
					{
						$extend = new MemberExtend();
						$data = array(
							'member_id' => $model->id,'weixin_openid' => $param['openid'],'nickname'  => $param['nickname'],
							'headimgurl' => $this->saveWeixinImg($param['headimgurl']),'province' =>$param['province'],
							'sex' => $param['sex'],'city' => $param['city'],'country' =>$param['country'],'add_time' => time()
						);
						$extend->attributes = $data;
						$extend->save();
					}
					$this->login($model);
				}
				$this->go();
			}else{
				$this->showMessage(0, '您已提交', $url);
			}
		}
		unset($_SESSION['already']);
		$this->render('mobile');
	}
	
	/**
	 * 将微信用户与往来用户相应绑定(1当前处于登录的用户,则绑定用户2新用户,则创建用户)
	 * @param unknown_type $param
	 * 
	 */
	private function bind($param)
	{
		unset($_SESSION['already']);
		if(!Yii::app()->user->getIsGuest())
		{
			// 当前已登录的用户
			$uid = Yii::app()->user->id;
			$model = Member::model()->findByPk($uid);
			if(!empty($model))
			{
				$extend = MemberExtend::model()->findByPk($uid);
				if(empty($extend))
				{
					$extend = new MemberExtend();
					$data = array(
						'member_id' => $uid,'weixin_openid' => $param['openid'],'nickname'  => $param['nickname'],
						'headimgurl' => $this->saveWeixinImg($param['headimgurl']),'province' =>$param['province'],
						'sex' => $param['sex'],'city' => $param['city'],'country' =>$param['country'],'add_time' => time()
					);
					$extend->attributes = $data;
					$extend->save();
				}
				// 更新微信openid
				if($model->weixin_openid != $param['openid'])
				{
					$model->weixin_openid = $param['openid'];
					$model->save();
				}
			}
		}else
		{
			// 未登录用户,注册或者登陆
			$extend = MemberExtend::model()->findByAttributes(array('weixin_openid'=>$param['openid']));
			if(!empty($extend))
			{
				// 老用户登陆
				$uid = $extend->member_id;
				$member = Member::model()->findByPk($uid);
				$this->login($member);
			}else{
				$member = Member::model()->findByAttributes(array('weixin_openid'=>$param['openid'],'from'=>1));
				if(!empty($member))
				{
					// 老用户登录
					$this->login($member);
					$extend = new MemberExtend();
					$data = array(
						'member_id' => $member->id,'weixin_openid' => $param['openid'],'nickname'  => $param['nickname'],
						'headimgurl' => $this->saveWeixinImg($param['headimgurl']),'province' =>$param['province'],
						'sex' => $param['sex'],'city' => $param['city'],'country' =>$param['country'],'add_time' => time()
					);
					$extend->attributes = $data;
					$extend->save();
				}else {
					// 新用户
					Util::addCookie('weixin', json_encode($param));
					Util::addSign();
					$this->render('mobile');
					Yii::app()->end();
				}
			}
		}
		$this->go();
	}
	
	/**
	 * 获取微信头像
	 * @param unknown_type $imgurl
	 */
	 private function saveWeixinImg($imgurl)
	{
		if(!empty($imgurl))
		{
			$prefix = substr($imgurl, 0,strripos($imgurl, '/')+1);
			$size_132 = $prefix . '132';    // 132大小的图片
			$size_649 = $prefix . '0';      // 640大小的图片
			$path = Image::getFolderPath('avatar');
			// 保存大图
			$filename = Image::createImageName();
			$img = Yii::app()->getComponent('curl')->get($size_649);
			$extension = $this->getExtension();
			file_put_contents($path[0] . $filename .'_b.'.$extension, $img);
			// 保存小图
			$img = Yii::app()->getComponent('curl')->get($size_132);
			$extension = $this->getExtension();
			$savePath =  $filename .'_s.'.$extension;
			file_put_contents($path[0] . $savePath, $img);
			return $path[1]. '/' . $savePath;
		}
		return $imgurl;
	}
	
	/**
	 * 获取图片扩展名
	 */
	private function getExtension()
	{
		$info = Yii::app()->getComponent('curl')->getInfo();
		$contentType = !empty($info['content_type']) ? $info['content_type'] : '';
		return !empty($contentType) ? substr($contentType, strpos($contentType, '/')+1) : 'png';
	}
	
	/**
	 * 登陆后的跳转
	 */
	private function go()
	{
		$refer = Util::getCookie('refer');
		if(empty($refer))
		{
			$refer = $this->createShareUrl(Yii::app()->user->id);
		}
		$this->clearCookie();  //清除设定的cookie
		unset($_SESSION['already']);
		$this->redirect($refer);
	}
	
	
	public function actionTest()
	{
		$this->render('refuse');
	}
}
