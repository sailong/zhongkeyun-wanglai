<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
			<div class="conArea">
				<div class="content-box">
					<div class="content-box-header">
						<h3><a href="<?php echo $this->createUrl('index');?>">返回</a></h3>
		
					</div>
					<div class="content-box-content">
					<?php $form=$this->beginWidget('CActiveForm',array(
														    'id'=>'ask-form',
														    'enableAjaxValidation'=>true,
														    'enableClientValidation'=>true,
            												'action' => $this->createUrl('set'),
															'htmlOptions' => array(
																	'enctype'=>'multipart/form-data',
																	'onsubmit'=>'return checkForm();',
															),
															
						)); 
	                ?>
	                    <input type="hidden" name="id" value="<?php echo $uid;?>"/>
	                    <input type="hidden" name="setDo" value="1"/>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<?php 
							   
								foreach ($purviewList as $val)
								{
									$checked = is_array($purview['menu']) && $purview['menu'] && in_array($val['id'],$purview['menu']) ? 'checked="checked"':'';
							?>
							<tr>
								<td align="right">
								<?php 
								if ($val['id']!='Info')
								{
								?>
								<input type="checkbox" name="data[]" value="<?php echo $val['id'];?>" id="<?php echo $val['id'];?>" <?php echo $checked;?>>
								<?php }?>
								</td>
								<td align="left"><?php echo $val['name'];?></td>
								<td align="left">
								<?php if ($val['id']=='Info') {
									foreach ($val['cates'] as $cate)
									{
										$checked = is_array($purview['infoCate']) && in_array($cate->id,$purview['infoCate']) ? 'checked="checked"':'';
										echo '<input type="checkbox" name="infoCate[]" value="'.$cate->id.'" '.$checked.'>&nbsp;&nbsp;'.$cate->title.'&nbsp;&nbsp;';
									}
									
								}?></td>
								<td align="left">&nbsp;</td>
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

function checkForm()
{
	
	return true;
}
</script>