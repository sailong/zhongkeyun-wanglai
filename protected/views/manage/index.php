<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta content="telephone=no" name="format-detection" />
	<title>往来</title>
	<link rel="stylesheet" href="/static/css/wanglai.css?vsesion=140126" />
	<script src="/static/js/wanglai.js?version=201401244"></script>
</head>
<body>
<?php $this->widget('application.components.widget.Menu'); ?>

<?php echo $this->renderPartial('/common/header',array('title'=>'名片夹'));?>

<div id="content">
	<section id="mycardinfo">
		<div class="usrpic"><img src="<?php echo Member::model()->getPhoto($model); ?>" alt="" /></div>
		<div class="usrinfo">
			<h2><?php echo $model->name;?></h2>
			<p><?php echo $model->mobile;?></p>
		</div>
		<div class="usrcardedit">
			<a href="<?php echo $this->createUrl('member/update'); ?>">修改名片</a>
		</div>
	</section>
	<ul class="mycarditems">
		<li>
			<a href="<?php echo $this->createUrl('interFollow'); ?>">
				<span class="ipic"><img src="/static/images/i1.png" alt="" /></span>
				<p class="p1">好友：<span><?php echo Follow::model()->calculateInterFollow(); ?></span></p>
				<?php $interFollow = Follow::model()->getLatestInterFollow();?>
				<p class="p2"><?php echo !empty($interFollow) ? '最近新增好友：'.$interFollow : '暂时没有新增好友' ;?></p>
			</a>
		</li>
		<li class="cor">
			<a href="<?php echo $this->createUrl('myFollow');?>">
				<span class="ipic"><img src="/static/images/i2.png" alt="" /></span>
				<p class="p1">我关注的：<span><?php echo Follow::model()->calculateMyFollow(); ?></span></p>
				<?php $follow = Follow::model()->getLatestMyFollow(); ?>
				<p class="p2"><?php echo !empty($follow) ? '最近关注的名片：'.$follow : '暂时还没有关注'?></p>
			</a>
		</li>
		<li>
			<a href="<?php echo $this->createUrl('followMe');?>">
				<span class="ipic"><img src="/static/images/i3.png" alt="" /></span>
				<p class="p1">关注我的：<span><?php echo Follow::model()->calculateFollowMe(); ?></span></p>
				<?php $name = Follow::model()->getLatestFollowMe(); ?>
				<p class="p2"><?php echo !empty($name) ? '最近关注我的名片:'.$name : '最暂时还没有人关注你'?></p>
				
				<?php $count = Follow::model()->calculateNewFollow();?>
				<?php if($count>0):?>
					<span class="num"><?php echo $count; ?></span>
				<?php endif;?>
			</a>
		</li>
		<li class="cor">
			<a href="<?php echo $this->createUrl('myViewer'); ?>">
				<span class="ipic"><img src="/static/images/i4.png" alt="" /></span>
				<p class="p1">我的访客：<span><?php echo ViewLog::model()->calculateMyViewer(); ?></span></p>
				<?php $viewer = ViewLog::model()->getLatestViewer(); ?>
				<p class="p2"><?php echo !empty($viewer) ? '最近访客：'.$viewer : '暂时没有最近访客'; ?></p>
			</a>
		</li>
		
		<li>
			<a href="<?php echo $this->createUrl('myFriend'); ?>">
				<span class="ipic"><img src="/static/images/i6.png" alt="" /></span>
				<p class="p1">全球排名：<span><?php echo Member::model()->getOrder($model->views); ?></span></p>
				<p class="p2">好友中有<?php echo Member::model()->calculateBeforeMe(); ?>个人超过了你</p>
			</a>
		</li>
	</ul>
</div>

<?php echo $this->renderPartial('/common/footer'); ?>
<div id="opbg2"></div>
</body>
</html>