<?php $this->pageTitle='修改密码';
$this->breadcrumbs=array(
	'个人中心' => array('/user/profile'),
	'修改密码',
);
$this->menu=array(
    array('label'=>'编辑', 'url'=>array('edit')),
    array('label'=>'修改密码', 'url'=>array('changepassword')),
    array('label'=>'退出', 'url'=>array('/user/logout')),
);

$this->title = '修改密码';
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'changepassword-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
<fieldset>

	<p class="help-block note">带 <span class="required">*</span> 字段必须填写</p>
	
	<?php echo $form->passwordFieldRow($model,'oldPassword'); ?>
	
	<?php echo $form->passwordFieldRow($model,'password'); ?>
	
	<?php echo $form->passwordFieldRow($model,'verifyPassword'); ?>
	
	<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'label'=>'保存',
			)); ?>
	</div>
</fieldset>

<?php $this->endWidget(); ?>
	