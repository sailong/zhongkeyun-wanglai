<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>往来人脉业务支撑系统</title>
<link rel="stylesheet" type="text/css" href="static/css/manage.css" />
<script type="text/javascript" src="static/js/jquery.js"></script>
<script type="text/javascript" src="static/js/common.js"></script>
</head>

<body class="loginbg">
	
	<div class="wrap">
		<div class="loginbox">
			<form id="loginform" method="post" action="<?php echo $this->createUrl('LoginDo');?>">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td align="right" width="90">用户ID：</td>
				<td><input id="uname" name="username" type="text" class="inp1" /></td>
			  </tr>
			  <tr>
				<td align="right">密码：</td>
				<td><input id="upsw" name="password" type="password" class="inp1" /></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><input name="" type="submit" class="sendBtn" value="登 录" /></td>
			  </tr>
			</table>
			</form>
		</div>
	</div>
<script>
$('#uname').focus();
</script>
</body>
</html>