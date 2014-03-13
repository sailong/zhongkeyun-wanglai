<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
	<div class="conArea">
			<div class="searchBox">
				<span class="txs">
					<a href="/admin/qiye">+创建企业名片+</a>
				</span>
			</div>

<div class="content-box">

<?php $this->widget('zii.widgets.grid.CGridView',array(
	'dataProvider'=>$dataProvider,
	'htmlOptions'=>array('class'=>'content-box-content'),
	'itemsCssClass'=>'table1',
	//'pager'=>array('class'=>'application.modules.admin.components.Yspage'),
	'columns' => array(
		array('header'=>'ID','name'=>'id'),
		'username',
		array('header'=>'创建时间','name'=>'create_at'),
		array('header'=>'最近登录时间','name'=>'lastvisit_at'),
		array(
			'header'=>'名片链接',
			'class'=>'CLinkColumn',
			'label'=>'名片链接',
			'urlExpression'=>'Yii::app()->controller->createUrl("card/view/",array("id"=>$data->mid))'
		),
		array(
			'header'=>'更改信息',
			'class'=>'CLinkColumn',
			'label'=>'更改信息',
			'urlExpression'=>'Yii::app()->controller->createUrl("update",array("id"=>$data->primaryKey))'
		),
		
	)
	));
?>




</div>

</div>