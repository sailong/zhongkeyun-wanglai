<?php

/**
 * This is the model class for table "follow".
 *
 * The followings are the available columns in table 'follow':
 * @property string $id
 * @property integer $mid
 * @property integer $follow_mid
 * @property integer $is_deleted
 * @property integer $follow_at
 */
class AR_Follow extends CActiveRecord
{
	
	/**
	 * 是否关注,0关注,1取消关注
	 * @var unknown
	 */
	const FOLLOW_IN = 0;
	const FOLLOW_OUT = 1;
	
	/**
	 * 最新关注(即上传查看后是否有最新的关注信息)
	 * @var unknown_type
	 */
	const NEW_NO  = 0;
	const NEW_YES = 1;
	
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
		return 'follow';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mid, follow_mid, follow_at', 'required'),
			array('mid, follow_mid, is_deleted, follow_at', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, mid, follow_mid, is_deleted, follow_at', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'mid' => 'Mid',
			'follow_mid' => 'Follow Mid',
			'is_deleted' => 'Is Deleted',
			'follow_at' => 'Follow At',
		);
	}
	
	/**
	 * 是否有新的关注
	 * @param int $mid
	 * @return 
	 */
	public function calculateNewFollow($mid=null)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT count(*) AS total FROM ".$this->tableName()." WHERE follow_mid={$mid} AND is_new=".self::NEW_YES;
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 统计我的收藏(我关注的不包含相互关注)
	 * @param int $mid 用户id
	 * @return int
	 */
	public function calculateMyFollow($mid=null)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT count(*) AS total FROM follow WHERE follow.mid={$mid} AND is_deleted=".self::FOLLOW_IN." AND follow.follow_mid NOT IN(SELECT f.mid FROM follow AS f WHERE f.follow_mid={$mid} AND is_deleted=".self::FOLLOW_IN.")";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 统计我的粉丝(关注我的不包含相互关注)
	 * @param int $mid 用户id
	 * @return int
	 */
	public function calculateFollowMe($mid=null)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT count(*) AS total FROM follow WHERE follow.follow_mid={$mid} AND is_deleted=".self::FOLLOW_IN." AND follow.mid NOT IN(SELECT follow_mid FROM follow WHERE `mid`={$mid} AND is_deleted=".self::FOLLOW_IN.")";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 统计相互关注的
	 * @param int $mid 用户id
	 * @return int
	 */
	public function calculateInterFollow($mid=null)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT count(*) as total FROM follow INNER JOIN follow AS f ON follow.follow_mid=f.mid AND follow.mid={$mid} AND follow.is_deleted=".self::FOLLOW_IN." AND f.follow_mid={$mid} AND f.is_deleted=".self::FOLLOW_IN;
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 我关注的,包含相互关注
	 * @param unknown_type $mid
	 */
	public function calculateMyFollowInclude($mid=null)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT count(*) AS total FROM follow WHERE `mid`={$mid} AND is_deleted=".self::FOLLOW_IN;
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 关注我的,包含相互关注
	 * @param unknown_type $mid
	 */
	public function calculateFollowMeInclude($mid=null)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT count(*) AS total FROM follow WHERE follow_mid={$mid} AND is_deleted=".self::FOLLOW_IN;
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	/**
	 * 检测$mid是否关注了$follow_mid
	 * @param unknown_type $mid
	 * @param unknown_type $follow_mid
	 * @return bool 关注true,or false
	 */
	public function checkFollow($mid,$follow_mid)
	{
		$sql = "SELECT * FROM follow WHERE `mid`={$mid} AND follow_mid={$follow_mid}";
		$follow = Yii::app()->db->createCommand($sql)->queryRow();
		return !empty($follow) && $follow['is_deleted'] == self::FOLLOW_IN ? true : false;
	}
	
	/**
	 * 检测多个用户是否关注了$mid
	 * @param int $follw_mid
	 * @param array $mids
	 */
	public function checkMultiFollow($follw_mid,$mids)
	{
		$result = array_fill_keys($mids, false);
		$sql = "SELECT * FROM follow WHERE `follow_mid`={$follw_mid} AND `mid` IN(".join(',',$mids).")";
		$all = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($all))
		{
			foreach ($all as $follow)
			{
				if($follow['is_deleted'] == self::FOLLOW_IN) $result[$follow['mid']] = true;
			}
		}
		return $result;
	}
	
	/**
	 * $mid 是否关注了一批人$follow_mids
	 * @param int $mid
	 * @param array $follow_mids
	 */
	public function checkMultiFollow2($mid, $follow_mids)
	{
		$result = array_fill_keys($follow_mids, false);
		if(!empty($follow_mids))
		{
			$sql = "SELECT * FROM follow WHERE `mid`={$mid} AND `follow_mid` IN(".join(',',$follow_mids).")";
			$all = Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($all))
			{
				foreach ($all as $follow)
				{
					if($follow['is_deleted'] == self::FOLLOW_IN) $result[$follow['follow_mid']] = true;
				}
			}
		}
		return $result;
	}
	
	/**
	 * 获取最近一个关注我的用户
	 * @param unknown_type $uid
	 */
	public function getLatestFollowMe($mid)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT member.name FROM follow,member WHERE follow.follow_mid={$mid} AND follow.mid=member.id AND follow.is_deleted=".Follow::FOLLOW_IN." ORDER BY follow_at DESC LIMIT 1";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	/**
	 * 获取最近一个我关注的用户
	 * @param unknown_type $uid
	 */
	public function getLatestMyFollow($mid)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT member.name FROM follow,member WHERE follow.mid={$mid} AND follow.follow_mid=member.id AND follow.is_deleted=".Follow::FOLLOW_IN." ORDER BY follow_at DESC LIMIT 1";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 获取最近一个相互关注的
	 */
	public function getLatestInterFollow($mid)
	{
		$mid = !empty($mid) ? $mid : Yii::app()->user->id;
		$sql = "SELECT member.name FROM follow INNER JOIN follow AS f ON follow.follow_mid=f.mid INNER JOIN member ON f.mid=member.id AND follow.mid={$mid} AND follow.is_deleted=".self::FOLLOW_IN." AND f.follow_mid={$mid} AND f.is_deleted=".self::FOLLOW_IN." ORDER BY follow.follow_at DESC LIMIT 1";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
}