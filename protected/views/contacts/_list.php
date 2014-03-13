<li class="rbtn" data-uid="<?php echo $model->create_mid; ?>" data-name="<?php echo $model->creater->name; ?>" data-address="<?php echo $model->creater->address; ?>" data-job="<?php echo $model->creater->position; ?>" data-company="<?php echo $model->creater->company; ?>">
	<img src="<?php echo Member::model()->getPhoto($model->creater); ?>" alt="" class="upic" />
	<p class="p1"><?php echo $model->creater->name; ?><span class="sponsor">发起人</span></p>
	<?php if(!Yii::app()->user->getIsGuest()):?>
		<p class="p2">电话：<em><?php echo $model->creater->mobile; ?></em></p>
	<?php endif; ?>
</li>
<?php 
	if(!empty($members))
		foreach ($members as $countact)
		{
			if($countact->member_id == $model->create_mid) continue;
?>
		<li class="rbtn" data-id="<?php echo $countact->id; ?>" data-uid="<?php echo $countact->member_id; ?>" data-ustype="0" data-name="<?php echo $countact->member->name; ?>" data-address="<?php echo $countact->member->address; ?>" data-job="<?php echo $countact->member->position; ?>" data-company="<?php echo $countact->member->company; ?>">
			<img src="<?php echo Member::model()->getPhoto($countact->member); ?>" alt="" class="upic" />
				
			<p class="p1"><?php echo $countact->member->name; ?></p>
			
			<?php if(!Yii::app()->user->getIsGuest()):?>
				<?php if(Yii::app()->user->id == $countact->member_id):?>
					<p class="p2">电话：<em><?php echo $countact->member->mobile; ?></em></p>
				<?php elseif($model->privacy == Contacts::PRIVACY_PUBLIC && in_array(Yii::app()->user->id, $memberIds)):?>
					<?php if(!empty($countact->member->mobile)):?><p class="p2">电话：<em><?php echo $countact->member->mobile; ?></em></p><?php endif;?>
				<?php else:?>
				<?php 
					// 这些圈友是否关注了我
					$follows = Follow::model()->checkMultiFollow(Yii::app()->user->id, $memberIds);
					if($follows[$countact->member_id])
						echo '<p class="p2">电话：<em>'.$countact->member->mobile.'</em></p>';
				?>
				<?php endif;?>
			<?php endif;?>
		</li>
<?php 
	}
?>