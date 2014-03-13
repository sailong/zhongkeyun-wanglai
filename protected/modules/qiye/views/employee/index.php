<?php
$this->breadcrumbs=array(
	'企业员工',
);

$this->title = '企业员工';
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'dataProvider'=>$dataProvider,
	'type'=>'striped bordered',
	'columns'=>array(
		'id','name','mobile','email','position','wanglai_number',
		array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
				'header'=>'操作',
				'template' => '{view}',
				'viewButtonUrl' => 'Yii::app()->controller->createUrl("member/view",array("id"=>Util::encode($data->primaryKey)))',
				'viewButtonOptions'=>array('class'=>'btn btn-mini btn-success','target'=>'_blank')
			)	

	)
)); ?>
