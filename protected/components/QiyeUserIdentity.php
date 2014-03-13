<?php

/**
 * 手机端企业登录
 * @author JZLJS00
 *
 */
class QiyeUserIdentity extends CUserIdentity
{
	private $_id;
	
	private $_mid;
	
	const ERROR_CARD_NOTEXIST=3;
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		Yii::import('application.modules.qiye.models.User');
		$user=User::model()->notsafe()->findByAttributes(array('username'=>$this->username));
		if($user===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(md5($this->password)!==$user->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$member = Member::model()->findByAttributes(array('id'=>$user->mid));
			if($member===null)
				$this->errorCode=self::ERROR_CARD_NOTEXIST;
			else
			{
				$this->_id=$user->mid;
				$this->username=$member->name;
				$this->setState('qiye', true);
				$this->errorCode=self::ERROR_NONE;
			}
		}
		return !$this->errorCode;
	}
	
	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
	
}