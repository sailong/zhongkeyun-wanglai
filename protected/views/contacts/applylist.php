<?php echo $this->renderPartial('/common/header',array('title'=>'等待审核的成员'));?>

<div id="content">
	<ul class="adrlist reviewlist">
	<?php 
		if(!empty($members))
			foreach ($members as $contact)
			{
				$position = $contact->member->position;
				$company = $contact->member->company;
				$address = $contact->member->address;
	?>
				<li>
					<img src="<?php echo Member::model()->getPhoto($contact->member); ?>" alt="" class="upic" />
					<p class="p1"><?php echo $contact->member->name; ?></p>
					<?php if(!empty($position)):?>
						<p class="p3"><?php echo $position;?></p>
					<?php endif;?>
					<?php if(!empty($company)):?>
						<p class="p3"><?php echo $company; ?></p>
					<?php endif;?>
					<?php if(!empty($address)):?>
						<p class="p3"><?php echo $address; ?></p>
					<?php endif;?>
					<p class="p2">电话：<em><?php echo $contact->member->mobile; ?></em></p>
					<a href="#" class="agree" data-id="<?php echo $contact->id; ?>">同意</a>
					<a href="#" class="refuse" data-id="<?php echo $contact->id; ?>">拒绝</a>
				</li>
	<?php
			}
	?>
	</ul>
</div>

<?php echo $this->renderPartial('/common/footer'); ?>
<script>

$(function(){

	var single = function(){
		var url = {
			pass: "<?php echo $this->createUrl('contacts/pass'); ?>",
			refuse: "<?php echo $this->createUrl('contacts/refuse'); ?>"
		};

		var bind = function(){
			// 同意
			$(".reviewlist").delegate(".agree","click",function(){
				var id = $(this).data('id');
				var that = $(this);
				//$.get(url.pass+"&id="+id,function(data){
				$.get(url.pass+"/id/"+id,function(data){
					if(data.code == 1){
						that.text('已通过');
						that.attr("class",'res');
						that.next().remove();
					}else{
						alert(data.message);
					}
				},'JSON');
			});
		
			// 拒绝
			$(".reviewlist").delegate(".refuse","click",function(){
				var id = $(this).data('id');
				var that = $(this);
				//$.get(url.refuse+"&id="+id,function(data){
				$.get(url.refuse+"/id/"+id,function(data){
					if(data.code == 1){
						that.text('已拒绝');
						that.attr("class",'res');
						that.prev().remove();
					}else{
						alert(data.message);
					}
				},'JSON');
		
			});
		};

		return function(){
			bind();
		}; 	

	}();

	single();	
})
</script>