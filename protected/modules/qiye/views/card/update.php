<?php
$this->breadcrumbs=array(
	'企业名片'=>array('/'),
	$model->name=>array('view'),
	'更新',
);
$id = Util::encode($model->id);
$this->menu=array(
	array('label'=>'查看名片','url'=>array('view','id'=>$id)),
	array('label'=>'更改资料','url'=>array('update','id'=>$id)),
	array('label'=>'更换头像','url'=>array('photo','id'=>$id)),
);

$model->id = $id;
$this->title='更改名片';

?>
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
