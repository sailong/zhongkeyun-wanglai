<div class="brand">当前位置：首页 ></div>
<div class="conArea">
	<div class="content-box">
		<div class="content-box-header">
			
		</div>
		<div class="content-box-content">
		<?php if(Yii::app()->user->hasFlash('email')):?>
			<span class="red"><?php echo Yii::app()->user->getFlash('email');?></span>
		<?php endif;?>
			<?php $form=$this->beginWidget('CActiveForm',array(
					'id'=>'member-form'
				));
			?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
					<tr>
						<td width="10%"><label>邮箱：</label></td>
						<td>
							<input type="text" size="50" name="email" value="<?php echo $model->email;?>"/>多个邮箱用空格分开
						</td>
						<td width="30%">
							
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