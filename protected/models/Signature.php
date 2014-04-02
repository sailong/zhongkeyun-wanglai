<?php

/**
 * This is the model class for table "signature".
 *
 * The followings are the available columns in table 'signature':
 * @property string $id
 * @property string $object_id
 * @property string $member_id
 * @property string $create_time
 */
class Signature extends CActiveRecord
{
	protected $_objectID = 1; // 签名活动ID
	
	/*
	 * type 常量 1签名 2收藏
	 * 
	 * SIGN_TYPE_FLAG 1 签名
	 * 
	 * SIGN_TYPE_COLLECT 2 收藏
	 */
	const SIGN_TYPE_FLAG = 1;
	const SIGN_TYPE_COLLECT = 2;
	
	/*
	 * status 常量  0删除 1正常
	*
	* SIGN_STATUS_DEL 0 删除
	*
	* SIGN_STATUS_NORMAL 1 正常
	*/
	const SIGN_STATUS_DEL = 0;
	const SIGN_STATUS_NORMAL = 1;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Signature the static model class
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
		return 'signature';
	}
	
	public function defaultScope()
	{
	    return array(
	            'alias' => 'ar',
	            'condition' => "ar.status='".self::SIGN_STATUS_NORMAL."'",
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
			array('object_id, member_id, create_time, type, status', 'required'),
			array('object_id, member_id, create_time, type, status', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, object_id, member_id, create_time, type, status', 'safe', 'on'=>'search'),
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
			'sign' => array(self::BELONGS_TO, 'SignActivity', 'object_id'),
			'member'  => array(self::BELONGS_TO, 'Member', 'member_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'object_id' => 'Object',
			'member_id' => 'Member',
			'create_time' => 'Create Time',
	        'type' => 'Type',
	        'status' => 'Status',
		);
	}
	
	/**
	 * 获取某用户签名和收藏的签名数
	 * @param int $uid
	 * @param tinyint $type 类型(收藏2/签名1)
	 */
	public function getTotal($uid,$type=self::SIGN_TYPE_COLLECT,$status=self::SIGN_STATUS_NORMAL)
	{
	    return $this->countByAttributes(array('member_id'=>$uid,'type'=>$type,'status'=>self::SIGN_STATUS_NORMAL));
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('member_id',$this->member_id,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 检测是否已签名,已收藏
	 * @param object_id int 签名id
	 * @param $member_id int 用户id
	 * @return boole 收藏/签名true or false
	 */
	public function checkSignature($objectId, $member_id, $type=self::SIGN_TYPE_FLAG)
	{
		$model = $this->findByAttributes(array('object_id'=>$objectId,'member_id'=>$member_id,'type'=>$type,'status'=>self::SIGN_STATUS_NORMAL));
		
		return $model === null ? false : true;
	}
	
	
	/**
	 * 收藏或签名某签名
	 * @param int $object_id 签名id
	 * @param int $member_id
	 * @param int $type
	 */
	public function mark($object_id, $member_id, $type=self::SIGN_TYPE_FLAG)
	{
	    $signature = $this->findByAttributes(array('member_id'=>$member_id,'object_id'=>$object_id,'type'=>$type));
	    if($signature === null)
	    {
	        $signature = new Signature();
	        $signature->attributes = array('member_id'=>$member_id,'object_id'=>$object_id,'create_time'=>time(),'type'=>$type);
	        
	        if($signature->save())
	        {
	            return $signature;
	        }
	        return false;
	    }else{
	        //$this->_error = '已操作';
	        return false;
	    }
	}
}