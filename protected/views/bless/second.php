<?php //echo $this->renderPartial('/common/header',array('title'=>'三步定制贺卡'));?>

<header id="header">
	<h1><?php echo '三步定制贺卡';?></h1>
</header>

<div id="content">
	<h2 class="cardh2">第二步：<?php if(isset($data['blessing'])):?>选祝福<?php else:?>写祝福<?php endif;?></h2>
	<ul class="cardbglist cardtextlist">
	<?php $i=1;?>
		<?php if(isset($data['blessing']))?>
		<?php foreach($data['blessing'] as $value):?>
			<li>
				<a href="javascript:;">
					<p class="ctxt"><span class="ctl"><?php echo '祝福语'.$i; ?></span><span class="ctr"></span></p>
					<p class="cardp"><?php echo $value;?></p>
				</a>
			</li>
		<?php $i++;?>
		<?php endforeach;?>
	</ul>
	<form action="<?php echo $this->createUrl('bless/second'); ?>" method="post" class="cform">
		<div class="formitems">
			<textarea cols="40" id="cardcontent" rows="5" name="content" class="inp" placeholder="请输入您要发送的祝福语" required="required"></textarea>
		</div>
		<div class="formitems">
			<input type="submit" value="下一步" class="submitbtn" />
		</div>
	</form>
</div>

<?php //echo $this->renderPartial('/common/footer'); ?> 

<script>
$(document).ready(function(){
	$(".cardtextlist li").live("click",function(){
		$("#cardcontent").val($(this).find(".cardp").text());
		//$('html, body').animate({scrollTop: $(document).height()}, 300);
		$('html, body').animate({scrollTop: "5000px"}, 300);
	})
});
</script>
   