<?php

/**
 * This is the model class for table "stat".
 *
 * The followings are the available columns in table 'stat':
 * @property string $id
 * @property string $member_id
 * @property integer $pv_counts
 * @property integer $favorite_counts
 */
class Stat extends AR_Stat
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Stat the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 更新统计
	 * @param unknown $id
	 * @param unknown $fields
	 */
	public function updateStat($id,$fields=array())
	{
		if(!$id || !$fields) return;
		$ret = $this->updateCounters($fields,'member_id = '.$id);
		if(!$ret)
		{
			$this->setIsNewRecord(true);
			$this->member_id = $id;
			foreach ($fields as $key=>$f)
			{
				$this->$key = 1;
			}
			$ret = $this->insert();
		}
	}
	
	/**
	 * 批量获取用户统计数据
	 * @param array $uidArr
	 */
	public function getStat($uidArr,$select=array())
	{
		if(empty($uidArr) || !is_array($uidArr)) return;
		$uidArr = array_unique($uidArr);
		if(count($uidArr) == 1)
		{
			$param['condition'] = ' member_id = '.$uidArr[0];
		}else
		{
			$param['condition'] = ' member_id in ('.implode(',',$uidArr).')';
		}
		$select[] = 'member_id';
		$param['select'] = implode(',', $select);
		$result = $this->findAll($param);
		if(!$result) return;
		$statInfo = array();
		foreach($result as $u)
		{
			foreach ($this->attributes as $key=>$val)
			{
				if(in_array($key, $select))   $statInfo[$u->member_id][$key] = $u->$key;
			}
		}
		return $statInfo;
	}
	
	/**
	 * 清除消息提示
	 * @param unknown $id
	 * @param unknown $field
	 */
	public function clearMsgNotice($id,$field)
	{
		$this->updateAll(array($field=>0),'member_id = '.$id);
	}
}