<?php
$this->breadcrumbs=array(
	'用户'=>array('admin'),
	$model->username,
);


$this->menu=array(
    array('label'=>'创建用户', 'url'=>array('create')),
    array('label'=>'更新用户', 'url'=>array('update','id'=>$model->id)),
    array('label'=>'删除用户', 'url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>UserModule::t('Are you sure to delete this item?'))),
    array('label'=>'管理用户', 'url'=>array('admin')),
    array('label'=>'管理字段', 'url'=>array('profileField/admin')),
    array('label'=>'用户列表', 'url'=>array('/user')),
);
?>
<h2>查看用户“<?php echo $model->username ; ?>”</h2>

<?php
 
	$attributes = array(
		'id',
		'username',
	);
	
	$profileFields=ProfileField::model()->forOwner()->sort()->findAll();
	if ($profileFields) {
		foreach($profileFields as $field) {
			array_push($attributes,array(
					'label' => UserModule::t($field->title),
					'name' => $field->varname,
					'type'=>'raw',
					'value' => (($field->widgetView($model->profile))?$field->widgetView($model->profile):(($field->range)?Profile::range($field->range,$model->profile->getAttribute($field->varname)):$model->profile->getAttribute($field->varname))),
				));
		}
	}
	
	array_push($attributes,
		'password',
		'email',
		'activkey',
		'create_at',
		'lastvisit_at',
		array(
			'name' => 'superuser',
			'value' => User::itemAlias("AdminStatus",$model->superuser),
		),
		array(
			'name' => 'status',
			'value' => User::itemAlias("UserStatus",$model->status),
		)
	);
	
	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>$attributes,
	));
	

?>
