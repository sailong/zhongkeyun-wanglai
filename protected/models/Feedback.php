<?php

/**
 * 意见反馈
 * @author JZLJS00
 *
 */
class Feedback extends CActiveRecord
{
	
	/**
	 * 反馈是否解决1,未解决,2已解决
	 * @var unknown_type
	 */
	const UNRESOLVED = 1;
	const RESOLVED = 2;
	
	
	public function tableName()
	{
		return 'feedback';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function rules()
	{
		return array();
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'mid' => '用户ID',
			'member_name' => '用户名',
			'content' => '反馈内容',
			'create_time' => '创建时间',
			'resolved' => '是否解决',
			'operator_id' => '操作用户id',
			'operator_name' => '操作用户'
		);
	}
}