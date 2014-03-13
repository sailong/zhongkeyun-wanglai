<?php echo $this->renderPartial('/common/header',array('title'=>''));?>
<div id="content">
	<article class="ztarticle">
		<h3 style="text-align:center"><?php echo $model->title;?></h3>
		<?php echo preg_replace("/width:\d+px;?|height:\d+px;?|width=\"\d+\"|height=\"\d+\"/i",'', $model->content); ?>
	</article>
	<a name="signature" id="signature">&nbsp;</a>
	<section class="btngroup">
		<?php if(Yii::app()->user->getIsGuest()):?>
			<a href="<?php echo $this->createUrl('signature/sign'); ?>" class="btn_b1">签名</a>
		<?php elseif(Signature::model()->checkSignature($model->id)):?>
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
				signUrl: "<?php echo $this->createUrl('signature/sign',array('id'=>Util::encode($model->id))); ?>",
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
			$.get("<?php echo $this->createUrl('signature/countShare',array('sign'=>Util::getSign(),'id'=>Util::encode($model->id))); ?>",function(){
				//$("#sharebg,#sharebox").hide();
			});
		}
		dataForWeixin.url = '<?php echo $this->createUrl("signature/index",array('id'=>Util::encode($model->id)));?>';
		dataForWeixin.title = '<?php echo $model->title;?>';
		dataForWeixin.desc = '<?php echo mb_substr(preg_replace('/[\s|\&nbsp;]/', '', strip_tags($model->content)),0,50,'UTF-8');?>';
		dataForWeixin.MsgImg = "<?php echo !empty($model->img) ? Yii::app()->request->getBaseUrl(true) .'/'. $model->img : Yii::app()->request->getBaseUrl(true).'/static/weixin/rlogo.png'; ?>";
	})
</script>	