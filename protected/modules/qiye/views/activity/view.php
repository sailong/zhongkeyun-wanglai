<?php
$id = Util::encode($model->id);
$this->breadcrumbs=array(
	'企业活动'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'全部活动','url'=>array('index')),
	array('label'=>'创建活动','url'=>array('create')),
	array('label'=>'编辑活动','url'=>array('update','id'=>$id)),
	array('label'=>'查看报名','url'=>array('applicants','id'=>$id))
);

$this->title = '查看活动';
?>

<?php if(Yii::app()->user->hasFlash('activityMessage')): ?>
<div class="alert alert-warning">
	<a data-dismiss="alert" class="close">×</a>
	<?php echo Yii::app()->user->getFlash('activityMessage'); ?>
</div>
<?php endif; ?>


<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'type'=>'striped',
	'attributes'=>array(
		'title',
		array(
			'label' => '举办城市',
			'value'  => $model->provinceName->name .'  '. $model->areaName->name
		),
		array(
			'name' => 'begin_time',
			'value' => (date('Y-m-d H:i',$model->begin_time))
		),
		array(
			'name' => 'end_time',
			'value' => (date('Y-m-d H:i',$model->end_time))
		),
		array(
			'name'=>'max',
			'value'=>$model->max > 0 ? $model->max : '无限制'
		),
		array(
			'name' => 'detail',
			'type' => 'html'
		),
		array(
			'name' => 'create_time',
			'value' => (date('Y-m-d H:i:s',$model->create_time))
		),
		array(
			'label' => '报名人数',
			'value' => ActivityMember::model()->countApplicants($model->id)
		)
	),
)); ?>
