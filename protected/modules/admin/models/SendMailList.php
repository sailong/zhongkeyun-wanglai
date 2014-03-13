<?php
class SendMailList extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'send_mail_list';
	}

	public function primaryKey()
	{
		return 'id';
		// return array('pk1', 'pk2');
	}
}