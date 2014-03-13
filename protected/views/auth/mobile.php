<?php echo $this->renderPartial('/common/header',array('title'=>'填写手机号'));?>

<div id="content"> 	
	<form action="<?php echo $this->createUrl('auth/mobile',array('sign'=>Util::getSign())); ?>" id="form1" class="formstyle" method="post">
		<div class="formitems">
			<label for="mobile">请输入您的手机号：</label>
			<input type="number" name="mobile" id="mobile" placeholder="请输入手机号" required="required" class="inp" />
		</div>
				
		<div class="formitems">
			<input type="submit" value="确定" class="submitbtn" />
		</div>
	</form>
</div>

<script>
//避免表单重复提交
$("form").submit(function(){
	var value = $("input[name=mobile]").val();
	var reg = /^(13|14|15|18)\d{9}$/;
	if(value == '' || !reg.test(value)){
		alert('请输入正确的手机号');
		return false;
	}
	$(":submit").attr("disabled",true)
	return true;
});
</script>