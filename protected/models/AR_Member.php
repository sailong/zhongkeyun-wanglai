<?php

/**
 * This is the model class for table "member".
 *
 * The followings are the available columns in table 'member':
 * @property string $id
 * @property string $weixin_openid
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property string $password
 * @property string $position
 * @property string $company
 * @property string $address
 * @property string $company_url
 * @property string $main_business
 * @property string $supply
 * @property string $demand
 * @property integer $views
 * @property string $show_item
 * @property string $weixin
 * @property string $yixin
 * @property string $laiwang
 * @property string $qq
 * @property string $hobby
 * @property string $notes
 * @property integer $from
 * @property integer $share_counts
 * @property string $avatar
 * @property string $profile
 * @property string $social_position
 * @property integer $is_vip
 * @property integer $created_at
 * @property integer $updated_at
 */
class AR_Member extends CActiveRecord
{
	
	/**
	 * 是否加V,0普通的，1加v
	 * @see property $is_vip
	 * @var int
	 */
	const TYPE_NORMAL = 0;
	const TYPE_VIP = 1;
	
	/**
	 * 是否企业号
	 * @var unknown_type
	 */
	const QIYE_NO = 0;
	const QIYE_YES = 1;
	
	/**
	 * 是否关注,1关注,2已取消关注
	 * @var int
	 */
	const SUBSCRIBE = 1;
	const UNSUBSCRIBE = 2;
	
	/**
	 * 1显示,0隐藏
	 * @var int
	 */
	const ITEM_SHOW = 1;
	const ITEM_HIDDEN = 0;
	
	/**
	 * 往来号等级|类型,0普通的，1金色
	 * @see property 
	 * @var int
	 */
	const WANG_LAI_NUMBER_NORMAL = 0;
	const WANG_LAI_NUMBER_GOLDEN = 1;
	/**
	 * 制定名片上隐藏的信息
	 */
	public static $hideOptions = array(
		'mobile' => '隐藏名片手机号码',
		'demand' => '隐藏名片供给信息',
		'supply' => '隐藏名片需求信息'
	);
	
	public $repeat_password;
	
	// 排名权重
	public $score; 
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Member the static model class
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
		return 'member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,weixin_openid,mobile', 'required', 'message' => '{attribute}不可为空'),
			array('name','application.components.validators.OCharacterValidator','message'=>'名字请输入中文或英文','on'=>'update'),
			//array('password','compare', 'compareAttribute' => 'repeat_password','on' => 'update', 'allowEmpty' => true, 'message' => '两次密码输入不一致'),
			array('mobile','application.components.validators.OMobileValidator', 'message' => '手机号码输入错误'),
			array('mobile','unique','message' => '手机号码已经存在了'),
			array('email','email','message' => '邮箱地址错误'),
			array('company_url','url','message'=>'公司微站格式错误'),
			//array('email', 'unique','message' => '邮箱已经存在了'),
			array('birthday,views,share_counts,created_at,updated_at', 'numerical', 'integerOnly'=>true),
			array('name, mobile, email, position, company, address, company_url, weixin, yixin, laiwang, qq, avatar', 'length', 'max'=>100),
			array('main_business, supply, demand,social_position','length', 'max'=>300),
			array('hobby, notes, profile', 'length', 'max'=>500),
			array('avatar', 'file', 'types' => 'jpg,jpeg,png', 'allowEmpty' => true, 'maxSize' => 3000000, 'on' => 'update', 'message' => '图片大小不能超过3M'),
			array('show_item,is_vip,is_qiye,initial,subscribe,wanglai_number,wanglai_number_grade,avatar','safe')
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'follow' => array(self::HAS_ONE,'Follow','follow_mid','select' => 'is_deleted','condition'=>"mid='".Yii::app()->user->id."'"),
			'extend' => array(self::HAS_ONE,'MemberExtend','member_id'),
			'stat'   => array(self::HAS_ONE, 'Stat', 'member_id')	
		);
	}

	public function scopes()
	{
		return array(
			'hot' => array(
				'order' => 'views DESC'
			),
			'initial' => array(
				'order' => 'initial ASC'		
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
			'weixin_openid' => 'Weixin Openid',
			'name' => '姓名',
			'mobile' => '手机',
			'email' => 'Email',
			'password' => '密码',
			'initial'  => '首字母',
			'repeat_password' => '确认密码',
			'position' => '职位',
			'company' => '公司',
			'address' => '地址',
			'company_url' => '微站',
			'main_business' => '主营业务',
			'supply' => '供给',
			'demand' => '需求',
			'views' => '浏览数(UV)',
			'show_item' => '显示选项',
			'weixin' => '微信号',
			'yixin' => '易信号',
			'laiwang' => '来往号',
			'qq' => 'qq号',
			'hobby' => '爱好',
			'notes' => '备注',
			'share_counts'=>'转发数',
			'avatar' => '头像',
			'profile' => '个人简介',
			'social_position' => '社会职务',
			'is_vip'=>'是否加V',
			'subscribe' => '是否关注',
			'wanglai_number'=>'往来号',
			'wanglai_number_grade'=>'是否是金色号',
			'created_at' => '创建时间',
			'updated_at' => '更新时间',
			'is_qiye' => '企业账号'
		);
	}

	/**
	 * 后台编辑名片，数据验证完后，判断密码是否修改，如果修改则重置密码，否则不变
	 * @see CModel::onAfterValidate()
	 */
	
	public function onAfterValidate($event) {
		if (!empty($this->password) && ($event->sender->getScenario() == 'update')) {
			$this->password = $this->setPassword($this->password);
		}
	}
	
	/**
	 * 获取用户头像(用户上传的头像,没有则用户微信的头像,没有则默认的头像)
	 * @param obj $model 用户model
	 * @param char $size 大小,s|b,大图或小图
	 * @param bool $default 是否返回默认头像
	 */
	public function getPhoto($model,$size='s',$default=true)
	{
		$photo = $default && $size == 's' ? self::getDefaultPhoto() : '';
		if(!empty($model->avatar))
		{
			$photo = Helper::getImage($model->avatar,$size);
		}else{
			$extend = MemberExtend::model()->findByAttributes(array('member_id'=>$model->id));
			if(!empty($extend) && !empty($extend->headimgurl))
			{
				$photo = Helper::getImage($extend->headimgurl,$size);
			}
		}
		return $photo;
	}
	
	/**
	 * 获取默认头像
	 */
	public static function getDefaultPhoto()
	{
		return Yii::app()->request->hostInfo.'/static/weixin/rlogo.png?version=12012';
	}
	
	/**
	 * 检测权限,名片页,是否可以查看手机号等信息
	 * @param obj $model 查看的名片对象
	 */
	public function checkAccess($model)
	{
		if(Yii::app()->user->getIsGuest())
			return false;
		$uid = Yii::app()->user->id;
		if($model->id == $uid)
			return true;
		return Follow::model()->checkFollow($model->id, intval(Yii::app()->user->id)) ? true : false;
	}
	
	/**
	 * 获取好友中有多少人超过了你
	 */
	public function calculateBeforeMe($mid=null)
	{
		$count = 0;
		$id = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT * FROM follow WHERE (`mid`={$id} OR follow_mid={$id}) AND is_deleted=".Follow::FOLLOW_IN;
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		$data = array();
		if(!empty($result))
		{
			$ids = array();
			foreach ($result as $value)
			{
				$ids[] = $value['mid'] == $id ? $value['follow_mid'] : $value['mid'];
			}
			array_push($ids, $id);
			$sql = "SELECT id,views FROM member WHERE id IN(".join(',',$ids).") ORDER by views DESC";
			$all = Yii::app()->db->createCommand($sql)->queryAll();
			$ownScore = 0;
			foreach ($all as $value)
			{
				if($value['id'] == $id)
				{
					$ownScore = $value['views'];break;
				}else{
					continue;
				}
			}
			foreach ($all as $value)
			{
				$count++;
				if($value['views'] == $ownScore || $value['id'] == $id)
					break;
				else
					continue;
			}
		}
		return $count>0 ? $count-1 : 0;
	}
}