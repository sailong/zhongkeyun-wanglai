<?php
class SendmailController extends AdminController
{
	
	
	public $nav='发送邮件';
	public $service_member_id = 2;
	
	public function actionIndex()
	{
	    $searchModel = new SearchForm();
	    if(isset($_GET['SearchForm'])) $searchModel->attributes = $_GET['SearchForm'];
	    //$searchModel->send_type = isset($_GET['SearchForm']['send_type'])?intval($_GET['SearchForm']['send_type']) : 1;
	  
	    $conditionArr = array();
		if($searchModel->keyword) $conditionArr[] = "title  like '%".$searchModel->keyword."%'";
		
		$conditionArr[] = 'create_mid = '.Member::SERVICE_MEMBER_ID;
		
		if($conditionArr) $condition = trim(implode(' AND ', $conditionArr),' AND ');
	    $order = 'id DESC';
	    $dataProvider = new CActiveDataProvider('Sendmail',
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
	    /* //获取用户姓名
	   $result  = $dataProvider->getData();
	    if($result)
	    {
	        $midArr = array();
	        foreach ($result as $r)
	        {
	            $midArr[] = $r->create_mid;
	        }
	        $data['memberArr'] = Member::model()->getMemberList($midArr,array('name'),'array');
	    } */
	    $data['searchModel'] = $searchModel;
	    $data['sendTypeArr'] = array(0 => '',1=> '所有会员',2=>'所有通讯录的发起人',3=>'某个通讯录的所有成员');
		$this->render('index',$data);
	}
	
	public function actionMailDetail($id){
	    $sendmail=new Sendmail;  
			
		$mail_info=Sendmail::model()->find(array(
			'select'=>'*',
			'condition'=>'id=:id',
			'params'=>array(':id'=>$id),
		));
		$data['info'] = $mail_info;
		$data['type'] = array(0 => '',1=> '所有会员',2=>'所有通讯录的发起人',3=>'某个通讯录的所有成员');
	    $this->render('mailinfo',$data);
	}
	
    /**
     * 修改操作
     */	
	public function actionAdd()
	{
		$send_type_arr = array(
				1=>'所有会员',
				2=>'所有通讯录的发起人',
				3=>'某个通讯录的所有成员'
			);
		$send_type = $_GET['SearchForm']['send_type'];
		$this->render('add',array('send_type'=>$send_type,'send_name'=>$send_type_arr[$send_type]));
	}
	
	//发送邮件
	public function actionSend()
	{
		$title = $_POST['title'];
		$content = $_POST['content'];
		$send_type = $_POST['send_type'];
		
		if(empty($title) || empty($content) || empty($send_type)){
			$this->showMessage('操作失败',1,array('index'));
		}
		
		if($send_type==1)
		{
			//发给全部会员
			$sql = 'SELECT email,id FROM member where `from`=1';
			
		}elseif($send_type==2)
		{
			//发给全部通讯录的发起人
			$sql = 'SELECT a.email,a.id FROM member a left join contacts b on a.id=b.create_mid where a.from=1 group by b.create_mid';
		}elseif($send_type==3)
		{
			//发给某个或多个通讯录的成员
			$contacts_str = trim($_POST['contacts_ids']);
			$contacts_ids = array();
			if(!empty($contacts_str))
			{
				preg_match('/id:(\d+)/', $contacts_str,$match);
				if(!empty($match[1]))
				{
					$contacts_ids[] = (int)$match[1];
				}
			}
			
			$contacts_ids_str = "'".implode("','",$contacts_ids)."'";
			
			$sql = "SELECT a.email,a.id FROM member a left join contacts_member_rel b on a.id=b.member_id and b.contacts_id in({$contacts_ids_str})and b.state=2 where a.from=1 group by b.member_id";
		}
		//添加邮件内容
		$send_mail_id = $this->addSendMail(array('title'=>$title,'content'=>$content,'send_type'=>$send_type));
		
		//发送邮件
		$this->send_mail_by_sql($sql,$title,$content,$send_mail_id);
		
		$this->showMessage('操作成功',1,array('index'));
	}
	
	//添加邮件内容
	private function addSendMail($data)
	{
		$model = new Sendmail;
		
		$model->title = $data['title'];
		$model->content = $data['content'];
		$model->create_mid = Member::SERVICE_MEMBER_ID;
		$model->send_type = $data['send_type'];
		$model->add_time = time();
		
		$rs = $model->save();
		$id = $model->attributes['id'];
		if($rs > 0){
			return $id;
		}
		
		return false;
		
	}
	
	//添加发送邮件记录
	private function addSendMailList($data)
	{
		if(empty($data)){
			return false;
		}
		$model = new SendMailList;
		
		$model->send_mail_id = $data['send_mail_id'];
		$model->to_mid = $data['to_mid'];
		$model->is_send = $data['is_send'];
		$model->send_time = time();
		$rs = $model->save();
		if($rs > 0){
			return true;
		}
		
		return false;
	}
	
	//批量添加发送记录
	private function addSendMailListBatch($val_arr)
	{
		if(empty($val_arr)){
			return false;
		}
		$vals_str = '';
		$vals_arr1 = array();
		foreach($val_arr as $key=>$val)
		{
			$vals_arr1[] = "('".implode("','",$val)."')";;
		}
		$vals_str = implode(",",$vals_arr1);
		
		//$field_str = "('".implode("','",$field_arr)."')";
		$field_str = "(send_mail_id,to_mid,is_send,send_time)";
		unset($vals_arr1,$field_arr);
		$sql = "insert into send_mail_list{$field_str} values{$vals_str}";
		$command = Yii::app()->db->createCommand($sql);
		$ret = $command->execute();
		if($ret > 0)
		{
			return true;
		}
		
		return false;
	}
	
	//获取某个通讯录
	public function actionGetContacts()
	{
		$data = array();
		$key = htmlspecialchars(trim(Yii::app()->request->getParam('term')));
		if(!empty($key))
		{
			$sql = "SELECT a.id,a.title,a.create_mid,b.name FROM contacts a left join member b on a.create_mid=b.id WHERE a.title LIKE '$key%'";
			$result = Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($result))
			{
				foreach($result as $value)
				{
					$value['name'] = !empty($value['name']) ? $value['name'] : ' ';
					$data[] = $value['title'] . '(创建人：'.$value['name'].',id:'.$value['id'].')';
				}
			}else{
				$data[] = '暂时无此通讯录';
			}
		}
		echo json_encode($data);
		exit();
	}
	//处理数据并发送邮件
	private function send_mail_by_sql($sql='',$title='',$content='',$send_mail_id=''){
	
		if(empty($sql) || empty($title)|| empty($content) || empty($send_mail_id)){
			return false;
		}
		$flag = true;
		$limit = 5000;
		$mail_arr = array();
		$page = 1;
		$num = 0;
		while($flag){
			$offset = ($page-1)*$limit;
			$sql_tmp = $sql." limit {$offset},{$limit}";
			$data = Yii::app()->db->createCommand($sql_tmp)->queryAll();
			if($data){
				$field_arr = array();
				$val_arr = array();
				foreach($data as $val){
					if($this->isEmail($val['email'])){
						$send_arr['send_mail_id'] = $send_mail_id;
						$send_arr['to_mid'] = $val['id'];
						$send_arr['is_send'] = 1;
						$send_arr['send_time'] = time();
						$send_arr_list[$val['id']] = $send_arr;
						$mail_arr[] = $val['email'];
					}
				}
				/* var_dump($send_arr_list);
				var_dump($mail_arr);die; */
				$this->addSendMailListBatch($send_arr_list);
				$mail_arr = array_flip(array_flip($mail_arr));
				//echo count($mail_arr).'</br>';
				$num+=count($mail_arr);
				Util::sendMail($mail_arr, $title, $content);
				unset($mail_arr,$send_arr,$send_arr_list);
			}else{ 
				$flag=false;
			}
			$page+=1;
			sleep(1);
		}
		//echo $num;
		return true;
	}
	
	//检测email格式是否正确
	private function isEmail($email){
		if(preg_match("/^[0-9a-zA-Z]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i",$email )) {
			return true;
		} else {
			return false;
		}
	} 
	

}