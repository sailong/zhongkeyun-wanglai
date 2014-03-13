<?php $form=$this->beginWidget('CActiveForm',array(
		'id'=>'member-form',
		'enableClientValidation'=>true,
		'errorMessageCssClass'=>'red',
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
		'htmlOptions' => array(
			'enctype'=>'multipart/form-data'
		)
));
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
		<tr>
			<td ></td>
			<td>
				<div class="row buttons">
					<?php echo CHtml::submitButton('保存'); ?>
				</div>
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'name'); ?></td>
			<td>
				<?php echo $form->textField($model,'name',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'name'); ?>		
			</td>
		</tr>
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'weixin_openid'); ?></td>
			<td>
				<?php echo $form->textField($model,'weixin_openid',array('size'=>100)); ?>
			</td>
			<td width="%30">
				<?php echo $form->error($model,'weixin_openid'); ?>			
			</td>
		</tr>
	
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'mobile'); ?></td>
			<td>
				<?php echo $form->textField($model,'mobile',array('size'=>100)); ?>
							
			</td>
			<td width="30%">
				<?php echo $form->error($model,'mobile'); ?>
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'email'); ?></td>
			<td>
				<?php echo $form->textField($model,'email',array('size'=>100)); ?>
					
			</td>
			<td width="30%">
				<?php echo $form->error($model,'email'); ?>		
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'password'); ?></td>
			<td>
				<?php echo $form->passwordField($model,'password',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'password'); ?>	
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'repeat_password'); ?></td>
			<td>
				<?php echo $form->passwordField($model,'repeat_password',array('size'=>100)); ?>
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'position'); ?></td>
			<td>
				<?php echo $form->textField($model,'position',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'position'); ?>
			</td>
		</tr>
	
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'company'); ?></td>
			<td>
				<?php echo $form->textField($model,'company',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'company'); ?>
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'address'); ?></td>
			<td>
				<?php echo $form->textField($model,'address',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'address'); ?>	
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'company_url'); ?></td>
			<td>
				<?php echo $form->textField($model,'company_url',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'company_url'); ?>	
			</td>
		</tr>
	
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'main_business'); ?></td>
			<td>
				<?php echo $form->textArea($model,'main_business',array('cols'=>85)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'main_business'); ?>	
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'supply'); ?></td>
			<td>
				<?php echo $form->textField($model,'supply',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'supply'); ?>
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'demand'); ?></td>
			<td>
				<?php echo $form->textField($model,'demand',array('size'=>100)); ?>
			</td>
			<td width=“30%”>
				<?php echo $form->error($model,'demand'); ?>
			</td>
		</tr>
	
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'views'); ?></td>
			<td>
				<?php echo $form->numberField($model,'views',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'views'); ?>		
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'show_item'); ?></td>
			<td>
				<?php echo $form->checkBoxList($model,'show_item',Member::$hideOptions,array('size'=>100)); ?>
			</td>
		</tr>
		
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'weixin'); ?></td>
			<td>
				<?php echo $form->textField($model,'weixin',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'weixin'); ?>
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'yixin'); ?></td>
			<td>
				<?php echo $form->textField($model,'yixin',array('size'=>100)); ?>
			</td>
			<td width="10%">
				<?php echo $form->error($model,'yixin'); ?>	
			</td>
		</tr>
		
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'laiwang'); ?></td>
			<td>
				<?php echo $form->textField($model,'laiwang',array('size'=>100)); ?>
				<?php echo $form->error($model,'laiwang'); ?>			
			</td>
			<td width="30%">
				<?php echo $form->error($model,'laiwang'); ?>	
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'qq'); ?></td>
			<td>
				<?php echo $form->textField($model,'qq',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'qq'); ?>
			</td>
		</tr>
		
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'hobby'); ?></td>
			<td>
				<?php echo $form->textField($model,'hobby',array('size'=>100)); ?>
			</td>
			<td width="10%">
				<?php echo $form->error($model,'hobby'); ?>	
			</td>
		</tr>
		
		<tr >
			<td width="10%"><?php echo $form->labelEx($model,'notes'); ?></td>
			<td>
				<?php echo $form->textArea($model,'notes',array('cols'=>85)); ?>
						
			</td>
			<td width="%10">
				<?php echo $form->error($model,'notes'); ?>	
			</td>
		</tr>
		
		<tr >
			<td width="10%"><?php echo $form->labelEx($model,'avatar'); ?></td>
			<td>
				<?php echo $form->fileField($model,'avatar',array('size'=>100)); ?>
				<?php echo $form->hiddenField($model,'avatar');?>
			</td>
			<td>
				<?php echo $form->error($model,'avatar'); ?>	
			</td>
		</tr>
		<tr >
			<td width="10%"></td>
			<td>
				<?php echo CHtml::image(Helper::getImage($model->avatar),'用户头像', array('width' => 200, 'height' => 200)); ?>			
			</td>
		</tr>
		
		<tr >
			<td width="10%"><?php echo $form->labelEx($model,'profile'); ?></td>
			<td>
				<?php echo $form->textField($model,'profile',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'profile'); ?>
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'social_position'); ?></td>
			<td>
				<?php echo $form->textField($model,'social_position',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'social_position'); ?>	
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'is_vip'); ?></td>
			<td>
				<?php echo $form->dropDownList($model,'is_vip',array(0 => '不是', 1 => '是')); ?>	
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