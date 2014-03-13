<?php

/**
 * 贺卡控制器相关行为
 * @author JZLJS00
 *
 */
class CardBehavior extends CBehavior
{
	
	public function events()
	{
		return array(
			'onBeforeIndex'  => 'beforeIndex',
		);
	
	}
	/**
	 * 记录“设计全新贺卡”的点击数
	 * @param unknown_type $event
	 */
	public function beforeIndex($event)
	{
		$id = intval(Yii::app()->request->getParam('from'));
		$model = Card::model()->findByPk($id);
		if(!empty($model))
		{
			$model->click_counts += 1;
			$model->save();
		}
	}
}