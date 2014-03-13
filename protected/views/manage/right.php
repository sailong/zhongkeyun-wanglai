<!-- 每个名片右侧显示的详情信息 -->

<section id="ucardinfo">
	<div class="close"><span class="cx">X</span></div>
	<ul>
	
	</ul>
	<a href="#" class="btn_b1" id="memberView">查看Ta的微名片</a>
	<a href="#" class="btn_b1" id="follow"></a>
</section>

<script>
$(function(){

	var single = function(){
		var param = {
			uid : <?php echo Yii::app()->user->id;?>,
			followInfo: <?php echo json_encode($followInfo); ?>,
			followUrl: "<?php echo $this->createUrl('member/follow') ;?>",
			searchUrl: "<?php echo Yii::app()->request->getRequestUri(); ?>",
			member: "<?php echo $this->createUrl('member/view'); ?>",   // 查看名片地址
			obj: null
		};

		// 每个圈友的个人信息
		var info = {
			uid: '',
			name: '',
			job: '',
			company: '',
			address: '',
			tel: ''
		};
		// 每查看一个用户,重置一次
		var reset = function(){
			$("#ucardinfo ul").empty();
			info.uid = '';
			info.name = '';
			info.job = '';
			info.company = '';
			info.address = '';
			info.tel = '';
			$("#follow").empty();
		};

		// 显示圈友个人信息
		var showInfo = function(){
			var obj = $("#ucardinfo ul");
			if(info.name != '')
				obj.append('<li><span class="stit">姓名：</span><em id="j_name">'+info.name+'</em></li>');
			if($.trim(info.job) != '')
				obj.append('<li><span class="stit">职位：</span><em id="j_job">'+info.job+'</em></li>');
			if($.trim(info.company) != '')
				obj.append('<li><span class="stit">公司：</span><em id="j_company">'+info.company+'</em></li>');
			if($.trim(info.address) != '')
				obj.append('<li><span class="stit">地址：</span><em id="j_address">'+info.address+'</em></li>');
			if(info.tel != '')
				obj.append('<li><span class="stit">手机：</span><a href="tel:'+info.tel+'" id="j_tel">'+info.tel+'</a></li>');
			//$("#memberView").attr("href",param.member + "&id="+info.uid);
			$("#memberView").attr("href",param.member + "/id/"+info.uid);
			var obj = $("#follow");
			//obj.attr("href",param.followUrl+'&mid='+info.uid+'&from='+param.uid);
			obj.attr("href",param.followUrl+'/mid/'+info.uid+'/from/'+param.uid);
			if(param.followInfo[info.uid]){
				obj.text('取消关注');
			}else{
				obj.text('+ 关注');
			}
		};
		
		var bind = function(){
			// 显示详细信息
			$("#content").delegate(".rbtn","click",function(e){
				e ? e.stopPropagation() : e.cancelBubble = true;
				reset();
				info.uid = $(this).data('uid');
				info.name = $(this).data('name');
				info.job = $(this).data('job');
				info.address = $(this).data('address');
				info.company = $(this).data('company');
				if($(this).data('mobile'))
					info.tel = $(this).data('mobile');
				else if($(this).find("p.p2").length>0)
					info.tel = $(this).find("em").text();
				$('body').toggleClass('right-page');
				param.obj = $(this);
				showInfo();
			});
			// 关注及取消关注
			$("#ucardinfo").delegate("#follow", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var that = $(this);
				//$.get($(this).attr("href") + "&_time=" + new Date().getTime(),function(data){
				$.get($(this).attr("href") + "/_time/" + new Date().getTime(),function(data){
					if(data.code == 1){
						// 变更关注状态 @todo 群友通讯录
						param.followInfo[info.uid] = param.followInfo[info.uid] == true ? false : true;
						that.text(data.message);
						param.obj.find(".addto").text(data.message);
					}
				},'JSON');
			});

			// 列表页关注及取消关注
			$("#content").delegate(".addto", "click", function(event){
				event.stopPropagation() ? event.stopPropagation() : event.cancelBubble=true;
				var that = $(this);
				that.attr("disabled","disabled");
				var uid = that.data('uid');
				//$.get(param.followUrl+"&mid="+uid+"&from="+param.uid+"&_time=" + new Date().getTime(),function(data){
				$.get(param.followUrl+"/mid/"+uid+"/from/"+param.uid+"/_time/" + new Date().getTime(),function(data){	
					that.removeAttr('disabled');
					if(data.code == 1){
						that.text(data.message);
						param.followInfo[uid] = param.followInfo[uid] == true ? false : true;
					}
				},'JSON');
			});

			// 搜索
			$("#content").delegate(":submit", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var keyword = $("input[name=keyword]").val();
				if( keyword == ''){
					alert('请输入搜索关键词');
					return false;
				}
				//$.get(param.searchUrl+"&keyword="+keyword,function(data){
				$.get(param.searchUrl+"/keyword/"+keyword,function(data){
					if(data.code == 1){
						$("ul.adrmemberlist").empty().html(data.message);
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