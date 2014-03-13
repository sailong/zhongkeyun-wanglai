<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'contacts-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="help-block note">带 <span class="required">*</span> 字段必须填写</p>

	<?php echo $form->textFieldRow($model,'title'); ?>

	<?php echo $form->textFieldRow($model,'description'); ?>

	<?php echo $form->dropDownListRow($model,'type',Contacts::types()); ?>

	<?php echo $form->dropDownListRow($model,'privacy',array(1=>'公开',2=>'私密')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? '创建' : '保存',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
