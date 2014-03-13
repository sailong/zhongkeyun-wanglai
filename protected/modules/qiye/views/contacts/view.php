<?php
$id = Util::encode($model->id);
$this->breadcrumbs=array(
	'企业微群'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'企业微群','url'=>array('index')),
	array('label'=>'创建微群','url'=>array('create')),
	array('label'=>'编辑微群','url'=>array('update','id'=>$id)),
	array('label'=>'查看群友','url'=>array('members','id'=>$id)),
);

$this->title = '查看微群';
?>

<?php if(Yii::app()->user->hasFlash('contactsMessage')): ?>
<div class="alert alert-warning">
	<a data-dismiss="alert" class="close">×</a>
	<?php echo Yii::app()->user->getFlash('contactsMessage'); ?>
</div>
<?php endif; ?>


<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'type'=>'striped',
	'attributes'=>array(
		'title','description',
		array('name'=>'type','value'=>Contacts::types($model->type)),
		array('name'=>'privacy', 'value'=>($model->type == Contacts::PRIVACY_PRIVATE ? '私密' : '公开')),
		array('name'=>'create_time', 'value'=>date('Y-m-d H:i:s', $model->create_time)),
		array(
			'label'=>'成员人数',
			'value'=>ContactsMember::model()->countPass($model->id)
		)
	),
)); ?>
