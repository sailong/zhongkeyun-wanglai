<div data-role="page" id="actpage">
	
    <div data-theme="b" data-role="header" data-position="fixed">
		<a href="#" data-rel="back">返回</a>
        <h3>填写验证码</h3>
    </div>
   
    <div data-role="content" id="cr"> 
    	<form id='form1' data-ajax="false" method="post" action="<?php echo $this->createUrl('site/login'); ?>">
    	
	    	<p>您已注册过往来账号，我们已发送验证码短信到这个号码：</p>
	    	
	    	<p class="phonenum"><?php echo $mobile; ?></p>
	    	
			<div data-role="fieldcontain">
				<input type="number" name="code" id="yzm" placeholder="请输入验证码" required="required" />
				<p class="tc">接收短信大约需要<span id="seconds">60</span>秒</p>
				<input type="button" id="retriveCode" disabled="disabled" value="重新获取验证码">
			</div>
	
			<div data-role="fieldcontain">
				<input type="submit" value="登录" data-theme="a"/>
			</div>
			
			<p>超过15分钟仍未收到验证码请联络客服QQ：1085606688，客服电话：4000737088.（工作时间：周一至周五9:00-17:00）</p>

		</form>
	</div>
</div>

<script>
$(function(){
	var url = "<?php echo $this->createUrl('site/retriveCode'); ?>";
	var checkUrl = "<?php echo $this->createUrl('site/checkCode'); ?>"

	function change()
	{
		var left = parseInt($("#seconds").text());
		if(left>0)
		{
			left = left - 1;
			$("#seconds").text(left);
		}else{
			$("#retriveCode").removeAttr('disabled');
			$("#retriveCode").button('refresh');
			$(".tc").hide();
			window.clearInterval(interval);
		}
	}
	var interval = setInterval(change,1000);

	$("#retriveCode").bind("tap",function(){
		$.post(url,function(data){
			if(data.code == 1)
			{
				$("#seconds ").text(60);
				$("#retriveCode").attr("disabled","disabled");
				$("#retriveCode").button('refresh');
				$(".tc").show();
				interval = setInterval(change,1000);
			}else{
				alert(data.message);
			}
		},'json');
	});

	// 检测验证码
	$(":submit").click(function(event){
		var code = $(":input[name=code]").val();
		var that = $(this);
		if(code != '')
		{
			$.ajax({
				url:checkUrl,
				data:{code:code},
				type:"POST",
				dataType: 'json',
				async: false,
				success: function(data){
					if(data.code ==1)
					{
						that.attr("disabled",true).button("refresh");
						$("#form1").fieldcontain('refresh');
						$("#form1").submit();
						return true;
					}else{
						alert(data.message);
						$(":submit").removeAttr('disabled').button("refresh");
						return false;
					}
				}
			});
			event.preventDefault();
		}
	});
})

</script>