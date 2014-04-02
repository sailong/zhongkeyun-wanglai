<?php if(!empty($signs)):?>
	<ul class="adrlist articlelist fix">
		<?php foreach ($signs as $sign):?>
			<li class="adr_s">
				<a href="<?php echo $this->createUrl('view',array('id'=>Util::encode($sign->id))); ?>">
					<h2><?php echo $sign->title; ?></h2>
					<?php if($sign->publish==0):?><span class="sharespan sharenoe">未发布</span><?php elseif($sign->publish==1):?><span class="sharespan">分享：<?php if($sign->share_counts){echo $sign->share_counts;}else{echo 0;} ?></span><?php endif;?>
					<p class="s1"><?php echo date('Y-m-d H:i', $sign->create_time);?> <span><?php echo $sign->creater->name;?></span> 阅读<?php if($sign->pv_counts){echo $sign->pv_counts;}else{echo 0;}?></p>
					<p class="s2"><?php echo mb_substr(preg_replace('/[\s|\&nbsp;]/', '', strip_tags($sign->content)),0,50,'UTF-8');?></p>
				</a>
			</li>
		<?php endforeach;?>
	</ul>
<?php endif;?>

