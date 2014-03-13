<?php echo $this->renderPartial('/common/header',array('title'=>'等待审核的成员'));?>

<div id="content">
	<ul class="adrlist reviewlist">
	<?php 
		if(!empty($data))
			foreach ($data as $member)
			{
	?>
		<li>
			<img src="<?php echo Member::model()->getPhoto($member->applicant); ?>" class="upic" />
			<p class="p1"><?php echo $member->applicant->name; ?></p>
			<p class="p2">电话：<em><?php echo $member->applicant->mobile; ?></em></p>
			<a href="#" class="agree" data-id="<?php echo $member->id; ?>">同意</a>
			<a href="#" class="refuse" data-id="<?php echo $member->id; ?>">拒绝</a>
		</li>
		
	<?php }?>
	
	</ul>
</div>

<?php echo $this->renderPartial('/common/footer'); ?>

<script>

$(function(){

	var single = function(){
		var url = {
			pass: "<?php echo $this->createUrl('activity/pass'); ?>",
			refuse: "<?php echo $this->createUrl('activity/refuse'); ?>"
		};

		var bind = function(){
			// 同意
			$(".reviewlist").delegate(".agree","click",function(){
				var id = $(this).data('id');
				var that = $(this);
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