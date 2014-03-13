<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
			<div class="conArea">
				<div class="content-box">
					<div class="content-box-header">
						<h3><?php echo $this->nav;?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->createUrl('index');?>">返回列表</a>
					</div>
					<div class="content-box-content">
					<?php $form=$this->beginWidget('CActiveForm',array(
														    'id'=>'ask-form',
														    'enableAjaxValidation'=>true,
														    'enableClientValidation'=>true,
            												'action' => $this->createUrl('updateDo'),
															'htmlOptions' => array(
																	'enctype'=>'multipart/form-data'
															)
						)); 
	                ?>
	                    <input type="hidden" name="Partner[id]" value="<?php echo $model->id;?>"/>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<?php 
							    $notShow = array('id','weight','created_at');
								foreach ($model->attributes as $key=>$m)
								{
									if(in_array($key,$notShow)) continue;
							?>
							<tr>
								<td align="right"><?php echo $form->label($model,$key); ?></td>
								<td align="left">
								
								<?php 
								
								if($key=='image_path')
								{
									echo '<input type="file" name="image_path" id="file_upload" />';
									if($m)
									{
										echo '<img src="'.Helper::getImage($m).'" width="153" height="74"/>';
									}
								}elseif ($key=='cate_id')
								{
								 	echo '<select name="Partner[cate_id]" id="cate_id">';
								 	
								 	foreach($cate as $val)
								 	{
								 		echo '<option value="'.$val->id.'">'.$val->title.'</option>';
								 	}
								 	echo '</select>';
								 }else{
									
								 	echo $form->textField($model,$key,array(
								 			'class' => 'inp1 nofocus',
								 			'size' => '50',
								 			'maxlength' => 50,
								 	));
								}
								?>
								</td>
								<td align="right"></td>
								<td align="left"></td>
							</tr>
							<?php }?>
							<tr>
								<td align="right"></td>
								<td align="left"><input name="" type="submit" value="提&nbsp;&nbsp;交" class="btn1" /></td>
								<td align="right"></td>
								<td align="left"></td>
							</tr>

						</table>
						
						<?php $this->endWidget(); ?>
						
					</div>
				</div>
			</div>
<script>
								
$(function() {
	var cate_id = "<?php echo $model->cate_id;?>";
	if(cate_id) $('#cate_id').val(cate_id);
}); 

</script>