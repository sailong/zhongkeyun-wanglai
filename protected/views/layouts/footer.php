<?php if(Yii::app()->user->getIsGuest()):?>

<div class="toolsbar">
	<div class="toolsbox2">
		<a href="javascript:;" class="smenu">未登录</a>
		<div class="sul">
			<ul>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('member/index'); ?>">创建名片</a></li>
			</ul>
			<u></u>
		</div>
	</div>
	<div class="toolsbox2">
		<a href="javascript:;" class="smenu">微人脉</a>
		<div class="sul">
			<ul>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('manage/index'); ?>">名片夹</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('manage/myFriend');?>">排行榜</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('manage/myViewer');?>">谁看过我</a></li>
			</ul>
			<u></u>
		</div>
	</div>
	<div class="toolsbox2">
		<a href="javascript:;" class="smenu">微活动</a>
		<div class="sul">
			<ul>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('activity/create'); ?>">发起活动</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('activity/index',array('type'=>'create')); ?>">我发起的</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('activity/index',array('type'=>'join')); ?>">我参与的</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('bless/index'); ?>">送贺卡</a></li>
			</ul>
			<u></u>
		</div>
	</div>
	<div class="toolsbox2">
		<a href="javascript:;" class="smenu">更多</a>
		<div class="sul">
			<ul>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('feedback/service'); ?>">客户服务</a></li>
			</ul>
			<u></u>
		</div>
	</div>
</div>

<?php else: ?>

<!--底部导航栏###登录-->


<div class="toolsbar">
	<div class="toolsbox2">
		<a href="javascript:;" class="smenu">我</a>
		<div class="sul">
			<ul>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('member/index'); ?>">我的名片</a></li>
				<li><a data-ajax="false" href="javascript:;" id="orcode_btn">名片二维码</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('member/update'); ?>">修改名片</a></li>
			</ul>
			<u></u>
		</div>
	</div>
	<div class="toolsbox2">
		<a href="javascript:;" class="smenu">微人脉</a>
		<div class="sul">
			<ul>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('manage/index'); ?>">名片夹</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('manage/myFriend');?>">排行榜</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('manage/myViewer');?>">谁看过我</a></li>
			</ul>
			<u></u>
		</div>
	</div>
	<div class="toolsbox2">
		<a href="javascript:;" class="smenu">微活动</a>
		<div class="sul">
			<ul>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('activity/create'); ?>">发起活动</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('activity/index',array('type'=>'create')); ?>">我发起的</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('activity/index',array('type'=>'join')); ?>">我参与的</a></li>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('bless/index'); ?>">送贺卡</a></li>
			</ul>
			<u></u>
		</div>
	</div>
	<div class="toolsbox2">
		<a href="javascript:;" class="smenu">帮助</a>
		<div class="sul">
			<ul>
				<li><a data-ajax="false" href="<?php echo $this->createUrl('feedback/service'); ?>">客户服务</a></li>
				<li><a data-ajax="false" href="http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA4MjA5MDQwNg==&appmsgid=10025129&itemidx=1&sign=139560e3c35b36dd5d8527dcd03556c3#wechat_redirect">意见反馈</a></li>
			</ul>
			<u></u>
		</div>
	</div>
</div>

<!-- 二维码 -->
<?php $this->widget('application.components.widget.OQrcode');?>


<?php endif;?>


<script>
$(document).ready(function(){
	$(".smenu").bind("tap",function(e){
		e?e.stopPropagation():event.cancelBubble = true;
		if($(this).next().is(":hidden")){
			$(this).parents(".toolsbar").find(".sul").hide();
			$(this).next().show();
		}else{
			$(this).next().hide();
		}
		
	});
	$(".sul a").bind("tap",function(e){
		e?e.stopPropagation():event.cancelBubble = true;
	});
	$(document).tap(function(){
		$(".sul").hide();
	});

	$("input,textarea").focus(function(){
		$(".toolsbar").hide();
	}).blur(function(){
		$(".toolsbar").show();
	});
		
});
</script>

