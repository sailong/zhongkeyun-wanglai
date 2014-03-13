<?php 
	if(!empty($data))
		foreach ($data as $member)
		{
?>
	<li class="rbtn" data-uid="<?php echo $member->id;?>" <?php if($followMe[$member->id]):?>data-mobile="<?php echo $member->mobile; ?>"<?php endif;?> data-name="<?php echo $member->name; ?>" data-address="<?php echo $member->address; ?>" data-job="<?php echo $member->position; ?>" data-company="<?php echo $member->company; ?>">
		<div class="upic">
			<p class="p3"><?php echo $member->score;?></p>
			<p class="p4">好友排名</p>
		</div>
		<p class="p1"><?php echo $member->name; ?></p>
		<p class="p2"><span>访客人数：<?php echo $member->views; ?></span> <span>全球排名：<?php echo Member::model()->getOrder($member->views); ?></span></p>
	</li>
<?php 
		$i++;
	 }
 ?>