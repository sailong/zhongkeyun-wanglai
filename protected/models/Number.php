<?php

/**
 * This is the model class for table "number".
 *
 * The followings are the available columns in table 'number':
 * @property string $id
 * @property string $number
 * @property integer $length
 * @property integer $number_status
 * @property integer $is_keep
 * @property integer $created_at
 */
class Number extends CActiveRecord
{
	
	/**
	 * 往来号长度4,5,6位
	 * @var unknown_type
	 */
	const LEVEL_FOUR = 4;
	const LEVEL_FIVE = 5;
	const LEVEL_SIX  = 6;
	
	/**
	 * 分配状态0可分配1已发放2 已锁定
	 */
	const ASSIGN_STATUS_ABLE   = 0;
	const ASSIGN_STATUE_SENDED = 1;
	const ASSIFN_STATUS_LOCK   = 2;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Number the static model class
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
		return 'number';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number, length, created_at', 'required'),
			array('length, number_status, is_keep, created_at', 'numerical', 'integerOnly'=>true),
			array('number', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, number, length, number_status, is_keep, created_at', 'safe', 'on'=>'search'),
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
			'number' => 'Number',
			'length' => 'Length',
			'number_status' => 'Number Status',
			'is_keep' => 'Is Keep',
			'created_at' => 'Created At',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('number_status',$this->number_status);
		$criteria->compare('is_keep',$this->is_keep);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * 分配账号
	 * @param unknown_type $number
	 */
	public function assignNumber()
	{
		$connection = Yii::app()->db;
		$sql = "SELECT id,number FROM `number` WHERE id >= (SELECT FLOOR(RAND() * (SELECT MAX(id) FROM `number`))) AND is_keep = 0 AND number_status = 0 AND `length`>4
ORDER BY `length` ASC LIMIT 1";
		$command = $connection->createCommand($sql);
		$result = $command->queryAll();
		if(!$result) return;
		//给此账号加锁
		$result = $result[0];
		
		if(!$this->updateByPk($result['id'], array('number_status'=>2))) return;
		
		return $result;
	}
	
	/**
	 * 往来号长度,默认6位
	 */
	public function getNumber()
	{
		$number = false;
		$beginId = 110001;  // 6为往来号的起始ID和结束ID
		$endId = 1109999;
		do{
			$id = rand($beginId, $endId);
			$sql = "SELECT * FROM `number` WHERE id >={$id} AND is_keep=0 AND number_status=".self::ASSIGN_STATUS_ABLE." AND `length`=".self::LEVEL_SIX." ORDER BY id LIMIT 10";
			$result = Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($result))
			{
				foreach ($result as $row)
				{
					$number = $row['number'];
					$model = Member::model()->findByAttributes(array('wanglai_number'=>$number));
					if(empty($model))
					{
						$this->updateByPk($row['id'], array('number_status'=>self::ASSIGN_STATUE_SENDED));
						break;
					}
				}
			}
		}while(!$number);
		
		return $number;
	}
	
}