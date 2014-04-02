<?php
/**
 * 签名模块
 * @author changsailong
 * @date 2013-3-24
 *
 */
class SignController extends FrontController
{
	
	public $defaultAction = 'index';
	
	
	/**
	 *
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
		    'accessControl - view,list',
			'ajaxOnly + comment,countShare,sign,collect'
		);
	}
	
	public function accessRules()
	{
		return array(
			 array(
				'deny',
				'actions' => array('index','comment','sign','preview','collect','create','mypublish','mycomment','mymarked','update','publish'),
				'users'   => array('?'),
				'deniedCallback' => array($this,'deny')
			)
		);
	}
	
	public function deny()
	{
		$actionId = $this->getAction()->getId();
		$refer = in_array($actionId, array('sign','comment','collect','create')) ? Yii::app()->request->getUrlReferrer() : Yii::app()->request->getRequestUri();
		Util::addCookie('refer', $refer);
		$this->goAuth();
	}
	
	public function behaviors()
	{
		return array(
			'sign' => 'application.components.behaviors.SignBehavior'
		);
	}
	
	/**
	 * 签名模块入口页
	 */
	public function actionIndex()
	{
	    $uid = Yii::app()->user->id;
	    $totalCreate = SignActivity::model()->getCreateTotal($uid);
	    
	    $totalCollect = Signature::model()->getTotal($uid);
	    $this->render('index',compact('totalCreate','totalCollect'));
	}
	
	/**
	 * 发布签名
	 */
	public function actionCreate()
	{
	    $model = new SignActivity();
		if(isset($_POST['Sign']))
		{
	        $params = $_POST['Sign'];
	        if(empty($params['title']) || empty($params['content']))
	        {
	            Yii::app()->user->setFlash('sign','请填写完整信息');
	        }else{
	            $content = trim($params['content']);
	        
	            $model->attributes = array('title'=>trim($params['title']),'create_mid'=>Yii::app()->user->id,'create_time'=>time(),'publish'=>SignActivity::PUBLISH_NO,'content'=>$content);
	            if($model->save())
	            {
	                $url = $this->createUrl('preview', array('id'=>$model->id));
	                $this->redirect($url);
	            }else{
	                $error = array_values($model->getErrors());
	                Yii::app()->user->setFlash('sign',$error[0][0]);
	            }
	        }
		    
		}else{
			$token = $this->createToken($this);
		}
		
	    $this->render('create',array('model'=>$model,'token'=>$token));
	}
	
	/**
	 * 预览签名
	 * @param unknown $id
	 */
	public function actionPreview($id)
	{
		$model = $this->loadModel($id);
		$this->render('preview',array('model'=>$model));
		Yii::app()->end();
	}
	
	/**
	 * 发布标签名(创建-预览-发布)
	 */
	public function actionPublish($id)
	{
		$model = $this->loadModel($id);
		$model->updateByPk($model->id, array('publish'=>SignActivity::PUBLISH_YES,'publish_time'=>time()));
		$this->redirect(array('view','id'=>Util::encode($id)));
	}
	
	/**
	 * 编辑签名
	 * @param int $id
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		if(isset($_POST['Sign']))
		{
			$params = $_POST['Sign'];
			if(empty($params['title']) || empty($params['content']))
			{
				Yii::app()->user->setFlash('sign','请填写完整信息');
			}else{
				$content = trim($params['content']);
				$model->title = trim($params['title']);
				$model->content = $content;
				$model->update_time = time();
				$model->save();
				$url = $this->createUrl('view', array('id'=>Util::encode($model->id)));
				$this->redirect($url);
			}
		}
		$this->render('create', array('model'=>$model));
	}
	
	/**
	 * 签名详情
	 * 
	 * @param int $id
	 */
	public function actionView($id)
	{
		$model = $this->loadModel(Util::decode($id));
		if($model->publish == SignActivity::PUBLISH_NO && $model->create_mid == Yii::app()->user->id)
		{
			// 未发布的签名,创建人继续发布
			$url = $this->createUrl('preview',array('id'=>$model->id));
			$this->redirect($url);
			Yii::app()->end();
		}elseif($model->publish == SignActivity::PUBLISH_YES)
		{
			// 已发布的签名
			$comments = SignComment::model()->with('member')->findAll(array('condition'=>'sign_id='.$model->id));
			$sql = "SELECT a.create_time, a.type, a.status, a.object_id, b.id, b.name from `signature` AS a LEFT JOIN member AS b ON a.member_id = b.id having a.object_id={$model->id} and a.status='".Signature::SIGN_STATUS_NORMAL."' and a.type='".Signature::SIGN_TYPE_FLAG."'";
			$upers = Yii::app()->db->createCommand($sql)->queryAll();
			$this->onAfterView(new CEvent($this,array('model'=>$model)));
			$this->render('view',array('model'=>$model,'comments'=>$comments,'upers'=>$upers));
		}else{
			$this->showMessage(0, '签名不存在');
		}
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
	 * 收藏签名
	 */
	public function actionCollect()
	{
		$model = $this->loadModel(Util::decode(Yii::app()->request->getParam('id')));
		if($model->publish == SignActivity::PUBLISH_NO)
			throw new CHttpException(404,'The requested page does not exist.');
		if(Signature::model()->mark($model->id, Yii::app()->user->id,Signature::SIGN_TYPE_COLLECT))
			$this->returnData(1,'已收藏');
		else 
			$this->returnData(0, '您已收藏过了！');
	}
	
	
	/**
	 * 签名
	 */
	public function actionSign()
	{
	    $model = $this->loadModel(Util::decode(Yii::app()->request->getParam('id')));
	    if($model->publish == SignActivity::PUBLISH_NO)
	    {
	        throw new CHttpException(404,'The requested page does not exist.');
	    }
	    $signtruemodel = Signature::model()->mark($model->id, Yii::app()->user->id,Signature::SIGN_TYPE_FLAG);
	    if($signtruemodel)
	    {
	        $html = $this->renderPartial('_sign',array('model'=>$signtruemodel),true);
	        $this->returnData(1,$html);
	    }
	    else
	    {
	        $this->returnData(0, '您已签名过了！');
	    }
	}
	
	
	/**
	 * 评论签名
	 */
	public function actionComment()
	{
		$model = $this->loadModel(Util::decode(Yii::app()->request->getParam('id')));
		if($model->publish == SignActivity::PUBLISH_NO)
		{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		$content = trim($_POST['comment']);
		if(empty($content)) 
		{
		    $this->returnData(0,'评论为空');
		}
		$comment = new SignComment();
		$comment->attributes = array('member_id'=>Yii::app()->user->id,'sign_id'=>$model->id,'create_time'=>time(),'comment'=>$content);
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
	 * 签名列表(查看某人的签名列表,必须是已经发布的)
	 */
	public function actionList()
	{
		$mid = Util::decode(Yii::app()->request->getParam('mid'));
		if(empty($mid)){
		    throw new CHttpException(404,'The requested page does not exist.');
		}
		if($mid == Yii::app()->user->id)
			$this->actionMypublish();
		else
		{
			$data = SignActivity::model()->published()->findAll(array('condition'=>'create_mid='.$mid));
			$this->render('list',array('signs'=>$data));
		}
	}
	
	/**
	 * 我创建的(发布的和未发布的)
	 */
	public function actionMypublish()
	{
		$signs = SignActivity::model()->owner()->findAll();
		$this->render('mypublish',array('signs'=>$signs));
	}
	
	/**
	 * 我收藏的列表
	 */
	public function actionMymarked()
	{
		$signs = SignActivity::model()->findAll(array('join'=>'right join signature as am on am.object_id=ar.id and am.member_id='.Yii::app()->user->id.' and am.type='.Signature::SIGN_TYPE_COLLECT.' and am.status='.Signature::SIGN_STATUS_NORMAL));
		$this->render('mymarked',array('signs'=>$signs));
	}
	
	/**
	 * 我评论的签名评论列表
	 */
	public function actionMycomment()
	{
		$signs = SignActivity::model()->findAll(array('distinct'=>true,'join'=>'right join sign_comment as ac on ac.sign_id=ar.id and ac.member_id='.Yii::app()->user->id));
		$this->render('mycomment',array('signs'=>$signs));
	}
	
	/**
	 * 统计签名分享的次数
	 */
	public function actionCountShare()
	{
	    $id = Util::decode(Yii::app()->request->getParam('id'));
		$model = $this->loadModel($id);
		$model->updateByPk($model->id,array('share_counts'=>$model->share_counts+1));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=SignActivity::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * 检测session form submit
	 */
	public function actionCheckFormSubmit()
	{
	   /*  $flag = $this->autoCheckFormSubmit();
	     */
	    $token = Yii::app()->request->getParam('token');
	    $flag = $this->checkToken($token);
	    if($flag){
	        $this->returnData(1,$flag);
	    }
	    $this->returnData(0,$flag);
	}
	
}