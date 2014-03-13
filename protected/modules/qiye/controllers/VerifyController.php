<?php
/**
 * 活动及群报名审核
 * @author JZLJS00
 *
 */
class VerifyController extends QiyeController
{
	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/qiye/column1';
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'ajaxOnly + passActivity,refuseActivity,passContacts,refuseContacts'
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','passActivity','refuseActivity','passContacts','refuseContacts'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * 微群及活动审核列表
	 */
	public function actionIndex()
	{
		$data = array();
		$activityApplyItem = $this->getActivityApplyData();
		if(!empty($activityApplyItem))
		{
			foreach ($activityApplyItem as $value)
			{
				$data[] = array('apply_id'=>$value['apply_id'],'member_id'=>$value['mid'], 'username'=>$value['name'], 'object_id'=>$value['activity_id'],'title'=>$value['title'],'type'=>'activity','apply_time'=>$value['create_time']);
			}
		}
		$contactsApplyItem = $this->getContactsApplyData();
		if(!empty($contactsApplyItem))
		{
			foreach ($contactsApplyItem as $value)
			{
				$data[] = array('apply_id'=>$value['apply_id'],'member_id'=>$value['mid'], 'username'=>$value['name'], 'object_id'=>$value['contacts_id'], 'title'=>$value['title'],'type'=>'contacts','apply_time'=>$value['apply_time']);
			}				
		}
		$this->render('index',array('data'=>$data));
	}
	
	/**
	 * 活动审核通过
	 */
	public function actionPassActivity($id)
	{
		$id = intval(Util::decode($id));
		$result = array();
		if(Bridge::passActivityApply($id))
		{
			$result = array('status'=>1,'msg'=>'<span class="label label-success">已通过</span>');
		}
		$this->ajaxReture($result);
	}
	
	/**
	 * 活动审核拒绝
	 */
	public function actionRefuseActivity($id)
	{
		$id = intval(Util::decode($id));
		$result = array();
		if(Bridge::refuseActivityApply($id))
		{
			$result = array('status'=>1,'msg'=>'<span class="label label-important">已拒绝</span>');
		}
		$this->ajaxReture($result);
	}
	
	/**
	 * 微群审核通过
	 */
	public function actionPassContacts($id)
	{
		$id = intval(Util::decode($id));
		$result = array();
		if(Bridge::passContactsApply($id))
		{
			$result = array('status'=>1,'msg'=>'<span class="label label-success">已通过</span>');
		}
		$this->ajaxReture($result);
	}
	
	/**
	 * 微群审核拒绝
	 */
	public function actionRefuseContacts($id)
	{
		$id = intval(Util::decode($id));
		$result = array();
		if(Bridge::refuseContactsApply($id))
		{
			$result = array('status'=>1,'msg'=>'<span class="label label-important">已拒绝</span>');
		}
		$this->ajaxReture($result);
	}
	
	/**
	 * 操作返回
	 * @param unknown_type $data
	 */
	private function ajaxReture($data=array())
	{
		if(empty($data))
			$data = array('status'=>0,'msg'=>'失败');
		echo json_encode($data);
	}
	/**
	 * 活动的申请
	 */
	private function getActivityApplyData()
	{
		$sql = "SELECT m.id as mid,m.name,amr.create_time,amr.id as apply_id,a.id as activity_id,a.title FROM member as m,activity_member_rel as amr,activity as a WHERE a.create_mid=".Yii::app()->user->mid
				." AND a.id=amr.activity_id AND amr.state=".ActivityMember::VERIFY_STATE_APPLY." AND amr.member_id=m.id";
		return Yii::app()->db->createCommand($sql)->queryAll();
	}
	
	/**
	 * 微群的申请
	 */
	private function getContactsApplyData()
	{
		$sql = "SELECT m.id as mid,m.name,cmr.apply_time,cmr.id as apply_id,c.id as contacts_id,c.title FROM member as m,contacts_member_rel as cmr,contacts as c WHERE c.create_mid=".Yii::app()->user->mid
				." AND c.id=cmr.contacts_id AND cmr.state=".ContactsMember::STATE_APPLY." AND cmr.member_id=m.id";
		return Yii::app()->db->createCommand($sql)->queryAll();
	}
	
}