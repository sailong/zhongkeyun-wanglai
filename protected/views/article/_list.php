<?php if(!empty($articles)):?>
	<ul class="adrlist articlelist fix">
		<?php foreach ($articles as $article):?>
			<li class="adr_s">
				<a href="<?php echo $this->createUrl('view',array('id'=>Util::encode($article->id))); ?>">
					<h2><?php echo $article->title; ?></h2>
					<?php if($article->publish==0):?><span class="sharespan sharenoe">未发布</span><?php elseif($article->publish==1):?><span class="sharespan">分享：<?php echo $article->share_counts; ?></span><?php endif;?>
					<p class="s1"><?php echo date('Y-m-d H:i', $article->create_time);?> <span><?php echo $article->creater->name;?></span> 阅读<?php echo $article->views;?></p>
					<p class="s2"><?php echo $article->summary;?></p>
				</a>
			</li>
		<?php endforeach;?>
	</ul>
<?php endif;?>
