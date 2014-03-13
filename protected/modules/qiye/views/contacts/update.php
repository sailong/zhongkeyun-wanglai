<?php
$id = Util::encode($model->id);
$this->breadcrumbs=array(
	'企业微群'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'编辑',
);

$this->menu=array(
	array('label'=>'企业微群','url'=>array('index')),
	array('label'=>'创建微群','url'=>array('create')),
	array('label'=>'查看微群','url'=>array('view','id'=>$id)),
	array('label'=>'查看群友','url'=>array('members','id'=>$id)),
);

$this->title = '编辑微群';
?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>