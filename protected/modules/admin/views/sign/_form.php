<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
				    'id'=>'SignActivity_content',   //Textarea id
				    'language'=>'zh_CN', // example: spanish
				    // Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
				    'items' => array(
						'langType'=>'zh_CN', // example: en  (INVOKE)
				      	'width'=>'700px',
						'height'=>'300px',
						'themeType'=>'simple',
						'allowImageUpload'=>true,
						'items'=>array(
							'formatblock','fontname', 'fontsize','lineheight','|', 'forecolor', 'hilitecolor', 'bold', 'italic',
							'underline', 'removeformat', '|', 'justifyleft', 'justifycenter',
							'justifyright', 'insertorderedlist','insertunorderedlist', '|',
							'emoticons', 'image', 'link','|','clearhtml','quickformat','baidumap','source','undo', 'redo'
						),
				        	
					)
				)
			); ?>


<?php $form=$this->beginWidget('CActiveForm',array(
		'id'=>'member-form',
		'enableClientValidation'=>false,
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
			<td width="10%"><?php echo $form->labelEx($model,'title'); ?></td>
			<td>
				<?php echo $form->textField($model,'title',array('size'=>100)); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'title'); ?>		
			</td>
		</tr>
		<tr>
			<td width="10%">
				<label><span class="required">*</span>发布人</label>
			</td>
			<td>
				<?php 
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
							'name'=>'publish_uids',
							'sourceUrl' => $this->createUrl('/admin/sign/GetCreater'),
                            'value'=>$create_mids,
							//'source'=>array('ac1', 'ac2', 'ac3'),
							// additional javascript options for the autocomplete plugin
							'options'=>array(
								'minLength'=>'2',
								
							),
							'htmlOptions'=>array(
								'style'=>'height:20px;',
								'size' => 100
							),
					));
			
				?>				
			</td>
			<td width="30%">
						
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'content'); ?></td>
			<td>
				<?php echo $form->textArea($model,'content'); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'content'); ?>	
			</td>
		</tr>
	
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'img'); ?></td>
			<td>
				<?php echo $form->fileField($model,'img'); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'img'); ?>	
			</td>
		</tr>
		
		<?php if(!empty($model->img)):?>
		<tr>
			<td width="10%"><label>原图片</label></td>
			<td>
				<img src="/<?php echo $model->img; ?>" height="100">
			</td>
			<td width="30%">
				
			</td>
		</tr>
		
		
		<?php endif;?>
		
		
		
		<tr>
			<td ></td>
			<td>
				<div class="row buttons">
					<?php echo CHtml::submitButton('保存'); ?>
				</div>
			</td>
		</tr>
	
	</table>
	
<?php $this->endWidget(); ?>
