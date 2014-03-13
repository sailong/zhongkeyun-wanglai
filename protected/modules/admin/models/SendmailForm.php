<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $uid
 * @property string $nickname
 * @property string $password
 * @property string $register_time
 * @property string $register_ip
 * @property string $lastlogin_time
 * @property string $lastlogin_ip
 * @property integer $account_status
 * @property integer $role_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $purview
 */
class SendmailForm extends CFormModel
{

	public $title;
	public $content;
	public $addressee;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('title, content, addressee', 'required', 'message'=>'不可为空'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'title'=>'标题',
            'addressee'=>'收件人',
			'content'=>'内容',
		);
	}
	
}