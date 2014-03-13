<?php
$id = Util::encode($model->id);
$this->breadcrumbs=array(
	'企业活动'=>array('index'),
	$model->title=>array('view','id'=>$id),
	'报名用户'
);

$this->menu=array(
	array('label'=>'企业活动','url'=>array('index')),
	array('label'=>'创建活动','url'=>array('create')),
	array('label'=>'查看活动','url'=>array('view','id'=>$id)),
	array('label'=>'编辑活动','url'=>array('update','id'=>$id)),
);

$this->title = '报名成员';
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
		'dataProvider'=>$dataProvider,
		'type'=>'striped bordered',
		'columns' => array(
				'id','name','mobile','email','position',
				array(
					'class'=>'bootstrap.widgets.TbButtonColumn',
					'header'=>'操作',
					'template' => '{view}',
					'viewButtonUrl' => 'Yii::app()->controller->createUrl("member/view",array("id"=>Util::encode($data->primaryKey)))',
					'viewButtonOptions'=>array('class'=>'btn btn-mini btn-success','target'=>'_blank')
				)
		)
)); 

?>

