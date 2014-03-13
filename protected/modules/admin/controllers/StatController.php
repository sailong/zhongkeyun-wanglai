<?php

class StatController extends AdminController
{
	public $nav = '数据统计';
		
	public function actionIndex()
	{
		$date = date('Y-m-d',time());
		$begin = strtotime($date);
		$end = $begin + 86400;
		$sql = "SELECT count('id') FROM member WHERE created_at BETWEEN {$begin} AND {$end} AND `from`=1";
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		$sql = "SELECT * FROM info WHERE date={$begin}";
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if(empty($result))
		{
			$result['pv'] = 0;
			$result['uv'] = 0;
		}
		$result['new'] = $count;
		$result['date'] = $date;
		
		
		$sql = "SELECT * FROM info WHERE date<{$begin} ORDER BY date DESC";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
	
		$this->render('index',array('data'=>$data,'current' => $result));
	}
	
	/**
	 * 统计活跃用户,一周内登录过的用户
	 */
	public function actionGetActiveMember()
	{
		$end = time();
		$start = $end - 86400 * 24;
		$sql = "SELECT count(*) FROM stat WHERE last_login_at BETWEEN {$start} AND {$end}";
		$total = Yii::app()->db->createCommand($sql)->queryScalar();
		$pagination = new CPagination($total);
		$pagination->setPageSize(20);
		$offset = $pagination->getOffset();
		$sql = "SELECT m.id,m.name,m.mobile,m.position,m.created_at,s.last_login_at FROM stat as s,member as m WHERE s.last_login_at BETWEEN {$start} AND {$end} AND s.member_id=m.id ORDER BY last_login_at DESC LIMIT {$offset},20";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		$this->render('activeMember',array('data'=>$data,'pagination'=>$pagination));
	}
	
	
}