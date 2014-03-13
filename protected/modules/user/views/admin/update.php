<?php
$this->breadcrumbs=array(
	'用户'=>array('admin'),
	$model->username=>array('view','id'=>$model->id),
	'更新'
);
$this->menu=array(
    array('label'=>'创建用户', 'url'=>array('create')),
    array('label'=>'查看用户', 'url'=>array('view','id'=>$model->id)),
    array('label'=>'管理用户', 'url'=>array('admin')),
    array('label'=>'管理字段', 'url'=>array('profileField/admin')),
    array('label'=>'用户列表', 'url'=>array('/user')),
);
?>

<h2>更新用户“<?php echo $model->username; ?>”</h2>

<?php
	echo $this->renderPartial('_form', array('model'=>$model,'profile'=>$profile));
?>