<div data-role="page" id="actpage">
    <div data-theme="b" data-role="header" data-position="fixed">
		<a href="#" data-rel="back">返回</a>
        <h3>填写手机号</h3>
    </div>
    <div data-role="content" id="cr">    	
    	<form id='form1' data-ajax="false" method="post" action="<?php echo $this->createUrl('index',array('sign'=>Util::getSign())); ?>">
			<div data-role="fieldcontain">
				<label for="mobile">输入您的手机号：</label>
				<input type="text" name="mobile" id="mobile" value="" placeholder="请输入手机号" required="required" data-val="true" data-val-regex="手机号格式输入有误" data-val-regex-pattern="(^[1]([3][0-9]{1}|[5][0-9]{1}|[8][0-9]{1})[0-9]{8})|(^4000-\d{4}-\d{2})"  data-val-required="手机号码不能为空" />
			</div>
			<?php if(Util::getCookie('from') != 'login'):?>
				<div data-role="fieldcontain">
					<label for="username">填写您的姓名:</label>
					<input type="text" name="name" id="username" value="" placeholder="请输入姓名" required="required" />
				</div>
			<?php endif;?>
			<div data-role="fieldcontain">
				<?php 
					$mobile = Util::getCookie('mobile');
					if(!empty($mobile) && !$this->verifySendCondition($mobile)):
				?>
					<input type="button" value="2小时内已登录超过5次，过会再来吧！" data-theme="a" disabled="disabled"/>
				<?php else:?>
					<input type="submit" value="下一步" data-theme="a"/>
				<?php endif;?>
			</div>
		</form>
	</div>
</div>
<script>
//避免表单重复提交
$("form").submit(function(){
	if($("#form1").find(".input-validation-error").length == 0){
		$(":submit").attr("disabled",true).button("refresh");
	}
});

</script>