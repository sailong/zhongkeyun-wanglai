<header id="header">
	<h1 id="allact">全部活动<img src="/static/images/moreact.png" alt="" /></h1>
	<span class="mbtn">菜单</span>
	<a href="<?php echo $this->createUrl('activity/create'); ?>" class="hbtn">创建</a>
</header>
<section id="acitybox">
 <?php 
	if(!empty($calculation))
	{
		echo '<ul>';
		foreach ($calculation as $value)
		{
			echo '<li><a name="choiceCity" href="#" value="'.$value['id'].'">'.$value['name'].'('.$value['total'].')</a></li>';
		}
		echo '</ul>';
		echo '<u></u>';
	}
?>
</section>
<div id="content">
	<div class="searchbox">
		<form method="GET" id="form1">
			<input type="text" class="sinp" placeholder="输入要搜索的关键字" name="keyword" required />
			<input type="submit" value="搜索" class="sbtn" />
		</form>
	</div>
	<ul id="actlist">
		<?php echo $this->renderPartial('_list',array('data'=>$data)); ?>
	</ul>
	<section class="btngroup" id="anum">
		<a href="javascript:;" class="btn_b1" id="abtn">点击加载更多</a>
	</section>
	<section class="sharebox fix">
		<a href="javascript:;" class="sharebtnl">发送给朋友</a>
		<a href="javascript:;" class="sharebtnr">分享到朋友圈</a>
	</section>
</div>

<script>

$(function(){

	// 加载更多
	var more = function(){
		var params = {
			pageSize:100, //100个分页 
			count:0,  //当前页面活动总数
			page:1,
			length:0,
			showItem:10,
			n:0,
			url:"<?php echo $this->createUrl('activity/more');?>",
			cid:0,
			keyword:'',
		};
	
		var init = function(){
			$("#anum").show();
			params.length = $("#actlist li:eq(1)").outerHeight();
			params.count = $("#actlist li").length;
			params.n = 1;
			if(params.count<=params.showItem){
				$("#anum").hide();
				$("#actlist").height(params.length*params.count);
			}else{
				$("#actlist").height(params.length*params.showItem);
			}
		};
	
		var getHeight = function(){
			return $("#actlist").height();
		};
		// 当前页加载更多
		var more = function(){
			var height = getHeight();
			if((params.n) * params.showItem > params.count)
			{
				$("#actlist").height(height+params.length*(params.count-(params.n-1)*params.showItem));
				$("#anum").hide();
			}else{
				$("#actlist").height(height+params.length*params.showItem);
			}
		};
		// 搜索
		var search = function(){
			var url = params.url + "&keyword="+params.keyword;
			pull(url);
		};
	
		// 下拉框搜索
		var select = function(){
			var url = params.url + "&cid="+params.cid;
			pull(url);
		};
	
		var pull = function(url){
			$.get(url,function(data){
				if(data.code == 1){
					$("#actlist").html(data.message);
					$('html, body').animate({scrollTop: "0px"}, 300);
					init();
				}else{
					alert(data.msg);
				}
			},'json');
		};
	
		var nextPage = function(){
			params.page += 1;
			var url = params.url + "&page="+params.page+"&cid="+params.cid+"&keyword="+params.keyword;
			pull(url);
		};
	
		var bind = function(){
			if(params.length>params.showItem){
				$("#abtn").bind("click",function(){
					params.n += 1;
					if(params.n<=10)
						more();
					else
						nextPage();
				});
			}
			// 点击城市搜索
			$("ul li a[name=choiceCity]").bind("click",function(event){
				event.stopPropagation();
				$("#acitybox").hide();
				params.cid = $(this).attr('value');
				select();
				return false;
			});
	
			// 搜索关键词
			$(":submit").bind("click",function(){
				params.keyword = $("input[name=keyword]").val();
				search();
				return false;
			});
		};
	
		return function(){
			init();
			bind();
		};

	}();

	more();
	
})
</script>