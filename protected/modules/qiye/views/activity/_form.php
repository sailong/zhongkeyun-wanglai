<?php 
$scriptFile = '/static/My97DatePicker/WdatePicker.js';
Yii::app()->clientScript->registerScriptFile($scriptFile);

$script = <<<EOF
$("#Activity_begin_time,#Activity_end_time").bind("focus",function(){
	WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm'});
})

EOF;
Yii::app()->clientScript->registerScript('datatime',$script);

?>
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
				    'id'=>'Activity_detail',   //Textarea id
				    'language'=>'zh_CN', // example: spanish
				    // Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
				    'items' => array(
						'langType'=>'zh_CN', // example: en  (INVOKE)
				      	'width'=>'100%',
						'height'=>'250px',
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

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'activity-form',
	'enableClientValidation'=>true,
	'focus'=>array($model,'title'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
	
	<p class="help-block note">带 <span class="required">*</span> 字段必须填写</p>

	<fieldset>
		<?php echo $form->textFieldRow($model,'title',array('maxlength'=>50)); ?>
	
		<?php echo $form->dropDownListRow($model,'province',District::model()->getAreaList(),
													array(
															'empty'=>'请选择省市',
															'ajax'=>array(
															'type'=>'GET',
															'url'=>$this->createUrl('getCity'),
															'update'=>'#Activity_area',
															'data'=> array('provinceId'=>'js:$("#Activity_province").val()')																																											
														)
	
			)); ?>
		<?php if($model->isNewRecord):?>
			<?php echo $form->dropDownListRow($model,'area',array(),array('empty'=>'请选择市区')); ?>
		<?php else:?>
			<?php echo $form->dropDownListRow($model,'area',District::model()->getAreaList($model->province),array('class'=>'span5','empty'=>'请选择市区')); ?>
		<?php endif;?>
		<?php echo $form->textFieldRow($model,'begin_time',array('class'=>'Wdate','maxlength'=>10)); ?>
	
		<?php echo $form->textFieldRow($model,'end_time',array('class'=>'Wdate','maxlength'=>10)); ?>
	
		<?php echo $form->textAreaRow($model,'detail',array('class'=>'span5','maxlength'=>1000)); ?>
		
		<?php echo $form->textFieldRow($model,'max',array('maxlength'=>10,'placeholder'=>'不填无限制')); ?>
		
		<?php echo $form->dropDownListRow($model,'verify',array(Activity::APPLY_VIRIFY_WITHOUT=>'公开',Activity::APPLY_VIRIFY_WITH=>'私密')); ?>
		
	
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'label'=>$model->isNewRecord ? '创建' : '保存',
			)); ?>
		</div>
	</fieldset>
<?php $this->endWidget(); ?>
