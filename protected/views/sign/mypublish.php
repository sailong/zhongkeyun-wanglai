<?php echo $this->renderPartial('/common/header',array('title'=>'签名管理'));?>

<div id="content">
	<nav class="tab">
		<a href="#" class="cur">我发布的<u></u></a>
		<span class="line">|</span>
		<a href="<?php echo $this->createUrl('mycomment'); ?>">我评论的</a>
	</nav>
	<?php echo $this->renderPartial('_list',array('signs'=>$signs)); ?>
</div>
	