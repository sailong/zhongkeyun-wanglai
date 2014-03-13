<?php
$this->breadcrumbs=array(
	$model->name,
);

$this->title = '查看用户';
?>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'type'=>'striped',
	'attributes'=>array(
		'name',
		'mobile',
		'email',
		'position',
		'company',
		'address',
		'company_url',
		'main_business',
		'supply',
		'demand',
		'weixin',
		'yixin',
		'laiwang',
		'qq',
		'hobby',
		'notes',
		'profile',
		'social_position',
		array(
			'name' => 'created_at',	
			'value'=>date('Y-m-d H:i:s', $model->created_at)
		),
		'wanglai_number',
	),
)); ?>
