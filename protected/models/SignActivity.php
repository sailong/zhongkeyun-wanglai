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

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content', 'required'),
			array('title', 'length', 'max'=>50),
			//array('content', 'length', 'max'=>10000),
			array('img', 'file', 'types' => 'jpg,jpeg,png,gif','allowEmpty'=>true),
			array('share_counts, pv_counts', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, img, share_counts, pv_counts,create_time,update_time', 'safe', 'on'=>'search'),
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
			'title' => '标题',
			'content' => '内容',
			'img' => '分享图片',
			'share_counts' => 'Share Counts',
			'pv_counts' => 'Pv Counts',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('share_icon',$this->share_icon,true);
		$criteria->compare('share_counts',$this->share_counts,true);
		$criteria->compare('pv_counts',$this->pv_counts,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取签名人数
	 * @param unknown_type $id
	 */
	public function calculateTotal($id)
	{
		$sql = "SELECT count(*) FROM signature WHERE object_id={$id}";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
}