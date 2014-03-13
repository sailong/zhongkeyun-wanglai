<div id="content">
	<header id="header">
		<h1>企业登录</h1>
	</header>
	
	<form id="form1" class="formstyle" method="post">
		<div class="formitems">
			<label for="title">用户名：</label>
			<input type="text" name="Login[username]" id="username" value="" class="inp" placeholder="请输入用户名" required="required" />
		</div>
		<div class="formitems">
			<label for="title">密码：</label>
			<input type="password" name="Login[password]" id="password" value="" class="inp" placeholder="请输入密码" required="required" />
		</div>
		
		<?php if(Yii::app()->user->hasFlash('error')):?>
			<span class="err"><?php echo Yii::app()->user->getFlash('error');?></span>
		<?php endif;?>
		<div class="formitems">
			<input type="submit" value="立即登录" class="btn_b2" />
		</div>
	</form>
	
</div>