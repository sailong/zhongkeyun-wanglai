<?php
/**
 * 文章模块
 * @author zhoujianjun
 * @date 2013-3-14
 *
 */
class ArticleController extends FrontController
{
	
	public $defaultAction = 'home';
	
	/**
	 *
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl - view,list',
			'ajaxOnly + mark,comment,countShare'
		);
	}
	
	public function accessRules()
	{
		return array(
			array(
				'deny',
				'actions' => array('index','home','comment','overView','create','mark','up','mypublish','mycomment','mymarked','update','publish'),
				'users'   => array('?'),
				'deniedCallback' => array($this,'deny')
			)
		);
	}
	
	public function deny()
	{
		$actionId = $this->getAction()->getId();
		$refer = in_array($actionId, array('mark','up','comment')) ? Yii::app()->request->getUrlReferrer() : Yii::app()->request->getRequestUri();
		Util::addCookie('refer', $refer);
		$this->goAuth();
	}
	
	public function behaviors()
	{
		return array(
			'article' => 'application.components.behaviors.ArticleBehavior'
		);
	}
	/**
	 * 文章模块入口页
	 */
	public function actionHome()
	{
		$uid = Yii::app()->user->id;
		$totalCreate = Article::model()->getTotal($uid);
		$totalMark = ArticleMark::model()->getTotal($uid);
		$this->render('home',compact('totalCreate','totalMark'));
	}
	
	/**
	 * 创建文章(未发布)
	 */
	public function actionCreate()
	{
		$model = new Article();
		if(isset($_POST['Article']))
		{
			$once = Yii::app()->user->getFlash('once');
			if($once == NULL)
			{
				$params = $_POST['Article'];
				if(empty($params['title']) || empty($params['content']))
				{
					Yii::app()->user->setFlash('article','请填写完整信息');
				}else{
					$content = trim($params['content']);
					$summary = mb_substr(preg_replace('/[\s|\&nbsp;]/', '', strip_tags($content)),0,50,'UTF-8');
					$model->attributes = array('title'=>trim($params['title']),'create_mid'=>Yii::app()->user->id,'create_time'=>time(),'publish'=>Article::PUBLISH_NO,'summary'=>$summary);
					if($model->save())
					{
						Yii::app()->user->setFlash('once', $model->id);
						$articleContent = new ArticleContent();
						$articleContent->article_id = $model->id;
						$articleContent->content = $content;
						if($articleContent->save())
						{
							$url = $this->createUrl('overView',array('id'=>$model->id));
							$this->redirect($url);
						}else{
							$model->delete();
						}
					}else{
						$error = array_values($model->getErrors());
						Yii::app()->user->setFlash('article',$error[0][0]);
					}
				}
			}else{
				$url = $this->createUrl('view', array('id'=>Util::encode($once)));
				$this->redirect($url);
			}
		}
		$this->render('create',array('model'=>$model));
	}
	
	/**
	 * 发布文章(创建-预览-发布)
	 */
	public function actionPublish($id)
	{
		$model = $this->loadModel($id);
		$model->updateByPk($model->id, array('publish'=>Article::PUBLISH_YES,'publish_time'=>time()));
		$this->redirect(array('view','id'=>Util::encode($id)));
	}
	
	/**
	 * 文章预览
	 */
	public function actionOverView($id)
	{
		$model = $this->loadModel($id);
		$this->render('overview',array('model'=>$model));
		Yii::app()->end();
	}
	
	/**
	 * 编辑文章
	 * @param int $id
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		if(isset($_POST['Article']))
		{
			$params = $_POST['Article'];
			if(empty($params['title']) || empty($params['content']))
			{
				Yii::app()->user->setFlash('article','请填写完整信息');
			}else{
				$content = trim($params['content']);
				$model->title = trim($params['title']);
				$model->summary = mb_substr(preg_replace('/[\s|\&nbsp;]/', '', strip_tags($content)),0,50,'UTF-8');
				$model->save();
				$model->content->content = $content;
				$model->content->save();
				$url = $this->createUrl('view',array('id'=>Util::encode($model->id)));
				$this->redirect($url);
			}
		}
		$this->render('create', array('model'=>$model));
	}
	
	/**
	 * 文章详情
	 * 
	 * @param int $id
	 */
	public function actionView($id)
	{
		$model = $this->loadModel(Util::decode($id));
		if($model->publish == Article::PUBLISH_NO && $model->create_mid == Yii::app()->user->id)
		{
			// 未发布的文章,创建人继续发布
			$url = $this->createUrl('overView', array('id'=>$model->id));
			$this->redirect($url);
			Yii::app()->end();
		}elseif($model->publish == Article::PUBLISH_YES)
		{
			// 已发布的文章
			$comments = ArticleComment::model()->with('member')->findAll(array('condition'=>'article_id='.$model->id,'limit'=>2));
			$viewers = Member::model()->findAll(array('alias'=>'m', 'join'=>'right join article_viewer as ve on ve.article_id='.$model->id.' and ve.member_id=m.id','order'=>'ve.view_time desc'));
			
			$marks = ArticleMark::model()->findAll(array('condition'=>'article_id='.$model->id.' and `delete`='.ArticleMark::UP_DELETE_NO.' and type='.ArticleMark::TYPE_UP));
			foreach($marks as $mark){
			    $user_ids[$mark->member_id]=$mark->member_id;
			}
			$totalUp = 0;
           
			$upers = array();
			foreach($viewers as $key=>$viewer){
			    if(in_array($viewer->id,$user_ids)){
			        $upers[$key] = $viewer;
			        unset($viewers[$key]);
			        $totalUp++;
			    }
			}
			
			$this->onAfterView(new CEvent($this,array('model'=>$model)));
			$this->render('view',array('model'=>$model,'comments'=>$comments,'viewers'=>$viewers,'upers'=>$upers,'totalUp'=>$totalUp));//,'upers'=>$upers
		}else{
			$this->showMessage(0, 'not exist');
		}
	}
	
	
	/**
	 *  php:sorted object in array according to a object's field.
	 *
	 * @param array $List
	 * @param var $by sort filed
	 * @param var $order desc/asc
	 * @param var $type sort type（num/string）
	 * @return array
	 */
	function ArraySort(array $List, $by, $order='', $type='') {
	    if (empty($List))
	        return $List;
	    foreach ($List as $key => $row) {
	        //    $sortby[$key] = $row[$by] ;
	        $sortby[$key] = $row->$by;
	    }
	    if ($order == "DESC") {
	        if ($type == "num") {
	            array_multisort($sortby, SORT_DESC, SORT_NUMERIC, $List);
	        } else {
	            array_multisort($sortby, SORT_DESC, SORT_STRING, $List);
	        }
	    } else {
	        if ($type == "num") {
	            array_multisort($sortby, SORT_ASC, SORT_NUMERIC, $List);
	        } else {
	            array_multisort($sortby, SORT_ASC, SORT_STRING, $List);
	        }
	    }
	    return $List;
	}
	
	/**
	 * 记录浏览量及浏览用户
	 * @param unknown $event
	 */
	public function onAfterView($event)
	{
		$this->raiseEvent('onAfterView', $event);
	}
	/**
	 * 收藏文章
	 */
	public function actionMark($id)
	{
		$model = $this->loadModel(Util::decode($id));
		if($model->publish == Article::PUBLISH_NO)
			throw new CHttpException(404,'The requested page does not exist.');
		if(ArticleMark::model()->mark($model->id, Yii::app()->user->id))
			$this->returnData(1,'已收藏');
		else 
			$this->returnData(0, '您已收藏过了！');
		$uid = Yii::app()->user->id;
	}
	
	/**
	 * 文章点赞
	 * @param unknown $id
	 */
	public function actionUp($id)
	{
		$model = $this->loadModel(Util::decode($id));
		if($model->publish == Article::PUBLISH_NO)
			throw new CHttpException(404,'The requested page does not exist.');
		
		$articleMark = ArticleMark::model()->findByAttributes(array('member_id'=>Yii::app()->user->id,'article_id'=>$model->id,'type'=>ArticleMark::TYPE_UP));
		if(empty($articleMark))
		{
			$articleMark = new ArticleMark();
			$articleMark->attributes = array('member_id'=>Yii::app()->user->id,'article_id'=>$model->id,'create_time'=>time(),'type'=>ArticleMark::TYPE_UP);
			$articleMark->save();
		}else{
			$articleMark->delete = intval(!($articleMark->delete));
			$articleMark->create_time = time();
		}
		$articleMark->save();
		$html = $articleMark->delete == ArticleMark::UP_DELETE_NO ? '<a href="'.$this->createUrl('member/view',array('id'=>Yii::app()->user->id)).'" id="'.Yii::app()->user->id.'">我</a>' : 'down';
		$this->returnData(1,$html);
	}
	
	/**
	 * 评论文章
	 */
	public function actionComment()
	{
		$model = $this->loadModel(Util::decode(Yii::app()->request->getParam('id')));
		if($model->publish == Article::PUBLISH_NO)
			throw new CHttpException(404,'The requested page does not exist.');
		$content = trim($_POST['comment']);
		if(empty($content)) $this->returnData(0,'评论为空');
		$comment = new ArticleComment();
		$comment->attributes = array('member_id'=>Yii::app()->user->id,'article_id'=>$model->id,'create_time'=>time(),'comment'=>$content);
		if($comment->save())
		{
			$html = $this->renderPartial('_comment',array('comment'=>$comment),true);
			$this->returnData(1,$html);
		}
		else 
		{
			$this->returnData(0,'评论过长了！');
		}
	}
	
	/**
	 * 文章列表(查看某人的文章列表,必须是已经发布的)
	 */
	public function actionList()
	{
		$mid = Util::decode(Yii::app()->request->getParam('mid'));
		if($mid == Yii::app()->user->id)
			$this->actionMypublish();
		else
		{
			$data = Article::model()->published()->findAll(array('condition'=>'create_mid='.$mid));
			$this->render('list',array('articles'=>$data));
		}
	}
	
	/**
	 * 我创建的(发布的和未发布的)
	 */
	public function actionMypublish()
	{
		$articles = Article::model()->owner()->findAll();
		$this->render('mypublish',array('articles'=>$articles));
	}
	
	/**
	 * 我收藏的列表
	 */
	public function actionMymarked()
	{
		$articles = Article::model()->findAll(array('join'=>'right join article_mark as am on am.member_id='.Yii::app()->user->id.' and am.article_id=ar.id and am.type='.ArticleMark::TYPE_MARK));
		$this->render('mymarked',array('articles'=>$articles));
	}
	
	/**
	 * 我评论的文章列表
	 */
	public function actionMycomment()
	{
		$articles = Article::model()->findAll(array('distinct'=>true,'join'=>'right join article_comment as ac on ac.member_id='.Yii::app()->user->id.' and ac.article_id=ar.id'));
		$this->render('mycomment',array('articles'=>$articles));
	}
	
	/**
	 * 统计文章分享的次数
	 */
	public function actionCountShare($id)
	{
		$model = $this->loadModel(Util::decode($id));
		$model->updateByPk($model->id,array('share_counts'=>$model->share_counts+1));
	}
	/**
	 * 加载更多评论
	 */
	public function actionLoadMoreComment()
	{
	    $id = Util::decode(Yii::app()->request->getParam('id'));
	    $comments = ArticleComment::model()->with('member')->findAll(array('condition'=>'article_id='.$id,'limit'=>10000,'offset'=>2));
	    if(empty($comments)){
	        $this->returnData(0,'暂无评论');
	    }
	    $html = '';
	    foreach ($comments as $comment){
	        $html .= $this->renderPartial('_comment',array('comment'=>$comment),true);
	    }
	    
	    $this->returnData(1,$html);
	   
	}
	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Article::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
}