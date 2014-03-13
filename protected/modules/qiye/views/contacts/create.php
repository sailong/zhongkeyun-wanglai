<?php
$this->breadcrumbs=array(
	'企业微群'=>array('index'),
	'创建',
);

$this->menu=array(
	array('label'=>'企业微群','url'=>array('index')),
);

$this->title = '创建微群';
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>