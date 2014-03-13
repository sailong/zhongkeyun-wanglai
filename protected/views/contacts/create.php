<?php echo $this->renderPartial('/common/header',array('title'=>'发起新的群通讯录'));?>

<div id="content">
	<?php $this->renderPartial('_form',array('model'=>$model))?>

</div>