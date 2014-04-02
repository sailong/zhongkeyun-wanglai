<?php echo $this->renderPartial('/common/header',array('title'=>'Ta的文章'));?>

<div id="content">
	<?php echo $this->renderPartial('_list',array('articles'=>$articles)); ?>
</div>
	