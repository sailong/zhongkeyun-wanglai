<?php
$this->breadcrumbs=array(
	'用户'=>array('admin'),
	UserModule::t('Create'),
);

$this->menu=array(
    array('label'=>'管理用户', 'url'=>array('admin')),
    array('label'=>'管理字段', 'url'=>array('profileField/admin')),
    array('label'=>'用户列表', 'url'=>array('/user')),
);
?>
<h2>创建用户</h2>

<?php
	echo $this->renderPartial('_form', array('model'=>$model,'profile'=>$profile));
?>