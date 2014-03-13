<?php 
if(!empty($data))
	foreach($data as $member)
	{
		$position = $member->position;
		$company = $member->company;
?>
		<li class="rbtn" data-uid="<?php echo $member->id; ?>" data-name="<?php echo $member->name; ?>" data-address="<?php echo $member->address; ?>" data-job="<?php echo $position; ?>" data-company="<?php echo $company; ?>">
			<img src="<?php echo Member::model()->getPhoto($member); ?>" alt="" class="upic" />
			<p class="p1">
			<?php echo $member->name;?>
				
			<?php if($myFollowInfo[$member->id]):?>
				<a href="javascript:;" data-uid="<?php echo $member->id; ?>" class="addto">取消关注</a>
			<?php else:?>
				<a href="javascript:;" data-uid="<?php echo $member->id; ?>" class="addto">+ 关注</a>
			<?php endif;?>
				
			<?php if($member->is_vip):?><img src="images/yellow_v.png" alt="" /><?php endif;?>
				
				
			</p>
			<?php if(!empty($position)):?>
				<p class="p3"><?php echo $position;?></p>
			<?php endif;?>
			<?php if(!empty($company)):?>
				<p class="p3"><?php echo $company; ?></p>
			<?php endif;?>
			<?php if($followMeInfo[$member->id]):?>
				<p class="p2">电话：<em><?php echo $member->mobile; ?></em></p>
			<?php endif;?>
		</li>
<?php }?>