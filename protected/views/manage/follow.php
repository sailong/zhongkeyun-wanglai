<?php echo $this->renderPartial('/common/header',array('title'=>$title));?>

<div id="content">
	<div class="searchbox">
		<input type="text" name="keyword" class="sinp" placeholder="输入要搜索的关键字" required /><input type="submit" value="搜索" class="sbtn" />
	</div>
	<ul class="adrlist adrmemberlist">
		<?php echo $this->renderPartial('_list',compact('data','followMeInfo','myFollowInfo')); ?>
	</ul>
</div>

<?php echo $this->renderPartial('right',array('followInfo'=>$myFollowInfo)); ?>


<?php echo $this->renderPartial('/common/footer'); ?>