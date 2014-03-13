<?php $id = Util::encode(3);?>

<?php echo $this->renderPartial('/common/header',array('title'=>'【祝贺李娜夺冠！】'));?>
<div id="content">
	<article class="ztarticle">
		<p class="pic"><img src="/static/images/ln.jpg" alt="" /></p>
		<p>去年澳网决赛的颁奖典礼，落败的李娜说：“我知道我不年轻了，但我还是要说，我盼着明年再来。”今年，她说到做到再次闯进决赛，梦想和机会终究会眷顾那些勇于坚持、永不放弃的人，在墨尔本两次屈居亚军之后，第三次再进决赛，这一次娜姐终于更进一步，圆梦澳网大满贯冠军！！！</p>
	</article>
	<a name="signature" id="signature">&nbsp;</a>
	<section class="btngroup">
		<?php if(Yii::app()->user->getIsGuest()):?>
			<a href="<?php echo $this->createUrl('signature/sign',array('id'=>$id)); ?>" class="btn_b1">签名</a>
		<?php elseif(Signature::model()->checkSignature(3)):?>
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
		dataForWeixin.title = '【祝贺李娜夺冠！】';
		dataForWeixin.desc = '热烈祝贺李娜圆梦澳网大满贯冠军！';
		dataForWeixin.MsgImg = "<?php echo Yii::app()->request->getBaseUrl(true);?>/static/weixin/ln.jpg";
			
	})
</script>	