<?php
class MyController extends Controller
{
	
    public function actionIndex()
    {   return;
    	if(!isset($_GET['key']) || $_GET['key']!='000999' ) die('error');
    	$model = Member::model()->findAll('created_at > :a AND views = 0',array(':a'=>1383912598));
    	$i = 0;
    	if($model) 
    	{
    		foreach ($model as $m)
    		{
    			$stat = Stat::model()->find('member_id='.$m->id);
    			if($stat && $stat->pv_counts>0)
    			{
    				$views = ceil(($stat->pv_counts/5)*3);
    				if($views==0) $views = 1;
    				if(Member::model()->updateAll(array('views'=>$views),'id = '.$m->id)) $i++;
    			}
    		}
    	}
    	echo 'ok---'.$i;
    }
    
    
    public function actionCountAdd()
    {
    	$start = '2013-10-25';
    	$data = strtotime($start);
    	for($i=1;$i<25;$i++)
    	{
    		$end = $data + 86400;
    		//echo date('Y-m-d',$data) . '_' . date('Y-m-d',$end);
  		//	echo "<br>";
    		$sql = "SELECT count('id') FROM member WHERE created_at BETWEEN {$data} AND {$end} AND `from`=1";
			$count = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "update info set new={$count} where date={$data}";
			Yii::app()->db->createCommand($sql)->execute();
			$data += 86400;
    	}
    }
}
?>