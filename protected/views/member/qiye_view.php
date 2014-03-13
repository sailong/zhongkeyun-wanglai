<?php echo $this->renderPartial('/common/header',array('title'=>'企业微名片'));?>

<div id="content">
	<section class="cardinfobox">
		<?php
			$photo = Member::model()->getPhoto($model,'s');
			$big = Member::model()->getPhoto($model,'b');
		?>
		<div class="uphoto cmp_uphoto">
			<?php if(!empty($big)) :?>
				<a href="javascript:;" id="bigpic"><img src="<?php echo $photo; ?>" alt="" /></a>
			<?php else:?>
				<img src="<?php echo $photo;?>" alt="" />
			<?php endif;?>
			<span class="cmp_icn">企</span>
		</div>
		<div class="uname cmp_uname">
			<h2 class="cmp_name"><a href="#"><?php echo $model->name;?></a></h2>
			<p class="cmp_fc">(企业资料已认证)</p><!--<p class="cmp_sc">(企业资料已认证)</p>-->
		</div>
		<div class="utotal cmp_utotal">
			<div class="utbox fix">
				<?php if(Yii::app()->user->id == $model->id):?>
					<a href="<?php echo $this->createUrl('manage/followMe'); ?>"><em><?php echo Follow::model()->calculateFollowMe($model->id); ?></em><br />被关注</a>
					<a href="<?php echo $this->createUrl('manage/myViewer'); ?>"><em><?php echo ViewLog::model()->calculateMyViewer($model->id);?></em><br />访客人数</a>
					<?php $contacts = Contacts::model()->findByAttributes(array('create_mid'=>Yii::app()->user->id,'default'=>Contacts::DEFAULT_YES));?>
					<a href="<?php echo $this->createUrl('contacts/view',array('id'=>Util::encode($contacts->id))); ?>"><em><?php echo Employee::getTotalEmployee($model->id); ?></em><br />员工人数</a>
				<?php else:?>
					<a href="javascript:;"><em><?php echo Follow::model()->calculateFollowMe($model->id); ?></em><br />被关注</a>
					<a href="javascript:;"><em><?php echo ViewLog::model()->calculateMyViewer($model->id);?></em><br />访客人数</a>
					<a href="javascript:;"><em><?php echo Employee::getTotalEmployee($model->id); ?></em><br />员工人数</a>
				<?php endif;?>
			</div>
		</div>
		<div class="cmp_btng fix">
			<?php if(Yii::app()->user->getIsGuest()) :?>
				<a href="<?php echo $this->createUrl('member/follow');?>" class="cmp_btngl">关注该企业</a>
				<a href="<?php echo $this->createUrl('employee/applyEmployee'); ?>" class="cmp_btngr">我是该企业员工</a>
			<?php elseif(Yii::app()->user->id != $model->id):?>
				<?php if(!is_null($model->follow) && $model->follow->is_deleted == Follow::FOLLOW_IN) :?>
					<a href="javascript:;" class="cmp_btngl" data-from="<?php echo Yii::app()->user->id;?>" data-identifier="<?php echo $model->id; ?>" id="follow">取消关注</a>                 
				<?php elseif($model->id !== Yii::app()->user->id):?>
					<a href="javascript:;" class="cmp_btngl" data-from="<?php echo Yii::app()->user->id;?>" data-identifier="<?php echo $model->id; ?>" id="follow">关注该企业</a>                 
				<?php endif;?>
				
				<?php $state = Employee::getEmployeeState($model->id);?>
				<?php if($state === false):?>
					<a href="javascript:;" class="cmp_btngr" id="applyEmployee" data-qid="<?php echo $model->id;?>">我是该企业员工</a>
				<?php elseif($state == ContactsMember::STATE_APPLY):?>
					<a href="javascript:;" class="cmp_btngr cmp_ped">企业员工申请中</a>
				<?php elseif($state == ContactsMember::STATE_PASS):?>
					<a href="javascript:;" class="cmp_btngr cmp_ped">我是该企业员工</a>
				<?php else:?>
					<a href="javascript:;" class="cmp_btngr cmp_ped">员工申请已拒绝</a>
				<?php endif;?>
			<?php endif;?>
		</div>
		<menu class="carddetail">
			<li class="ny"><em class="wlnum">企业往来号：</em><strong><?php echo $model->wanglai_number; ?></strong></li>
			<li class="nz"><em class="mobile">企业客服电话：</em><a href="tel:<?php echo $model->mobile ?>"><?php echo $model->mobile; ?></a></li>
			<?php if(!empty($model->email)):?><li class="nb"><em class="email">企业邮箱：</em><a href="mailto:<?php echo $model->email?>"><?php echo $model->email?></a></li><?php endif;?>
			<?php if(!empty($model->address)):?><li class="nb"><em class="address">企业地址：</em><a href="http://api.map.baidu.com/geocoder?address=<?php echo $model->address; ?>&output=html"><?php echo $model->address;?></a></li><?php endif;?>
			<?php if(!empty($model->weixin)):?><li class="ny"><em class="ot">微信公众号：</em><?php echo $model->weixin;?></li><?php endif;?>
			<?php if(!empty($model->main_business)):?><li class="nb"><em class="ot">业务介绍：</em><?php echo $model->main_business;?></li><?php endif;?>
			<?php if(!empty($model->supply)):?><li class="nb"><em class="ot">企业供给：</em><?php echo $model->supply;?></li><?php endif;?>
			<?php if(!empty($model->demand)):?><li class="nb"><em class="ot">企业需求：</em><?php echo $model->demand;?></li><?php endif;?>
		</menu>
	</section>
	
	<section class="cmp_btnb fix">
		<?php if(!empty($model->company_url)):?>
			<a href="<?php echo $model->company_url;?>" class="cmp_btnbl btn_b2">访问企业微站</a>
		<?php else:?>
			<a href="javascript:;" class="cmp_btnbl btn_b2" onclick="alert('敬请期待')">访问企业微站</a>
		<?php endif;?>
		<a href="http://api.map.baidu.com/geocoder?address=<?php echo $model->address; ?>&output=html" class="cmp_btnbr btn_b2">公司地址导航</a>
	</section>
	<section class="sharebox fix">
		<a href="javascript:;" class="sharebtnl">发送给朋友</a>
		<a href="javascript:;" class="sharebtnr">分享到朋友圈</a>
	</section>
	<section id="ifooter" class="fix">
		<p>欢迎关注往来微信公众号：wanglairm</p>
		<p>Powered by wanglai.cc</p>
	</section>
	</div>
	
<?php if(Yii::app()->user->id == $model->id):?>
	<?php echo $this->renderPartial('/common/footer',array('updateUrl'=>$this->createUrl('member/update',array('id'=>$model->id)))); ?>
<?php else:?>
	<?php echo $this->renderPartial('/common/footer'); ?>
<?php endif;?>
<div id="opbg2"></div>

<script>
$(function(){

	// 微信分享
	dataForWeixin.callback = function () {
		$.get("<?php echo $this->createUrl('member/countShare',array('id'=>$model->id)); ?>");
	}
	dataForWeixin.url = "<?php echo $this->createUrl('member/view',array('id'=>$model->id,'#'=>'mp.weixin.qq.com'));?>";
	dataForWeixin.title = '<?php echo $model->name;?>';
	dataForWeixin.desc = '企业微名片，企业的移动互联网通行证！';
	dataForWeixin.weibodesc = '企业微名片，企业的移动互联网通行证！';
	dataForWeixin.MsgImg = "<?php echo $photo; ?>";

	var url = "<?php echo $this->createUrl('member/follow'); ?>";
	var following = false;
	$("#follow").bind('click',function(){
		var that = this;
		var mid=$(this).attr('data-identifier');
		var from = $(this).attr('data-from');
		if(!following){
			$.post(url,{mid:mid,from:from},function(data){
				if(data.code == 1)
				{
					var followMe = parseInt($("#follow_me").text());
					followMe  = data.data.operator == '+' ? followMe+1 : followMe-1;
					$("#follow_me").text(followMe);
					$(that).text(data.message);
				}else
				{
					alert(data.message);
				}
				following = false;
			},'json');
		}
		following = true;
	});

	var employeeApplyUrl = "<?php echo $this->createUrl('employee/applyEmployee');?>";
	var applying = false;
	$("#applyEmployee").bind("click",function(){
		var that = $(this);
		var qid = $(this).attr('data-qid');
		if(!applying){
			$.post(employeeApplyUrl,{id:qid},function(data){
				if(data.code == 1)
				{
					that.addClass('cmp_ped');
					that.text(data.message);
				}else
				{
					alert(data.message);
					applying = false;
				}
			},'json');
		}
		applying = true;
	});

	// 查看大图
	$("#bigpic").bind('click',function(e){
		e?e.stopPropagation():event.cancelBubble = true;
		$("body").append("<div id='opbg'></div><div id='opbox'><img class='bimg' src='<?php echo $big;?>' /><a href='javascript:;' class='close'>X</a></div>");
		$("body").height(document.documentElement.clientHeight);
		
	});
})

</script>