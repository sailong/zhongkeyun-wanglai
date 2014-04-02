<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
<div class="conArea">
	<div class="content-box">
		<div class="content-box-header">
			<h3><?php echo $this->nav;?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->createUrl('index');?>">返回列表</a></h3>
		</div>
		<div class="content-box-content">
		<h2>代报名参加活动"<?php echo $model->title;?>"</h2>
		<?php if(Yii::app()->user->hasFlash('apply')):?>
			<span style="color:red"><?php echo Yii::app()->user->getFlash('apply');?></span>
		<?php endif;?>
		
		<?php $form=$this->beginWidget('CActiveForm',array(
		'id'=>'member-form',
		'enableClientValidation'=>false,
		'errorMessageCssClass'=>'red',
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
		
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
				<?php 
				
				for($i=1; $i<11; $i++)
				{
					echo '<tr>
					<td width="10%"><label>姓名:</label></td>
					<td>';
					
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
							'name'=>'Members[]',
							'id'=>'Members_'.$i,
							'sourceUrl' => $this->createUrl('/admin/activity/getCreater'),
							//'source'=>array('ac1', 'ac2', 'ac3'),
							// additional javascript options for the autocomplete plugin
							'options'=>array(
									'minLength'=>'2',
					
							),
							'htmlOptions'=>array(
									'style'=>'height:20px;',
									'size' => 100,
									'placeholder' => '输入姓名+空格会自动提示该姓名下的用户信息'
							),
					));

					echo '</td>
						</tr>';
					
				}
				
				?>
				
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
		</div>
	</div>
</div>