<?php echo $this->renderPartial('/common/header',array('title'=>'我的收藏'));?>

<div id="content">
    <?php echo $this->renderPartial('_list',array('signs'=>$signs)); ?>
</div>
	