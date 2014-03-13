<?php
$id = Util::encode($model->id);
$this->breadcrumbs=array(
	'企业活动'=>array('index'),
	$model->title=>array('view','id'=>$id),
	'更新',
);

$this->menu=array(
	array('label'=>'企业活动','url'=>array('index')),
	array('label'=>'创建活动','url'=>array('create')),
	array('label'=>'活动详情','url'=>array('view','id'=>$id)),
	array('label'=>'查看报名','url'=>array('applicants','id'=>$id))
);

$this->title = '编辑活动';
?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>