<div data-role="page" id="actpage">
	
    <div data-theme="b" data-role="header" data-position="fixed">
        <h3>设置必要信息</h3>
    </div>
    <div data-role="content" id="cr">    	
    	<form id='form1' data-ajax="false" action="<?php echo $this->createUrl('site/sign',array('sign'=>Yii::app()->user->getState('sign'))); ?>" method="post">
		<div data-role="fieldcontain">
			<label for="username">填写您的姓名:</label>
			<input type="text" name="name" id="username" value="" placeholder="请输入姓名" required="required" />
		</div>
		
		<!-- 
		<div data-role="fieldcontain">
			<label for="username">请设置密码:</label>
			<input type="password" name="password" id="password" value="" placeholder="请输入密码" required="required" />
		</div>
		-->
		
		<div data-role="fieldcontain">
			<label for="cop">已阅读并同意往来软件许可及服务协议</label>
			<input type="checkbox" name="" id="cop" checked="checked" required="required" />
			<input type="submit" value="完成注册" data-theme="a" />
		</div>
		
		<p>注册完成后点击菜单“<span class="tred">修改名片</span>”后即可完善往来微名片资料。</p>

		</form>
	</div>
</div>