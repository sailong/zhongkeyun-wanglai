<?php

/**
 * This is the model class for table "tmp_research".
 *
 * The followings are the available columns in table 'tmp_research':
 * @property string $id
 * @property string $mid
 * @property string $company
 * @property string $position
 * @property string $type
 * @property string $products
 * @property integer $employee
 * @property string $stage
 * @property string $income
 * @property string $profile_ratio
 * @property string $growth_ratio
 * @property string $capacity
 * @property string $cost
 * @property string $cost_ratio
 * @property string $information
 * @property string $web
 * @property string $function
 * @property string $sale_channel
 * @property string $sale_channel_ratio
 * @property string $promotion_channel
 * @property string $promotion_channel_ratio
 * @property string $internet
 * @property string $impact
 * @property string $change
 * @property string $advantage
 * @property string $disadvantage
 * @property string $question
 * @property string $create_time
 */
class Research extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Research the static model class
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
		return 'tmp_research';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mid,type,stage,cost, information, web,function,sale_channel,promotion_channel,impact,create_time', 'required'),
			array('employee', 'numerical', 'integerOnly'=>true),
			array('company, position, products, income, profile_ratio, growth_ratio, capacity, cost, function, promotion_channel', 'length', 'max'=>50),
			array('type, stage, cost_ratio, sale_channel_ratio, promotion_channel_ratio, internet, impact, change, advantage, disadvantage', 'length', 'max'=>100),
			array('information, question', 'length', 'max'=>200),
			array('web', 'length', 'max'=>5),
			array('sale_channel', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, mid, company, position, type, products, employee, stage, income, profile_ratio, growth_ratio, capacity, cost, cost_ratio, information, web, function, sale_channel, sale_channel_ratio, promotion_channel, promotion_channel_ratio, internet, impact, change, advantage, disadvantage, question, create_time', 'safe'),
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
			'company' => 'Company',
			'position' => 'Position',
			'type' => 'Type',
			'products' => 'Products',
			'employee' => 'Employee',
			'stage' => 'Stage',
			'income' => 'Income',
			'profile_ratio' => 'Profile Ratio',
			'growth_ratio' => 'Growth Ratio',
			'capacity' => 'Capacity',
			'cost' => 'Cost',
			'cost_ratio' => 'Cost Ratio',
			'information' => 'Information',
			'web' => 'Web',
			'function' => 'Function',
			'sale_channel' => 'Sale Channel',
			'sale_channel_ratio' => 'Sale Channel Ratio',
			'promotion_channel' => 'Promotion Channel',
			'promotion_channel_ratio' => 'Promotion Channel Ratio',
			'internet' => 'Internet',
			'impact' => 'Impact',
			'change' => 'Change',
			'advantage' => 'Advantage',
			'disadvantage' => 'Disadvantage',
			'question' => 'Question',
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
		$criteria->compare('mid',$this->mid,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('products',$this->products,true);
		$criteria->compare('employee',$this->employee);
		$criteria->compare('stage',$this->stage,true);
		$criteria->compare('income',$this->income,true);
		$criteria->compare('profile_ratio',$this->profile_ratio,true);
		$criteria->compare('growth_ratio',$this->growth_ratio,true);
		$criteria->compare('capacity',$this->capacity,true);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('cost_ratio',$this->cost_ratio,true);
		$criteria->compare('information',$this->information,true);
		$criteria->compare('web',$this->web,true);
		$criteria->compare('function',$this->function,true);
		$criteria->compare('sale_channel',$this->sale_channel,true);
		$criteria->compare('sale_channel_ratio',$this->sale_channel_ratio,true);
		$criteria->compare('promotion_channel',$this->promotion_channel,true);
		$criteria->compare('promotion_channel_ratio',$this->promotion_channel_ratio,true);
		$criteria->compare('internet',$this->internet,true);
		$criteria->compare('impact',$this->impact,true);
		$criteria->compare('change',$this->change,true);
		$criteria->compare('advantage',$this->advantage,true);
		$criteria->compare('disadvantage',$this->disadvantage,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}