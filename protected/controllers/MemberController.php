<?php
/**
 * 用户相关控制器
 * @author JZLJS00
 *
 */
class MemberController extends FrontController
{
	
	/**
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl - view',
			'ajaxOnly + countShare'
		);
	}
	
	public function accessRules()
	{
		return array(
			array('deny',
				'actions' => array('follow','getQRcode','getQRcodeLocal','index','update'),
				'users'   => array('?'),
				'deniedCallback' => array($this,'deny')
			)
		);
		
	}
	
	
	public function deny()
	{
		$actionId = $this->getAction()->getId();
		$refer = $actionId == 'follow' ? Yii::app()->request->getUrlReferrer() : Yii::app()->request->getRequestUri();
		Util::addCookie('refer', $refer);
		$this->goAuth();
	}

	public function behaviors()
	{
		return array(
			'member' => 'application.components.behaviors.MemberBehavior'
		);
	}
	/**
	 * 我的名片页(微信菜单页我的名片)
	 */
	public function actionIndex()
	{
		$url = $this->createUrl('member/view',array('id'=>$this->_member->id));
		$this->redirect($url);
	}
	
	/**
	 * 修改名片
	 */
	public function actionUpdate()
	{
		$model = $this->_member;
		if(isset($_POST['member']))
		{
			$param = $_POST['member'];
			!empty($param['birthday']) && $param['birthday'] = strtotime($param['birthday']);
			$param['show_item'] = json_encode($param['show_item']);
			if(isset($_FILES['upphoto']))
			{
				$image = Image::upload('upphoto','avatar',array('s'=>'200,200'),0);
				if($image && !empty($image['filePath'])) $param['avatar'] = $image['filePath'];
			}
			$model->attributes = $param;
			$model->updated_at = time();
			if($model->save())
			{
				$this->redirect($this->createShareUrl($model->id));
			}else{
				$error = array_values($model->getErrors());
				$this->showMessage(0, $error[0][0]);
			}
		}
		if(empty($model->show_item))
		{
			$model->show_item = json_encode(array('mobile'=>1,'demand'=>1,'supply'=>1));
		}
		$this->render('update',compact('model'));
	}
	
	/**
	 * 获取名片二维码(微信菜单页点击)
	 */
	public function actionGetQRcode()
	{
		$this->render('qrcode');
	}
	
	/**
	 * 本地获取二维码
	 */
	public function actionGetQRcodeLocal()
	{
		$model = $this->_member;
		$uid = $this->_member->id;
		$url = Yii::app()->request->getHostInfo() . '/' . $model->wanglai_number;
		$QR = Yii::app()->getBasePath() . '/../attachments/qrcode'.($uid % 10).'/qrcode_'.$uid.'.png';
		include Yii::getPathOfAlias('ext').'/QRcode/phpqrcode.php';
		$position = $model->position;
		if(preg_match('/^\w.*$/', $position))
		{
			$position = '：' . $position;
		}
		
		$conttent = <<<CODE
BEGIN:VCARD
VERSION:3.0
ADR;WORK:{$model->address}
EMAIL:{$model->email}
URL:{$url}
TEL:{$model->mobile}
ORG:{$model->company}
TITLE:{$position}  
N:{$model->name}
END:VCARD
CODE;
		QRcode::png ($conttent,$QR,'H',4,4);
		$logo = Yii::app()->getBasePath() . '/../attachments/rlogo.png';
		$QR = imagecreatefromstring(file_get_contents($QR));
		$logo = imagecreatefromstring(file_get_contents($logo));
		$QR_width = imagesx($QR);
		$QR_height = imagesy($QR);
		$logo_width = imagesx($logo);
		$logo_height = imagesy($logo);
		$logo_qr_width = $QR_width / 5;
		$scale = $logo_width / $logo_qr_width;
		$logo_qr_height = $logo_height / $scale;
		$from_width = ($QR_width - $logo_qr_width) / 2;
		imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		header('Content-type:image/png');
		imagepng($QR);
	}
	
	/**
	 * 关注及取消关注
	 */
	public function actionFollow()
	{
		if(Yii::app()->request->getIsAjaxRequest())
		{
			$mid = Yii::app()->request->getParam('mid');
			$from = Yii::app()->request->getParam('from');
			if(intval($from) == Yii::app()->user->id && $from != $mid)
			{
				$fromModel = Member::model()->findByPk($from);
				$model = Member::model()->findByPk($mid);
				if($model != null && $fromModel != null)
				{
					$follow = Follow::model()->findByAttributes(array('mid'=>$from,'follow_mid'=>$mid));
					if($follow == null)
					{
						$follow = new Follow();
						$follow->mid = $from;
						$follow->follow_mid = $mid;
						$follow->is_deleted = Follow::FOLLOW_IN;
					}else {
						$follow->is_deleted =  intval(!($follow->is_deleted));
					}
					$follow->is_new = $follow->is_deleted == Follow::FOLLOW_IN ? Follow::NEW_YES : Follow::NEW_NO;
					$follow->follow_at = time();
					$follow->save();
					if($follow->is_deleted == Follow::FOLLOW_IN)
					{
						$this->onAfterFollow(new CEvent($this,array('model'=>$model,'fromModel'=>$fromModel)));
					}
					$message = $follow->is_deleted == Follow::FOLLOW_IN ? '取消关注' : '+ 关注';
					$operator = $follow->is_deleted == Follow::FOLLOW_IN ? '+' : '-';
					$this->returnData(1,$message,array('operator'=>$operator));
				}
			}
			$this->returnData(0,'错误请求');
		}
	}
	
	public function onAfterFollow($event)
	{
		$this->raiseEvent('onAfterFollow', $event);
	}
	
	/**
	 * 创建名片,主要记录refer是否为某个用户的名片主页,相应增加小伙伴
	 */
	public function actionCreate()
	{
		$this->redirect($this->createUrl('member/index'));
	}
	
	/**
	 * 登录,由于登录区别对待,此处主要用于记录相关状态来源等信息
	 */
	public function actionLogin()
	{
		$this->redirect($this->createUrl('member/index'));
	}
	
	/**
	 * 查看名片
	 */
	public function actionView()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		$model = $this->loadModel($id);
		if(!empty($model->company_url) && substr($model->company_url, 0, 7) != 'http://')
		{
			$model->company_url =  'http://' . $model->company_url;
		}
		$model->show_item = !empty($model->show_item) ? json_decode($model->show_item,true) : array();
		if(!empty($model->show_item))
		{
			foreach ($model->show_item as $key => $value)
			{
				if($value == Member::ITEM_HIDDEN) $model->$key = '';
			}
		}
		$this->onAfterView(new CEvent($this,array('model'=>$model)));
		$view = $model->is_qiye == Member::QIYE_YES ? 'qiye_view' : 'view';
		$this->render($view,compact('model'));
	}
	
	public function onAfterView($event)
	{
		$this->raiseEvent('onAfterView', $event);
	}
	
	/**
	 * 名片分享后,执行回调函数增加分享次数
	 */
	public function actionCountShare()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		$model = Member::model()->findByPk($id);
		if(!empty($model))
		{
			$model->updateByPk($model->id,array('share_counts'=>$model->share_counts+1));
		}
	}
	
	/**
	 * 通过网络号查看名片
	 */
	public function actionNumber()
	{
		$number = trim(Yii::app()->request->getParam('number'));
		if(is_numeric($number))
		{
			$model = Member::model()->findByAttributes(array('wanglai_number'=>$number));
			if(!empty($model))
			{
				$this->redirect($this->createUrl('member/view',array('id'=>$model->id)));
			}
		}
		$this->showMessage(0, '名片不存在');
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Member::model()->with(array('extend','stat'))->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if($model->from != 1)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
}