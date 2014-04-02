<form action="<?php echo Yii::app()->getRequest()->getRequestUri(); ?>" class="formstyle" id="formstyle" method="post">
	<div class="formitems">
		更醒目、个性化地展示您的大作，更显大气、更易传播、更好互动
		<?php if(Yii::app()->user->hasFlash('article')):?>
			<span color="red"><?php echo Yii::app()->user->getFlash('article');?></span>
		<?php endif;?>
	
	</div>
	<div class="formitems">
		<input type="text" name="Article[title]" id="title" value="<?php echo $model->title; ?>" class="inp" placeholder="请输入请输文章标题" required="required" />
	</div>
	<div class="formitems">
		<textarea cols="40" rows="18" name="Article[content]" id="introduction" class="inp" placeholder="请输入请输入文章内容" required="required"><?php if(!$model->isNewRecord) echo $model->content->content;?></textarea>
	</div>
	<div class="formitems" style="color:#999;font-size: 0.9em">提示：发布违法、反动互动信息或者冒用他人、组织名义发布信息、将依据记录提交公安机关处理</div>
	<div class="formitems">
		<input type="button" value="提交预览" class="btn_b1" id="btn_b1"/>
	</div>
	<script>
		
	   var a = $("#btn_b1").click(function(){
		        $(this).attr('disabled',true); 
		        $("#formstyle").submit();
		   });
	  
	</script>
</form>