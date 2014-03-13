<?php
$this->breadcrumbs=array('用户');

if(UserModule::isAdmin()) {
	$this->layout='//layouts/qiye/column2';
	$this->menu=array(
	    array('label'=>'用户管理', 'url'=>array('/user/admin')),
	    array('label'=>'字段管理', 'url'=>array('profileField/admin')),
	);
}
?>

<h1>用户列表</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
			'name' => 'username',
			'type'=>'raw',
			'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
		),
		'create_at',
		'lastvisit_at',
	),
)); ?>
