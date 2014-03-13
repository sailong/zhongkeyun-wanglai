<?php

/**
 * 排行榜
 * @author Administrator
 *
 */
class Rank extends CWebApplication 
{
	
	
	
	
	/**
	 * 根据用户id获取用户好友排行（我关注的,关注我的,我的小伙伴,三者综合按view倒排)
	 * @param $id int 用户id
	 * @param $count int 各数,默认10个
	 * @return mixed bool|array  成功返回二维数组,包含的key为id,name,avatar(头像),views(访客人数),rank(全球排名)
	 */
	public static function getFriendTop($id, $pageSize=10, $page=1, $keyword='')
	{
		if(empty($id) || !is_numeric($id))
			return false;
		$connection = Yii::app()->db;
		$sql = "SELECT follow_mid FROM follow WHERE mid=$id AND is_deleted=" . Follow::FOLLOW_IN;
		$myFollow = $connection->createCommand($sql)->queryColumn();
		$sql = "SELECT mid FROM follow WHERE follow_mid=$id AND is_deleted=" . Follow::FOLLOW_IN;
		$followMe = $connection->createCommand($sql)->queryColumn();
		$sql = "SELECT new_uid FROM partner WHERE from_uid=$id";
		$myPartner = $connection->createCommand($sql)->queryColumn();
		$all = array_unique(array_merge($myFollow, $followMe, $myPartner));
		$conditon = array('in','id',$all);
		if (!empty($keyword)) {
			$keyword=strtr($keyword, array('%'=>'\%', '_'=>'\_'));
			$extra = "name like '%$keyword%' or mobile like '%$keyword%' or position like '%$keyword%' or company like '%$keyword%'";
			$conditon = "id IN(".join(',', $all).") AND ($extra)";
		}
		
		$count = $connection->createCommand()->select('count(id)')->from('member')->where($conditon)->queryScalar();
		
		$result = $connection->createCommand()
				->select('id,name,views,avatar,mobile')
				->from('member')
				->where($conditon)
				->order('views DESC')
				->offset($pageSize * ($page-1))
				->limit($pageSize)
				->queryAll();
		if (!empty($result)) 
		{
			array_walk($result, function (&$value) {
				$value['rank'] = Member::model()->getOrder($value['views']);
			});
		} else {
			$result = array();
		}
		return array('data' => $result,'total' => $count);
	}
	
}