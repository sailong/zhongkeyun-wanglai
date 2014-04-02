<?php echo $this->renderPartial('/common/header',array('title'=>'文章正文'));?>

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
	<img src="/static/images/sevshare.png" alt="" />
	<div class="sev_con">
		请点击右上角按钮，在弹出页面中选择“发送给朋友”或“分享到朋友圈”等操作。
		<p><a href="javascript:;" class="close">我知道了</a></p>
	</div>
</div>
<?php Util::addCookie('tipAlready', true);?>
<?php endif;?>

<div id="content">
	<aside class="article_tit">
		<h1><?php echo $model->title;?></h1>
		<p><?php echo date('Y-m-d  H:i', $model->create_time); ?> <a href="<?php echo Yii::app()->user->id == $model->create_mid ? $this->createUrl('mypublish') : $this->createUrl('list',array('mid'=>Util::encode($model->create_mid))); ?>"><?php echo $model->creater->name;?></a> 阅读<?php echo $model->views; ?>  分享<span id="sharesCount"><?php echo $model->share_counts; ?></span></p>
	</aside>
	<article class="article_content">
	<?php 
			$details = $model->content->content;
			preg_match_all('/(.*)\\n?/', $details,$matches);
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
	<section class="likelist">
		<u></u>
		<span id="alreadyUp">赞过的：</span>
		<?php if(!empty($upers)):?>
			<?php foreach ($upers as $uper):?>
				<a href="<?php echo $this->createUrl('member/view',array('id'=>$uper->id)); ?>" id="<?php echo $uper->id;?>"><?php echo $uper->name;?></a>
				<?php $totalUp++;?>
			<?php endforeach;?>
		<?php endif;?>
	</section>
	<section class="cmp_btnb fix">
		<?php if($guest):?>
			<a href="<?php echo $this->createUrl('up');?>" class="cmp_btnbl btn_b2">赞(<span id="upTotal"><?php echo $totalUp;?></span>)</a>
			<a href="<?php echo $this->createUrl('mark') ?>" class="cmp_btnbr btn_b2">收藏</a>
		<?php else:?>
			<?php if(ArticleMark::model()->checkMark($model->id, Yii::app()->user->id, ArticleMark::TYPE_UP)):?>
				<a href="javascript:;" class="cmp_btnbl btn_b2" id="up">已赞(<span id="upTotal"><?php echo $totalUp;?></span>)</a>
			<?php else:?>
				<a href="javascript:;" class="cmp_btnbl btn_b2" id="up"><span id="uptext">赞</span>(<span id="upTotal"><?php echo $totalUp;?></span>)</a>
			<?php endif;?>
			
			<?php if(ArticleMark::model()->checkMark($model->id, Yii::app()->user->id)):?>
				<a href="javascript:;" class="cmp_btnbr btn_b2 btn_b3">已收藏</a>
			<?php else:?>
				<a href="javascript:;" class="cmp_btnbr btn_b2" id="mark">收藏</a>
			<?php endif;?>
		<?php endif;?>
	</section>
	<section class="sharebox sharebox2 fix">
		<a href="javascript:;" class="sharebtnr">分享给朋友</a>
	</section>
	<section class="btngroup">
		<?php if($guest):?>
			<a href="<?php echo $this->createUrl('comment') ?>" class="btn_b2">写评论</a>
		<?php else:?>
			<a href="#loginmodal" class="btn_b2" id="modaltrigger">写评论</a>
		<?php endif;?>
			<a href="<?php echo $this->createUrl('create');?>" class="btn_b2">我也要发布文章</a>
	</section>
	<nav class="tab article_nav">
		<a href="javascript:;" class="cur">评论<u></u></a>
		<span class="line">|</span>
		<a href="javascript:;">最近谁看过</a>
	</nav>
	
	<ul class="comment article_tab">
		<?php if(!empty($comments)):?> 
			<?php foreach ($comments as $comment):?>
				<li>
					<a href="<?php echo $this->createUrl('list',array('mid'=>Util::encode($comment->member->id))); ?>">
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
	<ul class="member_ws article_tab fix" style="display: none">
		<?php if(!empty($viewers)):?>
			<?php foreach ($viewers as $viewer):?>
				<li>
					<a href="<?php echo $this->createUrl('member/view',array('id'=>$viewer->id));?>">
						<p><img src="<?php echo Member::model()->getPhoto($viewer);?>" alt="" /></p>
						<p class="s1"><?php echo $viewer->name;?></p>
					</a>
				</li>
			<?php endforeach;?>
		<?php else:?>
		        <li>
					<p>暂无浏览</p>
				</li>
	   <?php endif;?>
	</ul>
</div>
<script>
	$(function(){
		var index = 0;
		var uid = <?php echo $guest ? 0 : Yii::app()->user->id;?>;
		$(".article_nav a").click(function(){
			 index = $(".article_nav a").index(this);
			 $(this).addClass("cur").append("<u></u>").siblings().removeClass("cur").children("u").remove();  
			 $(".article_tab").eq(index).show().siblings(".article_tab").hide();
		});
		
		$(":submit[name=commentSubmit]").live('click',function(){
			var comment = $.trim($("input[name=comment]").val());
			if(comment == '')
			{
				alert('请输入评论内容');
				return false;
			}
			if (comment!=null && comment!=""){
				$.post("/article/comment",{id:"<?php echo $id;?>",comment:comment},function(result){
					if(result.code == 1)
					{
						$("ul.comment").prepend(result.message);
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
		});

		var mark = false;
		var up = false;

		// 收藏
		$("#mark").bind("click",function(){
			var that = $(this);
			if(!mark){
				$.get("/article/mark/id/<?php echo $id;?>",function(result){
					if(result.code == 1)
						that.text(result.message);
					else{
						alert(result.message);
						that.removeClass('btn_b3');
						mark = false;
					}
				},'json');
				that.addClass('btn_b3');
				mark = true;
			}
		});

		$("#up").bind("click",function(){
			var that = $(this);
			if(!up){
				up = true;
				$.get("/article/up/id/<?php echo $id;?>",function(result){
					if(result.code == 1){
						var totalPlace = $("#upTotal");
						if(result.message == 'down')
						{
							$("#uptext").text('赞');
							totalPlace.text(parseInt(totalPlace.text())-1);
							$(".likelist").find("a[id="+uid+"]").remove();
						}else{
							$("#uptext").text('已赞');
							totalPlace.text(parseInt(totalPlace.text())+1);
							$("#alreadyUp").after(result.message);
						}
					}
					else{
						alert(result.message);
					}
					up = false;
				},'json');
			}
		});

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
		dataForWeixin.desc = '<?php echo $model->summary; ?>';
		dataForWeixin.MsgImg = "<?php echo !empty($model->share_pic) ? Yii::app()->request->getBaseUrl(true) .'/'. $model->share_pic : Yii::app()->request->getBaseUrl(true).'/static/images/article_1.png'; ?>";
	})
</script>

<div class="tc_inp" id="loginmodal" style="display:none;">
	<div class="formitems">
        <input type="text" name="comment" id="title" value="" class="inp" placeholder="请输入评论内容" required="required">
    </div>
    <input type="submit" value="提交" class="pl_btn1" name="commentSubmit">
    <a href="#" class="pl_btn2 hidemodal">取消</a>
</div>