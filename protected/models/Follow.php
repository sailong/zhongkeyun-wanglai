<?php
class Follow extends AR_Follow
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Follow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 
	 */
	
// 	public function checkFollow($mid,$follow_mid)
// 	{
// 		$result = $this->find('mid = '.$mid.' AND follow_mid ='.$follow_mid);
// 		if(!$result) return -1;
// 		if($result->is_deleted) return -2;
// 		return 1;
// 	}
	
	public function getFollowCounts($mid)
	{
		$data['follow_me_counts'] = $this->count('follow_mid = :a and is_deleted = 0',array(':a'=>$mid));
		$data['my_follow_counts'] = $this->count('mid = :a and is_deleted = 0',array(':a'=>$mid));
		return $data;
	}
	
	
	/**
	 * 帅选判断关注哪些用户
	 */
	public function getMyFollowIdArr($mid,$follow_mid_Arr)
	{
		if(empty($follow_mid_Arr) || !is_array($follow_mid_Arr)) return;
		$follow_mid_Arr = array_unique($follow_mid_Arr);
		
		$param['condition'] = 'mid = '.$mid.' AND is_deleted = 0 AND ';
		
		if(count($follow_mid_Arr) == 1)
		{
			$param['condition'] .= ' follow_mid = '.$follow_mid_Arr[0];
		}else
		{
			$param['condition'] .= ' follow_mid in ('.implode(',',$follow_mid_Arr).')';
		}
		$param['select'] = 'follow_mid';
		$result = $this->findAll($param);
		if(!$result) return;
		$id_arr = array();
		foreach ($result as $r)
		{
			$id_arr[$r->follow_mid] = $r->follow_mid;
		}
		return $id_arr;
	}
}