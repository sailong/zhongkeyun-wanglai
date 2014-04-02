<li>
	<div class="img-s3"><a href="<?php echo $this->createUrl('member/view',array('id'=>$comment->member->id));?>" class="img-s3"><img src="<?php echo Member::model()->getPhoto($comment->member);?>" alt="" /></a></div>
    <div class="img-s4">
    <a href="<?php echo $this->createUrl('list',array('mid'=>Util::encode($comment->member->id))); ?>">
        <p class="s1"><?php echo $comment->member->name;?>：</p>
        <p class="s2"><?php echo $comment->comment;?></p>
        <p class="date"><?php echo date('Y/m/d H:i',$comment->create_time);?></p>
    </a>
    </div>
</li>