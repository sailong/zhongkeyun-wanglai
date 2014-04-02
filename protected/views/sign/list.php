<?php echo $this->renderPartial('/common/header',array('title'=>'Ta的签名'));?>

<div id="content">
	<?php echo $this->renderPartial('_list',array('signs'=>$signs)); ?>
</div>
	