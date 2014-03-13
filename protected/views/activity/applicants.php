<?php echo $this->renderPartial('/common/header',array('title'=>'活动报名成员'));?>

<div id="content">
	<div class="searchbox">
		<input type="text" name="keyword" class="sinp" placeholder="输入要搜索的关键字" required /><input type="submit" value="搜索" class="sbtn" />
	</div>
	<ul class="adrlist adrmemberlist">
		<?php 
			if(!empty($data))
				foreach ($data as $member)
				{
					$applicant = $member->applicant;
					$position = $applicant->position;
					$company = $applicant->company;
		?>
		<li class="rbtn" data-id="<?php echo $member->id; ?>" data-uid="<?php echo $member->member_id; ?>" data-name="<?php echo $applicant->name; ?>" data-address="<?php echo $applicant->address; ?>" data-job="<?php echo $position; ?>" data-company="<?php echo $company; ?>">
			<img src="<?php echo Member::model()->getPhoto($applicant); ?>" class="upic" />
			<p class="p1"><?php echo $applicant->name; ?>
			
			<?php if(Yii::app()->user->id != $member->member_id):?>
				<?php if(Yii::app()->user->getIsGuest()):?>
					<a href="<?php echo $this->createUrl('member/follow'); ?>" class="addto">+ 关注</a>
				<?php else:?>
					<?php if($followInfo[$member->member_id]):?><a href="javascript:;" data-uid="<?php echo $member->member_id; ?>" class="addto">取消关注</a><?php else:?><a href="javascript:;" data-uid="<?php echo $member->member_id; ?>" class="addto">+ 关注</a><?php endif;?>
				<?php endif;?>
			<?php endif;?>
			
			<?php if(Yii::app()->user->id == $member->member_id):?><span class="sponsor">本人</span><?php endif;?>
			
			</p>
			<?php if(!empty($position)):?>
				<p class="p3"><?php echo $position;?></p>
			<?php endif;?>
			<?php if(!empty($company)):?>
				<p class="p3"><?php echo $company; ?></p>
			<?php endif;?>
			
			<!-- 活动创建人 | 自己报名了看自己的信息 -->
			<?php if(($model->create_mid == Yii::app()->user->id) || (Yii::app()->user->id == $member->member_id)):?>
				<p class="p2">电话：<em><?php echo $applicant->mobile; ?></em></p>
			<?php else:?>		
			<?php 
				// 这些人是否关注了我
				$follows = Follow::model()->checkMultiFollow(Yii::app()->user->id, $memberIds);
				if($follows[$member->member_id])
					echo '<p class="p2">电话：<em>'.$applicant->mobile.'</em></p>';
			?>
			<?php endif;?>		
		</li>
		<?php }?>
		
	</ul>
</div>

<?php echo $this->renderPartial('/common/footer'); ?>

<section id="ucardinfo">
	<div class="close"><span class="cx">X</span></div>
	<ul>
		
	</ul>
	<a href="#" class="btn_b1" id="memberView">查看Ta的微名片</a>
	
</section>

<script>
$(function(){

	var single = function(){

		var followInfo = <?php echo !empty($followInfo) ? json_encode($followInfo) : json_encode(array()); ?>;

		var url = {
			 follow:"<?php echo $this->createUrl('member/follow'); ?>",  // 关注及取消关注
			 cancel:"<?php echo $this->createUrl('activity/cancel'); ?>", //取消活动报名
			 search:"<?php echo $this->createUrl('activity/search'); ?>",
			 member:"<?php echo $this->createUrl('member/view'); ?>",   // 查看名片地址
		};

		var param = {
			activityId:  "<?php echo $model->id; ?>",     //当前活动id
			creater_mid: "<?php echo $model->create_mid; ?>",           // 创建人的id
			uid:         "<?php echo Yii::app()->user->id; ?>",  		// 当前登录用户的id
			memberId:   0,								               // 通讯录里任意一个用户的id
			id:         0,                                      	   // 报名id
			obj: null												   // 某一个成员对象
		};

		// 每个圈友的个人信息
		var info = {
			uid:'',
			name: '',
			job: '',
			company: '',
			address: '',
			tel: ''
		};

		// 每查看一个用户,重置一次
		var reset = function(){
			$("#ucardinfo ul").empty();
			$("#cancel").remove();
			$("#follow").remove();
			info.uid = '';
			info.name = '';
			info.job = '';
			info.company = '';
			info.address = '';
			info.tel = '';
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
			$("#memberView").attr("href",url.member + "/id/"+info.uid);
			if(info.uid != param.uid){
				var followUrl = url.follow+'/mid/'+info.uid+'/from/'+param.uid;
				if(followInfo[info.uid]){
					$("#ucardinfo").append('<a href="'+followUrl+'" class="btn_b1" id="follow">取消关注</a>');
				}else{
					$("#ucardinfo").append('<a href="'+followUrl+'" class="btn_b1" id="follow">+ 关注</a>');
				}
			}

			if(param.uid == info.uid && param.uid != param.creater_mid)
				$("#ucardinfo").append('<a href="'+url.cancel+'/id/'+param.id+'" class="btn_b1" id="cancel">取消报名</a>');
		};

		var bind = function(){
			// 显示详细信息
			
			$("#content").delegate(".rbtn", "click",function(e){
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
				param.id = $(this).data('id');
				param.obj = $(this);
				showInfo();
			});
			// 关注及取消关注
			$("#ucardinfo").delegate("#follow", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var that = $(this);
				$.get($(this).attr("href") + "/_time/" + new Date().getTime(),function(data){
					if(data.code == 1){
						// 变更关注状态 @todo 群友通讯录
						followInfo[info.uid] = followInfo[info.uid] == true ? false : true;
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
				$.get(url.follow+"/mid/"+uid+"/from/"+param.uid+"/_time/" + new Date().getTime(),function(data){
					that.removeAttr('disabled');
					if(data.code == 1){
						that.text(data.message);
						followInfo[uid] = followInfo[uid] == true ? false : true;
					}
				},'JSON');
			});
			// 取消报名
			$("#ucardinfo").delegate("#cancel", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var that = $(this);
				$.get($(this).attr("href") + "/_time/" + new Date().getTime(),function(data){
					if(data.code == 1){
						that.remove();
						param.obj.remove();
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
				$.get(url.search+"/id/"+param.activityId+"/keyword/"+keyword,function(data){
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