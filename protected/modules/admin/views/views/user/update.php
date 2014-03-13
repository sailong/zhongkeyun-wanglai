<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
			<div class="conArea">
				<div class="content-box">
					<div class="content-box-header">
						<h3><?php echo $this->nav;?>&nbsp;&nbsp;&nbsp;&nbsp;
						 <?php if(Yii::app()->user->getState('role')=='1') { ?>
						<a href="<?php echo $this->createUrl('index');?>">返回列表</a>
						<?php }?>
					</div>
					<div class="content-box-content">
					<?php $form=$this->beginWidget('CActiveForm',array(
														    'id'=>'ask-form',
														    'enableAjaxValidation'=>true,
														    'enableClientValidation'=>true,
            												'action' => $this->createUrl('updateDo'),
															'htmlOptions' => array(
																	'enctype'=>'multipart/form-data',
																	'onsubmit'=>'return checkForm();',
															),
															
						)); 
	                ?>
	                    <input type="hidden" name="User[uid]" value="<?php echo $model->uid;?>"/>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<?php 
							    $show = array('nickname','password');
								foreach ($model->attributes as $key=>$m)
								{
									if(!in_array($key,$show)) continue;
							?>
							<tr>
								<td align="right"><?php echo $form->label($model,$key); ?></td>
								<td align="left">
								<?php 
								if($model->uid && $key=='nickname')
								{
									echo $model->nickname;
								}else 
								{
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
								<td align="right"><label for="User_password">确认密码</label></td>
								<td align="left">
								
								<input class="inp1 nofocus" size="50" maxlength="50" name="rep_password" id="rep_password" type="text" />								</td>
								<td align="right"></td>
								<td align="left"></td>
							</tr>
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
	$('#User_password').val('');
	$('#rep_password').val('');
}); 
var uid = "<?php echo $model->uid;?>";
function checkForm()
{
	var pwd = $.trim($('#User_password').val());
	var rep_pwd = $.trim($('#rep_password').val());
	if(!pwd || !rep_pwd)
	{
		alert('密码 与 确认密码都不能为空！');
		return false;
	}
	if(pwd.length<6 || pwd.length>10)
	{
		alert('密码长度在6~10个字符之间！');
		return false;
	}
	if(pwd!=rep_pwd)
	{
		alert('两次密码输入不一致！');
		return false;	
	}
	return true;
}
</script>