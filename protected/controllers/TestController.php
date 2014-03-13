<?php
class TestController extends FController
{

	public function loadModel($new=false)
	{
		return $new ? new Member() : Member::model();
	}
	
	public function actionCreate()
	{
		//$this->checkParam(array('openid'));
		$openid = isset($this->params['openid']) ? $this->params['openid'] : '';
		if(!$openid) $openid = $this->getOpenid();
		if($openid)
		{
			$model = $this->loadModel()->getMember($openid);
			$this->setOpenidIncookie($openid);
			if($model)
			{
				$this->showMessage(1,'您已经创建过微名片，请不要重复创建！',array('view','view_openid'=>$openid,'showall'=>1));
			}
		}
		$model = $this->loadModel(true);
		$data['model'] = $model;
		$data['openid'] = $openid;
		$data['from_uid'] = isset($this->params['from_uid']) ? intval($this->params['from_uid']) : '';
		$this->render('update',$data);
	}
	/**
	 * 修改
	 */
	public function actionUpdate()
	{
		$this->checkParam(array('openid'));
		$openid = $this->params['openid'];
		$model = $this->loadModel()->getMember($openid);
		if(!$model){
			$this->setOpenidIncookie($openid);
			$this->showMessage(0,'您还没有创建名片呢！',array('create','openid'=>$openid));
			$model = $this->loadModel(true);
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
		//var_dump($_POST);die;
		$this->checkParam(array('name','mobile','email'));
		$openid = isset($this->params['weixin_openid']) ? $this->params['weixin_openid'] : '';
		$id = isset($this->params['id']) ? intval($this->params['id']) : '';
		$password = isset($this->params['password']) ? trim($this->params['password']) : '';	
		if ($password) $this->params['password'] = $this->loadModel()->setPassword($password);
		if(!Helper::checkMobile($this->params['mobile'])) $this->showMessage(0,'请填写正确的手机号码！');
		if(!Helper::checkEmail($this->params['email'])) $this->showMessage(0,'请填写正确的邮箱！');
		if($id)
		{
			$model = $this->loadModel()->findByPk($id);
			if(!$model || $model->weixin_openid!=$openid) $this->showMessage(0,'非法请求！');
			if(!$password) unset($this->params['password']);
			if($this->loadModel()->checkExists('mobile', $this->params['mobile'],$id)) $this->showMessage(0, '手机号码已经存在');
			if($this->loadModel()->checkExists('email', $this->params['email'],$id)) $this->showMessage(0, 'email已经存在');
			//头像判断
			if(isset($this->params['avatar']))
			{
				if(!$this->params['avatar'] || !file_exists(Helper::getAttachPath().$this->params['avatar']))
				{
					unset($this->params['avatar']);
				}else 
				{
					if($model->avatar)
					{
						//Helper::unlinkImage($model->avatar);
					}
				}
			}
		}else 
		{
			//设置临时openid
			if(!$openid) $openid = $this->setTempOpenid();
	
			if(!$password) $this->showMessage(0,'密码不能为空！');
			if($this->loadModel()->getMember($openid)) $this->showMessage(1,'您已经创建过名片了！',array('view','view_openid'=>$openid,'openid'=>$openid));
			if($this->loadModel()->checkExists('mobile', $this->params['mobile'])) $this->showMessage(0, '手机号码已经存在');
			if($this->loadModel()->checkExists('email', $this->params['email'])) $this->showMessage(0, 'email已经存在');
			
			$model = $this->loadModel(true);
		}
		//---设置名片隐藏项目---------------
		$hiddenArr = $this->params['hidden'];
		unset($this->params['hidden']);
		//$hiddenArr = array();
		//---------------------------------
		foreach ($model->attributes as $key=>$val)
		{
			if(isset($this->params[$key]))
			{
				$model->$key = trim($this->params[$key]);
			}
		}
		$time = time();
		$param = array('view','view_openid'=>$openid);
		
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
		//----------------------------------
		if($id)
		{
			$model->updated_at = $time;
			if($model->save()) $this->redirect($param);
			$this->showMessage(0,'修改名片失败！');
		}else 
		{
			$model->created_at = $time;
			$model->weixin_openid = $openid;
			if($model->save())
			{
				//-小伙伴记录------------------------------------------------------------------------------------
				$from_uid=isset($this->params['from_uid']) ? intval($this->params['from_uid']) : '';
				if($from_uid)
				{
					$id = intval(Yii::app()->db->getLastInsertID());
					$partnerModel = new Partner;
					$partnerModel->new_uid=$id;
					$partnerModel->from_uid = $from_uid;
					$partnerModel->created_at = $time;
					$ret = $partnerModel->save();
				}
				//--------------------------------------------------------------------------------------
				$this->setOpenidIncookie($openid);
				$this->redirect($param);
				//$this->showMessage(1,'创建名片成功！',$param);
			}
			$this->showMessage(0,'创建名片失败！');
		}
	}
	
	//浏览名片
	public function actionView()
	{
		$this->checkParam(array('view_openid'));
		if(isset($this->params['openid']))
		{
			$openid = $this->params['openid'];
			$this->setOpenidIncookie($openid);
		}else {
			$openid = $this->getOpenid();
		}
		$view_openid = $this->params['view_openid'];
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
		$this->render('view',array('model'=>$model,'openid'=>$openid,'title'=>$model->name.'的微名片'));
	}
	
	
	//分享 ---浏览  名片
	public function actionShare()
	{
		$this->checkParam(array('id'));
		$id = $this->params['id'];
		$openid = $this->getOpenid();
		$model = $this->loadModel()->findByPk($id);
		if(!$model) $this->showMessage(0,'该名片信息不存在！');
		if($openid!=$model->weixin_openid && $model->from==1)
		{
			//更新pv浏览
			Stat::model()->updateStat($id,array('pv_counts'=>1));
			//判断是否浏览过
			if($this->checkView($id))
			{
				$model->views++;
				$model->save();
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
		$model->avatar = $this->loadModel()->getAvatar($model->avatar);
		$this->render('view',array('model'=>$model,'openid'=>$openid,'title'=>$model->name.'的微名片'));
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
		//$this->showMessage(1,'操作成功！',array('view','view_openid'=>$openid,'showall'=>0));
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
	
	public function actionCreateCh()
	{
		echo Yii::app()->createUrl('/Card/Update',array('openid'=>$fromUsername));die;
		$apiUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN';
		
		/* $post_data['name'] = '微名片';
		$post_data['sub_button'][]=array(
							'type'=>'click',
							'name'=>'免费制作微名片',
							'key' =>'create_card',
		);
		$post_data['sub_button'][]=array(
				'type'=>'click',
				'name'=>'修改微名片',
				'key' =>'update_card',
		);
		$post_data['sub_button'][]=array(
				'type'=>'click',
				'name'=>'我的微名片完整版',
				'key' =>'card_all',
		);
		
		$data['button'] = $post_data;
		 */
		
		$content='{
     "button":[
      {
           "name":"菜单",
           "sub_button":[
            {
               "type":"click",
               "name":"免费制作微名片",
               "key":"create_card"
            },
            {
               "type":"click",
               "name":"修改微名片",
               "key":"update_card"
            },
            {
               "type":"click",
               "name":"我的微名片(完整版)",
               "key":"card_all"
            },
			{
               "type":"click",
               "name":"我的微名片(非完整版)",
               "key":"card_all"
            }]
       }]
 }';
		
		var_dump(json_decode($content,true));die;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 我们在POST数据哦！
		curl_setopt($ch, CURLOPT_POST, 1);
		// 把post的变量加上
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		$output = curl_exec($ch);
		curl_close($ch);
		echo $output;
	}
	
	public function actionLogin()
	{
		$this->render('login');
	}
	
	public function actionLoginDo()
	{
		$this->checkParam(array('name','password'));
		$name = $this->params['name'];
		$condition = 'mobile = :a';
		if(Helper::checkEmail($name))
		{
			$condition = 'email = :a';
		}
		$model = $this->loadModel()->find($condition,array(':a'=>$name));
		if(!$model) $this->showMessage(0, '该用户信息不存在！');
		if($model->password!==$this->loadModel()->setPassword($this->params['password'])) $this->showMessage(0, '密码不正确！');
		$this->setOpenidIncookie($model->weixin_openid);
		$this->redirect(array('share','id'=>$model->id));
		
	}
	/**
	/**
	private function getOpenid()
	{
		return isset($_COOKIE['openid']) ? $_COOKIE['openid'] : '';
	}
	**/
	private function setOpenidIncookie($openid)
	{
		setcookie("openid",$openid,time()+3600*24*300);
	}
	
	public function actionJump()
	{
		$this->checkParam(array('act'));
		
		$act = $this->params['act'];
		unset($this->params['r'],$this->params['act']);
		$openid = $this->params['openid'];
		$cookie_openid = $this->getOpenid();
		if($cookie_openid && substr($cookie_openid, 0,12)=='temp_wanglai')
		{
			$model = $this->loadModel()->getMember($openid);
			if(!$model)
			{
				$model = $this->loadModel()->getMember($cookie_openid);
				if($model)
				{
					$model->weixin_openid = $openid;
					$model->save();
				}
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
						'select'=>'id,name',
						'condition'=>'id in ('.$ids.')'	
		);
		$data = $newData = array();
		$result = $this->loadModel()->findAll($param);
		
		foreach ($result as $r)
		{
			$data[$r->id]['id'] = $r->id;
			$data[$r->id]['name'] = $r->name;
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
	private function checkView($id)
	{
		$openid = $this->getOpenid();
		if(!$openid)
		{
			return ViewLog::model()->checkViewByCookie($id);
		}
		$member = $this->loadModel()->getMember($openid,'id');
		if (!$member || $member->id == $id) return false;
		$isViewd = ViewLog::model()->find('member_id = :a and viewd_member_id = :b',array(':a'=>$member->id,':b'=>$id));
		if($isViewd) return false;
		$model = new ViewLog;
		$model->member_id = $member->id;
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
}