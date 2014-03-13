
<header id="iheader">
	<h1>往来</h1>
	<h2>往来人脉 -- 让人人都有自己的微名片</h2>
</header>
<section class="se1 fix">
	<a href="<?php echo $this->createUrl('member/index');?>">
		<span class="hd">微名片</span>
		<span class="bd">
			<span class="p1">我的微名片</span>
			<span class="p2">往来人脉，让人人都有自己的微名片。</span>
		</span>
	</a>
</section>
<section class="se2 fix">
	<a href="<?php echo $this->createUrl('activity/myCreated'); ?>" class="a1">
		<span class="hd">微活动</span>
		<span class="bd">
			<span class="p1">微活动</span>
			<span class="p2">组织聚餐，会议，培训，KTV</span>
		</span>
	</a>
	<a href="<?php echo $this->createUrl('bless/index'); ?>" class="a2">
		<span class="hd">送贺卡</span>
		<span class="bd">
			<span class="p1">微贺卡</span>
			<span class="p2">给朋友送去一份祝福</span>
		</span>
	</a>
</section>
<section class="se3 fix">
	<a href="<?php echo $this->createUrl('contacts/myCreated'); ?>">
		<span class="hd">微群通讯录</span>
		<span class="bd">
			<span class="p1">我的通讯录</span>
			<span class="p2">让你和你的小伙伴时刻保持联系</span>
		</span>
	</a>
</section>
<footer id="ifooter" class="fix">
	<p><a href="<?php echo Yii::app()->createUrl('/site');?>">登录企业号</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl('/site/logout');?>">退出登录</a>
	<p>欢迎关注往来微信公众号：wanglairm</p>
	<p>Powered by wanglai.cc</p>
</footer>

<script>
$(function(){

	// 微信分享
	dataForWeixin.url = '<?php echo $this->createUrl('index/index');?>';
	dataForWeixin.title = '往来-让人人都有微名片';
	dataForWeixin.desc = '微名片，微活动，微群通讯录，微贺卡，您的掌上高端人脉管理专家！';
	dataForWeixin.weibodesc = '微名片，微活动，微群通讯录 您的掌上高端人脉管理专家！';
	dataForWeixin.MsgImg = "<?php echo Yii::app()->request->getBaseUrl(true);?>/static/weixin/rlogo.png";
})
</script>

