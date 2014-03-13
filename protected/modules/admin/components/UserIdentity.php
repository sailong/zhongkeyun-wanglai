<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    public $errorMsg;
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
        $ret = User::model()->adminLogin($this->username,$this->password);
        if($ret[0] < 0) 
        {
        	$this->errorMsg = $this->errorCode = $ret[1];
        }else
        {
        	$user = $ret[1];
        	if(!$user) $this->errorCode=self::ERROR_USERNAME_INVALID;
        	$this->_id = $user->uid;
        	$this->setState('uid', $user->uid);
        	$this->setState('role', $user->role_id);
        	$this->setState('nickname', $user->nickname);
        	$this->setState('purview', $user->purview ? json_decode($user->purview,true) : array());
        	$user->setAttribute('last_login_ip', Yii::app()->request->userHostAddress);
        	$user->setAttribute('last_login_time', time());
        	$user->save(false);
        	$this->errorCode=self::ERROR_NONE;
        }
       
		return !$this->errorCode;
	}

    public function getId()
    {
        return $this->_id;
    }
}