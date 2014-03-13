<?php
$id = Util::encode($model->id);
$this->breadcrumbs=array(
	'企业微群'=>array('index'),
	$model->title=>array('view','id'=>$id),
	'成员'
);

$this->menu=array(
	array('label'=>'企业微群','url'=>array('index')),
	array('label'=>'创建微群','url'=>array('create')),
	array('label'=>'查看微群','url'=>array('view','id'=>$id)),
	array('label'=>'编辑微群','url'=>array('update','id'=>$id)),
);

$this->title = '查看成员';
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
		'dataProvider'=>$dataProvider,
		'type'=>'striped bordered',
		'columns' => array(
				'id','name','mobile','email','position',
				array(
					'class'=>'bootstrap.widgets.TbButtonColumn',
					'template' => '{view}',
					'viewButtonUrl' => 'Yii::app()->controller->createUrl("member/view",array("id"=>Util::encode($data->primaryKey)))',
					'viewButtonOptions'=>array('class'=>'btn btn-mini btn-success','target'=>'_blank')
				)
		)
)); 

?>

