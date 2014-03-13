<?php

/**
 * This is the model class for table "partner".
 *
 * The followings are the available columns in table 'partner':
 * @property string $id
 * @property integer $uid
 * @property integer $from_uid
 * @property integer $created_at
 */
class Partner extends AR_Partner
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Partner the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getMyPartner($uid)
	{
		$param['select']='new_uid,from_uid';
		//$param['condition'] = 'new_uid ='.$uid.' or from_uid = '.$uid;
		$param['condition'] = 'from_uid='.$uid;
		$param['limit'] = 20;
		$param['order'] = 'id DESC';
		return $this->findAll($param);
	}
	
	public function getMyPartnerCounts($uid)
	{
		if(!$uid) return 0;
		return $this->count('from_uid = '.$uid);
	}
}