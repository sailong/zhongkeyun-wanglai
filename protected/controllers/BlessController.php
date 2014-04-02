<?php
/**
 * 贺卡控制器
 * @author JZLJS00
 *
 */
class BlessController extends FrontController
{
	
	/**
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl - view',
		);
	}
	
	public function accessRules()
	{
		return array(
			array('deny',
				'actions' => array('copy','first','second','index'),
				'users'   => array('?'),
				'deniedCallback' => array($this,'deny')
			)
		);
	}
	
	
	public function deny()
	{
		$refer = Yii::app()->request->getRequestUri();
		Util::addCookie('refer', $refer);
		$this->goAuth();
	}
	
	public function clearCookie()
	{
		Util::removeCookie('step');
		Util::removeCookie('cid');
		Util::removeCookie('content');
		Util::removeCookie('subCid');
		
	}
	/**
	 * (non-PHPdoc)
	 * @see CController::beforeAction()
	 */
	public function beforeAction($action)
	{
		parent::beforeAction($action);
		$actionId = $action->getId();
		$step = Util::getCookie('step');
		switch ($actionId){
			case 'first':
				if($step != 'first')
				{
					$this->clearCookie();
					$this->redirect($this->createUrl('bless/index'));
				}	
			break;
			case 'second':
				if($step != 'second')
				{
					$this->clearCookie();
					$this->redirect($this->createUrl('bless/index'));
				}
				break;
			case 'generate':
				if($step != 'generate')
				{
					$this->clearCookie();
					$this->redirect($this->createUrl('bless/index'));
				}
				break;
		}
		
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CController::behaviors()
	 */
	public function behaviors()
	{
		return array(
			'manage' => 'application.components.behaviors.CardBehavior'
		);
	}
	
	/**
	 * 贺卡首页,显示贺卡当前分类
	 */
	public function actionIndex()
	{
		$category = Card::model()->category;
		unset($category[1],$category[2],$category[3],$category[6],$category[7]);
		Util::addCookie('step', 'first');
		$this->onBeforeIndex(new CEvent());
		$this->render('index',array('category'=>$category));
	}
	
	public function onBeforeIndex($event)
	{
		$this->raiseEvent('onBeforeIndex', $event);
	}
	
	/**
	 * 挑选类型
	 */
	public function actionFirst()
	{
		$id = intval(Yii::app()->request->getParam('id',1));
		$category = Card::model()->getCategory($id);
		$children = !empty($category) ? $category['children'] : array();
		if(empty($children))
		{
			$this->clearCookie();
			$this->redirect($this->createUrl('bless/index'));
		}
		Util::addCookie('step', 'second');
		Util::addCookie('cid', $id);
		$this->render('first',array('category'=>$children));
	}
	
	/**
	 * 选择祝福语
	 */
	public function actionSecond()
	{
		$subCid = intval(Yii::app()->request->getParam('id',1));
		if(isset($_POST['content']))
		{
			$content = trim($_POST['content']);
			if(empty($content))
			{
				$this->showMessage(0, '请选择祝福语');
			}
			Util::addCookie('content', $content);
			Util::addCookie('step', 'generate');
			$this->redirect($this->createUrl('bless/generate'));
			$this->render('generate');
			Yii::app()->end();
		}
		Util::addCookie('subCid', $subCid);
		$cid = Util::getCookie('cid');
		$data = Card::model()->getCategory($cid);
		if(empty($data))
		{
			$this->clearCookie();
			$this->redirect($this->createUrl('bless/index'));
		}
		$this->render('second',array('data'=>$data));
	}
	
	/**
	 * 生成贺卡
	 */
	public function actionGenerate()
	{
		$uid = Yii::app()->user->id;
		$username = '';
		if(!empty($uid))
		{
			$user = Member::model()->findByPk($uid);
			!empty($user) && $username = $user['name'];
		}
		
		if(isset($_POST['from'],$_POST['to']))
		{
			$from = trim($_POST['from']);
			$to = trim($_POST['to']);
			if(empty($username) && empty($from))
			{
				$this->showMessage(0, '需要知道您是谁啊,请填写寄件人');
			}
			$cid = Util::getCookie('cid');
			$subCid = Util::getCookie('subCid');
			$category = Card::model()->getCategory($cid);
			if(empty($category))
			{
				$this->clearCookie();
				$this->redirect($this->createUrl('bless/index'));
			}
			$img = $category['children'][$subCid]['img'];
			
			$data = array(
				'from_mid' => $uid,'from_user' => $from,'to_user' => $to,'content' => Util::getCookie('content'),
				'cid' => Util::getCookie('cid'),'send_time' => time(),'share_counts'=>0,'click_counts'=>0,
				'sub_cid' => $subCid
			);
			$model = new Card();
			$model->attributes = $data;
			if($model->save())
			{
				$this->clearCookie();
				$this->redirect($this->createUrl('bless/view',array('id'=>$model->id,'from'=>'self')));
				Yii::app()->end();
			}else{
				print_r($model->getErrors());die;
			}
		}
		$this->render('generate',array('username'=>$username));
	}
	
	public function actionView()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		$model = $this->loadModel($id);
		$from = Yii::app()->request->getParam('from','');
		$photo = '';
		if(!empty($model->from_mid))
		{
			$member = Member::model()->with('extend')->findByPk($model->from_mid);
			$photo = Member::model()->getPhoto($member,'s',false);
		}
		$category = Card::model()->getCategory();
		$img = $category[$model->cid]['children'][$model->sub_cid]['img'];
		$shareImg = !empty($photo) ? $photo : Yii::app()->request->getHostInfo() . $img;
		$this->render('view',array('model'=>$model,'img'=>$img,'from'=>$from,'shareImg'=>$shareImg,'photo'=>$photo));
	}
	
	
	public function actionCountShare()
	{
		if(Yii::app()->request->getIsAjaxRequest())
		{
			$id = intval(Yii::app()->request->getParam('id'));
			$model = $this->loadModel($id);
			$model->share_counts += 1;
			$model->save();
		}
	
	}
	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Card::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * 根据一张卡片生成另一张卡片
	 */
	public function actionCopy()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		$model = $this->loadModel($id);
		$uid = Yii::app()->user->id;
		$username = '';
		if(!empty($uid))
		{
			$user = Member::model()->findByPk($uid);
			!empty($user) && $username = $user['name'];
		}
		if(isset($_POST['from'],$_POST['to']))
		{
			$hash = Yii::app()->request->getParam('hash');
			if(empty($hash) || $hash != Util::getCookie('hash'))
			{
				$this->showMessage(0, 'Error');
			}
			
			$from = trim($_POST['from']);
			$to = trim($_POST['to']);
			if(empty($username) && empty($from))
			{
				$this->showMessage(0, '需要知道您是谁啊,请填写寄件人');
			}
			
			$data = array(
				'from_mid' => $uid,'from_user' => $from,'to_user' => $to,'content' => $model->content,
				'cid' => $model->cid,'send_time' => time(),'share_counts'=>0,'click_counts'=>0,
				'sub_cid' => $model->sub_cid
			);
			$new = new Card();
			$new->attributes = $data;
			if($new->save())
			{
				$this->clearCookie();
				$this->redirect($this->createUrl('bless/view',array('id'=>$new->id,'from'=>'self')));
				Yii::app()->end();
			}else{
				print_r($model->getErrors());die;
			}
		}
		Util::addCookie('hash', time());
		$this->render('copy',array('model'=>$model,'username'=>$username));
		
	}
	
	
	
}
