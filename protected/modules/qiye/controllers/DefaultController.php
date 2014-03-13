<?php

class DefaultController extends QiyeController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/qiye/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * 首页统计
	 */
	public function actionIndex()
	{
		// 总员工数
		$mid = Yii::app()->user->mid;
		$model = Member::model()->findByPk($mid);
		$totalEmployee = $this->getTotalEmployee();
		$totalActivity = Activity::model()->countByAttributes(array('create_mid'=>$mid));
		$totalContacts = Contacts::model()->countByAttributes(array('create_mid'=>$mid,'default'=>Contacts::DEFAULT_NO));
		$totalApply = $this->getTotalContactsApply() + $this->getTotalActivityApply();
		$latestEmployeeStat = array();
		foreach($this->getLatestEmployeeStat() as $key => $value)
		{
			$latestEmployeeStat["'".date('m/d',$key)."'"] = $value;
		}
		$this->render('index',compact('totalEmployee','totalActivity','totalContacts','totalApply','latestEmployeeStat','model'));
	}
	
	/**
	 * 获取员工总数
	 */
	private function getTotalEmployee()
	{
		// 员工群
		$total = 0;
		$contacts = Contacts::model()->findByAttributes(array('create_mid'=>Yii::app()->user->mid,'default'=>Contacts::DEFAULT_YES));
		if(!empty($contacts))
		{
			$sql = "SELECT count(*) FROM contacts_member_rel WHERE contacts_id=".$contacts->id." AND state=".ContactsMember::STATE_PASS;
			$total = Yii::app()->db->createCommand($sql)->queryScalar();
		}
		return $total;
	}
	
	/**
	 * 有多少群申请
	 */
	private function getTotalContactsApply()
	{
		$sql = "SELECT count(cmr.id) FROM contacts_member_rel as cmr,contacts as c WHERE c.create_mid=".Yii::app()->user->mid." AND c.id=cmr.contacts_id AND cmr.state=".ContactsMember::STATE_APPLY;
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 有多少活动申请
	 */
	private function getTotalActivityApply()
	{
		$sql = "SELECT count(amr.id) as total FROM activity_member_rel as amr,activity as a WHERE a.create_mid=".Yii::app()->user->mid." AND a.id=amr.activity_id AND amr.state=".ActivityMember::VERIFY_STATE_APPLY;
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 最近7天用户数量
	 */
	private function getLatestEmployeeStat()
	{
		$current = strtotime(date('Y-m-d'));
		$before1 = $current - 86400;
		$before2 = $before1 - 86400;
		$before3 = $before2 - 86400;
		$before4 = $before3 - 86400;
		$before5 = $before4 - 86400;
		$before6 = $before5 - 86400;
		$data = array($before6=>0,$before5=>0,$before4=>0,$before3=>0,$before2=>0,$before1=>0,$current=>0);
		$start = strtotime(date('Y-m-d')) - 7*86400;
		$contacts = Contacts::model()->findByAttributes(array('create_mid'=>Yii::app()->user->mid,'default'=>Contacts::DEFAULT_YES));
		$sql = "SELECT * FROM contacts_member_rel WHERE contacts_id={$contacts->id} AND `state`=".ContactsMember::STATE_PASS." AND update_time BETWEEN {$start} AND ".time()." ORDER BY update_time ASC";
		$all = Yii::app()->db->createCommand($sql)->queryAll();
		$result = array();
		if(!empty($all))
		{
			foreach ($all as $value)
			{
				$passtime = $value['update_time'];
				if($passtime>=$before6 && $passtime<$before5)
				{
					$data[$before6] += 1;
				}elseif($passtime>=$before5 && $passtime<$before4)
				{
					$data[$before5] += 1;
				}elseif($passtime>=$before4 && $passtime<$before3)
				{
					$data[$before4] += 1;
				}elseif($passtime>=$before3 && $passtime<$before2)
				{
					$data[$before3] += 1;
				}elseif($passtime>=$before2 && $passtime<$before1)
				{
					$data[$before2] += 1;
				}elseif($passtime>=$before1 && $passtime<$before2)
				{
					$data[$before1] += 1;
				}elseif ($passtime>=$current && $passtime<time())
				{
					$data[$current] += 1;
				}
			}
		}
		return $data;
	}
	
	/**
	 * 最近7天PV
	 */
	private function getLatestPvStat()
	{
		
		
	}
	
	/**
	 * 最近7天UV
	 */
	private function getLatestUvStat()
	{
		
	}
}