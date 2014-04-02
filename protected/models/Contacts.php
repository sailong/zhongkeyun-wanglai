<?php

/**
 * This is the model class for table "contacts".
 *
 * The followings are the available columns in table 'contacts':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property integer $type
 * @property integer $privacy
 * @property string $create_mid
 * @property string $create_time
 * @property string $update_time
 * @property integer $deleted
 * @property string $share_counts
 * @property string $uv_counts
 * @property string $pv_counts
 */
class Contacts extends CActiveRecord
{
	
	/**
	 * 群隐私设置,1公开(群友君可见),2私密(仅群管理员可见)
	 * @var unknown_type
	 */
	const PRIVACY_PUBLIC = 1;
	const PRIVACY_PRIVATE = 2;
	
	/**
	 * 是否是系统默认的群
	 * @var unknown_type
	 */
	const DEFAULT_NO = 0;
	const DEFAULT_YES = 1;
	
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Contacts the static model class
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
		return 'contacts';
	}

	/**
	 * 群类型
	 */
	public static function types($key=NULL)
	{
		$types = array(
			1 => '好友通讯录', 2 => '同事通讯录', 3 => '同学/校友通讯录', 4 => '亲友通讯录',
			5 => '同乡通讯录', 6 => '兴趣爱好', 7 => 'IT/互联网', 8 => '建筑工程', 9 => '服务',
			10 => '传媒', 11 => '营销与广告', 12 => '教师', 13 => '律师', 14 => '公务员',
			15 => '银行', 16 => '咨询', 17 => '教育', 18 => '投资', 19 => '其它'	
		);
		return isset($types[$key]) ? $types[$key] : $types;
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, create_mid, create_time', 'required'),
			array('type, privacy, deleted', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>50),
			array('description', 'length', 'max'=>100),
			array('create_mid, create_time, update_time, share_counts, uv_counts, pv_counts', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, description, type, privacy, create_mid, create_time, update_time, deleted, share_counts, uv_counts, pv_counts,default', 'safe'),
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
			'creater' => array(self::BELONGS_TO, 'Member', 'create_mid'),
			'member' => array(self::MANY_MANY, 'Member', 'contacts_member_rel(contacts_id,member_id)')
		);
	}

	public function scopes()
	{
		return array(
			// 创建的
			'owner' => array(
				'condition' => "create_mid='".Yii::app()->user->id."'",
				'order'		=> 'create_time DESC'
			),
			'system' => array(
				'condition' => "`default`='".self::DEFAULT_YES."'"
			),
			'custom' => array(
				'condition' => "`default`='".self::DEFAULT_NO."'"
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
			'title' => '标题',
			'description' => '描述',
			'type' => '类型',
			'privacy' => '权限',
			'create_mid' => '创建人',
			'create_time' => '创建时间',
			'update_time' => 'Update Time',
			'deleted' => 'Deleted',
			'share_counts' => '分享次数',
			'uv_counts' => 'UV',
			'pv_counts' => 'PV',
			'default' => '系统'
		);
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
						'attributes' => array('id')
				)
		));
	}
	/**
	 * 统计某用户创建群的数量
	 */
	public function calculateCreated($mid)
	{
		$sql = "SELECT count(*) FROM ".$this->tableName()." WHERE create_mid={$mid}";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
}