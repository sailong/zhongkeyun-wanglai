<?php

/**
 * 整合第三方数据
 * @author zhoujianjun
 *
 */
class MigrateController extends CController
{
	
	/**
	 * 存储错误信息
	 * @var unknown
	 */
	private $_error = NULL;
	
	
	private $_key = '&%wang&&*lai';
	
	/**
	 * 整合同道会活动
	 * 
	 */
	
	
	public function actionMigrateTong()
	{
		set_time_limit(0);
		$host = 'http://www.tongdaohui.com';
		$token = 'd2FuZ2xhaTp3YW5nbGFpMTIzNDU2';
		$source = Activity::SOURCE_TONGDAOHUI;
		$i = 1;
		while ($i)
		{
			$url = $host . '/wanglai/activityList/'.$token.'/'.$i;
			$data = json_decode(Yii::app()->curl->get($url),true);
			if(!empty($data))
			{
				foreach ($data as $value)
				{
					$id = $value['Id'];
					$model = ActivityMigrate::model()->findByAttributes(array('object_id'=>$id,'source'=>$source));
					if(empty($model))
					{
						$url = $host . '/wanglai/activityDetail/'.$token.'/'.$id;
						$detail = json_decode(Yii::app()->curl->get($url),true);
						if($value['Etime'] > time())
						{
							// 入库未过期的活动
							$province = District::model()->findByAttributes(array('name'=>$value['City']));
							if(!empty($province))
							{
								if($province->level == 1)
								{
									$province_id = $province->id;
									$area = 0;
								}elseif($province->level == 2)
								{
									$province_id = $province->parent_id;
									$area = $province->id;
								}else{
									Yii::log('City NOT exist!' . print_r($value,true));
									continue;
								}
								$content = preg_replace('/(.*)<img.*src=\"(.*)\".*(width=\"\d+\").*(height=\"\d+\").*\/>(.*)/', '\\1<img src="'.$host.'\\2"\>\\5',  $detail['Description']);
								$activity = new Activity();
								$attributes = array(
										'create_mid' => 0,
										'source' => $source,
										'title' => $value['Title'],
										'province' => $province_id,
										'area' => $area,
										'district' => $value['Place'],
										'begin_time' => $value['Stime'],
										'end_time' => $value['Etime'],
										'cost' => intval($value['Cost']),
										'sponer' => $value['organizer'],
										'state' => Activity::VERIFY_STATE_WITHOUT,
										'verify' => Activity::APPLY_VIRIFY_WITHOUT,
										'create_time' => time(),
										'videoUrl' => $value['VideoUrl'],
										'detail' => $content
								);
								$activity->setAttributes($attributes,false);
								if($activity->save())
								{
									$log = new ActivityMigrate();
									$log->attributes = array(
										'source' => $source,
										'activity_id' =>$activity->id,
										'object_id' => $value['Id'],
										'extra' => serialize(array('apply_field' => $detail['Apply_Field'],'process'=>$detail['Process'])),
										'create_time' => time()	
									);
									if(!$log->save())
									{
										Yii::log('ActivityMigrate Save error!' . print_r($log->getErrors(),true). print_r($value,true));
									}
								}else{
									Yii::log('Activity Save errror!' . print_r($activity->getErrors(),true) . print_r($value,true));
								}
							}else{
								Yii::log('City NOt exist' . print_r($value,true));
							}
						}else{
							Yii::log('expire time object_id is:' . $id );
						}
					}
				}
			}else{
				break;
			}
			$i++;
		}
	}
	
	/**
	 * 通道会报名测试
	 */
	public function actionApply()
	{
		$data = array(
				'r_id' => 977,
				'name' => '周健君',
				'mobile' => '18701533591',
				'company' => '往来',
				'position' => '工程师',
				'sex' => '女',
				'remark' => '报名测试',
				'email' => 'zhoujianjun307@163.com'
		);
		$data['json'] = json_encode($data);
		
		$url = 'http://www.tongdaohui.com/wanglai/doApplyRoadshow/d2FuZ2xhaTp3YW5nbGFpMTIzNDU2';
		
		//$url = 'http://www.wl.com/api/migrate/test';
		$result = Yii::app()->curl->post($url,$data);
		$result = json_decode($result);
		
		if(!empty($result))
		{
			print_r($result);die;
		}
		
	}
	
	public function actionTest()
	{
		$data = array(
			'activity_id' => 977,
			'name' => '通道会',
			'mobile' => '17912345634',
			'email' => 'zhoujiaun@mail.com',
			'position' => '',
			'company' => '',
			'source'=>2,
		);
		$str = http_build_query(ksort($data),'&');
		$data['signature'] = md5($str.'%wang$*&lai@&#');
		$url = 'http://www.wanglai.cc/api/apply';
		
		$result = Yii::app()->curl->post($url,$data);
		$result = json_decode($result,true);
		print_r($result);die;
	}
	
	/**
	 * 取消报名测试
	 */
	public function actionCancel()
	{
		$url = "http://www.tongdaohui.com/index.php?app=home&mod=wanglai&act=deleteApplyRoadshow&token=d2FuZ2xhaTp3YW5nbGFpMTIzNDU2&r_id=977&mobile=18701533591";
		
		//$url = 'http://www.tongdaohui.com/wanglai/deleteApplyRoadshow?toke=d2FuZ2xhaTp3YW5nbGFpMTIzNDU2&r_id=977&mobile=18701533591';
		$result = Yii::app()->curl->get($url);
		
		print_r(json_decode($result,true));
		
	}
	
}