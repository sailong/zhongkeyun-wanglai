<div data-role="page" id="GetPassword">
    <div data-theme="b" data-role="header">
        <h3>填写新密码</h3>
    </div>
    <div data-role="content">
        <form action="<?php echo $this->createUrl('UpdatePassword');?>" method="post" data-ajax="false"  id="form1" onsubmit="return checkForm();">
			<input type="hidden" name="id"     value="<?php echo $id;?>"/>
			<input type="hidden" name="mobile" value="<?php echo $mobile;?>"/>
			<input type="hidden" name="code"   value="<?php echo $code;?>"/>
			<input type="hidden" name="sign"   value="<?php echo $sign;?>"/>
            <div data-role="fieldcontain">
	            <input data-val="true" data-val-required="新密码不能为空" id="password" name="password" placeholder="新密码" type="password" data-val-length="长度6~16个字符之间" data-val-length-max="16" data-val-length-min="6" />
	            <span class="field-validation-valid" data-valmsg-for="password" data-valmsg-replace="true"></span>
	        </div>
	        <div data-role="fieldcontain">
	            <input data-val="true" data-val-required="再输入一次密码" id="password2" name="password2" placeholder="再输入一次密码" type="password" data-val-length="长度6~16个字符之间" data-val-length-max="16" data-val-length-min="6"/>
	            <span class="field-validation-valid" data-valmsg-for="password2" data-valmsg-replace="true"></span>
	        </div>
			<p>
                <input type="submit" value="确定" data-theme="a"/>
                <span class="field-validation-valid" data-valmsg-for="createresult" data-valmsg-replace="true"></span>
            </p>
        </form>
    </div>
</div>
<script type="text/javascript">
function checkForm()
{
	if($('#password').val()!=$('#password2').val())
	{
		alert('两次密码输入不一致，请重新输入！');
		return false;
	}
	return true;
}
</script>