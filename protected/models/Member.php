<?php
class Member extends AR_Member
{
	/**
	 * 往来小秘书id
	 */
	const SERVICE_MEMBER_ID = 250720;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Member the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取名片信息
	 * @param int    $openid
	 * @param string $field
	 * @return 
	 */
	public function getMember($openid,$field='')
	{
		if (!$openid) return ;
		$param['condition']= 'weixin_openid=:a';
		$param['params'] = array(':a'=>$openid);
		if($field) $param['select'] = $field;
		return $this->find($param);
	}
	
	public function setPassword($pwd)
	{
		return md5(md5('weixin_card'.$pwd));
	}
	
	public function checkExists($filed,$value,$id=0)
	{
		$ret = $this->find($filed.'=:a',array(':a'=>$value));
		if(!$id) return $ret;
		if(!$ret) return false;
		return $ret->id==$id ? false : true;
	}
	
	/**
	 * 获取顺序
	 * @param unknown $views
	 */
	public function getOrder($views)
	{
		$sql="SELECT COUNT(*) as v_order FROM  `member` WHERE views >=".$views;
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->queryAll();
		
		if(!$result || $result[0]['v_order'] == 1)
		{
			$count = 1;
		}else
		{
			$count = $result[0]['v_order'];
		}
		if($views<10)
		{
			return $count + (10-$views) * 14321;
		}else
		{
			return $views<=50 ? $count+10000 : $count;
		}
		
	}
	
	/**
	 * 批量获取用户统计数据
	 * @param array $uidArr
	 */
	public function getMemberList($uidArr,$select=array(),$returnType='object',$byIdRank=false)
	{
		if(empty($uidArr) || !is_array($uidArr)) return;
		$uidArr = array_unique($uidArr);
		if(count($uidArr) == 1)
		{
			$param['condition'] = ' id = '.$uidArr[0];
		}else
		{
			$param['condition'] = ' id in ('.implode(',',$uidArr).')';
		}
		$param['condition'].=' AND `from` = 1';
		$select[] = 'id';
		$param['select'] = implode(',', $select);
		$result = $this->findAll($param);
		if(!$result) return;
		if($byIdRank)
		{
			//判断是否有头像
			$hasAvatar = in_array('avatar', $select) ? true : false;
			//整理顺序
			$obj = new stdClass();
			foreach ($result as $val)
			{
				$id = $val->id;
				if($hasAvatar) $val->avatar = $this->getAvatar($val->avatar,$val->id);
				$obj->$id = $val;
			}
			$result = new stdClass();
			foreach ($uidArr as $id)
			{
				$result->$id = $obj->$id;
			}
			
		}
		if($returnType=='object')
		{
			return $result;
		}else
		{
			$data = array();
			foreach($result as $u)
			{
				foreach ($this->attributes as $key=>$val)
				{
					if(in_array($key, $select))   $data[$u->id][$key] = $key=='avatar' ? $this->getAvatar($u->avatar,$u->id)  :$u->$key;
				}
			}
			return $data;
		}
	}
	/**
	 * 获取头像
	 * @param unknown $avatar
	 * @return string
	 */
	public function getAvatar($avatar,$id)
	{
		
		$avatar = Helper::getImage($avatar);
		if(empty($avatar))
		{
			$model = self::model()->with('extend')->findByPk($id);
			if(!empty($model->extend->headimgurl))
			{
				$avatar = Helper::getImage($model->extend->headimgurl);
			}else{
				$avatar = Yii::app()->request->hostInfo.'/static/weixin/card.jpg?version=12012';
			}
		}
		return $avatar;
	}
	/**
	 * @param unknown $id
	 * @param unknown $field
	 * @return void|Ambigous <NULL, unknown, multitype:unknown Ambigous <unknown, NULL> , mixed, multitype:, multitype:unknown >
	 */
	public function getMemberBy($by_filed,$value,$fields='')
	{
		if (!$by_filed) return ;
		$param['condition']= $by_filed.' = :a';
		$param['params'] = array(':a'=>$value);
		if($fields) $param['select'] = $fields;
		$ret = $this->find($param);
		if($ret)
		{
			if(!$fields || stristr($fields, 'avatar'))
			{
				$ret->avatar = $this->getAvatar($ret->avatar,$ret->id);
			}
		}
		return $ret;
	}
}