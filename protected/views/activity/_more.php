<div class="actmore" id="anum">
	<a href="#" data-role="button" id="abtn">点击加载更多活动</a>
</div>

<script>
$(document).ready(function(){

	//点击加载更多
	var inum = $(".jslist li").length;
	var n = 0;
	if(inum<=10){
		$("#anum").hide();
	}else{
		$(".jslist").height($(".jslist li:eq(1)").outerHeight()*10);
	}
	
	$("#abtn").bind("click",function(){
		n+=1;
		var aheight = $(".jslist").height();
		if((n+1)*10>inum){
			var m = inum-n*10;
			$(".jslist").height(aheight+$(".jslist li:eq(1)").outerHeight()*m);
			$("#anum").hide();
		}else{
			$(".jslist").height(aheight+$(".jslist li:eq(1)").outerHeight()*10);
		}
	})
	
});



</script>