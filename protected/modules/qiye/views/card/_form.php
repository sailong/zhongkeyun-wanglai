<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'member-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="help-block note">带 <span class="required">*</span> 字段为必填</p>

	<?php echo $form->textFieldRow($model,'name'); ?>

	<?php echo $form->textFieldRow($model,'mobile'); ?>

	<?php echo $form->textFieldRow($model,'email'); ?>

	<?php echo $form->textFieldRow($model,'company'); ?>

	<?php echo $form->textFieldRow($model,'address'); ?>

	<?php echo $form->textFieldRow($model,'company_url'); ?>

	<?php echo $form->textFieldRow($model,'main_business'); ?>

	<?php echo $form->textFieldRow($model,'supply'); ?>

	<?php echo $form->textFieldRow($model,'demand'); ?>

	<?php echo $form->textFieldRow($model,'weixin'); ?>

	<?php echo $form->textFieldRow($model,'yixin'); ?>

	<?php echo $form->textFieldRow($model,'laiwang'); ?>

	<?php echo $form->textFieldRow($model,'qq'); ?>

	<?php echo $form->textFieldRow($model,'notes'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'保存',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
