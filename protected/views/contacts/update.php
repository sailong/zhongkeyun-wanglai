<?php echo $this->renderPartial('/common/header',array('title'=>'编辑'));?>

<div id="content">
	<?php echo $this->renderPartial('_form',array('model'=>$model))?>
</div>