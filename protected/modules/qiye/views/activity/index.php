<?php
$this->breadcrumbs=array(
	'企业活动',
);

$this->menu=array(
	array('label'=>'创建活动','url'=>array('create')),
);

$this->title = '全部活动';
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'type'=>'striped bordered',
	'dataProvider'=>$dataProvider,
	'columns' => array(
		'title',
		array('header'=>'状态','type'=>'html','value'=>'time()>$data->end_time ? "<span class=\"label label-important\">结束</span>": "<span class=\"label label-success\">进行中</span>"'),
		array('name'=>'create_time','value'=>'date("y/m/d H:i:s",$data->create_time)'),
		array('name'=>'begin_time','value'=>'date("y/m/d H:i",$data->begin_time)'),
		array('name'=>'end_time','value'=>'date("y/m/d H:i",$data->end_time)'),
		array(
			'class'=>'CLinkColumn',
			'header'=>'已报名',
			'labelExpression'=>'ActivityMember::model()->countApplicants($data->id)',
			'urlExpression'=>'Yii::app()->controller->createUrl("applicants",array("id"=>Util::encode($data->primaryKey)))',
			'linkHtmlOptions'=>array('title'=>'查看报名')
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=>'操作',
			'viewButtonUrl'=>'Yii::app()->controller->createUrl("view",array("id"=>Util::encode($data->primaryKey)))',
			'updateButtonUrl'=>'Yii::app()->controller->createUrl("update",array("id"=>Util::encode($data->primaryKey)))',
			'template'=>'{view} {update}',
		)
	)
)); ?>
