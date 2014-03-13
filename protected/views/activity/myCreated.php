<header id="header">
	<h1>我的活动</h1>
	<span class="mbtn">菜单</span>
	<a href="<?php echo $this->createUrl('activity/create');?>" class="hbtn">发起活动</a>
</header>

<div id="content">
	<nav class="tab">
		<a href="<?php echo $this->createUrl('activity/myCreated'); ?>" class="cur">我发起的<u></u></a>
		<span class="line">|</span>
		<a href="<?php echo $this->createUrl('activity/myJoined'); ?>">我参与的</a>
	</nav>
	<div class="space"></div>
	
	
	<?php 
		if(!empty($data))
		{
			echo '<ul id="actlist">';
			foreach ($data as $activity)
			{
		?>
				<li <?php if(time()>$activity->end_time && strtotime(date('Y-m-d',time())) > strtotime(date('Y-m-d',$activity->end_time))):?> class="act_end"<?php endif;?>>
					<a href="<?php echo $this->createUrl('activity/detail', array('id'=>$activity->id)); ?>">
						<span class="datebox">
							<span class="dated"><?php echo date('m月d日',$activity->begin_time); ?></span>
							<span class="datew"><?php echo Util::getLocalWeek($activity->begin_time); ?></span>
						</span>  
						<h3><?php echo $activity->title; ?></h3>  
						<?php if(!empty($activity->subject)):?><p>主题：<?php echo $activity->subject; ?><?php endif;?></p>
						<p><?php echo District::model()->getCity($activity->province,$activity->area); ?>&nbsp;|&nbsp;<?php echo date('Y年m月d日',$activity->begin_time); ?></p>
						<p class="ui-act-bottom">已报名：<?php echo ActivityMember::model()->countApplicants($activity->id); ?>&nbsp;&nbsp;|&nbsp;&nbsp;浏览：<?php echo $activity->views;?>&nbsp;&nbsp;|&nbsp;&nbsp;转发：<?php echo $activity->shares; ?></p>
					</a>
				</li>
		<?php 
			} 
			echo '</ul>';
		?>
		
			<section class="btngroup" id="anum">
					<a href="javascript:;" class="btn_b1" id="abtn">点击加载更多</a>
			</section>
	
	<?php }?>
</div>

<?php echo $this->renderPartial('/common/footer'); ?>

<script>

$(function(){

	//点击加载更多(初期方案)

	var inum = $("#actlist li").length;
	var n = 0;
	if(inum<=10){
		$("#anum").hide();
	}else{
		$("#actlist li:gt(9)").hide();
	}
	$("#abtn").bind("click",function(){
		n+=1;
		if((n+1)*10>inum){
			$("#actlist li").show();
			$("#anum").hide();
		}else{
			$("#actlist li:lt("+(n+1)*10+")").show();
		}
	});

	/**
	var inum = $("#actlist li").length;
	var n = 0;
	if(inum<=10){
		$("#anum").hide();
	}else{
		$("#actlist").height($("#actlist li:eq(1)").outerHeight()*10);
	}
	$("#abtn").bind("click",function(){
		n+=1;
		var aheight = $("#actlist").height();
		if((n+1)*10>inum){
			var m = inum-n*10;
			$("#actlist").height(aheight+$("#actlist li:eq(1)").outerHeight()*m);
			$("#anum").hide();
		}else{
			$("#actlist").height(aheight+$("#actlist li:eq(1)").outerHeight()*10);
		}
	})
	**/

})
</script>
