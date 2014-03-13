<?php $this->pageTitle='编辑';
$this->breadcrumbs=array(
	'个人中心'=>array('profile'),
	'编辑',
);
$this->menu=array(
    array('label'=>'编辑', 'url'=>array('edit')),
    array('label'=>'修改密码', 'url'=>array('changepassword')),
    array('label'=>'退出', 'url'=>array('/qiye/logout')),
);

$this->title = '编辑';
?>


<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'profile-form',
	'enableClientValidation'=>true,
	'focus'=>array($model,'username'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
<fieldset>

	<p class="help-block note">带 <span class="required">*</span> 字段必须填写</p>
	
	<?php echo $form->textFieldRow($model,'username'); ?>
	
	<?php echo $form->textFieldRow($model,'email'); ?>
	
	<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'label'=>'保存',
			)); ?>
	</div>
</fieldset>

<?php $this->endWidget(); ?>
	
