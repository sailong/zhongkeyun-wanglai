<?php

/**
 * This is the model class for table "view_log".
 *
 * The followings are the available columns in table 'view_log':
 * @property string $id
 * @property integer $member_id
 * @property integer $viewd_member_id
 * @property integer $created_at
 */
class ViewLog extends AR_ViewLog
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_ViewLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function checkViewByCookie($id)
	{
		$expeTime = time()+3600*24*30;
		$views = isset($_COOKIE['views']) ? $_COOKIE['views'] : array();
		$viewArr = array($id);
		if(!$views)
		{
			setcookie('views',json_encode($viewArr),$expeTime);
			return true;
		}
		$views = json_decode($views,true);
		if(in_array($id, $views)) return false;
		$views[]=$id ;
		setcookie('views',json_encode($views),$expeTime);
		return true;
	}
	
}