<?php
class ActivityController extends AdminController
{
	
	public $nav='活动管理';
	public $service_member_id = 2;
	
	public function actionIndex()
	{
		$searchModel = new SearchForm();
		if(isset($_GET['SearchForm'])) $searchModel->attributes = $_GET['SearchForm'];
		$searchModel->activity_create = isset($_GET['SearchForm']['activity_create'])?intval($_GET['SearchForm']['activity_create']) : 0;
		$condition = ' state !='.Activity::VERIFY_STATE_DELETED;
		$conditionArr = array();
		if($searchModel->keyword) $conditionArr[] = "title  like '%".$searchModel->keyword."%'";
		if($searchModel->activity_create==1)
		{
			$conditionArr[] = 'create_mid = '.Member::SERVICE_MEMBER_ID;
		}elseif($searchModel->activity_create==2)
		{
			$conditionArr[] = 'create_mid != '.Member::SERVICE_MEMBER_ID;
		}
		if($conditionArr) $condition = trim(implode(' AND ', $conditionArr),' AND ');
		$order = Yii::app()->request->getParam('order','');
		$order = empty($order) ? 'id DESC' : $order;
		$dataProvider = new CActiveDataProvider('Activity',
				array(
					'criteria' => array(
						'order' => $order,
						'condition' => $condition,	
					),
					'pagination' => array(
						'pageSize' => 20
					),
				)
		);
		
		$data = array('dataProvider'=>$dataProvider);
		//获取用户姓名
		$result  = $dataProvider->getData();
		if($result)
		{
			$midArr = array();
			foreach ($result as $r)
			{
				$midArr[] = $r->create_mid;
			}
			$data['memberArr'] = Member::model()->getMemberList($midArr,array('name'),'array');
		}
		$data['searchModel'] = $searchModel;
		$data['stateArr'] = array(0 => '',1=> '审核中',2=>'通过',3=>'拒绝','4'=>'已删除');
		$this->render('index',$data);
	}
	
	public function actionDelete()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(!$id) $this->ajaxJsonReturn(0, '参数错误!');
		$model = Activity::model()->findByPk($id);
		if(!$model) $this->ajaxJsonReturn(0,'该数据不存在或已经被删除！');
		//删除报名记录
		//$model->state = Activity::VERIFY_STATE_DELETED;
		if($model->updateByPk($id, array('state'=>Activity::VERIFY_STATE_DELETED)))
		{
			//ActivityMember::model()->deleteAll('activity_id = '.$id);
			$this->ajaxJsonReturn(1, '删除成功!');
		}
		$this->ajaxJsonReturn(0, '删除失败!');
	}
	
	public function actionViewJoiners($id)
	{
		$dataProvider = new CActiveDataProvider('ActivityMember',
				array(
						'criteria' => array(
								'order' => 'id DESC',
								'condition' => 'activity_id = '.$id,
						),
						'pagination' => array(
								'pageSize' => 10
						),
				)
		);
		$data = array('dataProvider'=>$dataProvider);
		//获取用户姓名
		$result  = $dataProvider->getData();
		if($result)
		{
			$midArr = array();
			foreach ($result as $r)
			{
				$midArr[] = $r->member_id;
			}
			$data['memberArr'] = Member::model()->getMemberList($midArr,array('name','avatar'),'array');
		}
		$this->render('viewJoiners',$data);
	}
	
	public function actionDeleteJoiner()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(!$id) $this->ajaxJsonReturn(0, '参数错误!');
		$model = ActivityMember::model()->findByPk($id);
		if(!$model) $this->ajaxJsonReturn(0,'该数据不存在或已经被删除！');
		//删除报名记录
		if($model->delete())
		{
			$this->ajaxJsonReturn(1, '删除成功!');
		}
		$this->ajaxJsonReturn(0, '删除失败!');
	}
	
    /**
     * 修改操作
     */	
	public function actionUpdate()
	{
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if($id)
		{
			$model = Activity::model()->findByPk($id);
			if($model)
			{
				//$model->begin_time = date('Y-m-d H:i',$model->begin_time);
				//$model->end_time = date('Y-m-d H:i',$model->end_time);
			}
		}else
		{
			$model = new Activity();
			$model->create_mid = Member::SERVICE_MEMBER_ID;
			$model->state = Activity::VERIFY_STATE_PASS;
		}
		
		if($model->max == 0)
			$model->max = '';
		$data['model'] = $model;
		
		//$data['types'] = Activity::model()->getActivityTypes();
		$this->render('update',array('data'=>$data));
	}
	
	public function actionUpdateDo()
	{
		$flag = false;
		$post = $_POST['Activity'];
		if($post['id'])
		{
			$model = Activity::model()->findByPk($post['id']);
			if (!$model) $this->showMessage('数据不存在',0);
		}else
		{
			$model = new Activity();
			$post['create_time'] = time();
			$post['create_mid'] = Member::SERVICE_MEMBER_ID;
		}
		
		if($post['create_mid'] == Member::SERVICE_MEMBER_ID)
		{
			// 小编的活动编辑或创建
			$create_mid = trim($_POST['create_mid']);
			if(!empty($create_mid))
			{
				preg_match('/id:(\d+)/', $create_mid,$match);
				if(!empty($match[1]))
				{
					$post['create_mid'] = $match[1];
					$flag = true;
				}else{
					$this->showMessage('请不要更改指定人的内容!');
				}
			}
		}
		if($post['begin_time'])  $post['begin_time'] = strtotime($post['begin_time']); 
		if($post['end_time'])    $post['end_time'] = strtotime($post['end_time']);
		//var_dump($post);die;
		$model->attributes = $post;
		//var_dump($post['state']);die;
		$model->setScenario('background');
		if($model->save())
		{
			if($flag)
			{
				$rel = ActivityMember::model()->findByAttributes(array('activity_id'=>$model->id,'member_id'=>$post['create_mid']));
				if(empty($rel))
				{
					$activityMember = new ActivityMember();
					$activityMember->activity_id = $model->id;
					$activityMember->member_id = $post['create_mid'];
					$activityMember->state = ActivityMember::VERIFY_STATE_WITHOUT;
					$activityMember->create_time = time();
					$activityMember->save();
				}
			}
			$this->showMessage('操作成功',1,array('index'));
			
			
		}else
		{
			print_r($model->getErrors());
			/* header('content-type:text/html;charset=utf-8');
			var_dump($model->errors); */
			$data['model'] = $model;
			$this->render('update',array('data'=>$data));
		}
		
		
		
		//$this->showMessage('操作成功',1,array('index'));
		
	}
	
	/**
	 * 获取城市列表
	 */
	public function actionGetAreaList()
	{
		$id = (int) $_GET['id'];
		
		/* $specialCity = District::model()->getSpecialCityId();
		if(isset($specialCity[$id]))
		{
			echo CHtml::tag('option',array('value'=>$id),CHtml::encode($specialCity[$id]),true);
			die;
		}
		 */
		$data=District::model()->findAll('parent_id=:a',array(':a'=>$id));
		
		$data=CHtml::listData($data,'id','name');
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
		}
	}
	
	public function getProvince()
	{
		$province =  District::model()->findAll('level = 1');
		$provinceArr[0]='请选择';
		foreach ($province as $p)
		{
			$provinceArr[$p->id] = $p->name;
		}
		return $provinceArr;
	}
	
	public function actionGetCreater()
	{
		$data = array();
		$key = htmlspecialchars(trim(Yii::app()->request->getParam('term')));
		if(!empty($key))
		{
			$sql = "SELECT * FROM member WHERE name LIKE '$key%'";
			$result = Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($result))
			{
				foreach($result as $value)
				{
					$data[] = $value['name'] . '(职位：'.$value['position'].',手机：'.$value['mobile'].',公司：'.$value['company'].',id:'.$value['id'].')';
				}
			}
		}
		echo json_encode($data);
		exit();
	}

}