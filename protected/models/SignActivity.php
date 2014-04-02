<?php

/**
 * This is the model class for table "sign_activity".
 *
 * The followings are the available columns in table 'sign_activity':
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $share_icon
 * @property string $share_counts
 * @property string $pv_counts
 */
class SignActivity extends CActiveRecord
{
    
    /**
     * 签名是否已发布,0预览,1已发布
     * @var unknown
     */
    const PUBLISH_NO = 0;
    const PUBLISH_YES = 1;
    /**
     * 签名是否删除  status,0删除,1正常
     * @var status
     */
    const STATUS_DEL_YES = 0;
    const STATUS_DEL_NO = 1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SignActivity the static model class
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
		return 'sign_activity';
	}

	
	public function defaultScope()
	{
	    return array(
	            'alias' => 'ar',
	            'condition' => "ar.status='".self::STATUS_DEL_NO."'",
	            'order' => 'ar.id DESC'
	    );
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content,create_time,create_mid,publish', 'required'),
			array('title', 'length', 'max'=>50),
			//array('content', 'length', 'max'=>10000),
			array('img', 'file', 'types' => 'jpg,jpeg,png,gif','allowEmpty'=>true),
			array('share_counts, pv_counts', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, share_counts, pv_counts,create_time,update_time,create_mid,publish,publish_time', 'safe', 'on'=>'search'),
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
		        'creater' => array(self::BELONGS_TO, 'Member', 'create_mid')
		);
	}
	/**
	 * @return array relational rules.
	 */
// 	public function relations()
// 	{
// 	    // NOTE: you may need to adjust the relation name and the related
// 	    // class name for the relations automatically generated below.
// 	    return array(
// 	            'articleComments' => array(self::HAS_MANY, 'ArticleComment', 'article_id'),
// 	            'content' => array(self::HAS_ONE, 'ArticleContent', 'article_id'),
// 	            'articleMarkeds' => array(self::HAS_MANY, 'ArticleMarked', 'article_id'),
// 	            'creater' => array(self::BELONGS_TO, 'Member', 'create_mid')
// 	    );
// 	}
	public function scopes()
	{
	    return array(
	            'owner' => array(
	                    'condition' => "create_mid = '". Yii::app()->user->id . "'"
	            ),
	            // 已发布的
	            'published' => array(
	                    'condition' => "publish='".self::PUBLISH_YES."'"
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
			'content' => '内容',
			'img' => '分享图片',
			'share_counts' => '分享次数',
			'pv_counts' => 'PV',
			'create_time' => '创建时间',
	        'create_mid' => '创建人',
	        'update_time' => '修改时间',
	        'publish' => '是否发布',
	        'publish_time' => '发布时间'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

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
	 * 获取某用户签名
	 * @param int $uid
	 */
	public function getCreateTotal($uid)
	{
	    return $this->countByAttributes(array('create_mid'=>$uid));
	}
	
	/**
	 * 获取签名人数
	 * @param unknown_type $id
	 */
	public function calculateTotal($id)
	{
		$sql = "SELECT count(*) FROM signature WHERE object_id={$id} and status='".Signature::SIGN_STATUS_NORMAL."' and type='".Signature::SIGN_TYPE_FLAG."'";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
}