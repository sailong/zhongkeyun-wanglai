<li>
	<a href="<?php echo $this->createUrl('list',array('mid'=>Util::encode($comment->member->id))); ?>">
	<p class="s1"><?php echo $comment->member->name;?>：<span class="date"><?php echo date('Y/m/d H:i',$comment->create_time);?></span></p>
	<p class="s2"><?php echo $comment->comment;?></p>
	</a>
</li>