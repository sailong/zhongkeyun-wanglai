<?php
$this->breadcrumbs=array(
	'活动'=>array('index'),
	'创建',
);

$this->menu=array(
	array('label'=>'全部活动','url'=>array('index')),
);

$this->title='创建活动';
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>