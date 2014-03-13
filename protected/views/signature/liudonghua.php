<?php $id = Util::encode(4);?>

<?php echo $this->renderPartial('/common/header',array('title'=>'东华老师年会发言'));?>
<div id="content">
	<article class="ztarticle">
<p>东华上台发言，首先拿黄总开涮：<br>
一看今天的年会主题“含苞待放的日子”就知道丽陆有多“闷骚”了。现场爆笑！<br>
目前移动互联最牛的商业模式就是社交，国外最牛的移动互联网公司市盈率达到800多倍，而且据说要进入中国。马蔚华曾对阿里、微信等金融等产品充满恐惧。<br>
一、正和岛三问：<br>
1、我们是不是真正的高端企业家o2o社交平台？<br>
2、我们离完全的高端企业家o2o社交平台还有多远（市场承认、用户承认、社会承认）？<br>
3、我们的对手在哪里？我们没有马云当年那么自信：在电子商务领域阿里巴巴拿着望远镜也看不到对手。但目前我们现在没有看到特别明显的对手。<br>
二、过去一年正和岛的服务太多了，虽没有达到完全目标，但大方向是对的。<br>
三、微信的迅速崛起使客户大量时间被抢占，但在正和岛的客户情感粘性还在，为什么？因为我们的严把标准、用户之上的“魂”还在。<br>
四、《每日一问》很大程度上支撑了正和岛的客户端价值体验，我们有一大群非常努力的人在使劲往前奔。<br>
五、我们的客户预期管理做得不够好。<br>
六、我们还不是真正意义的互联网公司，我们起码在三年内要实现1个员工服务100个客户才合格。<br>
七、我们的指导思想，三个凡是：<br>
1、凡是岛邻能做的我们一概不做（凡是岛邻能做的、做得更好的、做得差不多的）；<br>
2、凡是能够在线上完成的事情，我们一概不去线下去折腾；<br>
3、凡是线上解决不了、凡是岛邻做不到的，非我们做的，我们一定要投入所有优势兵力做出特色、做到极致！<br>
八、一定要憋住、坚持住我们的三个凡是。<br>
九、落地的保障首先是夯实、夯实基本面，提供基础的产品和服务，把做得不够好的服务认真补课。<br>
1、信任基石：正和岛是野山參的血脉与基因，没有一个公司可以违背规矩的胡萝卜成长还能可持续；<br>
2、数据驱动：我们已经具备近3000位付费会员了，已经具备了自信放慢脚步的前提与数据驱动的条件；<br>
3、聚焦：把一件事做到对方离不开你，这就是价值。包括岛内不错的私董会还属于传统模式，如何植入进移动互联的基因已经到了紧要的时刻。<br>
十、正和岛马上腾空出世的产品WE+如果做好了，将会承载帮助每个商务人群的信用关系与商业价值的利器。<br>
</p>
	</article>
	<a name="signature" id="signature">&nbsp;</a>
	<section class="btngroup">
		<?php if(Yii::app()->user->getIsGuest()):?>
			<a href="<?php echo $this->createUrl('signature/sign',array('id'=>$id)); ?>" class="btn_b1">签名</a>
		<?php elseif(Signature::model()->checkSignature(4)):?>
			<a href="javascript:;" class="btn_b3">已签名</a>
		<?php else:?>
			<a href="javascript:;" class="btn_b1" id="signature2">签名</a>
		<?php endif;?>
	</section>
	<section class="sharebox fix">
		<a href="javascript:;" class="sharebtnl">发送给朋友</a>
		<a href="javascript:;" class="sharebtnr">分享到朋友圈</a>
	</section>
	<section class="ztusrlist fix">
		<div class="tit">已签名的往来小伙伴(<span id="totalSign"><?php echo $total;?></span>)：</div>
		<ul id="signaturelist">
			<?php if(!empty($all))
					foreach ($all as $member):	
			?>
					<li><a href="<?php echo $this->createUrl('member/view',array('id'=>$member['id'])); ?>"><?php echo $member['name']; ?><span><?php echo date('Y/m/d H:i',$member['create_time']); ?></span></a></li>
			<?php endforeach;?>
		</ul>
		<div class="more"><a href="javascript:;" class="btn_b1" id="morebtn">点击加载更多</a></div>
	</section>
</div>

<script>
	$(function(){
		// 签名
		$("#signature2").bind("click", function(event){
			event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
			var param = {
				signUrl: "<?php echo $this->createUrl('signature/sign',array('id'=>$id)); ?>",
				viewUrl: "<?php echo $this->createUrl('member/view'); ?>"
			};
			var that = $(this);
			$.get(param.signUrl,function(result){
				if(result.code == 1){
					var html = '<li><a href="'+param.viewUrl+'&id='+result.data.id+'">'+result.data.name+'<span>'+result.data.create_time+'</span></a></li>';
					$("#signaturelist").append(html);
					var orginal = $("#totalSign").text();
					var current = parseInt(orginal)+1;
					$("#totalSign").text(current);
					that.text('已签名');
					that.removeClass('btn_b1').addClass('btn_b3');
					that.unbind("click");
				}			
			},'JSON')
		});

		
		//点击加载更多(初期方案)
		var inum = $(".ztusrlist li").length;
		var n = 0;
		if(inum<=30){
			$("#morebtn").hide();
		}else{
			$(".ztusrlist ul").height($(".ztusrlist li:eq(1)").outerHeight()*30);
		}
		$("#morebtn").bind("click",function(){
			n+=1;
			var aheight = $(".ztusrlist ul").height();
			if((n+1)*30>inum){
				var m = inum-n*30;
				$(".ztusrlist ul").height(aheight+$(".ztusrlist li:eq(1)").outerHeight()*m);
				$("#morebtn").hide();
			}else{
				$(".ztusrlist ul").height(aheight+$(".ztusrlist li:eq(1)").outerHeight()*30);
			}
		})

		// 微信分享
		// 微信分享
		dataForWeixin.callback = function () {
			$.get("<?php echo $this->createUrl('signature/countShare',array('sign'=>Util::getSign(),'id'=>$id)); ?>",function(){
				//$("#sharebg,#sharebox").hide();
			});
		}
		dataForWeixin.url = '<?php echo $this->createUrl("signature/index",array('id'=>$id));?>';
		dataForWeixin.title = '东华老师年会发言';
		dataForWeixin.desc = '正和岛创始人刘东华年会发言';
		dataForWeixin.MsgImg = "<?php echo Yii::app()->request->getBaseUrl(true);?>/static/weixin/ldx.jpg";
			
	})
</script>	