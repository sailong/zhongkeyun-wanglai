<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
			<div class="conArea">
				<div class="content-box">
					<div class="content-box-content">
						
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							
							<tr>
								<td align="right"><label for="User_password">手机号码</label></td>
								<td align="left">
									<input class="inp1 nofocus" size="120" maxlength="50" name="mobile" id="mobile" type="text"/>	
								</td>
								<td align="right"></td>
								<td align="left"></td>
							</tr>
							<tr>
								<td align="right"><label for="User_password">验证码</label></td>
								<td align="left">
									<input class="inp1 nofocus" size="120" maxlength="50" name="code" id="mobile" type="text" disabled="disabled"/>	
								</td>
								<td align="right"></td>
								<td align="left"></td>
							</tr>
							
							<tr>
								<td align="right"></td>
								<td align="left">
									<input type="button" value="提&nbsp;&nbsp;交" class="btn1" id="search"/></td>
								<td align="right"></td>
								<td align="left"></td>
							</tr>

						</table>
					</div>
				</div>
			</div>
<script>

$(document).ready(function(){
	(function(){
		var url = "<?php $this->createUrl('admin/code/index');?>";
		$("#search").bind("click",function(){
			var mobile = $.trim($("input[name=mobile]").val());
			$.post(url,{mobile:mobile},function(data){
				if(data.status == 1)
					$("input[name=code]").val(data.msg);
				else
					alert(data.msg);
			},'json');
		});
	})()

	
})



</script>				