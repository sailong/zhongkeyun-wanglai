<?php echo $this->renderPartial('/common/header',array('title'=>'我的通讯录'));?>

<div id="content">
	<nav class="tab">
		<a href="#" class="cur">我参与的<u></u></a>
		<span class="line">|</span>
		<a href="<?php echo $this->createUrl('contacts/myCreated'); ?>">我发起的</a>
	</nav>
	<ul class="adrlist fix">
	<?php 
		if(!empty($data))
			foreach ($data as $value)
			{
				$privacy = $value->contacts->privacy == Contacts::PRIVACY_PRIVATE ? true : false;
			
	?>
				<li <?php if($privacy):?>class="adr_s fix" <?php else:?>class="fix" <?php endif;?>>
					<a href="<?php echo $this->createUrl('contacts/view',array('id'=>Util::encode($value->contacts_id))); ?>">
						<span class="stype"><?php if($privacy):?>私密<?php else:?>公开<?php endif;?></span>
						<p class="p1"><?php echo $value->contacts->title; ?></p>
						<p class="p2"><span>人数:<?php echo isset($stat[$value->contacts->id]) ? $stat[$value->contacts->id] : 0; ?></span><span>浏览数:<?php echo $value->contacts->pv_counts; ?></span><span>发起人:<?php echo $value->contacts->creater->name; ?></span></p>
					</a>
				</li>
		<?php 
		
			}
		?>		
	</ul>
</div>

<?php echo $this->renderPartial('/common/footer'); ?>