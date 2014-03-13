<!-- 每个名片右侧显示的详情信息 -->

<section id="ucardinfo">
	<div class="close"><span class="cx">X</span></div>
	<ul>
	
	</ul>
	<a href="#" class="btn_b1" id="follow"></a>
</section>

<script>
$(function(){

	var single = function(){
		var param = {
			uid : <?php echo Yii::app()->user->id;?>,
			followInfo: <?php echo json_encode($followInfo); ?>,
			followUrl: "<?php echo $this->createUrl('member/follow') ;?>"
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
			showInfo();
		};

		// 显示圈友个人信息
		var showInfo = function(){
			var obj = $("#ucardinfo ul");
			if(info.name != '')
				obj.append('<li><span class="stit">姓名：</span><em id="j_name">'+info.name+'</em></li>');
			if(info.job != '')
				obj.append('<li><span class="stit">职位：</span><em id="j_job">'+info.job+'</em></li>');
			if(info.company != '')
				obj.append('<li><span class="stit">公司：</span><em id="j_company">'+info.company+'</em></li>');
			if(info.address != '')
				obj.append('<li><span class="stit">地址：</span><em id="j_address">'+info.address+'</em></li>');
			if(info.tel != '')
				obj.append('<li><span class="stit">手机：</span><a href="tel:'+info.tel+'" id="j_tel">'+info.tel+'</a></li>');
			var obj = $("#follow");
			obj.attr("href",param.followUrl+'&mid='+info.uid+'&from='+param.uid);
			if(param.followInfo[info.uid]){
				obj.text('取消关注');
			}else{
				obj.text('+ 关注');
			}
		};
		
		var bind = function(){
			// 显示详细信息
			$("body").delegate(".adrmemberlist li.rbtn","click",function(e){
				e ? e.stopPropagation() : event.cancelBubble = true;
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
				reset();
			});
			// 关注及取消关注
			$("body").delegate("#follow", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var that = $(this);
				$.get($(this).attr("href") + "&_time=" + new Date().getTime(),function(data){
					if(data.code == 1){
						// 变更关注状态 @todo 群友通讯录
						param.followInfo[info.uid] = param.followInfo[info.uid] == true ? false : true;
						that.text(data.message);
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