<script language="javascript" type="text/javascript" src="/static/My97DatePicker/WdatePicker.js"></script>
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
				    'id'=>'Activity_detail',   //Textarea id
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
		'action'=>$this->createUrl('updateDo'),
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
					<?php echo $form->hiddenField($model,'id');?>
					<?php echo $form->hiddenField($model,'create_mid');?>
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
		<!--  
		<tr>
			<td width="10%"><?php //echo $form->labelEx($model,'type'); ?></td>
			<td>
				<?php //echo $form->dropDownList($model,'type',Activity::model()->getActivityTypes()); ?>	
			</td>
		</tr>
		-->
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'verify'); ?></td>
			<td>
				<?php echo $form->dropDownList($model,'verify',array(Activity::APPLY_VIRIFY_WITHOUT=>'公开',Activity::APPLY_VIRIFY_WITH=>'私密')); ?>	
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'province'); ?></td>
			<td>
				<?php echo $form->dropDownList($model,'province',$this->getProvince(),array(
																			    'ajax' => array(
																			    'type'=>'GET',
																			    'url'=>$this->createUrl('GetAreaList'),
																			    'update'=>'#Activity_area',
																			    'data'=>array('id'=>'js:$("#Activity_province").val()')
																			)));
				 ?>	
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'area'); ?></td>
			<td>
				<?php echo $form->dropDownList($model,'area',array()); ?>	
			</td>
		</tr>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'district'); ?></td>
			<td>
				<?php echo $form->textField($model,'district',array('size'=>100)); ?>
					
			</td>
			<td width="30%">
				<?php echo $form->error($model,'district'); ?>		
			</td>
		</tr>
		
		<tr>
			<td width="10%">开始时间</td>
			<td>
				<input class="Wdate inp1 nofocus" size="25"  type="text" id="Activity_begin_time" value="<?php echo $model->begin_time ? date('Y-m-d H:i',$model->begin_time):'';?>" name="Activity[begin_time]" onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm'})"/>
			</td>
			<td width="30%">
				
			</td>
		</tr>

			<tr>
			<td width="10%">结束时间</td>
			<td>
				<input class="Wdate inp1 nofocus" size="25"  type="text" id="Activity_end_time" value="<?php echo $model->end_time ? date('Y-m-d H:i',$model->end_time):'';?>" name="Activity[end_time]" onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm'})"/>
			</td>
			<td width="30%">
				
			</td>
		</tr>
	
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'detail'); ?></td>
			<td>
				<?php echo $form->textArea($model,'detail'); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'detail'); ?>	
			</td>
		</tr>
	
		
		<?php if($model->create_mid == Member::SERVICE_MEMBER_ID):?>
			
			
			<tr>
				<td width="10%"><label>指定创建人</label></td>
				<td>
				<?php 
			
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
							'name'=>'create_mid',
							'sourceUrl' => $this->createUrl('/admin/activity/getCreater'),
							//'source'=>array('ac1', 'ac2', 'ac3'),
							// additional javascript options for the autocomplete plugin
							'options'=>array(
								'minLength'=>'1',
								
							),
							'htmlOptions'=>array(
								'style'=>'height:20px;',
								'size' => 100
							),
					));
			
				?>
				
				</td>
			</tr>
		<?php endif;?>
		
		<tr>
			<td width="10%"><?php echo $form->labelEx($model,'max'); ?></td>
			<td>
				<?php echo $form->textField($model,'max',array('size'=>100,'placeholder'=>'不写没限制')); ?>
			</td>
			<td width="30%">
				<?php echo $form->error($model,'max'); ?>		
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

<script>
								
$(function() {
	var province_id = "<?php echo $model->province;?>";
	var city_id = "<?php echo $model->area;?>";
	if(province_id)
	{
		getCity(province_id);
		if(city_id)
		{
			$('#Activity_area').val(city_id);
		}
	}
	
}); 


function getCity(id)
{
	if(!id) return;
	var url = "<?php echo $this->createUrl('activity/GetAreaList',array('id'=>'0')); ?>";
	var html=$.ajax({url:url+id,async:false});
	$('#Activity_area').html(html.responseText);
	
}
</script>