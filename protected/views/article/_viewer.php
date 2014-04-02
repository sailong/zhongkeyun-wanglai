<li>
    <a href="<?php echo $this->createUrl('member/view',array('id'=>$viewer['id']));?>">
        <p><img src="<?php echo Member::model()->getPhoto($viewer['user_model']);?>" alt="" /></p>
        <p class="s1"><?php echo $viewer['name'];?></p>
        <p class="s1"><?php if($viewer['type']==2){echo '（赞过）';}?></p>
    </a>
</li>