<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
<div class="conArea">
	<div class="content-box">
		<div class="content-box-header">
			<h3><?php echo $this->nav;?>&nbsp;&nbsp;&nbsp;&nbsp;</h3>
		</div>
		<div class="content-box-content">
		<?php $form=$this->beginWidget('CActiveForm',array(
						'id'=>'password-form',
														
					)); 
                ?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
				<tr>
					<td align="right"><label>用户名</label></td>
					<td align="left">
						<input class="inp1 focus" size="50" maxlength="50" name="User[username]" type="text" />								</td>
					<td align="right"></td>
					<td align="left"></td>
				</tr>
				
				<tr>
					<td align="right"><label for="User_password">输入密码</label></td>
					<td align="left">
						<input class="inp1 nofocus" size="50" maxlength="50" name="User[password]" type="password" />								</td>
					<td align="right"></td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="right"><label for="User_password">确认密码</label></td>
					<td align="left">
					
					<input class="inp1 nofocus" size="50" maxlength="50" name="User[repeatPassword]" type="password" />								</td>
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