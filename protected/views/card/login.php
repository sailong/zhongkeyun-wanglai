<div data-role="page" id="Login">
    <div data-theme="b" data-role="header">
        <h3>请先登录</h3>
    </div>
    <div data-role="content">
    	<div class="headertit">
    		<p>登录后即可报名和发起活动</p>
    		<p>没有账号？请点击 <a href="<?php echo $this->createUrl('site/sign'); ?>" data-ajax="false">快速注册</a></p>
    	</div>
        <form action="<?php echo $this->createUrl('loginDo');?>" method="post" data-ajax="false">
        <input type="hidden" value="<?php echo isset($openid) ? $openid:'';?>" name="openid"/>
        <input type="hidden" value="<?php echo isset($sign) ? $sign:'';?>" name="sign"/>        
                <div data-role="fieldcontain">
                    <input data-val="true" data-val-regex="格式输入有误" data-val-regex-pattern="^([1]([3][0-9]{1}|[5][0-9]{1}|[8][0-9]{1}|[4][0-9]{1})[0-9]{8})|(\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+)|^\d{3,10}$" data-val-required="该项不能为空" id="EmailOrCellphone" name="name" placeholder="邮箱/手机号/往来号" type="text" value="" />
                    <span class="field-validation-valid" data-valmsg-for="EmailOrCellphone" data-valmsg-replace="true"></span>
                </div>
                <div data-role="fieldcontain">
                    <input data-val="true" data-val-required="密码不能为空" id="Password" name="password" placeholder="管理密码" type="password" />
                    <span class="field-validation-valid" data-valmsg-for="Password" data-valmsg-replace="true"></span>
                </div>

                <p>
                    <input type="submit" value="登录" data-theme="a" />
                    <span class="field-validation-valid" data-valmsg-for="loginresult" data-valmsg-replace="true"></span>
                </p>
                <?php if(Helper::is_mobile()){?>
                 <a data-role="button" data-theme="b" href="<?php echo $this->createUrl('Create');?>" data-ajax="false">创建微名片</a>
                 <?php }?>
                 <a data-role="button" data-theme="b" href="javascript:;<?php echo $this->createUrl('Password/step1'); ?>" data-ajax="false">找回密码</a>
            	 <p style="color:red">若您需要找回密码，请联系人工客服：4000737088 ，客服工作时间：周一至周五9:00-17:00</p>
            </form>
             <p class="copyright">欢迎关注往来微信公众号：wanglairm<br />往来人脉-让人人都有自己的微名片<br />Powered by <a href="http://www.wanglai.cc">wanglai.cc</a></p>
    </div>
</div>