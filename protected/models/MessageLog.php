<?php
/**
 * This is the model class for table "message_log".
 *
 * The followings are the available columns in table 'message_log':
 * @property string $id
 * @property integer $member_id
 * @property string $mobile
 * @property integer $action
 * @property integer $created_at
 * @property integer $send_status
 */
class MessageLog extends AR_MessageLog
{
	
	/**
	 * action类型1,创建名片2找回密码3登录验证码4注册验证
	 * @var unknown_type
	 */
	const ACTION_TYPE_CREATE   = 1;
	const ACTION_TYPE_PASSWORD = 2;
	const ACTION_TYPE_LOGIN    = 3;
	const ACTION_TYPE_SIGN     = 4;
	const ACTION_TYPE_BIND	   = 5;
	
	/**
	 * 发送成功与失败 1成功0失败
	 */
	const SMS_SUCCESS = 1;
	const SMS_FAILED = 0;
	
	public function scopes()
	{
		return array(
			'recently' => array(
				'order' => 'id DESC',
				'limit' => 1
			)	
		);
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_MessageLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function saveData($param)
	{
		$this->setIsNewRecord(true);
		foreach($param as $key=>$val)
		{
			$this->$key = $val;
		}
		$this->insert();
	}
	
}