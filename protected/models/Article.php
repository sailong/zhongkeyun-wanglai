<?php

/**
 * This is the model class for table "article".
 *
 * The followings are the available columns in table 'article':
 * @property string $id
 * @property string $title
 * @property string $creater_mid
 * @property string $create_time
 * @property string $views
 * @property string $share_counts
 * @property integer $publish
 * @property integer $update_time
 * @property string $publish_time
 *
 * The followings are the available model relations:
 * @property ArticleComment[] $articleComments
 * @property ArticleContent $articleContent
 * @property ArticleMarked[] $articleMarkeds
 */
class Article extends CActiveRecord
{
	
	/**
	 * 文章是否已发布,0未发布,1已发布
	 * @var unknown
	 */
	const PUBLISH_NO = 0;
	const PUBLISH_YES = 1;
	
	/**
	 * 正常文章(可能存在不合法或已删除文章,相应状态标识在此处添加)
	 * @var unknown
	 */
	const ARTICLE_OK = 1;
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Article the static model class
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
		return 'article';
	}

	public function defaultScope()
	{
		return array(
			'alias' => 'ar',
			'condition' => "state='".self::ARTICLE_OK."'",
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
			array('title, create_mid, create_time, summary', 'required'),
			array('title', 'length', 'max'=>50),
			array('summary', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, share_pic,create_mid, create_time, views, share_counts, publish, update_time, publish_time', 'safe'),
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
			'articleComments' => array(self::HAS_MANY, 'ArticleComment', 'article_id'),
			'content' => array(self::HAS_ONE, 'ArticleContent', 'article_id'),
			'articleMarkeds' => array(self::HAS_MANY, 'ArticleMarked', 'article_id'),
			'creater' => array(self::BELONGS_TO, 'Member', 'create_mid')
		);
	}

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
			'title' => 'Title',
			'summary' => '摘要',
			'creater_mid' => 'Creater Mid',
			'create_time' => 'Create Time',
			'views' => 'Views',
			'share_counts' => 'Share Counts',
			'publish' => 'Publish',
			'update_time' => 'Update Time',
			'publish_time' => 'Publish Time',
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
		$criteria->compare('create_mid',$this->creater_mid,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('views',$this->views,true);
		$criteria->compare('share_counts',$this->share_counts,true);
		$criteria->compare('publish',$this->publish);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('publish_time',$this->publish_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取文章总数
	 * @param int $uid 用户id
	 * @param int $publish 已发布|未发布|全部
	 */
	public function getTotal($uid, $publish=null)
	{
		$condition = array('create_mid'=>$uid);
		if($publish !== null)
			$condition['publish'] = $publish;
		return $this->countByAttributes($condition);
		
	}
}