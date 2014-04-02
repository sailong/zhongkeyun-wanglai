<?php echo $this->renderPartial('/common/header',array('title'=>'签名管理'));?>

<div id="content">
	<nav class="tab">
		<a href="<?php echo $this->createUrl('mypublish'); ?>">我发布的</a>
		<span class="line">|</span>
		<a href="#" class="cur">我评论的<u></u></a>
	</nav>
	<?php echo $this->renderPartial('_list',array('signs'=>$signs)); ?>
</div>
	