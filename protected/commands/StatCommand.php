<?php

/**
 * 用于统计的控制台程序
 * @author EchoEasy
 *
 */
class StatCommand extends CConsoleCommand
{
	
	
	/**
	 * 统计每天新增用户数
	 * yiic stat countAdded
	 */
	public function actionCountAdded()
	{
		$current = date('Y-m-d',time());
		$begin = strtotime($current)-86400;
		$end = strtotime($current);
		$sql = "SELECT count('id') FROM member WHERE created_at BETWEEN {$begin} AND {$end} AND `from`=1";
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		$sql = "UPDATE info SET new={$count} WHERE date={$begin}";
		Yii::app()->db->createCommand($sql)->execute();
	}
	
	
	
}