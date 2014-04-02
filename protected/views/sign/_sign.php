<li>
<a href="<?php echo $this->createUrl('member/view',array('id'=>$model->member->id)); ?>">
	<p class="s1"><?php echo $model->member->name;?><span class="date"><?php echo date('Y/m/d H:i',$model->create_time);?></span></p>
	<p class="s2">已签名</p>
</a>
</li>