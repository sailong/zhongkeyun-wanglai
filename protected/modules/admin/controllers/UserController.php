<?php

class UserController extends AdminController
{

	public $nav = '系统用户管理';
	private function loadModel($new=false)
	{
		return $new ? new User : User::model();
	}

	/**
	 * 列表
	 */
	public function actionIndex()
	{
      
		$this->checkAdmin();
		$model = $this->loadModel();
		$criteria = new CDbCriteria();
		$criteria->order  = 'uid desc';
		$count = $model->count($criteria);
		$pager = new CPagination($count);
		$pager->pageSize = 10;
		$pager->applyLimit($criteria);
		$result = $model->findAll($criteria);
		$data['pages'] = $pager;
		$data['data']  = $result;
		$data['count'] = $count;
		$this->render('index', $data);
	}
   
	
	/**
	 * 添加/修改
	 */
	public function actionUpdate()
	{
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        
        $from = isset($_GET['from']) ? 1 : 0;
        
        if($from) $id = Yii::app()->user->getState('uid');
        
	    $model = $this->loadModel();
	    if($id)
	    {
	    	$model = $model->findByPk($id);
	    	if(!$model) $this->showMessage('该信息不存在！',0);
	    }
	    $data['id'] = $id;
	    $data['model'] = $model;
	    $this->render('update', $data);
	    
	}
	
	public function actionUpdateDo()
	{
		$data = $_POST['User'];
		$id = isset($data['uid']) ? intval($data['uid']) : 0;
		if(!$data['password']) $this->showMessage('密码不能为空！',0);
		if($id)
		{
			$model = $this->loadModel()->findByPk($id);
			if(!$model) $this->showMessage('数据不存在或已经被删除！',0);
            foreach ($data as $k=>$v) 
            {
                $model->$k = $v;
            }
            $model->password = $this->loadModel()->createMd5Password($data['password']);
			if($model->save())
			{
				$this->showMessage('修改数据成功！',1);
			}else 
			{
				$this->showMessage('修改失败！',0);
			}
		}else 
		{
			$this->checkAdmin();
			if(!$data['nickname']) $this->showMessage('账号不能为空！',0);
			$model = $this->loadModel(true);
            $data['created_at'] = time();
            $data['role_id'] = 2;
            $data['password'] = $this->loadModel()->createMd5Password($data['password']);
            $data['register_ip'] = Helper::get_real_ip();
            $data['lastlogin_ip'] = Helper::get_real_ip();
            foreach ($data as $k=>$v) {
                $model->$k = $v;
            }
            $model->register_time = $model->lastlogin_time =  date('Y-m-d H:i:s');
            
			if($model->save()) $this->showMessage('添加数据成功！',1,array('index'));
			$this->showMessage('添加失败！',0);
			
		}
		
	}
	/**
	 * 删除
	 */
	public function actionDelete()
	{
		$this->checkAdmin();
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(!$id) $this->ajaxJsonReturn(0, '参数错误!');
		$model = $this->loadModel()->findByPk($id);
		if(!$model) $this->ajaxJsonReturn(0,'该数据不存在或已经被删除！');
		if($model->role_id==1) $this->ajaxJsonReturn(0,'管理员不能被删除！');
		$model->delete();
		$this->ajaxJsonReturn(1, '删除成功!');
	}
	
	public function checkAdmin()
	{
		if(Yii::app()->user->getState('role')!=1) $this->showMessage('没有操作权限！',0);
	}
	
	
	//--------------------
	public function actionSet()
	{
		$this->checkAdmin();
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		if(!$id) $this->ajaxJsonReturn(0, '参数错误!');
		$model = $this->loadModel()->findByPk($id);
		if(!$model) $this->ajaxJsonReturn(0,'该用户不存在或已经被删除！');
		if(isset($_REQUEST['setDo']))
		{
			
			$data['menu'] = $_POST['data'];
			$data['infoCate'] = $_POST['infoCate'];
			
			if(in_array('Egg', $data['menu']))
			{
				array_push($data['menu'],'Eggcode','EggPrize','EggLog','User');
			}
			
			if($data['infoCate'])
			{
				$data['menu'][]='Info';
				$data['infoCate'][]=0;
			}
			$model->purview = json_encode($data);
			$model->save();
			$this->showMessage('权限分配成功！',1,array('index'));
		}else 
		{
			$data['purview'] = $model->purview ? json_decode($model->purview,true) : array();
			$data['purviewList'] = $this->getMenuList(true);
			$data['uid'] = $id;
			$this->render('set', $data);
		}
	}
	
	
}
