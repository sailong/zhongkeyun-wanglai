<?php echo $this->renderPartial('/common/header',array('title'=>'微名片'));?>

<div id="content">
	<section class="ranking">
		<p class="p1"><u></u>全球名片排名：第<em><?php echo Member::model()->getOrder($model->views);?></em>名</p>
		<p>共有<?php echo $model->views ;?>人看过该名片，累计浏览量<?php echo !is_null($model->stat) ? $model->stat->pv_counts : 0 ;?>次</p>
	</section>
	<section class="cardinfobox">
		<?php
			$photo = Member::model()->getPhoto($model,'s');
			$big = Member::model()->getPhoto($model,'b');
		?>
		<div class="uphoto">
			<?php if(!empty($big)) :?>
				<a href="javascript:;" id="bigpic"><img src="<?php echo $photo; ?>" alt="" /></a>
			<?php else:?>
				<img src="<?php echo $photo;?>" alt="" />
			<?php endif;?>

		</div>
		<div class="uname">
			<h2 id="vtips"><?php echo $model->name;?>
				<?php if($model->is_vip) :?>
					<img src="/static/images/yellow_v.png" alt="" /><div id="tipsbox">往来官方个人认证</div>
				<?php endif;?>
			</h2>
			<?php if(Yii::app()->user->getIsGuest()) :?>
				<a href="<?php echo $this->createUrl('member/follow');?>">关注TA</a>
			<?php elseif(!is_null($model->follow) && $model->follow->is_deleted == Follow::FOLLOW_IN) :?>
				<a href="javascript:;" data-from="<?php echo Yii::app()->user->id;?>" data-identifier="<?php echo $model->id; ?>" id="follow">取消关注</a>                 
			<?php elseif($model->id !== Yii::app()->user->id):?>
				<a href="javascript:;" data-from="<?php echo Yii::app()->user->id;?>" data-identifier="<?php echo $model->id; ?>" id="follow">关注TA</a>                 
			<?php endif;?>
		</div>
		<div class="utotal">
			<div class="utbox fix">
				<a href="<?php echo (Yii::app()->user->id == $model->id) ? $this->createUrl('manage/interFollow') : 'javascript:;' ; ?>"><em id="follow_me"><?php echo Follow::model()->calculateInterFollow($model->id); ?></em><br />互相关注</a>
				<a href="<?php echo (Yii::app()->user->id == $model->id) ? $this->createUrl('manage/followMe') : 'javascript:;' ; ?>"><em><?php echo Follow::model()->calculateFollowMe($model->id); ?></em><br />被关注</a>
				<a href="<?php echo (Yii::app()->user->id == $model->id) ? $this->createUrl('manage/myViewer') : 'javascript:;' ; ?>"><em><?php echo ViewLog::model()->calculateMyViewer($model->id);?></em><br />访客人数</a>
			</div>
		</div>
		<menu class="carddetail">
			<?php $access = Member::model()->checkAccess($model);//检测是否可以查看手机登信息 ?>
			<?php if(!empty($model->company)):?><li class="np"><a href="<?php echo $model->company_url;?>" class="f14" data-ajax="false"><?php echo $model->company;?></a></li><?php endif;?>
			<?php if(!empty($model->wanglai_number)):?><li class='nc'><em class="wlnum">往来号：</em><strong style="<?php echo $model->wanglai_number_grade ? 'color:#f90' : ''?>"><?php echo $model->wanglai_number;?></strong></li><?php endif;?>
			<?php if(!empty($model->mobile)):?><li><em class="mobile">手机：</em><?php if($access):?><a href="tel:<?php echo $model->mobile;?>"><?php echo $model->mobile;?></a><?php else:?><span style="color:#cccccc">（互相关注后可见）</span><?php endif;?></li><?php endif;?>
			<?php if(!empty($model->position)):?><li><em class="job">职位：</em><?php echo $model->position;?></li><?php endif;?>
			<?php if(!empty($model->email)):?><li><em class="email">邮箱：</em><?php if($access):?><a href="mailto:<?php echo $model->email?>"><?php echo $model->email?></a><?php else:?><span style="color:#cccccc">（互相关注后可见）</span><?php endif;?></li><?php endif;?>
			<?php if(!empty($model->address)):?><li><em class="address">地址：</em><a data-ajax="false" href="http://api.map.baidu.com/geocoder?address=<?php echo $model->address; ?>&output=html"><?php echo $model->address;?></a></li><?php endif;?>
		                	
			<?php 
				$show = array('supply'=>'供给','demand'=>'需求','qq'=>'Q Q','weixin'=>'微信号','yixin'=>'易信号','laiwang'=>'来往号','main_business'=>'业务介绍','social_position'=>'社会职务','profile'=>'个人简介','hobby'=>'兴趣爱好');
				foreach ($show as $key=>$val)
				{ 
					if(!empty($model->$key))
					{
						$class = '';
						if(!in_array($key,array('supply','demand','qq')))
						{
							$class = mb_strlen($val,'UTF-8') == 3 ? 'nc' : 'nb';
						}
						if(!empty($class))
							echo '<li class="'.$class.'"><em class="ot">'.$val.'：</em>';
						else 
							echo '<li><em class="ot">'.$val.'：</em>';
											
						if(in_array($key, array('qq','weixin','yixin','laiwang')) && !$access)
							echo '<span style="color:#cccccc">（互相关注后可见）</span>';
						else 
							echo $model->$key;
					}
				}
			?>	
		</menu>
	</section>
	<section class="btngroup">
	<?php if(!Yii::app()->user->getIsGuest() && Yii::app()->user->id != $model->id): ?>
		<a href="<?php echo $this->createUrl('member/index'); ?>" class="btn_b1">查看我的微名片</a>
	<?php endif;?>
	
	<?php if(!Yii::app()->user->getIsGuest()):?>
		<a href="<?php echo $this->createUrl('member/update'); ?>" class="btn_b2">修改微名片</a>
		<a href="http://mp.weixin.qq.com/s?__biz=MzA4MjA5MDQwNg==&mid=10037567&idx=1&sn=cca5af4bdad47a091b37d1ce732ed160&scene=1#rd" class="btn_b1">加V认证</a>
	<?php endif;?>
	</section>
	<section class="sharebox fix">
		<a href="javascript:;" class="sharebtnl">发送给朋友</a>
		<a href="javascript:;" class="sharebtnr">分享到朋友圈</a>
	</section>
</div>
<?php if(Yii::app()->user->id == $model->id):?>
	<?php echo $this->renderPartial('/common/footer',array('updateUrl'=>$this->createUrl('member/update',array('id'=>$model->id)))); ?>
<?php else:?>
	<?php echo $this->renderPartial('/common/footer'); ?>
<?php endif;?>

<script>
$(function(){

	// 微信分享
	dataForWeixin.callback = function () {
		$.get("<?php echo $this->createUrl('member/countShare',array('id'=>$model->id)); ?>");
	}
	dataForWeixin.url = "<?php echo $this->createUrl('member/view',array('id'=>$model->id,'#'=>'mp.weixin.qq.com'));?>";
	dataForWeixin.title = '<?php echo $model->name;?>的微名片';
	dataForWeixin.desc = '<?php echo $model->name;?>的微名片,保存在微信上并可发送给朋友或分享到朋友圈、微信群！';
	dataForWeixin.weibodesc = '#微名片#，这是<?php echo $model->name;?>的微名片，请大家惠存，你也来制作自己的微名片吧！';
	dataForWeixin.MsgImg = "<?php echo $photo; ?>";

	var url = "<?php echo $this->createUrl('member/follow'); ?>";
	$("#follow").bind('click',function(){
		var that = this;
		var mid=$(this).attr('data-identifier');
		var from = $(this).attr('data-from');
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
		},'json');
	});

	// 查看大图
	$("#bigpic").bind('click',function(e){
		e?e.stopPropagation():event.cancelBubble = true;
		$("body").append("<div id='opbg'></div><div id='opbox'><img class='bimg' src='<?php echo $big;?>' /><a href='javascript:;' class='close'>X</a></div>");
		$("body").height(document.documentElement.clientHeight);
		
	});
})

</script>