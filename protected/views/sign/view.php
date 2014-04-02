<?php echo $this->renderPartial('/common/header',array('title'=>'签名正文'));?>
<?php 
	$id = Util::encode($model->id);
	$guest = Yii::app()->user->getIsGuest() ? true : false;
	$totalUp = 0;
if(!$guest)
{
	$file = '/static/js/tc_inp.js';
	$client = Yii::app()->clientScript;
	$client->registerScriptFile($file,CClientScript::POS_BEGIN);
	
	$script = <<<EOF
	 $('#modaltrigger').leanModal({ bottom:0, overlay: 0.45, closeButton: ".hidemodal" });
EOF;
	
	$client->registerScript('comment', $script, CClientScript::POS_END);
	
}
?>
<?php if((Yii::app()->user->id == $model->create_mid) && !Util::getCookie('tipAlready')):?>
<div class="sevshare">
		<img src="/static/images/sevshare-qm.png" alt="" />
		<div class="sev_con">
			请点击右上角按钮，在弹出页面中选择"发送给朋友"或"分享到朋友圈"等操作。
			<p><a href="javascript:;" class="close">我知道了</a></p>
		</div>
	</div>
	<?php Util::addCookie('tipAlready', true);?>
<?php endif;?>
	<div id="content">
		<aside class="article_tit">
			<h1><?php echo $model->title;?></h1>
			<p><?php echo date('Y-m-d  H:i', $model->create_time); ?> <a href="<?php echo Yii::app()->user->id == $model->create_mid ? $this->createUrl('mypublish') : $this->createUrl('list',array('mid'=>Util::encode($model->create_mid))); ?>"><?php echo $model->creater->name;?></a> 阅读(<?php echo $model->pv_counts;?>)  分享(<span id="sharesCount"><?php echo $model->share_counts;?></span>)</p>
		</aside>
		<article class="article_content">
			<?php 
			    $content = $model->content;
    			preg_match_all('/(.*)\\n?/', $content,$matches);
    			if(!empty($matches[1]))
    			{
    			    $details = '';
    			    foreach ($matches[1] as $match)
    			    {
    			        $details .= "<p>".$match."</p>";
    			    }
    			}
    			echo $details;
			?>
		</article>
		<?php if(!empty($upers)):?>
			<?php foreach ($upers as $uper):?>
				<?php $totalUp++;?>
			<?php endforeach;?>
			<?php if(empty($totalUp)){$totalUp=0;}?>
		    <?php endif;?>
        <section class="btngroup">
            <?php if($guest):?>
                <a href="<?php echo $this->createUrl('sign') ?>" class="btn_b1">签名(<?php echo $totalUp;?>)</a>
            <?php else:?>
                <!-- 判断是否签名 -->
                <?php if(Signature::model()->checkSignature($model->id, Yii::app()->user->id,Signature::SIGN_TYPE_FLAG)):?>
                <a href="javascript:;" class="btn_b3">已签名(<?php echo $totalUp;?>)</a>
                <?php else:?>
                <a href="javascript:;" id="to_sign" class="btn_b1">签名(<?php echo $totalUp;?>)</a>
                <?php endif;?>
            <?php endif;?>
        </section>
		<section class="sharebox fix">
		<?php if($guest):?>
                <a href="<?php echo $this->createUrl('collect') ?>" class="cmp_btnbr collection">收藏</a>
        <?php else:?>
    		<!-- 判断是否收藏 -->
    		<?php if(Signature::model()->checkSignature($model->id, Yii::app()->user->id,Signature::SIGN_TYPE_COLLECT)):?>
            <a href="javascript:;"  class="cmp_btnbr collection">已收藏</a>
            <?php else:?>
            <a href="javascript:;" id="c_prom" class="cmp_btnbr collection">收藏</a>
            <?php endif;?>
        <?php endif;?>
		<a href="javascript:;" class="sharebtnr">分享到朋友圈</a>
	</section>
		<section class="btngroup">
			<?php if($guest):?>
			<a href="<?php echo $this->createUrl('comment') ?>" class="btn_b2">写评论</a>
    		<?php else:?>
    			<a href="#loginmodal" class="btn_b2" id="modaltrigger">写评论</a>
    		<?php endif;?>
			<a href="<?php echo $this->createUrl('Create');?>" class="btn_b2">我也要发起签名</a>
		</section>
		<nav class="tab article_nav">
			<a href="javascript:;" class="cur">已签名<u></u></a>
			<span class="line">|</span>
			<a href="javascript:;">评论</a>
		</nav>
		<!-- 签名列表 -->
		<ul class="comment article_tab ul_sign">
		   <?php if(!empty($upers)):?>
			<?php foreach ($upers as $uper):?>
			    <li>
				<a href="<?php echo $this->createUrl('member/view',array('id'=>$uper['id'])); ?>">
					<p class="s1"><?php echo $uper['name'];?><span class="date"><?php echo date('Y/m/d H:i',$uper['create_time']);?></span></p>
					<p class="s2">已签名</p>
				</a>
			    </li>
			<?php endforeach;?>
			<?php else:?>
		        <li>
					<p style="font-size:16px;padding-top:5px;padding-left:7px;">暂无签名</p>
				</li>
		    <?php endif;?>
		</ul>
		<!-- 评论列表 -->
		<ul class="comment article_tab ul_comment" style="display:none;">
		
		     <?php if(!empty($comments)):?> 
			<?php foreach ($comments as $comment):?>
				<li>
					<a href="<?php echo $this->createUrl('member/view',array('id'=>$comment->member->id));//echo $this->createUrl('list',array('mid'=>Util::encode($comment->member->id))); ?>">
						<p class="s1"><?php echo $comment->member->name;?>：<span class="date"><?php echo date('Y/m/d H:i',$comment->create_time);?></span></p>
						<p class="s2"><?php echo $comment->comment;?></p>
					</a>
				</li>
			<?php endforeach;?>
			<?php else:?>
		        <li>
					<p style="font-size:16px;padding-top:5px;padding-left:7px;">暂无评论</p>
				</li>
	       <?php endif;?>
			
		</ul>
		
	</div>
	<script>
		$(function(){
			var index = 0;
			$(".article_nav a").click(function(){
				 index = $(".article_nav a").index(this);
				 $(this).addClass("cur").append("<u></u>").siblings().removeClass("cur").children("u").remove();  
				 $(".article_tab").eq(index).show().siblings(".article_tab").hide();
			});

			//签名ajax
			$("#to_sign").click(function(){
				var self = $(this);
				if (self.hasClass('disabled'))
				{
					return false;
				}
				    
				$.post("/sign/sign",{id:"<?php echo $id;?>"},function(result){
					if(result.code == 1)
					{
						self.addClass('disabled');
						self.removeClass('btn_b1').addClass('btn_b3');
						self.html("已签名(<?php echo $totalUp+1?>)");
						$("ul.ul_sign").prepend(result.message);
					}else{
						alert(result.message);
					}
				},'json');
			});
			//收藏ajax
			$("#c_prom").click(function(){
				var self = $(this);
				if (self.hasClass('disabled'))
				{
					return false;
				}
				$.post("/sign/collect",{id:"<?php echo $id;?>"},function(result){
					if(result.code == 1)
					{
						self.addClass('disabled');
						self.html("已收藏");
						//alert(result.message);
					}else{
						alert(result.message);
					}
				},'json');
			});
			//评论ajax
			$("#commentSubmit").live('click',function(){
				var comment = $.trim($("input[name=comment]",$("#loginmodal")).val());
				if(comment == '')
				{
					alert('请输入评论内容');
					return false;
				}
				if (comment!=null && comment!=""){
					$.post("/sign/comment",{id:"<?php echo $id;?>",comment:comment},function(result){
						if(result.code == 1)
						{
							$("ul.ul_comment").prepend(result.message);
						}else{
							alert(result.message);
						}
						$("#loginmodal").find(":input[name=comment]").val("");
						$("#lean_overlay").hide();
						$("#loginmodal").css({"display":"none","position":"fixed","opacity":0,"z-index":11000});
					},'json');
				}
			});
			
			$(".sevshare .close").click(function(){
				$(this).parents(".sevshare").hide();
			})

			// 分享
			dataForWeixin.callback = function () {
			 	 $.post("<?php echo $this->createUrl('countShare',array('id'=>$id));?>", function () {
				 	 var share = $("#sharesCount");
				 	var count = parseInt(share.text())+1;
					 share.text(count);
			   });
			}
			
			dataForWeixin.url = "<?php echo $this->createUrl('view',array('id'=>$id)); ?>";
			dataForWeixin.title = '<?php echo $model->title;?>';
			dataForWeixin.desc = '<?php echo mb_substr(preg_replace('/[\s|\&nbsp;]/', '', strip_tags($model->content)),0,50,'UTF-8'); ?>';
			dataForWeixin.MsgImg = "<?php echo !empty($model->img) ? Yii::app()->request->getBaseUrl(true) .'/'. $model->img : Yii::app()->request->getBaseUrl(true).'/static/images/qm_logo.png'; ?>";
		})
	</script>
    
<div class="tc_inp" id="loginmodal" style="display:none;">
	<div class="formitems">
        <input type="text" name="comment" id="comment" value="" class="inp" placeholder="请输入评论内容" required="required">
    </div>
    <input name="" type="submit" value="提交" class="pl_btn1" id="commentSubmit">
    <a href="#" class="pl_btn2 hidemodal">取消</a>
</div>