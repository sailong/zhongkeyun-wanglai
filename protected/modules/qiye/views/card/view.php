<?php
$this->breadcrumbs=array(
	'企业名片'=>array('/'),
	$model->name,
);
$id = Util::encode($model->id);
$this->menu=array(
	array('label'=>'更改信息','url'=>array('update','id'=>$id)),
	array('label'=>'修改头像','url'=>array('photo','id'=>$id)),
);

$this->title = '查看名片';
?>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'type'=>'striped',
	'attributes'=>array(
		array('label'=>'企业名称','name'=>'name'),
		array('label'=>'客服电话','name'=>'mobile'),
		'email',
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
		'notes',
		'wanglai_number',
	),
)); ?>
