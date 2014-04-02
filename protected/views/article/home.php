<?php echo $this->renderPartial('/common/header',array('title'=>'文章'));?>

<div id="content">
	<section class="se1 a_se1 fix">
		<a href="<?php echo $this->createUrl('create');?>">
			<span class="hd">发布文章</span>
			<span class="bd">
				<span class="p1">发布文章</span>
				<span class="p2">更醒目、个性化地展示您的大作，更显大气、更易传播、更好互动。</span>
			</span>
		</a>
	</section>
	<section class="se2 a_se2 fix">
		<a href="<?php echo $this->createUrl('mypublish');?>" class="a1">
			<span class="hd">文章管理</span>
			<span class="bd">
				<span class="p1">管理<em><?php echo $totalCreate;?></em></span>
				<span class="p2">管理我发布过的文章</span>
			</span>
		</a>
		<a href="<?php echo $this->createUrl('mymarked'); ?>" class="a2">
			<span class="hd">我的收藏</span>
			<span class="bd">
				<span class="p1">收藏<em><?php echo $totalMark; ?></em></span>
				<span class="p2">收藏的文章都在这里</span>
			</span>
		</a>
	</section>
</div>