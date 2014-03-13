<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
<div class="conArea">
	<div class="content-box">
		<div class="content-box-header">
			<h3><?php echo $this->nav;?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->createUrl('index');?>">返回列表</a></h3>
		</div>
		<div class="content-box-content">
			<?php $form=$this->beginWidget('CActiveForm',array(
				'id'=>'qiyeCard-form',
				'enableClientValidation'=>false,
				'errorMessageCssClass'=>'red',
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
			));
?>



	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
		<tr>
			<td width="25%"><label class="required" for="Member_name">企业名称 <span class="required">*</span></label></td>
			<td>
				<?php echo $form->textField($model,'name',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'name'); ?>		
			</td>
		</tr>
		
		<tr>
			<td width="25%"><?php echo $form->labelEx($model,'wanglai_number'); ?></td>
			<td>
				<?php echo $form->textField($model,'wanglai_number',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'wanglai_number'); ?>		
			</td>
		</tr>
		
		<tr>
			<td width="25%"><label class="required" for="Member_mobile">客服电话<span class="required">*</span></label></td>
			<td>
				<?php echo $form->textField($model,'mobile',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'mobile'); ?>		
			</td>
		</tr>
		
		<tr >
			<td ></td>
			<td>
				<div class="row buttons">
					<?php echo CHtml::submitButton('保存'); ?>
				</div>
			</td>
		</tr>
		
	</table>
	
	<?php $this->endWidget(); ?>
		</div>
	</div>
</div>