<?php 
	if(!empty($data))
		foreach ($data as $member)
		{
?>
			<li class="rbtn" data-id="<?php echo $member->id; ?>" data-uid="<?php echo $member->member_id; ?>" data-name="<?php echo $member->applicant->name; ?>" data-address="<?php echo $member->applicant->address; ?>" data-job="<?php echo $member->applicant->position; ?>" data-company="<?php echo $member->applicant->company; ?>">
				<img src="<?php echo Member::model()->getPhoto($member->applicant); ?>" class="upic" />
				<p class="p1"><?php echo $member->applicant->name; ?><?php if(Yii::app()->user->id == $member->member_id):?><span class="sponsor">本人</span><?php endif;?></p>
				<!-- 活动创建人 | 自己报名了看自己的信息 -->
				<?php if(($model->create_mid == Yii::app()->user->id) || (Yii::app()->user->id == $member->member_id)):?>
					<p class="p2">电话：<em><?php echo $member->applicant->mobile; ?></em></p>
				<?php else:?>		
				<?php 
					// 这些人是否关注了我
					$follows = Follow::model()->checkMultiFollow(Yii::app()->user->id, $memberIds);
					if($follows[$member->member_id])
						echo '<p class="p2">电话：<em>'.$member->applicant->mobile.'</em></p>';
				?>
				<?php endif;?>		
			</li>

<?php }?>