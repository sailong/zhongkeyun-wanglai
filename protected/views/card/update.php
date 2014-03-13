<style type="text/css">
        .uploading{ float:left; background:url(static/weixin/loading2.gif) no-repeat left center; padding-left:18px;display:none; line-height:24px; height:24px; color:#333; }
</style>
<div data-role="page" id="Create">
	<div id="floating">
		<div id="floatingCirclesG">
			<div class="f_circleG" id="frotateG_01">
			</div>
			<div class="f_circleG" id="frotateG_02">
			</div>
			<div class="f_circleG" id="frotateG_03">
			</div>
			<div class="f_circleG" id="frotateG_04">
			</div>
			<div class="f_circleG" id="frotateG_05">
			</div>
			<div class="f_circleG" id="frotateG_06">
			</div>
			<div class="f_circleG" id="frotateG_07">
			</div>
			<div class="f_circleG" id="frotateG_08">
			</div>
		</div>
		<p>正在提交信息中，请稍候...</p>
	</div>
	
    <div data-theme="b" data-role="header">
        <h3><?php echo $model->id ? '修改' : '创建';?>微名片</h3>
    </div>
    <div data-role="content" style="margin-bottom:30px">
        <form action="<?php echo $this->createUrl('updateDo');?>" method="post" data-ajax="false" enctype="multipart/form-data" id="form1" target="upload">
            
			<input type="hidden" name="weixin_openid" value="<?php echo $openid;?>">
			<input type="hidden" name="id" value="<?php echo $model->id;?>">
			<input type="hidden" name="from_uid" value="<?php echo isset($from_uid) ? $from_uid : '';?>">
			<!-- 基本信息（必填）： -->
            <div data-role="fieldcontain">
                <input data-val="true" data-val-length="长度不能超过20个字符" data-val-length-max="20" data-val-required="姓名不能为空" id="name" name="name" placeholder="姓名(必填)" type="text" value="<?php echo $model->name;?>" />
                <span class="field-validation-valid" data-valmsg-for="name" data-valmsg-replace="true"></span>
            </div>

            <div data-role="fieldcontain">
                <input data-val="true" data-val-regex="手机号格式输入有误" data-val-regex-pattern="^[1]([3][0-9]{1}|[5][0-9]{1}|[8][0-9]{1}|[4][0-9]{1})[0-9]{8}$"  data-val-required="手机号码不能为空" id="mobile" name="mobile" placeholder="手机(必填)" type="text" value="<?php echo $model->mobile;?>" />
                <span class="field-validation-valid" data-valmsg-for="mobile" data-valmsg-replace="true"></span>
            </div>
			 <div data-role="fieldcontain">
			 	<input type="checkbox" value="0" id="mibile_lab" name="hidden[mobile]" <?php $this->checkIsChecked($model->show_item,'mobile');?>/>
			 	<label for="mibile_lab">隐藏名片手机号码</label>
			 </div>
			
           
            
             <div data-role="fieldcontain">
                <?php if(!$model->id) {?>
                <input data-val="true" data-val-required="管理密码不能为空" placeholder="密码(必填,用于以后修改名片信息)" id="password" name="password"  type="password" />
               <?php }else{?>
               <input data-val="true"  placeholder="密码(不修改则留空)" id="password" name="password"  type="password" />
               <?php }?>
                <span class="field-validation-valid" data-valmsg-for="password" data-valmsg-replace="true"></span>
            </div>
             <div data-role="fieldcontain">
                <input data-val="true" data-val-length="长度不能超过100个字符" data-val-length-max="100"   id="email" name="email" placeholder="邮箱(选填)" type="text" value="<?php echo $model->email;?>" />
                <span class="field-validation-valid" data-valmsg-for="email" data-valmsg-replace="true"></span>
            </div>
            
            <div data-role="fieldcontain">
                <input id="birthday" name="birthday" placeholder="生日(选填)" type="text" value="<?php echo !empty($model->birthday) ? date('Y-m-d',$model->birthday) : '' ;?>" />
                <span class="field-validation-valid" data-valmsg-for="position" data-valmsg-replace="true"></span>
            </div>
            
            <div data-role="fieldcontain">
                <input data-val="true" data-val-length="长度不能超过100个字符" data-val-length-max="100" id="position" name="position" placeholder="职位(选填)" type="text" value="<?php echo $model->position;?>" />
                <span class="field-validation-valid" data-valmsg-for="position" data-valmsg-replace="true"></span>
            </div>

            <div data-role="fieldcontain">
                <textarea data-val="true" oninput="this.style.posHeight=this.scrollHeight" style="height: auto" rows="1" data-val-length="长度不能超过100个字符" data-val-length-max="100" id="company" name="company" placeholder="公司(选填)"><?php echo $model->company;?></textarea>
                <span class="field-validation-valid" data-valmsg-for="company" data-valmsg-replace="true"></span>
            </div>


            <div data-role="fieldcontain">
                <textarea data-val="true" oninput="this.style.posHeight=this.scrollHeight" style="height: auto" rows="1" data-val-length="长度不能超过200个字符" data-val-length-max="200" id="address" name="address" placeholder="地址(选填)"><?php echo $model->address;?></textarea>
                <span class="field-validation-valid" data-valmsg-for="address" data-valmsg-replace="true"></span>
            </div>
			<!-- 选填信息： -->
            
			<p id="show_p" style="display: <?php echo $model->id ? 'none' : 'block';?>"><a data-role="button" data-theme="c" href="javascript:;" data-ajax="false" onclick="showItem();">填写更多个人信息</a></p>
            <div id="gj_item" style="display: <?php echo $model->id ? 'block' : 'none';?>">
             <div data-role="fieldcontain">
                <input data-val="true" data-val-length="长度不能超过200个字符" data-val-length-max="200" id="company_url" name="company_url" placeholder="公司微站(选填)" type="text" value="<?php echo $model->company_url;?>" />
                <span class="field-validation-valid" data-valmsg-for="company_url" data-valmsg-replace="true"></span>
            </div>
            
            <div data-role="fieldcontain">
                <textarea data-val="true" oninput="this.style.posHeight=this.scrollHeight" style="height: auto" rows="1" data-val-length="长度不能超过200个字符" data-val-length-max="200" id="main_business" name="main_business" placeholder="业务介绍(选填)"><?php echo $model->main_business;?></textarea>
                <span class="field-validation-valid" data-valmsg-for="main_business" data-valmsg-replace="true"></span>
            </div>
            
            <div data-role="fieldcontain">
                <textarea data-val="true" oninput="this.style.posHeight=this.scrollHeight" style="height: auto" rows="1" data-val-length="长度不能超过200个字符" data-val-length-max="200" id="supply" name="supply" placeholder="供给(选填)"><?php echo $model->supply;?></textarea>
                <span class="field-validation-valid" data-valmsg-for="supply" data-valmsg-replace="true"></span>
            </div>
            <div data-role="fieldcontain">
			 	<input type="checkbox" value="0" id="supply_lab" name="hidden[supply]" <?php $this->checkIsChecked($model->show_item,'supply');?>/>
			 	<label for="supply_lab">隐藏名片供给信息</label>
			 </div>
            <div data-role="fieldcontain">
                <textarea data-val="true" oninput="this.style.posHeight=this.scrollHeight" style="height: auto" rows="1" data-val-length="长度不能超过200个字符" data-val-length-max="200" id="demand" name="demand" placeholder="需求(选填)"><?php echo $model->demand;?></textarea>
                <span class="field-validation-valid" data-valmsg-for="demand" data-valmsg-replace="true"></span>
            </div>
            
             <div data-role="fieldcontain">
			 	<input type="checkbox" value="0" id="demand_lab" name="hidden[demand]" <?php $this->checkIsChecked($model->show_item,'demand');?>/>
			 	<label for="demand_lab">隐藏名片需求信息</label>
			 </div>
            
            <div data-role="fieldcontain">
                <input data-val="true" data-val-length="长度不能超过50个字符" data-val-length-max="50" id="weixin" name="weixin" placeholder="微信号(选填)" type="text" value="<?php echo $model->weixin;?>" />
                <span class="field-validation-valid" data-valmsg-for="weixin" data-valmsg-replace="true"></span>
            </div>
            
            <div data-role="fieldcontain">
                <input data-val="true" data-val-length="长度不能超过50个字符" data-val-length-max="50" id="yixin" name="yixin" placeholder="易信号(选填)" type="text" value="<?php echo $model->yixin;?>" />
                <span class="field-validation-valid" data-valmsg-for="yixin" data-valmsg-replace="true"></span>
            </div>
            
            <div data-role="fieldcontain">
                <input data-val="true" data-val-length="长度不能超过50个字符" data-val-length-max="50" id="laiwang" name="laiwang" placeholder="来往号(选填)" type="text" value="<?php echo $model->laiwang;?>" />
                <span class="field-validation-valid" data-valmsg-for="laiwang" data-valmsg-replace="true"></span>
            </div>
            
            <div data-role="fieldcontain">
                <input data-val="true" data-val-length="长度不能超过15个字符" data-val-length-max="15" id="qq" name="qq" placeholder="QQ号(选填)" type="text" value="<?php echo $model->qq;?>" />
                <span class="field-validation-valid" data-valmsg-for="qq" data-valmsg-replace="true"></span>
            </div>
             <div data-role="fieldcontain">
                <input data-val="true" data-val-length="长度不能超过300个字符" data-val-length-max="300" id="social_position" name="social_position" placeholder="社会职务(选填)" type="text" value="<?php echo $model->social_position;?>" />
                <span class="field-validation-valid" data-valmsg-for="social_position" data-valmsg-replace="true"></span>
            </div>
            
               <div data-role="fieldcontain">
                <textarea data-val="true" oninput="this.style.posHeight=this.scrollHeight" style="height: auto" rows="1" data-val-length="长度不能超过500个字符" data-val-length-max="500" id="profile" name="profile" placeholder="个人简介(选填)"><?php echo $model->profile;?></textarea>
                <span class="field-validation-valid" data-valmsg-for="profile" data-valmsg-replace="true"></span>
            </div>
            
              <div data-role="fieldcontain">
                <textarea data-val="true" oninput="this.style.posHeight=this.scrollHeight" style="height: auto" rows="1" data-val-length="长度不能超过200个字符" data-val-length-max="200" id="hobby" name="hobby" placeholder="兴趣爱好(选填)"><?php echo $model->hobby;?></textarea>
                <span class="field-validation-valid" data-valmsg-for="hobby" data-valmsg-replace="true"></span>
            </div>
            
          <?php if($model->id){?>
          	<div data-role="fieldcontain">
              <span>头像：</span><input type="file" id="upphoto" name="upphoto" />
            <a data-role="button" data-inline="true" data-icon="send" id="sendtofriendbtn" data-theme="f" class="sharebtn" style="display:none;">安卓无法上传头像解决方案</a>
            <p style="color:red">因手机上网速度可能不稳定，建议上传头像的图片大小不要超过2M</p>
            </div>
           <?php }?>
            </div>
            <p>
                <input type="button" value="<?php echo $model->id ? '保存设置' : '生成微名片';?>" data-theme="b" id="upd_submit"/>
                <span class="field-validation-valid" data-valmsg-for="createresult" data-valmsg-replace="true"></span>
            </p>
            <?php if(!$model->id){?>
            <p><a data-role="button" data-theme="c" href="<?php echo $this->createUrl('login',array('openid'=>$openid));?>" data-ajax="false">已有微名片点这里</a></p>
            <?php }?>

        </form>
    </div>
    <div id="sharebg">&nbsp;</div>
    <div id="sharebox">
        <img src="static/weixin/follow2.png?v=2" />
    </div>
</div>
<div id="operate-ok"></div>
<iframe name="upload" style="display:none"></iframe>
<script src="static/weixin/mobiscroll.custom-2.6.2.min.js?v=1311204" type="text/javascript"></script>	
<script>
$(function() {
	
	$("#upd_submit").bind("tap",function() {
		var v_name = $("#name").val();
		var v_mobile = $("#mobile").val();
		var v_email = '2222';//$("#email").val();
		var d_h = $("#Create").height()-$(window).height();
		if(v_name!=""&&v_mobile!=""&&v_email!=""){
			$("#floating").show();
			$("#floatingCirclesG").css("margin-top",d_h+100);
			$("#form1").submit();
		}else{
			alert("您输入的信息不完整，请返回检查");
		}
		
	});
    var visbles = $("#hidvisble").val();
    if (visbles == "False") {
        $("#cbvisbile").attr("checked", true);
    }
    var s = navigator.userAgent;
    if (s.indexOf("Android") >= 0 && s.indexOf("MicroMessenger") >= 0) {
        $("#sendtofriendbtn").show();
        $("#upphoto").hide();
    }
    $("#sharebtn,#sendtofriendbtn,#followbtn").click(function () {
        $("#sharebg,#sharebox").show();
        $("#sharebox").css({ "top": $(window).scrollTop() })
    });
    $("#sharebg,#sharebox").click(function () {
        $("#sharebg,#sharebox").hide();
    });
    var imgarr = ["jpeg","jpg","png","gif","JPG","PNG","JPEG","GIF"];
    //限制上传图片大小
    $("#upphoto").change(function(evt){return;
    	var files = this.files;
    	var file_1 = files[0];
  		var size = file_1.size;
		var fname = file_1.name;
		var type = fname.substring(fname.lastIndexOf('.')+1,fname.length);

		if($.inArray(type,imgarr)==-1){
			$(this).val("");
			alert("您上传的文件格式不正确，支持的图片格式有jpg、gif、png，请重新上传。");
		}
		else{
			if(size>=3*1024*1024){
				$(this).val("");
				alert("您上传的文件超出3M，请处理后重新上传。");
			}
		}
    }); 

    var opt = {
            preset: 'date', //日期
            theme: 'jqm', //皮肤样式
            display: 'modal', //显示方式 
            mode: 'scroller', //日期选择模式
            dateFormat: 'yy-mm-dd', // 日期格式
            setText: '确定', //确认按钮名称
            cancelText: '取消',//取消按钮名籍我
            dateOrder: 'yymmdd', //面板中日期排列格式
            dayText: '日', monthText: '月', yearText: '年', //面板中年月日文字
            endYear:2020 //结束年份
        };
    $('#birthday').mobiscroll(opt);
});

//表单返回
function callback(data){
	$("#floating").hide();
	var obj = eval('(' + data + ')');
	if(obj.code==0){
		alert(obj.error);
		//window.top.location.reload();
	}else{
		window.location.href=obj.url;
	}
}
function showItem()
{
	$('#gj_item').show();
	$('#show_p').hide();
}
//文件上传
function Upload(action, repath, uppath, iswater, isthumbnail, filepath) {
	return;
}
</script>