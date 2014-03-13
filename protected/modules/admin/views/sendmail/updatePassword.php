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
	                    <input type="hidden" name="Member[id]" value="<?php echo $model->id;?>"/>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<?php 
							 
								
								$show = array('name','password','is_vip');
								foreach ($model->attributes as $key=>$m)
								{
									if(!in_array($key,$show)) continue;
							?>
							<tr>
								<td align="right"><?php echo $form->label($model,$key); ?></td>
								<td align="left">				
								<?php
										$placeholder='';
										if($key=='password') 
										{
											$model->password='';
											$placeholder='不修改则留空';
										}
										if($key=='name')
										{
											echo $m;
										}elseif ($key=='is_vip')
										{
											//echo $form->checkBoxList($model,$key,array('1'=>'',));
											$checked= $m ? 'checked="checked"' : '';
											echo '<input type="checkbox" name="Member[is_vip]" value="1" '.$checked.'/>';
										}else 
										{
											
										 	 echo $form->textField($model,$key,array(
										 			'class' => 'inp1 nofocus',
										 			'size' => '50',
										 			'maxlength' => 50,
													'placeholder'=>$placeholder,
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