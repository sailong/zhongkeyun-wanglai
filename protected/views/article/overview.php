<?php echo $this->renderPartial('/common/header',array('title'=>'文章预览'));?>

<div id="content">
	<?php 
		$updateUrl = $this->createUrl('article/update',array('id'=>$model->id));
		$publishUrl = $this->createUrl('article/publish',array('id'=>$model->id));
	
	?>
	<section class="cmp_btnb fix">
		<a href="<?php echo  $updateUrl; ?>" class="cmp_btnbl btn_b2">返回修改</a>
		<a href="<?php echo $publishUrl; ?>" class="cmp_btnbr btn_b2">确定发布</a>
	</section>
	<aside class="article_tit">
		<h1><?php echo $model->title;?></h1>
		<p><?php echo date('Y-m-d  H:i', $model->create_time); ?> <a href="<?php echo $this->createUrl('mypublish'); ?>"><?php echo $model->creater->name;?></a> 阅读<?php echo $model->views; ?>  分享<?php echo $model->share_counts; ?></p>
	</aside>
	<article class="article_content">
	
	<?php 
			$details = $model->content->content;
			preg_match_all('/(.*)\\n?/', $details,$matches);
 			if(!empty($matches[1]))
 			{
 				$details = '';
 				foreach ($matches[1] as $match)
	 				{
 					$details .= "<p>".$match."</p>";
 				}
 			}
 			echo $details;
	?>
	</article>
	<section class="cmp_btnb fix">
		<a href="<?php echo $updateUrl; ?>" class="cmp_btnbl btn_b2">返回修改</a>
		<a href="<?php echo $publishUrl; ?>" class="cmp_btnbr btn_b2">确定发布</a>
	</section>
</div>