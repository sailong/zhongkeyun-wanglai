<?php

/**
 * This is the model class for table "article_comment".
 *
 * The followings are the available columns in table 'article_comment':
 * @property string $id
 * @property string $article_id
 * @property string $member_id
 * @property string $comment
 * @property integer $create_time
 *
 * The followings are the available model relations:
 * @property Article $article
 */
class ArticleComment extends CActiveRecord
{
	
	/**
	 * 评论状态
	 */
	const STATUS_OK = 1;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ArticleComment the static model class
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
		return 'article_comment';
	}

	
	public function defaultScope()
	{
		return array(
			'alias' => 'ac',
			'order' => 'ac.id DESC',
			'condition' => 'status='.self::STATUS_OK
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
			array('article_id, member_id, comment, create_time', 'required'),
			array('create_time', 'numerical', 'integerOnly'=>true),
			array('article_id, member_id', 'length', 'max'=>10),
			array('comment', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, article_id, member_id, comment, create_time', 'safe', 'on'=>'search'),
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
			'article' => array(self::BELONGS_TO, 'Article', 'article_id'),
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
			'article_id' => 'Article',
			'member_id' => 'Member',
			'comment' => 'Comment',
			'create_time' => 'Create Time',
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
		$criteria->compare('article_id',$this->article_id,true);
		$criteria->compare('member_id',$this->member_id,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}