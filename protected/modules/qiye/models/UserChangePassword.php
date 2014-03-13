<?php
/**
 * UserChangePassword class.
 * UserChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class UserChangePassword extends CFormModel {
	public $oldPassword;
	public $password;
	public $verifyPassword;
	
	public function rules() {
		return Yii::app()->controller->id == 'recovery' ? array(
			array('password, verifyPassword', 'required'),
			array('password, verifyPassword', 'length', 'max'=>128, 'min' => 4,'message' => '密码太短,至少4个字符'),
			array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => '两次输入密码不一致'),
		) : array(
			array('oldPassword, password, verifyPassword', 'required'),
			array('oldPassword, password, verifyPassword', 'length', 'max'=>128, 'min' => 4,'message' => '密码太短,至少4个字符'),
			array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => '两次输入密码不一致'),
			array('oldPassword', 'verifyOldPassword'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'oldPassword'=>'原始密码',
			'password'=>'新密码',
			'verifyPassword'=>'确认密码',
		);
	}
	
	/**
	 * Verify Old Password
	 */
	 public function verifyOldPassword($attribute, $params)
	 {
		 if (User::model()->notsafe()->findByPk(Yii::app()->user->id)->password != Yii::app()->getModule('qiye')->encrypting($this->$attribute))
			 $this->addError($attribute, '原始密码错误');
	 }
}