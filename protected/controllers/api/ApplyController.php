<?php
/**
 * 第三方接口,活动报名
 * @author zhoujianjun
 *
 */
class ApplyController extends CController
{
	
	
	public $defaultAction = 'apply';
	
	
	/**
	 * 合作方及对应的key
	 * @var unknown
	 */
	private $keys = array(
		Activity::SOURCE_TONGDAOHUI => '%wang$*&lai@&#'
	);
	

	/**
	 * 第三方报名活动同步('activity_id','name','mobile','email(可选)','position(可选)','company(可选)')
	 */
	public function actionApply()
	{
		$request = Yii::app()->request;
		$result = array('status'=>0,'msg'=>'报名成功');
		if($request->getIsPostRequest())
		{
			$activity_id = $request->getPost('activity_id','');
			$name = $request->getPost('name');
			$mobile = $request->getPost('mobile');
			$signature = $request->getPost('signature');
			$source = intval($request->getPost('source'));
			if(empty($activity_id) || empty($mobile) || empty($name) || empty($signature) || !key_exists($source,$this->keys))
				$result = array('status' => 1, 'msg' => '缺少必要字段');
			else
			{
				$position = $request->getPost('position');
				$company = $request->getPost('company');
				$email = $request->getPost('email');
				$data = array(
					'activity_id'=>$activity_id,
					'mobile' => $mobile,
					'name'  => $name,
					'email' => $email,
					'source' => $source,
					'position' => $position,
					'company' => $company
				);
				if(md5(http_build_query(ksort($data),'&').$this->keys[$source])!= $signature)
					$result = array('status' => 2, 'msg' => '签名错误');
				else
				{
					$activity = ActivityMigrate::model()->with('activity')->findByAttributes(array('object_id'=>$activity_id,'source'=>$source));
					if(empty($activity->activity))
						$result = array('status' => 3, 'msg' => '活动不存在');
					else 
					{
						$member = Member::model()->findByAttributes(array('mobile'=>$mobile,'from'=>1));
						if(empty($member))
						{
							$member = new Member();
							$member->mobile = $mobile;
							$member->name = $name;
							$member->weixin_openid = 'temp_wanglai_'.md5(uniqid().mt_rand(1,999999));
							$member->created_at = time();
							$member->initial = Util::getFirstLetter($name);
							$member->wanglai_number = Number::model()->getNumber();
							if(!$member->save())
							{
								Yii::log(print_r($member->getErrors(),true));
							}
						}
						if(!empty($member))
						{
							$apply = Activity::model()->apply($activity->activity_id,$member->id);
							if($apply['status'] != 1)
							{
								Yii::log(file_get_contents("php://input") . '报名失败' . $result['msg']);
							}
						}
					}
				}
			}
		}else {
			$result = array('status'=>4,'msg'=>'错误请求');
		}
		echo CJSON::encode($result);
		Yii::app()->end();
	}
	
	
}