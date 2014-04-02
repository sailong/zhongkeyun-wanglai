
	<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
	<div class="conArea">
		<div class="content-box">
			<div class="content-box-header">
			<h3>邮箱详细&nbsp;&nbsp;&nbsp;&nbsp;<a href="/admin/Sendmail/index">返回列表</a></h3>
			</div>
			<div class="content-box-content">
				<table class="table1" border="0" cellpadding="0" cellspacing="0" id="yw0">
					<tr class=""><th>标题</th><td><?php echo $info->title;?></td></tr>
					<tr class=""><th>发送类型</th><td><?php echo $type[$info->send_type];?></td></tr>
					<tr class=""><th>内容</th><td><?php echo $info->content;?></td></tr>
				</table>	
			</div>
		</div>
	</div>