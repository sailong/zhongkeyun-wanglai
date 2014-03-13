<?php echo $this->renderPartial('/common/header',array('title'=>'我的通讯录'));?>

<div id="content">
	<nav class="tab">
		<a href="<?php echo $this->createUrl('contacts/myJoined'); ?>">我参与的</a>
		<span class="line">|</span>
		<a href="#" class="cur">我发起的<u></u></a>
	</nav>
	<ul class="adrlist fix">
	<?php 
		if(!empty($data))
			foreach ($data as $value)
			{
				$privacy = $value->privacy == Contacts::PRIVACY_PRIVATE ? true : false;
				$verify = isset($stat[$value->id]['apply']) && $stat[$value->id]['apply']>0 ? true : false;
				if($privacy && $verify)
					$class = 'adr_s review fix';
				elseif($privacy)
					$class = 'adr_s fix';
				elseif($verify)
					$class = 'review fix';
				else 
					$class = '';
					
	?>
					<li <?php if(!empty($class)) echo 'class="'.$class.'"';?>>
						<a href="<?php echo $this->createUrl('contacts/view',array('id'=>Util::encode($value->id))); ?>" <?php if($verify):?> class="ainfo" <?php endif; ?>>
							<span class="stype"><?php if($privacy):?>私密<?php else:?>公开<?php endif;?></span>
							<p class="p1"><?php echo $value->title; ?></p>
							<p class="p2"><span>人数:<?php echo isset($stat[$value->id]['pass']) ? $stat[$value->id]['pass'] : 0; ?></span><span>浏览数:<?php echo $value->pv_counts; ?></span></p>
						</a>
						<?php if($verify):?>
							<a href="<?php echo $this->createUrl('contacts/applyList',array('id'=>Util::encode($value->id))) ;?>" class="areview">
								<span><?php echo $stat[$value->id]['apply']; ?></span>
								<i>等待审核</i>
							</a>
						<?php endif;?>
					</li>
			<?php 
				}
			?>
	</ul>
	<ul class="noitem fix">
		<li>
			<p>需要梳理微信好友、收集通讯录？</p>
			<a href="<?php echo $this->createUrl('contacts/create'); ?>" class="btn_b1">发起新的群通讯录</a>
		</li>
	</ul>
</div>

<?php echo $this->renderPartial('/common/footer'); ?>
