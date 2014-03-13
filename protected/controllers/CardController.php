<?php
class CardController extends FController
{

	private $is_send_message = false;//是否发送短信
	public $from_uid = 0;
	public $service_tel = '4000737088';
	public function loadModel($new=false)
	{
		return $new ? new Member() : Member::model();
	}
	
	public function actionCreate()
	{
		$url = $this->createUrl('site/index');
		$this->redirect($url);
		
		$openid = isset($this->params['openid']) ? $this->params['openid'] : '';
		if(!$openid) $openid = $this->getOpenid();
		if($openid)
		{
			$model = $this->loadModel()->getMember($openid);
			if($model)
			{
				$this->setOpenidIncookie($openid);
				$this->showMessage(1,'您已经创建过微名片，请不要重复创建！',array('view','view_openid'=>$openid,'showall'=>1));
			}
		}
		$model = $this->loadModel(true);
		$data['model'] = $model;
		$data['openid'] = $openid;
		$data['from_uid'] = isset($this->params['from_uid']) ? intval($this->params['from_uid']) : '';
		$this->render('create',$data);
	}
	/**
	 * 修改
	 */
	public function actionUpdate()
	{
		$this->checkParam(array('openid'));
		$openid = $this->params['openid'];
		$cookieOpenid = $this->getOpenid();
		if(!$cookieOpenid) $this->showMessage(0,'登录后方可修改自己的名片！',array('Login'));
		if($cookieOpenid && $cookieOpenid!=$openid)
		{
			$this->showMessage(0,'登录后方可修改自己的名片！',array('Login'));
		}
		$model = $this->loadModel()->getMember($openid);
		if(!$model){
			$this->setOpenidIncookie($openid);
			$this->showMessage(0,'您还没有创建名片呢！',array('create','openid'=>$openid));
		}
		$model->show_item = $model->show_item ? json_decode($model->show_item,true) : array();
		$model->avatar = $this->loadModel()->getAvatar($model->avatar);
		$data['model'] = $model;
		$data['openid'] = $openid;
		$this->setOpenidIncookie($openid);
		$this->render('update',$data);
	}
	//创建 修改 名片
	public function actionUpdateDo()
	{
		$this->checkParam(array('name','mobile'));
		$openid = isset($this->params['weixin_openid']) ? $this->params['weixin_openid'] : '';
		$id = isset($this->params['id']) ? intval($this->params['id']) : '';
		$password = isset($this->params['password']) ? trim($this->params['password']) : '';	
		if ($password) $this->params['password'] = $this->loadModel()->setPassword($password);
		$function = $id ? 'replyIframeMessage' : 'showMessage';
		if(!Helper::checkMobile($this->params['mobile'])) 
		{
			$this->$function(0,'请填写正确的手机号码！');
		}
		if($this->params['email'])
		{
			if(!Helper::checkEmail($this->params['email'])) $this->$function(0,'请填写正确的邮箱！');
		}
		if($id)
		{
			$model = $this->loadModel()->findByPk($id);
			if(!$model || $model->weixin_openid!=$openid) $this->replyIframeMessage(0, '非法请求！');//$this->showMessage(0,'非法请求！');
			if(!$password) unset($this->params['password']);
			if($this->loadModel()->checkExists('mobile', $this->params['mobile'],$id)) $this->replyIframeMessage(0, '手机号码已经存在');
			if($this->params['email'])
			{
				if($this->loadModel()->checkExists('email', $this->params['email'],$id)) $this->replyIframeMessage(0, 'email已经存在');
			}
			
			//头像判断
			if(isset($_FILES['upphoto']))
			{
				$image = Image::upload('upphoto','avatar',array('s'=>'200,200'),0);
				if($image && $image['filePath']) $this->params['avatar'] = $image['filePath'];
			}
			if(!empty($this->params['birthday']))
			{
				$this->params['birthday'] = strtotime($this->params['birthday']);
			}
			
		}else 
		{
			if(!$password) $this->showMessage(0,'密码不能为空！');
			if($this->loadModel()->checkExists('mobile', $this->params['mobile'])) $this->showMessage(0, '手机号码已经存在');
			if($this->params['email'])
			{
				if($this->loadModel()->checkExists('email', $this->params['email'])) $this->showMessage(0, 'email已经存在');
			}
			
			if($openid)
			{
				$member = $this->loadModel()->getMember($openid,'id');
				if($member) $this->showMessage(1,'您已经创建过名片了！',array('Share','id'=>$openid,'sign'=>$this->getShareUrlSign($member->id)));
			}else 
			{
				//设置临时openid
				$openid = $this->setTempOpenid();
			}
			$model = $this->loadModel(true);
			unset($this->params['id']);
		}
		//---设置名片隐藏项目---------------
		$hiddenArr = isset($this->params['hidden']) ? $this->params['hidden'] : array();
		var_dump($hiddenArr);die;
		unset($this->params['hidden']);
		//$hiddenArr = array();
		//---------------------------------
		
		$model->attributes = $this->params;
		$time = time();
		//$param = array('view','view_openid'=>$openid);
		$param = array('share','id'=>$id,'sign'=>$this->getShareUrlSign($model->id));
		//---------------------------------
		$show = array(
				'mobile'=>1,
				'supply'=>1,
				'demand'=>1,
		);
		if($hiddenArr)
		{
			foreach ($hiddenArr as $key=>$h)
			{
				if(isset($show[$key])) $show[$key] = 0;
			}
		}
		$model->show_item = json_encode($show);
		$model->setScenario(NULL);
		//----------------------------------
		if($id)
		{
			$model->updated_at = $time;
			if($model->save()) 
			{
				$param = array('id'=>$id,'sign'=>$this->getShareUrlSign($model->id));
				$this->replyIframeMessage(1, '修改成功！',$this->createUrl('Share',$param));
				//$this->redirect($param);
			}
			$error = $model->getError('name');
			$msg = !empty($error) ? $error : '修改名片失败！';
			$this->replyIframeMessage(0, $msg);
		}else 
		{
			$model->created_at = $time;
			$model->weixin_openid = $openid;
			if($model->save())
			{
				$model->id = intval(Yii::app()->db->getLastInsertID());
				//-小伙伴记录------------------------------------------------------------------------------------
				//$from_uid=isset($this->params['from_uid']) ? intval($this->params['from_uid']) : '';
				$from_uid = $this->getCookie('from_uid');
				if($from_uid)
				{
					$partnerModel = new Partner;
					$partnerModel->new_uid=$model->id;
					$partnerModel->from_uid = $from_uid;
					$partnerModel->created_at = $time;
					if($partnerModel->save())
					{
						//统计邀请数量
						InviteStat::model()->updateStat($from_uid,array('all_counts'=>1,'left_counts'=>1));
						$this->destoryCookie('from_uid');
					}
				}
				$eventParam['model'] = $model;
				$this->RegisterOkEvent($eventParam);
				//--------------------------------------------------------------------------------------
				$this->setOpenidIncookie($openid);
				$refer = Yii::app()->user->getState('refer');
				if(!empty($refer))
					$this->redirect($refer);
				$param['id'] = $model->id;
				$param['sign'] = $this->getShareUrlSign($model->id);
				
				$this->redirect($param);
			}
			$this->showMessage(0,'创建名片失败！');
		}
	}
	
	//浏览名片
	public function actionView()
	{
		$this->checkParam(array('view_openid'));
		
		$view_openid = $this->params['view_openid'];
		//-------------------------------------------------------------------------------------
		$exceptionOpenid = 'opve0t-klUTWj0oKR9e7BlpBelO8';
		$openid = $this->getOpenid();
		if($view_openid==$exceptionOpenid)
		{
			if($openid==$exceptionOpenid)
			{
				$this->destoryCookie('openid');
			}
			$id = '196453';
			$this->redirect(array('share','id'=>$id,'sign'=>$this->getShareUrlSign($id)));
		}else 
		{
			$member = $this->loadModel()->getMember($view_openid,'id');
			if($member) $this->redirect(array('share','id'=>$member->id,'sign'=>$this->getShareUrlSign($member->id)));
			if($openid)
			{
				$member = $this->loadModel()->getMember($openid,'id');
				if($member) $this->redirect(array('share','id'=>$member->id,'sign'=>$this->getShareUrlSign($member->id)));
			}
			$this->showMessage(0,'请先登录',array('Login'));
		}
		//--------------------------------------------------------------------------------------
		if(isset($this->params['openid']))
		{
			$openid = $this->params['openid'];
			$this->setOpenidIncookie($openid);
		}else {
			$openid = $this->getOpenid();
		}
		
		
		
		$model = $this->loadModel()->getMember($view_openid);
		if(!$model) $this->showMessage(0,'您还没有创建名片呢！',array('create','openid'=>$openid));
		$model->show_item = $model->show_item ? json_decode($model->show_item,true) : array();
		if($model->show_item)
		{
			foreach ($model->show_item as $key=>$val)
			{
				if(!$val){ $model->$key='';}
			}
		}
		$model->avatar = $this->loadModel()->getAvatar($model->avatar);
		$data['pv_counts'] = 0;
		$stat = Stat::model()->getStat(array($model->id),array('pv_counts','last_update_at'));
		if($stat)
		{
			$data['pv_counts'] = $stat[$model->id]['pv_counts'];
			$data['last_update_at'] = $stat[$model->id]['last_update_at'];
		}
		$data['member_id'] = $model->id;
		//获取关注信息
		$data['follow'] = Follow::model()->getFollowCounts($model->id);
		$data = array_merge($data,array('model'=>$model,'openid'=>$openid));
		$this->render('view',$data);
	}
	
	
	//分享 ---浏览  名片
	public function actionShare()
	{
		$this->checkParam(array('id'));
		$id = $this->params['id'];
		
		// 跳转到新地址
		$this->redirect($this->createUrl('member/view',array('id'=>$id,'wx'=>'#mp.weixin.qq.com')));
		//链接地址url后缀（一键拨号）
		if(!isset($_GET['wx']))
		{
			$this->redirect($this->createShareUrl($id));
			die;
		}
		//-名片链接安全判断--------------------------------------
		$sign = isset($this->params['sign']) ? $this->params['sign'] : '';
		$limit_min_id = 185650;
		if($id > $limit_min_id)
		{
			if(!$sign || $sign != $this->getShareUrlSign($id)) $this->showMessage(0,'非法请求！');
			//echo 444444;
		} 
		
		
		
		//---------------------------------------
		$openid = $this->getOpenid();
		$model = $this->loadModel()->with('extend')->findByPk($id);
		if(!$model) $this->showMessage(0,'该名片信息不存在！');
		$data['is_follow'] = 0;
		$data['member_id'] = 0;
		$data['showFollow'] = 1;
		if($openid)
		{
			$member = $this->loadModel()->getMember($openid,'id');
			if($member)
			{
				//判断是否关注过
				if($openid !=$model->weixin_openid) $data['is_follow'] = Follow::model()->checkFollow($member->id,$id);
				$data['member_id'] = $member->id;
				//获取统计信息
				$data['pv_counts'] = 0;
				$stat = Stat::model()->getStat(array($member->id),array('pv_counts','last_update_at'));
				if($stat)
				{
					$data['pv_counts'] = $stat[$id]['pv_counts'];
					$data['last_update_at'] = $stat[$id]['last_update_at'];
				}
			}else
			{
				$openid = '';
				$this->destoryCookie('openid');
			}
		}
		if($openid!=$model->weixin_openid && $model->from==1)
		{
			//更新pv浏览
			Stat::model()->updateStat($id,array('pv_counts'=>1));
			//判断是否浏览过
			if($this->checkView($data['member_id'],$id))
			{
				$model->updateCounters(array('views'=>1),'id='.$id);
				//$model->views++;
				//$model->save();var_dump($model->errors);die;
			}
		}
		$model->show_item = $model->show_item ? json_decode($model->show_item,true) : array();
		if($model->show_item)
		{
			foreach ($model->show_item as $key=>$val)
			{
				if(!$val){ $model->$key='';}
			}
		}
		
		
		$data['big'] = Helper::getImage($model->avatar,'b');
		if(empty($data['big']))
		{
			if(!empty($model->extend))
			{
				$headimgurl = $model->extend->headimgurl;
				if(!empty($headimgurl))
				{
					$data['big'] = Helper::getImage($headimgurl,'b');
				}
			}
		}
		
		$model->avatar = $this->loadModel()->getAvatar($model->avatar,$model->id);
		//是否有大图
		if(!empty($model->birthday))
			$model->birthday = date('Y-m-d',$model->birthday);
		
		$data = array_merge($data,array('model'=>$model,'openid'=>$openid));
		
		//获取统计信息
		if(!isset($data['pv_counts']))
		{
			$data['pv_counts'] = 0;
			$stat = Stat::model()->getStat(array($id),array('pv_counts'));
			if($stat)
			{
				$data['pv_counts'] = $stat[$id]['pv_counts'];
			}
		}
		//获取关注信息
		$data['follow'] = Follow::model()->getFollowCounts($id);
		$this->from_uid = $model->id;
		if(!$openid)
		{
			$this->setcookie('from_uid',$model->id);
		}
		$this->render('view',$data);
	}
	
	public function actionSet()
	{
		$this->checkParam(array('openid'));
		$openid = $this->params['openid'];
		$model = $this->loadModel()->getMember($openid);
		if(!$model){
			$this->setOpenidIncookie($openid);
			$this->showMessage(0,'您还没有创建名片呢！',array('create','openid'=>$openid));
		}
		$model->show_item = $model->show_item ? json_decode($model->show_item,true) : array();
		$this->render('set',array('model'=>$model,'openid'=>$openid));
	}
	
	public function actionSetDo()
	{
		
		$this->checkParam(array('openid','id'));
		$openid = $this->params['openid'];
		$model = $this->loadModel()->findByPk($this->params['id']);
		if(!$model || $model->weixin_openid!=$openid) $this->showMessage(0,'非法请求！');
		$show = array('mobile','supply','demand');
		foreach ($show as $val)
		{
			if(!isset($_POST['data'][$val])) $_POST['data'][$val] = 0;
		}
		$data['show_item'] = json_encode($_POST['data']);
		$this->loadModel()->updateByPk($this->params['id'],$data);
		$this->redirect(array('view','view_openid'=>$openid,'showall'=>0));
	}
	
	public function ischeckd($arr,$field,$value)
	{
		$str='';
		if(isset($arr[$field]) && $arr[$field]==$value)
		{
			$str = 'checked="checked"';
		}
		return $str;
	}
	
	public function actionLogin()
	{
		$refer = Yii::app()->request->getUrlReferrer();
		Yii::app()->user->setState('refer',$refer);
		$url = $this->createUrl('site/index');
		$this->redirect($url);
		exit;
		
		$openid = $this->getOpenid();
		if(!empty($openid))
		{
			$model = Member::model()->getMember($openid);
			if(!empty($model))
			{
				$param = array('share','id'=>$model->id,'sign'=>$this->getShareUrlSign($model->id));
				$this->redirect($param);
			}
		}
		$this->destoryCookie('openid');
		$this->render('login',$this->params);
	}
	/**
	 * 登录
	 * 允许用手机号 邮箱 往来号登录
	 */
	public function actionLoginDo()
	{
		$openid = isset($this->params['openid']) ? $this->params['openid'] : '';
		$sign = isset($this->params['sign']) ? $this->params['sign'] : '';
		if($openid && $sign!=Helper::createSign($openid)) $this->showMessage(0, '非法请求');
		
		$this->checkParam(array('name','password'));
		$name = $this->params['name'];
	
		if(Helper::checkEmail($name))
		{
			$condition = 'email = :a';
		}elseif (Helper::checkMobile($name) || $name==$this->service_tel)
		{
			$condition = 'mobile = :a';
		}else 
		{
			if(!is_numeric($name)) $this->showMessage(0, '无效的往来账号');
			$condition = 'wanglai_number = :a';
		}
		$model = $this->loadModel()->find($condition,array(':a'=>$name));
		if(!$model) $this->showMessage(0, '该用户信息不存在！');
		if($model->password!==$this->loadModel()->setPassword($this->params['password'])) $this->showMessage(0, '密码不正确！');
		//更新openid
		if($openid)
		{
			$checkHasBind = $this->loadModel()->getMember($openid,'id');
			if($checkHasBind)
			{
				$openid = $model->weixin_openid;
			}else
			{
				//先判断是不是存在
				if($openid!=$model->weixin_openid)
				{
					$ret = $model->updateByPk($model->id, array('weixin_openid'=>$openid));
					if(!$ret) $this->showMessage(0, '登录失败！');
				}
			}
		}else 
		{
			$openid = $model->weixin_openid;
		}
		$this->setOpenidIncookie($openid);
		$this->destoryCookie('from_uid');
		$param = array('share','id'=>$model->id,'sign'=>$this->getShareUrlSign($model->id));
		
		//微活动登录后回到refer
		$refer = Yii::app()->user->getState('refer');
		if(!empty($refer))
			$this->redirect($refer);
		$this->redirect($param);
		//提示
		/* if($sign)
		{
			$this->showMessage(1, '恭喜你，绑定账号成功',$param);
		}else
		{
			$this->redirect($param);
		} */
		
	}
	
	
	
	private function setOpenidIncookie($openid)
	{
		setcookie("openid",$openid,time()+3600*24*300);
	}
	
	private function destoryCookie($name)
	{
		setcookie($name,'',time()-3600);
	}
	
	public function actionJump()
	{
		$this->checkParam(array('act'));
		$act = $this->params['act'];
		unset($this->params['r'],$this->params['act']);
		$openid = $this->params['openid'];
		$cookie_openid = $this->getOpenid();
		$this->params['id'] = 0;
		if($openid && $cookie_openid && substr($cookie_openid, 0,12)=='temp_wanglai')
		{
			$model = $this->loadModel()->getMember($openid);
			if(!$model)
			{
				$model = $this->loadModel()->getMember($cookie_openid);
				if($model)
				{
					$this->params['id'] = $model->id;
					//$model->weixin_openid = $openid;
					if($this->loadModel()->updateByPk($model->id, array('weixin_openid'=> $openid))) $this->setOpenidIncookie($openid);
				}
			}else 
			{
				$this->params['id'] = $model->id;
			}
		}
		array_unshift($this->params, $act);
		$this->redirect($this->params);
	}
	
	/*
	 * 设置临时openid 
	 */
	private function setTempOpenid()
	{
		return 'temp_wanglai_'.md5(uniqid().mt_rand(1,999999));
	}
	//设置临时openid
	/* if(!$openid)
	{
		$this->setOpenidIncookie('temp_'.md5(uniqid().mt_rand(1,999999)));
	} */
	
	public function getUrl($url)
	{
		if(!$url) return;
		$suffix='http://';
		if(stristr($url, $suffix)===false) $url=$suffix.$url;
		return $url;
	}
	
	public function actionStat()
	{
		$this->checkParam(array('id'));
		$str = $this->params['id'];
		list($id,$key) = explode('-', $str);
		if(!$id || !$key) return;
		if($key!=md5($id.'wanglai123')) return;
		$this->loadModel()->updateCounters(array('share_counts'=>1), 'id='.$id);
	}
	/**
	 * 获取小伙伴列表
	 * @param unknown $uid
	 * @return void|multitype:Ambigous <>
	 */
	public function getMyPartnerList($uid)
	{
		$list = Partner::model()->getMyPartner($uid);
		if(!$list) return;
		
		$idArr = array();
		foreach ($list as $p)
		{
			//if(!$p->new_uid || !$p->from_uid) continue;
			if($p->new_uid != $uid) $idArr[] = $p->new_uid;
			if($p->from_uid != $uid) $idArr[] = $p->from_uid;
		}
		$idArr = array_unique($idArr);
		if(!$idArr) return;
		$ids = implode(',', $idArr);
		$param = array(
						'select'=>'id,name,avatar',
						'condition'=>'id in ('.$ids.')'	
		);
		$data = $newData = array();
		$result = $this->loadModel()->findAll($param);
		
		foreach ($result as $r)
		{
			$data[$r->id]['id'] = $r->id;
			$data[$r->id]['name'] = $r->name;
			$data[$r->id]['avatar'] = $r->avatar;
		}
		foreach ($idArr as $id)
		{
			if(isset($data[$id])) $newData[]= $data[$id];
		}
		return $newData;
		
	}
	/**
	 * 验证是否浏览过该用户的名片
	 * @param unknown $id
	 * @return boolean
	 */
	private function checkView($member_id,$id)
	{
		$openid = $this->getOpenid();
		if(!$openid)
		{
			return ViewLog::model()->checkViewByCookie($id);
		}
		if (!$member_id || $member_id == $id) return false;
		$isViewd = ViewLog::model()->find('member_id = :a and viewd_member_id = :b',array(':a'=>$member_id,':b'=>$id));
		if($isViewd)
		{
			$isViewd->last_viewd_at = time();
			$isViewd->save();
			return false;
		}
		$model = new ViewLog;
		$model->member_id = $member_id;
		$model->viewd_member_id = $id;
		$model->created_at = time();
		$model->save();
		return true;
	}
	/**
	 * 判断是否被选中
	 */
	public function checkIsChecked($show,$name,$value=0)
	{
		if($show && isset($show[$name]) && $show[$name] ==$value) echo 'checked="checked"';
	}
	/**
	 * 发送短信
	 * @param unknown $param
	 */
	private function RegisterOkEvent($param)
	{
		$model = $param['model'];
		//--发送短信----------------------------------------------------------------------------
		if($this->is_send_message)
		{
			$msgContent = '您已成功注册往来微名片。关注往来微信公众号wanglairm获得更多功能，点击wanglai.cc登录您的微名片';
			$path = dirname(dirname(__FILE__)).'/extensions/Sms/';
			include_once($path.'sms.php');
			$sms = new sms();
			$return = $sms->send($model->mobile,$msgContent);
			$data = array();
			$data['member_id'] = $model->id;
			$data['mobile'] = $model->mobile;
			$data['action'] = 1;
			$data['created_at'] = time();
			$data['send_status'] = isset($return[1]) && trim($return[1]==0) ? 1 : 0;
			MessageLog::model()->saveData($data); 	
		}
	}

	public function actionLogout()
	{
		$this->destoryCookie('openid');
		$this->destoryCookie('from_uid');
		Yii::app()->user->logout();  //活动相关的用到
		$url = Yii::app()->createUrl('/');
		$this->redirect($url);
	}
	
	/**
	 * 生成个人主页url字符串
	 * @param unknown $id
	 * @return string
	 */
	private function getShareUrlSign($id)
	{
		return Helper::createSign($id.'@#%&*');
	}
	
	private function replyIframeMessage($code,$error,$url='')
	{
		$data = array('code'=>$code,'error'=>$error,'url'=>$url);
		echo "<script type='text/javascript'>window.parent.callback('".json_encode($data)."');</script>";
		die;
	}
	
	/**
	 * 查看头像大图
	 */
	public function actionShowPhoto() 
	{
		$id = Yii::app()->request->getParam('id');
		$model = Member::model()->findByPk(intval($id));
		if ($model === null) 
		{
			$this->showMessage(0,'非法请求！');
		}
		
		$src = Helper::getImage($model->avatar,'b');
		if (!empty($src)) {
			$this->render('showPhoto',array('src' => $src));
		}
		
	}
	
	/**
	 * 未登录用户查看我的小伙伴
	 */
	public function actionShowPartner() 
	{
		$id = Yii::app()->request->getParam('id');
		$model = Member::model()->findByPk(intval($id));
		if ($model == null)
		{
			$this->showMessage(0,'非法请求！');
		}
		$data = $this->getMyPartnerList(intval($id));
		
		$this->render('showPartner',array('data' => $data,'model' => $model));
	}
	
	/**
	 * 检测权限
	 * 
	 */
	public function checkAccess($model)
	{
		$openid = $this->getOpenid();
		if(empty($openid)) return false;
		$weixin_openid = $model->weixin_openid;
		if($openid == $weixin_openid) return true;
		$id = $model->id;
		$member = Member::model()->getMember($openid);
		$sql = "SELECT count(id) FROM follow WHERE mid=$id AND follow_mid={$member->id} AND is_deleted=0";
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		return $count==1 ? true : false;
	}
	
	/**
	 * 生成二维码
	 */
	public function actionGetQRcode()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		$model = Member::model()->findByPk($id);
		if($model==null)
		{
			$this->showMessage(0,'用户不存在');
		}
		$url = $this->createShareUrl($id);
		$url = Yii::app()->request->getHostInfo() . $url;
		$aliase = Yii::getPathOfAlias('ext');
		$surplus = $id % 10;
		$QR = Yii::app()->getBasePath() . '/../attachments/qrcode'.$surplus.'/qrcode_'.$id.'.png';
		include $aliase.'/QRcode/phpqrcode.php';
		$conttent = <<<CODE
BEGIN:VCARD
VERSION:3.0
N:{$model->name}
ADR;WORK:{$model->address}
EMAIL:{$model->email}
URL:{$url}
TEL:{$model->mobile}
END:VCARD
CODE;
		QRcode::png ($conttent,$QR,'H',4,4);
		$logo = Yii::app()->getBasePath() . '/../attachments/wlogo.png';
		$QR = imagecreatefromstring(file_get_contents($QR));
		$logo = imagecreatefromstring(file_get_contents($logo));
		$QR_width = imagesx($QR);
		$QR_height = imagesy($QR);
		$logo_width = imagesx($logo);
		$logo_height = imagesy($logo);
		$logo_qr_width = $QR_width / 5;
		$scale = $logo_width / $logo_qr_width;
		$logo_qr_height = $logo_height / $scale;
		$from_width = ($QR_width - $logo_qr_width) / 2;
		imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		header('Content-type:image/png');
		imagepng($QR);
	}
	
	
}
