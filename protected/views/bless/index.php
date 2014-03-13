<?php echo $this->renderPartial('/common/header',array('title'=>'送贺卡'));?>

<div id="content">
	<ul class="zcardlist">
		<?php 
    		if(!empty($category))
    			foreach ($category as $key => $value){
    	?>
		<li>
			<a href="<?php echo $this->createUrl('bless/first',array('id'=>$value['id'])); ?>"><img src="<?php echo $value['cover']; ?>" alt="<?php echo $value['title'];?>" /><span><?php echo $value['title'];?></span></a>
		</li>
		
		<?php }?>
	</ul>
</div>

<?php echo $this->renderPartial('/common/footer'); ?>
