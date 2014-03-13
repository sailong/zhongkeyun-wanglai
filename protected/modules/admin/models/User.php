<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $uid
 * @property string $nickname
 * @property string $email
 * @property string $mobile
 * @property string $password
 * @property integer $register_time
 * @property string $register_ip
 * @property integer $lastlogin_time
 * @property string $lastlogin_ip
 * @property integer $account_status
 * @property integer $role_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends AR_User
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function relations()
	{
		return array(
				'profile'=>array(self::HAS_ONE, 'UserProfile', 'uid'),
				'stat'   =>array(self::HAS_ONE, 'UserStat', 'uid'),
		);
	}
	/**
	 * 生成用户密码
	 */
	public function createMd5Password($password)
	{
		return md5(md5('BMW-2013'.$password));
	}
	/**
	 * 判断昵称是否存在
	 * @param string $nickname
	 */
	public function checkNicknameIsExists($nickname)
	{
		$result = $this->find(" nickname = :a",array(':a' => $nickname));
		return $result ? true : false;
	}
	
	/**
	 * 判断email是否存在
	 * @param string $email
	 */
	public function checkEmailIsExists($email)
	{
		$result = $this->find(" email = :a",array(':a' => $email));
	
		return $result ? true : false;
	}
	
	public function checkAccountStatus($uid)
	{
		if(!$uid) return -1;
		$p['select'] = 'account_status';
		$p['condition'] = 'uid = '.$uid;
		$user = $this->find($p);
		if(!$user) return -1;//用户不存在
		if($user->account_status == 0) return -2; //被删除
		return 1;
	}
	
	
	/**
	 * 登录
	 * @param string $nickname
	 * @param string $password
	 */
	public function adminLogin($nickname,$password)
	{
		$param['select'] = 'uid,nickname,password,account_status,role_id,purview';
		$param['condition'] = 'nickname = :a';
		if(Helper::checkEmail($nickname)) $param['condition'] = 'email = :a';
		$param['params'] = array(':a' => $nickname);
		//验证用户
		$user = $this->find($param);
		if(!$user) return array(-100,'用户不存在');
		if($this->createMd5Password($password) != $user->password) return array(-101,'密码错误');
		if($user->account_status == 0) return array(-102,'账号被删除');
		if($user->role_id == 0) return array(-103,'未被授权');
		//记录登录数据
		$user->lastlogin_time = time();
		$user->lastlogin_ip = Helper::get_real_ip();
		$user->save();
		return array(0,$user);
	}
	
	public function attributeLabels()
	{
		return array(
				'uid' => 'Uid',
				'nickname' => '账号',
				'password' => '密码',
				'register_time' => 'Register Time',
				'register_ip' => 'Register Ip',
				'lastlogin_time' => 'Lastlogin Time',
				'lastlogin_ip' => 'Lastlogin Ip',
				'account_status' => 'Account Status',
				'role_id' => 'Role',
				'created_at' => 'Created At',
				'updated_at' => 'Updated At',
		);
	}
	
}