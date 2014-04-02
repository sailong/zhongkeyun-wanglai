<?php
/**
 * 活动模型
 * @author zjj
 *
 */
class Activity extends CActiveRecord
{
	
	/**
	 * 活动报名属性1公开活动部需要审核2需要审核后才能参加
	 * @var int
	 */
	const APPLY_VIRIFY_WITHOUT = 1;
	const APPLY_VIRIFY_WITH    = 2;
	
	/**
	 * 活动是否需要审核状态,0为VIP不需要审核1审核中2通过3拒绝4已删除
	 * @var unknown_type
	 */
	const VERIFY_STATE_WITHOUT = 0;
	const VERIFY_STATE_GOING   = 1;
	const VERIFY_STATE_PASS    = 2;
	const VERIFY_STATE_REFUSED = 3;
	const VERIFY_STATE_DELETED = 4; 
	
	/**
	 * 合作活动(2 同道会)
	 * @var unknown
	 */
	const SOURCE_SELE = 1;
	const SOURCE_TONGDAOHUI = 2;
	
	/**
	 * 临时,需要填写调查问卷的活动id
	 * @var unknown_type
	 */
	public static $special_id = 101025;
	
	/**
	 * 临时,需要发送多个邮件的邮箱
	 * @var unknown_type
	 */
	public static $emails = array('981100088@qq.com','fanhui@zibolan.com');
	
	//public static $emails = array('573932979@qq.com','zhoujianjun@zhongkeyun.com');
	
	/**
	 * 活动类型,暂时比较少,没存数据库
	 */
	public static function getActivityTypes($type=NULL)
	{
		$types = array(
			'salon' => '讲座', 'party' => '聚会', 'exhibition' => '展览',
			'travel' => '旅行', 'commonweal' => '公益', 'sports' => '运动',
			'entertainment' => '娱乐'
		);
		return !empty($type)&&isset($types[$type]) ? $types[$type] : $types;
	}
	
	public function rules()
	{
		return array(
			array('title,province,begin_time,end_time,detail,create_time,state,verify','required'),
			array('title','length', 'max'=>100),
			array('district','length','max' =>200),
			array('begin_time','compare','compareAttribute'=>'end_time','operator'=>'<=','message'=>'开始时间必须小于结束时间','on' => 'front'),
			array('end_time','compare','operator'=>'>=','compareValue'=>strtotime(date('Y-m-d',time())),'message'=>'结束时间必须大于当前时间','on' => 'front'),
			array('province,area,state,verify,max','numerical', 'integerOnly'=>true),
			array('detail','length','max'=>8000),
			array('update_time,soure,cost,videoUrl,create_mid','safe')	
		);
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Follow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity';
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'creater'    => array(self::BELONGS_TO, 'Member', 'create_mid'),
			'applicants' => array(self::MANY_MANY, 'Member', 'activity_member_rel(activity_id,member_id)'),
			'provinceName'   => array(self::BELONGS_TO, 'District', 'province'),
			'areaName'		 => array(self::BELONGS_TO, 'District', 'area'),
			'cooperator'  => array(self::HAS_ONE, 'ActivityMigrate', 'activity_id')
		);
	}
	
	public function defaultScope()
	{
		return array(
			'alias' => 'a',
			'condition' => 'a.state='.self::VERIFY_STATE_WITHOUT.' or a.state='.self::VERIFY_STATE_PASS
		);
	}
	
	public function scopes()
	{
		return array(
			'recently' => array(
				//'alias' => 'a',
				'order' => 'end_time DESC'
				//'order' => 'a.id DESC'
			),
			'owner' => array(
				 'condition' => "create_mid = '". Yii::app()->user->id . "'"	
			),
			'filter' => array(
				'condition' => '(state='.self::VERIFY_STATE_WITHOUT.' OR state='.self::VERIFY_STATE_PASS.')'
			),
			'past' => array(
				'condition' => 'end_time<'.strtotime(date('Y-m-d',time())),
				'alias' => 'a',
				'order' => 'a.end_time DESC'
			),
			'current' => array(
				'condition' => 'end_time>='.strtotime(date('Y-m-d',time())),
				'alias' => 'a',
				'order' => 'a.id DESC'		
			)
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'create_mid'  => '创建人',
			'title' 	  => '活动标题',
			'subject'     =>  '活动主题',
			'type'		  => '活动类型',
			'province'	  => '一级城市',
			'area'		  => '二级城市',
			'district'	  => '具体地址',
			'begin_time'  => '开始时间',
			'end_time'	  => '结束时间',
 			'max'		  => '人数限制',
			'sponer'	  => '主办方',
			'state'		  => '活动状态',
			'detail'	  => '活动详情',
			'verify'	  => '是否需要审核',
			'create_time' => '创建时间',
			'update_time' => '编辑时间',
			'source'      => '平台'
		);
	}
	
	/**
	 * 按城市统计活动个数
	 */
	public function calculateTotalByCity()
	{
		$data = array();
		$specials = District::getSpecialCityId();
		$specials_id = join(',',array_keys($specials));
		$sql = "SELECT province,count(*) as total FROM ".$this->tableName()." WHERE province IN(".$specials_id.") AND state IN(".self::VERIFY_STATE_WITHOUT.",".self::VERIFY_STATE_PASS.") GROUP BY province HAVING total>0";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($result))
		{
			foreach ($result as $value)
			{
				$data[] = array('id' => $value['province'],'name' => $specials[$value['province']],'total' => $value['total']);
			}
		}
		$sql = "SELECT area,count(*) as total FROM ".$this->tableName()." WHERE province NOT IN(".$specials_id.") AND state IN(".self::VERIFY_STATE_WITHOUT.",".self::VERIFY_STATE_PASS.") GROUP BY area HAVING total>0";
		$result2 = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($result2))
		{
			foreach ($result2 as $value)
			{
				$area = District::model()->findByPk($value['area']);
				$data[] = array('id' => $value['area'], 'name' => str_replace('市','',$area->name),'total' => $value['total']);
			}
		}
		
		if(!empty($data) && count($data)>1)
		{
			$amounts = array();
			foreach ($data as $value)
			{
				$amounts[$value['id']] = $value['total'];
			}
			array_multisort($amounts,SORT_DESC,$amounts);
		}
		return $data;
	}
	
	/**
	 * 检测活动是否可以报名
	 * @param $obj 
	 */
	private function checkAbleApply($model)
	{
		if(strtotime(date('Y-m-d',time()))>$model->end_time)
		{
			return array('status' => 0, 'msg' => '活动已结束了哦,下次抓紧时间');
		}
		if($model->state==self::VERIFY_STATE_GOING)
		{
			return array('status' => 0,'msg' => '活动审核中,过一段时间再来报名');
		}
		if($model->state == self::VERIFY_STATE_REFUSED)
		{
			return array('status' => 0,'msg' => '活动不可报名');
		}
		return array('status' => 1, 'msg' => '可报名');
	}
	
	/**
	 * 报名活动
	 * @param int $id 活动ID
	 * @param int $mid 用户id
	 */
	public function apply($id,$mid)
	{
		$model = $this->findByPk($id);
		$result = array('status' => 0, 'msg' => '活动不存在');
		if($model !== null)
		{
			$result = $this->checkAbleApply($model);
			if($result['status'] == 1)
			{
// 				if(ActivityMember::model()->checkApply($mid, $id))
// 				{
// 					$result = array('status' => 0, 'msg' => '您已经报名了哦');
// 					return $result;
// 				}
				$activityMember = ActivityMember::model()->findByAttributes(array('member_id'=>$mid,'activity_id'=>$id));
				if(empty($activityMember))
				{
					$activityMember = new ActivityMember();
					$activityMember->activity_id = $id;
					$activityMember->member_id = $mid;
					$activityMember->state = $model->verify == self::APPLY_VIRIFY_WITHOUT ? ActivityMember::VERIFY_STATE_WITHOUT : ActivityMember::VERIFY_STATE_APPLY;
					$activityMember->create_time = time();
				}else{
					$activityMember->canceled = ActivityMember::CANCELED_NO;
					$activityMember->create_time = time();
				}
				if($activityMember->save())
				{
					if($model->source == self::SOURCE_SELE)
					{
						$follow = Follow::model()->findByAttributes(array('mid'=>$mid,'follow_mid'=>$model->create_mid));
						if(!empty($follow))
						{
							if($follow->is_deleted == Follow::FOLLOW_OUT)
								$follow->is_deleted = Follow::FOLLOW_IN;
						}else {
							$follow = new Follow();
							$follow->mid = $mid;
							$follow->follow_mid = $model->create_mid;
							$follow->follow_at  = time();
							$follow->is_new = Follow::NEW_YES;
						}
						$follow->save();
						return array('status' => 1, 'msg' => '报名成功');
					}
					return array('status' => 1, 'msg' => '报名成功');
				}
			}
		}
		return $result;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('title',$this->title,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => 'id desc',
				'attributes' => array('id','begin_time','end_time','views')
			)
		));
	}

/**
 * 统计某一用户创建的活动
 * @param unknown_type $mid
 */
	public function calculateCreated($mid)
	{
		$sql = "SELECT count(*) AS total FROM ".$this->tableName()." WHERE create_mid={$mid}";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 检测第三方活动报名要求
	 * @param Activity $model 活动对象
	 * @param Member $member  当前登录用户对象
 	 */
	public function checkCooperatApply(&$model,$member)
	{
		if($model->source == self::SOURCE_TONGDAOHUI)
		{
			// 有没自定义要求字段
			$a = unserialize($model->cooperator);
			$applyFields = $a['apply_field'];
			if(!empty($applyFields))
			{
				$fields = array('company','position','email');
				foreach ($applyFields as $value)
				{
					if($value['must'] == 1)
					{
						$name = $value['name'];
						if(in_array($name, $fields) && empty($member->{$name}))
						{
							return false;
						}
					}
				}
			}
		}
		return true;
	}
}