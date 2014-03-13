<?php

/**
 * 区域模型
 * @author JZLJS00
 *
 */
class District extends CActiveRecord
{
	
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
		return 'district';
	}
	
	/**
	 * 根据省市信息返回指定的城市(4个直辖市特殊处理)
	 * @param int $province  一级id
	 * @param int $area      二级id
	 */
	public function getCity($province,$area)
	{
		$model = $this->findByPk(intval($province));
		$specials = array('北京市','天津市','上海市','重庆市');
		if(in_array($model->name, $specials))
			return str_replace('市', '', $model->name);
		$model = $this->findByPk(intval($area));
		return str_replace('市', '', $model->name);
	}
	
	/**
	 * 4个直辖市的id,4个直辖市显示一级城市,其余的显示二级城市,所以这样的特殊处理
	 */
	public static function getSpecialCityId()
	{
		return array(
			1  => '北京',
			2  => '天津',
			9  => '上海',
			22 => '重庆'	
		);
	}
	
	/**
	 * 获取某一级城市列表
	 * @param int $level
	 * @return array
	 */
	public function getAreaList($parent_id=0)
	{
		$data = array();
		$sql = "SELECT * FROM ".$this->tableName()." WHERE parent_id=".$parent_id;
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($result as $value)
		{
			$data[$value['id']] = $value['name'];
		}
		return $data;
	}
	
}