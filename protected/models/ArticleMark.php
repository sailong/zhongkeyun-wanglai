<?php

/**
 * This is the model class for table "article_marked".
 *
 * The followings are the available columns in table 'article_marked':
 * @property string $member_id
 * @property string $article_id
 * @property string $create_time
 *
 * The followings are the available model relations:
 * @property Article $article
 */
class ArticleMark extends CActiveRecord
{
	
	/**
	 * 保存错误信息,供controller调用
	 * @var unknown
	 */
	public $_error = null;
	
	/**
	 * 操作类型,1收藏2点赞
	 * @var unknown
	 */
	const TYPE_MARK = 1;
	const TYPE_UP = 2;
	
	/**
	 * 点赞是否删除
	 * @var unknown
	 */
	const UP_DELETE_YES = 0;
	const UP_DELETE_NO = 1;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ArticleMarked the static model class
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
		return 'article_mark';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, article_id, create_time, type', 'required'),
			array('member_id, article_id, create_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('member_id, article_id, create_time, delete', 'safe'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'member_id' => 'Member',
			'article_id' => 'Article',
			'create_time' => 'Create Time',
		);
	}
	
	/**
	 * mark(收藏) up(点赞)
	 * @see CActiveRecord::scopes()
	 */
	public function scopes()
	{
		return array(
			'mark' => array(
				'condition' => 'type='.self::TYPE_MARK
			),
			'up' => array(
				'condition' => 'type='.self::TYPE_UP
			)
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

		$criteria->compare('member_id',$this->member_id,true);
		$criteria->compare('article_id',$this->article_id,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取某用户mark的文章数
	 * @param int $uid
	 * @param tinyint $type 类型(收藏/点赞)
	 */
	public function getTotal($uid,$type=self::TYPE_MARK)
	{
		return $this->countByAttributes(array('member_id'=>$uid,'type'=>$type));
	}
	
	
	/**
	 * 检测是否已收藏,已点赞
	 * @param $article_id int 文章id
	 * @param $member_id int 用户id
	 * @return boole 收藏true or false
	 */
	public function checkMark($article_id, $member_id, $type=self::TYPE_MARK)
	{
		$model = $this->findByAttributes(array('article_id'=>$article_id,'member_id'=>$member_id,'type'=>$type));
		return $model === null ? false : true;
	}
	
	/**
	 * 收藏或点赞某文章
	 * @param int $article_id
	 * @param int $member_id
	 * @param int $type
	 */
	public function mark($article_id, $member_id, $type=self::TYPE_MARK)
	{
		$articleMark = $this->findByAttributes(array('member_id'=>$member_id,'article_id'=>$article_id,'type'=>$type));
		if($articleMark === null)
		{
			$articleMark = new ArticleMark();
			$articleMark->attributes = array('member_id'=>$member_id,'article_id'=>$article_id,'create_time'=>time(),'type'=>$type);
			$articleMark->save();
			return true;
		}else{
			$this->_error = '已操作';
			return false;
		}
	}
	
	
	
	
}