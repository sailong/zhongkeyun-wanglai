<div data-role="page" id="GetPassword">
    <div data-theme="b" data-role="header">
        <h3>手机号验证</h3>
    </div>
    <div data-role="content">
        <form action="<?php echo $this->createUrl('step2');?>" method="post" data-ajax="false" id="form1">
            <div data-role="fieldcontain">
                <input data-val="true" data-val-regex="手机号格式输入有误" data-val-regex-pattern="^[1]([3][0-9]{1}|[5][0-9]{1}|[8][0-9]{1})[0-9]{8}$"  data-val-required="手机号码不能为空" id="mobile" name="mobile" placeholder="请输入手机号" type="text" value="<?php echo $mobile;?>" />
                <span class="field-validation-valid" data-valmsg-for="mobile" data-valmsg-replace="true"></span>
            </div>
            <p>
                 <button id="getcode" data-theme="b" href="#" data-ajax="false">获取验证码</button>
            </p>
			 <div data-role="fieldcontain">
                <input data-val="true" data-val-required="验证码不能为空" id="yzm" name="code" placeholder="验证码" type="text" value="" data-val-length-max="7"/>
                <span class="field-validation-valid" data-valmsg-for="yzm" data-valmsg-replace="true"></span>
            </div>
			
			<p>超过<span id="num"><?php echo $this->time_limit;?></span>秒（2分钟）还未收到短信可以重新获取验证码。超过15分钟仍未收到验证码请联络客服QQ：1085606688，客服热线：4000737088，微信客服：wanglairm，客服工作时间：周一至周五9:00-17:00
			
			</p>

			<p>
                <input type="submit" value="下一步" data-theme="a" onclick="return checkCode();"/>
                <span class="field-validation-valid" data-valmsg-for="createresult" data-valmsg-replace="true"></span>
            </p>   
        </form>
    </div>
</div>
 <script type="text/javascript">
    	$(function(){
    		var wait=<?php echo $this->time_limit;?>;
    		var g = $("#getcode");
    		var r = true;
    		
    		g.bind("tap",function(){alert('若您需要找回密码，请联系人工客服：4000737088 ，客服工作时间：周一至周五9:00-17:00');return false;
    			if(r){
    				if($.trim($("#mobile").val())==""){
        				$("#mobile").focus();
	    				alert("请输入正确的手机号码！");
	    				return false;
	    			}else{
	    				getcode();
	    			}
    			}
    			
    		})
    		var url="<?php echo $this->createUrl('SendCode',array('sign'=>$sendSign));?>";
    		function getcode(){
    			var mobile = $("#mobile").val();
    			$.post(url+"&mobile="+mobile,function(result){
    				if(result.code==1){
    					$("#yzm").focus();
    					time();
    					alert("验证码已发送到您的手机，请注意查收！");
    				}
    				else{
    					alert(result.message);
						if(result.code!=-1)
						{
							location.href='<?php echo $this->createUrl('Step1');?>&mobile='+mobile;
						}
    					
    				}
    			},type="json")
    		}
			function time() {
		        if (wait == 0) {
		        	g.parent().removeClass("graybutton");
		        	r = true;
		        	g.attr("disabled",false);
		           $("#num").html(<?php echo $this->time_limit;?>);
		            wait = <?php echo $this->time_limit;?>;
		            
		        } else {
		        	g.parent().addClass("graybutton");
		        	g.attr("disabled", true);
		        	r = false;
		            $("#num").html(wait);
		            wait--;
		            setTimeout(function() {
		                time(g)
		            },
		            1000)
		        }
		    }
    		
    	})
 function checkCode()
 {alert('若您需要找回密码，请联系人工客服：4000737088 ，客服工作时间：周一至周五9:00-17:00');return false;
   		    var url='<?php echo $this->createUrl('checkCode');?>';
    		var mobile = $("#mobile").val();
    		var code = $("#yzm").val();
    		if(!mobile)
    		{
				alert('您还没有输入手机号码呢');
				$("#mobile").focus();
				return false;
        	}
    		if(!code)
    		{
				alert('请输入验证码');
				$("#yzm").focus();
				return false;
        	}
   		    var ret=false;
			  $.ajax({
					    type:"POST",
					    async:false,
					    url:url+"&mobile="+mobile+"&code="+code,
					    success:function(result){
							if(result.code==1){
								ret= true;
							}
							else{
								alert(result.message);
								ret = false;
							}
						},
						dataType:'json'});
				return ret;
  }
</script>