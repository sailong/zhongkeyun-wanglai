<?php echo $this->renderPartial('/common/header',array('title'=>'微名片排名'));?>

<div id="content">
	<div class="searchbox">
		<input type="text" name="keyword" class="sinp" placeholder="输入要搜索的关键字" required /><input type="submit" value="搜索" class="sbtn" />
	</div>
	<ul class="adrlist adrmemberlist adrtop">
		<?php echo $this->renderPartial('_rank',compact('data')); ?>
	
	</ul>
</div>

<?php echo $this->renderPartial('right',array('followInfo'=>$myFollowInfo)); ?>

<?php echo $this->renderPartial('/common/footer'); ?>

<script>
$(function(){
	(function(){
		// 显示top样式
		$first = $("ul.adrmemberlist li:first");
		$first.addClass("top1");
		$first.next().addClass("top2");
		$first.next().next().addClass("top3");
	})()
})
</script>