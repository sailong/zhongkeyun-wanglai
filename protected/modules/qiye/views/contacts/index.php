<?php
$this->breadcrumbs=array(
	'企业微群',
);

$this->menu=array(
	array('label'=>'创建微群','url'=>array('create')),
);

$this->title = '企业微群';
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'dataProvider'=>$dataProvider,
	'type'=>'striped bordered',
	'columns' => array(
		'title',
		array('name'=>'type','value'=>'Contacts::types($data->type)'),
		array('name'=>'create_time','value'=>'date("Y-m-d H:i:s",$data->create_time)'),
		array(
			'class'=>'CLinkColumn',
			'header'=>'人数',
			'labelExpression'=>'ContactsMember::model()->countPass($data->id)',
			'urlExpression'=>'Yii::app()->controller->createUrl("members",array("id"=>Util::encode($data->primaryKey)))',
			'linkHtmlOptions'=>array('title'=>'查看成员')
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'viewButtonUrl'=>'Yii::app()->controller->createUrl("view",array("id"=>Util::encode($data->primaryKey)))',
			'updateButtonUrl'=>'Yii::app()->controller->createUrl("update",array("id"=>Util::encode($data->primaryKey)))',
			'template'=>'{view} {update}',
			'header'=>'操作',
		)
	)
)); ?>
