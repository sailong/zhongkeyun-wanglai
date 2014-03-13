<?php

class CardController extends AdminController
{

	public $nav = '名片管理';
	
	private function loadModel($new=false)
	{
		return $new ? new Member : Member::model();
	}

	/**
	 * 列表
	 */
	public function actionIndex()
	{
      
		$searchModel = new SearchForm();
		if(isset($_GET['SearchForm'])) $searchModel->attributes = $_GET['SearchForm'];
		$orderStr = isset($_GET['order']) ? $_GET['order'] : '';
		$order = '';
		if($orderStr)
		{
			list($order,$order_way) = explode('-', $orderStr);
		}
		$selectArr = array('id','name','mobile','email','views','created_at','subscribe','wanglai_number');
		if($order=='pv')
		{
			$model = Stat::model();
			$criteria = new CDbCriteria();
			$criteria->order  = 'pv_counts '.$order_way;
			$count = $model->count($criteria);
			$pager = new CPagination($count);
			$pager->pageSize = 10;
			$pager->applyLimit($criteria);
			$result = $model->findAll($criteria);
			$pvArr = array();
			if($result)
			{
				$idArr = array();
				foreach ($result as $r)
				{
					$idArr[] = $r->member_id;
					$pvArr[$r->member_id]['pv_counts'] = $r->pv_counts;
				}
				$data = $this->loadModel()->getMemberList($idArr,$selectArr);
				//整理顺序
				if($data)
				{
					$obj = new stdClass();
					foreach ($data as $val)
					{
						$id = $val->id;
						$obj->$id = $val;
					}
					$result = new stdClass();
					foreach ($idArr as $id)
					{
						if(isset($obj->$id)) $result->$id = $obj->$id;
					}
				}else 
				{
					$result = null;
				}
			}
		}else 
		{
			

			$model = $this->loadModel();
			$criteria = new CDbCriteria();
			$criteria->order  = 'id desc';
			$criteria->condition =' `from` = 1';
			$criteria->select = implode(',', $selectArr);
			if($order=='uv') $criteria->order  = 'views '.$order_way;
			
			if($searchModel->keyword)
			{
// 				$searchModel->keyword = trim($searchModel->keyword);
// 				if(is_numeric($searchModel->keyword))
// 				{
// 					if(strlen($searchModel->keyword) == 11)
// 						$criteria->condition .= " AND mobile  ='".$searchModel->keyword."'";
// 					else 
// 						$criteria->condition .= " AND wanglai_number='".$searchModel->keyword."'";
// 				}else 
// 				{
// 					$criteria->condition .= " AND name  like '%".$searchModel->keyword."%'";
// 				}
				$criteria->condition .= " AND (mobile = '".$searchModel->keyword."' OR wanglai_number ='".$searchModel->keyword."' OR name  like '%".$searchModel->keyword."%')";
			}
			$count = $model->count($criteria);
			$pager = new CPagination($count);
			$pager->pageSize = 10;
			$pager->applyLimit($criteria);
			$result = $model->findAll($criteria);
			$pvArr = array();
			if($result)
			{
				$idArr = array();
				foreach ($result as $r)
				{
					$idArr[] = $r->id;
				}
				$pvArr = Stat::model()->getStat($idArr,array('pv_counts'));
			}
		
		}
		
		$data['pages'] = $pager;
		$data['data']  = $result;
		$data['count'] = $count;
		$data['pvArr'] = $pvArr;
		$data['model'] = $searchModel;
		$data['order'] = $orderStr;
		
		//-------------------------------
		$connection = Yii::app()->db;
		$sql = "SELECT sum(views) as views,sum(share_counts) as share_counts  FROM `member` where  `from` = 1";
		$command = $connection->createCommand($sql);
		$result = $command->queryAll();
		$data['stat'] = $result[0];
		$this->render('index', $data);
	}
   
	
	/**
	 * 更新名片信息
	 * @param int $id
	 */
	public function actionUpdate($id) {
		$model=Member::model()->findByPk($id);
		$old_wanglai_number = $model->wanglai_number; 
		$wanglai_number = 0;
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if (isset($_POST['Member'])) {
			$model->setScenario('update');
			$model->attributes = $_POST['Member'];
			//----往来号------------------
			if($_POST['Member']['wanglai_number'])
			{
				$wanglai_number = trim($_POST['Member']['wanglai_number']);
				$length = strlen($wanglai_number);
				if(!is_numeric($wanglai_number) || $length >6)
				{
					$this->showMessage('修改失败:往来号不能多于6位数字',0);
				}
				if($old_wanglai_number!==$wanglai_number)
				{
					$result = Member::model()->getMemberBy('wanglai_number',$wanglai_number,'id');
					if($result) $this->showMessage('修改失败:往来号 '.$wanglai_number.' 已经被其他人使用',0);
					if($length>=4)
					{
						//判断该号码是否存在
						$numberModel = Number::model()->find('number = :a',array(':a'=>$wanglai_number));
						if(!$numberModel) $this->showMessage('修改失败:往来号 '.$wanglai_number.' 不存在',0);
						if($numberModel->is_keep==0)
						{
							if($numberModel->number_status>0)
							{
								$this->showMessage('修改失败:往来号 '.$wanglai_number.' 已被使用或锁定',0);
							}
						}
					}
					$model->wanglai_number = $wanglai_number;
				}
				
			}else
			{
				$model->wanglai_number = '';
				$model->wanglai_number_grade = 0;
				
			}
			
// 			if(empty($_POST['Member']['password'])) {
// 				unset($model->password);
// 				unset($model->repeat_password);
// 			}else {
// 				$model->setAttribute('repeat_password', $_POST['Member']['repeat_password']);
// 			}
			
			$show_items = !empty($_POST['Member']['show_item']) ? $_POST['Member']['show_item'] : array();
			$hide_items = array_diff(array_keys(Member::$hideOptions), $show_items);
			$show_items = array_fill_keys($show_items, 0);
			$hide_items = array_fill_keys($hide_items, 1);
			$model->show_item = json_encode(array_merge($show_items, $hide_items));
			$image = Image::upload('Member[avatar]','avatar',array('s'=>'200,200'),0);
			if (!empty($image)) {
				$model->avatar = $image['filePath'];
			}
			
			if ($model->save()) {
				//-----------------------------------
				if($wanglai_number)
				{
					Number::model()->updateAll(array('number_status'=>1),'number=:a',array(':a'=>$wanglai_number));
					if($old_wanglai_number!==$wanglai_number)
					{
						Number::model()->updateAll(array('number_status'=>0),'number=:a',array(':a'=>$old_wanglai_number));
					}
				}else
				{
					//恢复往来号
					if($old_wanglai_number && $model->wanglai_number==='')
					{
						Number::model()->updateAll(array('number_status'=>0),'number=:a',array(':a'=>$old_wanglai_number));
					}
				}
				//------------------------------------------
				
				$this->redirect(array('view','id' => $model->id));
			}
		}
		$model->show_item = !empty($model->show_item) ? array_keys(json_decode($model->show_item,true),0) : array();
		$model->password = '';
		$model->repeat_password = '';
		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 查看名片详情
	 */
	public function actionView($id) {
		$model=Member::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		$show_item = json_decode($model->show_item,true);
		$txt = array();
		if(!empty($show_item))
		{
			foreach ($show_item as $key => $value) {
				if ($value == 0) {
					$txt[] = Member::$hideOptions[$key];
				}
			}
		}
		
		$model->show_item = join('  ', $txt);
		$this->render('view', array('model' => $model));
	}
	
	/**
	 * 删除
	 */
	public function actionDelete()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(!$id) $this->ajaxJsonReturn(0, '参数错误!');
		$model = $this->loadModel()->findByPk($id);
		if(!$model) $this->ajaxJsonReturn(0,'该数据不存在或已经被删除！');
		//$model->delete();
		$model->from = 3;
		$model->setScenario('delete');
		if($model->save())
			$this->ajaxJsonReturn(1, '删除成功!');
		else 
			print_r($model->getErrors());
	}
	
	/**
	 * 创建分享链接url
	 */
	public function createShareUrl($id)
	{
		return Yii::app()->createUrl('/Card/Share',array('id'=>$id,'sign'=>Helper::createSign($id.'@#%&*')));
	}
	
}
