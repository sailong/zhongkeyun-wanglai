<?php

/**
 * 主要是为了使用user组件
 * @author Administrator
 *
 */
class FrontUserIdentity extends CBaseUserIdentity
{
	
	private $_id;
	
	private $_name;
	
	
	/**
	 * @param object $model 已合法Member对象
	 */
	public function __construct($model)
	{
		$this->_id = $model->id;
		$this->_name=$model->name;
	}
	
	/**
	 * 已验证的用户,主要用来CWebUser组件调用
	 * @see CWebUser::login()
	 */
	public function authenticate()
	{
		$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	public function getName()
	{
		return $this->_name;
	}
	
}