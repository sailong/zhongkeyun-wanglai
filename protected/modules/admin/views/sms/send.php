<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
			<div class="conArea">
				<div class="content-box">
					<div class="content-box-content">
	
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							
							<tr>
								<td align="right"><label for="User_password">手机号码</label></td>
								<td align="left">
								<input class="inp1 nofocus" size="120" maxlength="50" name="mobile" id="mobile" type="text" placeholder="多个手机号用#隔开,如：15299999999#15611111111"/>	
								</td>
								<td align="right"></td>
								<td align="left"></td>
							</tr>
							
							
							<tr>
								<td align="right"><label for="User_password">短信内容</label></td>
								<td align="left">
								
								
								<input class="inp1 nofocus" size="120" maxlength="250" name="content" id="content" type="text" />	
								</td>
								<td align="right"></td>
								<td align="left"></td>
							</tr>
							<tr>
								<td align="right"></td>
								<td align="left"><input name="" type="submit" value="提&nbsp;&nbsp;交" class="btn1" onclick="send();" id="sub_button"/></td>
								<td align="right"></td>
								<td align="left"></td>
							</tr>

						</table>
				
						
					</div>
				</div>
			</div>
<script>					
function send()
{
	var mobile = $.trim($('#mobile').val());
	var content = $.trim($('#content').val());
	if(!mobile || !content)
	{
		alert('手机号与短信内容都不能为空！');
		return false;
	}
	$('#sub_button').attr('disabled',true);
	$.ajax({
		  type: 'POST',
		  async:false,
		  url: '<?php echo $this->createUrl('SendDo');?>',
		  data: {"mobile": mobile,"content":content},
		  success: function(data){
				     alert(data.msg); 
				   },
		  dataType: "json"
		});
	 $('#sub_button').attr('disabled',false);
	 $('#mobile').val('');
	return false;
}
</script>