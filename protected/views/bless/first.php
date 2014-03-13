<?php echo $this->renderPartial('/common/header',array('title'=>'三步定制贺卡'));?>

<div id="content">
	<h2 class="cardh2">第一步：挑美图</h2>
	<ul class="cardbglist">
		<?php  foreach ($category as $value):?>
		<li>
			<a href="<?php echo $this->createUrl('bless/second',array('id'=>$value['id'])); ?>">
				<p class="ctxt"><span class="ctl"><?php echo $value['title'];?></span><span class="ctr"></span></p>
				<p><img src="<?php echo $value['img'];?>" /></p>
			</a>
		</li>
		<?php endforeach;?>
	</ul>
</div>

<?php echo $this->renderPartial('/common/footer'); ?> 