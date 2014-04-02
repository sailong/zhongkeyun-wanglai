<?php echo $this->renderPartial('/common/header',array('title'=>'发起签名'));?>
<div id="content">
	<form action="#" id="form1" class="formstyle" method="post" onsubmit="return checkSubmit();">
		<!-- <div class="formitems">更醒目、个性化地展示您的大作，更显大气、更易传播、更好互动</div> -->
		<?php if(Yii::app()->user->hasFlash('sign')):?>
			<span color="red"><?php echo Yii::app()->user->getFlash('sign');?></span>
		<?php endif;?>
		<div class="formitems">
			<input type="text" name="Sign[title]" id="title" value="<?php echo $model->title; ?>" class="inp" placeholder="请输入请输签名标题" required="required" />
		</div>
		<div class="formitems">
			<textarea cols="40" rows="18" name="Sign[content]" id="introduction" class="inp" placeholder="请输入请输入签名内容" required="required"><?php echo $model->content;?></textarea>
		</div>
		<div class="formitems" style="color:#999;font-size: 0.9em">提示：发布违法、反动互动信息或者冒用他人、组织名义发布信息、将依据记录提交公安机关处理</div>
		<div class="formitems">
		  <input type="submit" value="提交预览" class="btn_b1"/>
		  <input type="hidden" value="<?php echo $token;?>" name="token"/>
		</div>
	</form>
</div>
<script>
function checkSubmit(){
	var flag = false;
	$.ajax({
		type: 'POST',  
        url: "/sign/checkformsubmit",  
        data: {'token':'<?php echo $token;?>'}, 
        dataType: "json",  
        async:false,  
        success: function (result) {  
        	if(result.code == 1)
    		{
        		flag = true;
    		}else{
        		alert("不可重复提交！");
    			flag = false;
    		}
        }  
        
	});
	return flag;
}
</script>