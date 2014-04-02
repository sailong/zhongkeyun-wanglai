<?php
/**
 * 文章有关的行为
 * @author zhoujianjun
 *
 */
class ArticleBehavior extends CBehavior
{

	public function events()
	{
		return array(
			'onAfterView'   => 'afterView'
		);
	}
	
	/**
	 * 文章详情页计数及记录用户浏览日志
	 * @param unknown_type $event
	 */
	public function afterView($event)
	{
		// 增加浏览量
		$model = $event->params['model'];// 活动对象
		$model->updateByPk($model->id,array('views'=>$model->views+1));
		
		// 记录用户浏览日志(已登录用户)
		if(!Yii::app()->user->getIsGuest() && Yii::app()->user->id != $model->create_mid)
		{
			$articleViewer = ArticleViewer::model()->findByAttributes(array('member_id'=>Yii::app()->user->id,'article_id'=>$model->id));
			if(empty($articleViewer))
			{
				$articleViewer = new ArticleViewer();
				$articleViewer->attributes = array('member_id'=>Yii::app()->user->id,'article_id'=>$model->id,'view_time'=>time());
				$articleViewer->view_time = time();
			}else{
				// 更新浏览时间
				$articleViewer->view_time = time();
			}
			$articleViewer->save();
		}
	}	
}
