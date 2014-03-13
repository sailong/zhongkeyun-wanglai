<?php $id = Util::encode($model->id);?>
<header id="header">
	<h1>微群通讯录</h1>
	<span class="mbtn">菜单</span>
	<?php if(Yii::app()->user->getIsGuest()):?>
		<a href="<?php echo $this->createUrl('contacts/apply'); ?>" class="hbtn">我要加入</a>
	<?php elseif(Yii::app()->user->id != $model->create_mid):?>
		<?php $relation = ContactsMember::model()->checkHasApply($model->id, Yii::app()->user->id);?>
		<?php if(!empty($relation) && $relation->state == ContactsMember::STATE_APPLY):?>
			<a href="javascript:;" class="hbtn">审核中</a>
		<?php elseif(empty($relation) || $relation->state != ContactsMember::STATE_PASS):?>
			<a href="javascript:;" class="hbtn" id="applyTop">我要加入</a>
		<?php endif;?>
	<?php endif;?>
</header>

<div id="content">
	<section class="adrinfo">
		<h2><?php echo $model->title; ?></h2>
		<p><?php echo $model->description; ?>
		<p class="p1">人数：<span><?php echo count($members); ?></span>类型：<span><?php echo Contacts::types($model->type);?></span>性质：<span><?php echo $model->privacy == Contacts::PRIVACY_PRIVATE ? '私密' : '公开'; ?></span><br />(<?php if($model->privacy == Contacts::PRIVACY_PRIVATE):?>手机号等隐私信息非公开，只有相互关注过微名片的群友才能看到彼此的手机号等隐私信息<?php else:?>群友加入后，手机号等隐私信息相互公开<?php endif;?>)</p>
	</section>
	<div class="searchbox">
		<input type="text" name="keyword" class="sinp" placeholder="输入要搜索的关键字" required /><input type="submit" value="搜索" class="sbtn" />
	</div>
	<ul class="adrlist adrmemberlist">
		<!-- 发起人 -->
		<?php $creater = $model->creater; ?>
		<li class="rbtn" data-uid="<?php echo $model->create_mid; ?>" data-name="<?php echo $model->creater->name; ?>" data-address="<?php echo $model->creater->address; ?>" data-job="<?php echo $model->creater->position; ?>" data-company="<?php echo $model->creater->company; ?>">
			<img src="<?php echo Member::model()->getPhoto($model->creater); ?>" alt="" class="upic" />
			<p class="p1"><?php echo $creater->name; ?>
			<span class="sponsor">发起人</span>
			<?php if(!Yii::app()->user->getIsGuest()):?>
				<?php if((Yii::app()->user->id != $creater->id) && (in_array(Yii::app()->user->id, $memberIds))): ?>
					<?php if($followInfo[$creater->id]):?><a href="javascript:;" data-uid="<?php echo $creater->id; ?>" class="addto">取消关注</a><?php else:?><a href="javascript:;" data-uid="<?php echo $creater->id; ?>" class="addto">+ 关注</a><?php endif;?>
				<?php endif;?>
			<?php endif;?>
			</p>
			<?php if(!empty($creater->position)):?>
				<p class="p3"><?php echo $creater->position;?></p>
			<?php endif;?>
			<?php if(!empty($creater->company)):?>
				<p class="p3"><?php echo $creater->company; ?></p>
			<?php endif;?>
			<?php if(!Yii::app()->user->getIsGuest()):?>
				<p class="p2">电话：<em><?php echo $model->creater->mobile; ?></em></p>
			<?php endif; ?>
		</li>
		<?php 
			if(!empty($members))
				foreach ($members as $countact)
				{
					if($countact->member_id == $model->create_mid) continue;
					$member = $countact->member;
					$position = $member->position;
					$company = $member->company;
					
		?>
				<li class="rbtn" data-id="<?php echo $countact->id; ?>" data-uid="<?php echo $countact->member_id; ?>" data-ustype="0" data-name="<?php echo $member->name; ?>" data-address="<?php echo $member->address; ?>" data-job="<?php echo $position; ?>" data-company="<?php echo $company; ?>">
					<img src="<?php echo Member::model()->getPhoto($member); ?>" alt="" class="upic" />
					<p class="p1"><?php echo $member->name; ?>
					<?php if(!Yii::app()->user->getIsGuest()):?>
						<?php if($followInfo[$countact->member_id]):?>
							<a href="javascript:;" data-uid="<?php echo $countact->member_id; ?>" class="addto">取消关注</a>
						<?php else:?>
							<a href="javascript:;" data-uid="<?php echo $countact->member_id; ?>" class="addto">+ 关注</a>
						<?php endif;?>
					<?php endif;?>
					</p>
					
					<?php if(!empty($position)):?>
						<p class="p3"><?php echo $position;?></p>
					<?php endif;?>
					<?php if(!empty($company)):?>
						<p class="p3"><?php echo $company; ?></p>
					<?php endif;?>
					<p class="p2">
					
					<?php if(!Yii::app()->user->getIsGuest()):?>
						<!-- 创建人 || 查看自己的 -->
						<?php if(Yii::app()->user->id == $countact->member_id || Yii::app()->user->id == $model->create_mid):?>
							<span>电话：<em><?php echo $member->mobile; ?></em></span>
						<?php elseif($model->privacy == Contacts::PRIVACY_PUBLIC && in_array(Yii::app()->user->id, $memberIds)):?>
							<?php if(!empty($member->mobile)):?><span>电话：<em><?php echo $member->mobile; ?></em></span><?php endif;?>
						<?php else:?>
						<?php 
							// 这些圈友是否关注了我
							$follows = Follow::model()->checkMultiFollow(Yii::app()->user->id, $memberIds);
							if($follows[$countact->member_id])
								echo '<span>电话：<em>'.$member->mobile.'</em></span>';
						?>
						<?php endif;?>
					</p>
					<?php endif;?>
				</li>
				
			<?php 
				}
			?>
	</ul>
	
	<section class="btngroup" id="anum">
		<a href="javascript:;" class="btn_b5" id="abtn">点击加载剩余<span id="mnum"></span>人</a>
	</section>
	
	<?php if(Yii::app()->user->getIsGuest()):?>
		<a href="<?php echo $this->createUrl('contacts/apply'); ?>" class="btn_b1">我要加入</a>
	<?php elseif(Yii::app()->user->id != $model->create_mid):?>
		<?php $relation = ContactsMember::model()->checkHasApply($model->id, Yii::app()->user->id);?>
		<?php if(!empty($relation) && $relation->state == ContactsMember::STATE_APPLY):?>
			<a href="javascript:;" class="btn_b1 wait">等待创建人<?php echo $model->creater->name; ?>审核</a>
		<?php elseif(empty($relation) || $relation->state != ContactsMember::STATE_PASS):?>
			<a href="javascript:;" class="btn_b1" id="applyBottom">我要加入</a>
		<?php endif;?>
	<?php endif;?>
	
	<?php if(Yii::app()->user->id == $model->create_mid):?>
			<?php $applyCount = ContactsMember::model()->countApply($model->id);?>
			<?php if($applyCount>0):?>
				<a href="<?php echo $this->createUrl('contacts/applyList',array('id'=>Util::encode($model->id)));?>" class="btn_b1">待审核（<?php echo $applyCount;?>）</a>
			<?php endif;?>
	<?php endif;?>
	
	<?php if($model->create_mid == Yii::app()->user->id):?>
		 <a href="javascript:;" id="prom" class="btn_b1">发送通讯录列表到我的邮箱</a>
	<?php endif;?>
	<a href="<?php echo $this->createUrl('contacts/create'); ?>" class="btn_b2">我也要创建通讯录</a>
	
	<?php echo $this->renderPartial('/common/share'); ?>
</div>

<footer id="footer">
	<span class="mbtn"><img src="/static/images/menu.png" alt="" />菜单</span>
	<a href="javascript:window.history.go(-2);" class="goback"><img src="/static/images/goback.png" alt="" />返回</a>
	<?php if(Yii::app()->user->id == $model->create_mid):?>
		<a href="<?php echo $this->createUrl('contacts/update',array('id'=>$id)); ?>" class="eac"><img src="/static/images/eac.png" alt="" />编辑</a>
	<?php endif;?>	
</footer>

<!-- 每个名片的详细内容 -->
<section id="ucardinfo">
	<div class="close"><span class="cx">X</span></div>
	<ul>
		
	</ul>
	<a href="#" class="btn_b1" id="memberView">查看Ta的微名片</a>
</section>


<script>

$(function(){

	//点击加载更多(初期方案)
	var inum = $("ul.adrlist li").length;
	var n = 0;
	if(inum<=20){
		$("#anum").hide();
	}else{
		$("ul.adrlist li:gt(19)").hide();
		$("#mnum").text(inum-20);
	}
	$("#abtn").bind("click",function(){
		n+=1;
		if((n+1)*20>inum){
			$("ul.adrlist li").show();
			$("#anum").hide();
		}else{
			$("ul.adrlist li:lt("+(n+1)*20+")").show();
			$("#mnum").text(inum-20*(n+1));
		}
	});

	var single = function(){
		// 是否已登录
		var login = false;

		var send = false; // 是否sending mail
		
		// 当前用户和群里其它用户的关注关系
		<?php if(!Yii::app()->user->getIsGuest()):?>
			var follow = <?php echo json_encode($followInfo);?>;
		<?php endif;?>
		
		// 各请求地址
		var url = {
			apply : "<?php echo $this->createUrl('contacts/apply'); ?>",
			remove: "<?php echo $this->createUrl('contacts/remove'); ?>",
			quit  : "<?php echo $this->createUrl('contacts/quit'); ?>",
			member: "<?php echo $this->createUrl('member/view'); ?>",   // 查看名片地址
			follow: "<?php echo $this->createUrl('member/follow'); ?>",  // 关注及取消关注
			search: "<?php echo $this->createUrl('contacts/search'); ?>", // 搜索地址
			sendMail:"<?php echo $this->createUrl('contacts/sendMail',array('id'=>$id)); ?>"
		};				

		var param = {
			email: "<?php echo $this->_member->email;?>",
			contactsId:  "<?php echo $id; ?>",     //当前通讯录的id
			creater :    "<?php echo $model->creater->name; ?>",		// 名片创建人姓名
			creater_mid: "<?php echo $model->create_mid; ?>",           // 创建人的id
			uid:         "<?php echo Yii::app()->user->id; ?>",  		// 当前登录用户的id
			memberId:   0,								               // 通讯录里任意一个用户的id
			id:         0,                                      	   // 报名id
			obj: null,												   // 某一个成员对象
			members:<?php echo json_encode($memberIds); ?>,
			isMember:false
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
		// 初始化,是否登录
		var init = function(){
			if(param.uid > 0){
				login = true;
				for(var mid in param.members){
					if(param.members[mid] == param.uid){
						param.isMember = true;
						break;
					}
				}
			}
		};

		// 每查看一个用户,重置一次
		var reset = function(){
			$("#ucardinfo ul").empty();
			$("#memberView").nextAll().remove();
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
			$("#memberView").attr("href",url.member + "/id/"+param.memberId);
		};

		// 按需显示各选项
		var showButton = function(){
			// 显示移除该通讯录
			var showRemove = function(){
				// 已登录 && 是创建人 && 不移除自己
				if(login && (param.uid == param.creater_mid) && (param.memberId != param.uid)){
					var html = '<a href="'+url.remove+'/id/'+param.id+'" class="btn_b1" id="remove">移出该通讯录</a>';
					$("#ucardinfo").append(html);
				}		
			};
			// 是否显示关注及取消关注
			var showFocus = function(){
				// 已登录 && 不是查看自己的信息 && 未关注过
				if(login && param.isMember){
					if(param.uid != param.memberId){
						if(follow[param.memberId] == false)
							html = '<a href="'+url.follow+'/mid/'+param.memberId+'/from/'+param.uid+'" class="btn_b1" id="follow">+ 关注</a>';
						else
							html = '<a href="'+url.follow+'/mid/'+param.memberId+'/from/'+param.uid+'" class="btn_b1" id="follow">取消关注</a>';
							
						$("#ucardinfo").append(html);
					}
				}
				/**
				if(!login){
					var html = '<a href="'+url.follow+'" class="btn_b1">+ 关注</a>';
					$("#ucardinfo").append(html);
				}else if(param.uid != param.memberId){
					if(follow[param.memberId] == false)
						html = '<a href="'+url.follow+'&mid='+param.memberId+'&from='+param.uid+'" class="btn_b1" id="follow">+ 关注</a>';
					else
						html = '<a href="'+url.follow+'&mid='+param.memberId+'&from='+param.uid+'" class="btn_b1" id="follow">取消关注</a>';
					$("#ucardinfo").append(html);
				}
				**/
			};
			// 显示退出通讯录
			var showQuit = function(){
				// 已登录 && 非创建人 && 查看自己的信息
				if(login && (param.uid != param.creater_mid) && (param.uid == param.memberId)){
					var html = '<a href="'+url.quit+'/id/'+param.id+'" class="btn_b1" id="quit">退出该通讯录</a>';
					$("#ucardinfo").append(html);
				}
			};
			// 各选项的显示顺序
			return function(){
				showRemove();
				showFocus();
				showQuit();
			};
		}();
		
		var bind = function(){
			// 查看申请人信息
			$("#content").delegate(".rbtn","click",function(event){
				event.stopPropagation();
				reset();
				info.uid = $(this).data('uid');
				info.name = $(this).data('name');
				info.job = $(this).data('job');
				info.address = $(this).data('address');
				info.company = $(this).data('company');
				if($(this).find("p.p2").length>0)
					info.tel = $(this).find("em").text();
				$('body').toggleClass('right-page');
				param.obj = $(this);
				param.memberId = $(this).data('uid');
				param.id = $(this).data('id');
				showButton();   // 重新显示应该显示的选项
				showInfo();
			});

			
			// top apply
			$("body").delegate("#applyTop", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var that = $(this);
				$.get(url.apply+"/id/"+param.contactsId + "/_time/" + new Date().getTime(),function(data){
					if(data.code == 1){
						that.removeAttr("id").text('审核中');
						that.unbind("click");
						var bottomApply = $("#applyBottom");
						bottomApply.removeAttr("id");
						bottomApply.addClass('wait').text('等待创建人'+param.creater+'的审核');
					}
				},'JSON');
			});
			// 申请加入 bottom APPly
			$("#content").delegate("#applyBottom", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var that = $(this);
				$.get(url.apply+"/id/"+param.contactsId + "/_time/" + new Date().getTime(),function(data){
					if(data.code == 1){
						that.removeAttr("id").addClass('wait').text('等待创建人'+param.creater+'的审核');
						that.unbind("click");
						var topApply = $("#applyTop");
						topApply.removeAttr("id");
						topApply.text('审核中');
					}
				},'JSON');
			});
			// 退出群
			$("#ucardinfo").delegate("#quit", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var that = $(this);
				$.get($(this).attr("href") + "/_time/" + new Date().getTime(),function(data){
					if(data.code == 1){
						that.remove();
						param.obj.remove();
					}
				},'JSON');
			});
			// 移出群
			$("#ucardinfo").delegate("#remove", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var that = $(this);
				$.get($(this).attr("href") + "/_time/" + new Date().getTime(),function(data){
					if(data.code == 1){
						that.remove();
						param.obj.remove();
					}
				},'JSON');
			});
			// 关注及取消关注
			$("#ucardinfo").delegate("#follow", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				var that = $(this);
				$.get($(this).attr("href") + "/_time/" + new Date().getTime(),function(data){
					if(data.code == 1){
						that.text(data.message);
						follow[info.uid] =follow[info.uid] == true ? false : true;
						param.obj.find(".addto").text(data.message);
					}
				},'JSON');
			});

			// 列表页关注及取消关注
			$("#content").delegate(".addto", "click", function(event){
				event.stopPropagation() ? event.stopPropagation() : event.cancelBubble=true;
				var that = $(this);
				that.attr("disabled","disabled");
				var uid = $(this).data('uid');
				$.get(url.follow+"/mid/"+uid+"/from/"+param.uid+"/_time/" + new Date().getTime(),function(data){
					that.removeAttr('disabled');
					if(data.code == 1){
						that.text(data.message);
						follow[uid] =follow[uid] == true ? false : true;
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
				
				$.get(url.search+"/id/"+param.contactsId+"/keyword/"+keyword,function(data){
					if(data.code == 1){
						$("ul.adrmemberlist").empty().html(data.message);
					}
				},'JSON');
			});
			
			// 输入邮箱
			$("#content").delegate("#prom", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				if(param.email == ''){
					var email=prompt("请输入您的邮箱地址","");
				    if(!email){
				    	return false;
				    }
				    param.email = email;
				}
				var that = $(this);
				if(!send){
					send = true;
					that.addClass('btn_b3');
					$.get(url.sendMail+'/email/'+param.email, function(result){
						if(result.code == 1){
							alert('发送成功');
						}else{
							alert(result.message);
							param.email = '';
						}
						that.removeClass('btn_b3');
						send = false;
					},'JSON');
				}
			});
		};
		// return
		return function(){
			init();
			bind();
		};
	}();

	single();
	
	// 微信分享
	dataForWeixin.callback = function () {
		$.get("<?php echo $this->createUrl('contacts/countShare',array('id'=>$id)); ?>");
	}
	dataForWeixin.url = "<?php echo $this->createUrl('contacts/view',array('id'=>$id,'#'=>'mp.weixin.qq.com')); ?>";
	dataForWeixin.title = '<?php echo $model->title;?>';
	dataForWeixin.desc = '<?php echo str_replace("\r\n","",$model->description);?>';
	dataForWeixin.weibodesc = '#微名片#，这是<?php echo $model->title;?>的微名片，请大家惠存，你也来制作自己的微名片吧！';
	dataForWeixin.MsgImg = "<?php echo Yii::app()->request->getBaseUrl(true);?>/static/weixin/wq.jpg";

})
</script>