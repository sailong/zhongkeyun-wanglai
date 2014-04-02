<?php echo $this->renderPartial('/common/header',array('title'=>'文章正文'));?>

<?php 
	$id = Util::encode($model->id);
	$guest = Yii::app()->user->getIsGuest() ? true : false;
	//$totalUp = 0;
	$totalView = 0;
if(!$guest)
{
	$file1 = '/static/js/tc_inp.js';
	$client = Yii::app()->clientScript;
	$client->registerScriptFile($file1,CClientScript::POS_BEGIN);
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
		<!--<section class="likelist">
			<u></u>
			赞过的：<a href="#">正版强盗</a><a href="#">王大炮</a><a href="#">阿里郎</a><a href="#">正版强盗</a><a href="#">王大炮</a><a href="#">阿里郎</a>
		</section>-->
		<section class="cmp_btnb fix">
		    <?php if($guest):?>
			<a href="<?php echo $this->createUrl('up');?>" class="cmp_btnbl btn_b2">赞(<span id="upTotal"><?php echo $totalUp;?></span>)</a>
			<a href="<?php echo $this->createUrl('mark') ?>" class="cmp_btnbr btn_b2">收藏</a>
    		<?php else:?>
    			<?php if(ArticleMark::model()->checkMark($model->id, Yii::app()->user->id, ArticleMark::TYPE_UP)):?>
    				<a href="javascript:;" class="cmp_btnbl btn_b2" id="up"><span id="uptext">已赞</span>(<span id="upTotal"><?php echo $totalUp;?></span>)</a>
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
		<div class="pl-tab">
            <h4><u class="open-2"></u><a href="javascript:;" id="view_more_id">更多</a>最近访客</h4>
            <?php if(!empty($upers) || !empty($viewers)):?>
            <ul class="member_ws article_tab" id="viewers_list">
                <?php if(!empty($upers)):?>
			    <?php foreach ($upers as $uper):?>
			    <?php $totalView++;?>
                <li class='totalview_aaa'<?php if($totalView>4){echo " style='display:none;'";}?>>
                    <a href="<?php echo $this->createUrl('member/view',array('id'=>$uper->id));?>">
                        <p><img src="<?php echo Member::model()->getPhoto($uper);?>" alt="" /></p>
                        <p class="s1"><?php echo $uper->name;?></p>
                        <p class="s1">（赞过）</p>
                    </a>
                </li>
                
               <?php endforeach;?>
		       <?php endif;?>
                <?php if(!empty($viewers)):?>
			    <?php foreach ($viewers as $viewer):?>
			    <?php $totalView++;?>
                <li class='totalview_aaa'<?php if($totalView>4){echo " style='display:none;'";}?>>
                    <a href="<?php echo $this->createUrl('member/view',array('id'=>$viewer->id));?>">
                        <p><img src="<?php echo Member::model()->getPhoto($viewer);?>" alt="" /></p>
                        <p class="s1"><?php echo $viewer->name;?></p>
                        <p class="s1"></p>
                    </a>
                </li>
               <?php endforeach;?>
		       <?php endif;?>
		       <?php endif;?>
            </ul>
        </div>

        <div class="pl-tab">
            <h4><u class="open-2"></u><a href="javascript:;" id="comment_more_id">更多</a>评论</h4>
            <ul class="comment article_tab" id="comment_list">
                <div><input type="text" class="pl_inp" name="comment" id="title" value="" placeholder="请输入评论内容" required="required"><input name="commentSubmit" type="submit" value="提交" class="pl_btn_s"></div>
                <?php if(!empty($comments)):?> 
			    <?php foreach ($comments as $comment):?>
                <li>
                	<div class="img-s3"><a href="<?php echo $this->createUrl('member/view',array('id'=>$comment->member->id));?>" ><img src="<?php echo Member::model()->getPhoto($comment->member);?>" alt="" /></a></div>
                    <div class="img-s4">
                    <a href="<?php echo $this->createUrl('list',array('mid'=>Util::encode($comment->member->id))); ?>">
                        <p class="s1"><?php echo $comment->member->name;?>：</p>
                        <p class="s2"><?php echo $comment->comment;?></p>
                        <p class="date"><?php echo date('Y/m/d H:i',$comment->create_time);?></p>
                    </a>
                    </div>
                </li>
                <?php endforeach;?>
                <?php endif;?>
                
            </ul>
        </div>
        
        <section class="sharebox sharebox2 fix">
			<a href="javascript:;" class="sharebtnr">分享给朋友</a>
		</section>
		<section class="btngroup">
			<a href="<?php echo $this->createUrl('create');?>" class="btn_b2">我也要发布文章</a>
		</section>
	</div>
<script>
	$(function(){
		var index = 0;
		$(".article_nav a").click(function(){
			 index = $(".article_nav a").index(this);
			 $(this).addClass("cur").append("<u></u>").siblings().removeClass("cur").children("u").remove();  
			 $(".article_tab").eq(index).show().siblings(".article_tab").hide();
		});
		$(":submit[name=commentSubmit]").live('click',function(){
			//alert($(this).parents("div:first").html());return false;
			var self = $(this);
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
						self.parents("div:first").after(result.message);
					}else{
						alert(result.message);
					}
				},'json');
			}
		});
	    var comment_flag = false;
		$("#comment_more_id").live('click',function(){
			var self = $(this);
			//alert($(this).parents("div:first").html());return false;
			if(comment_flag){
				var parent = self.parents('h4:first');
				var u_obj = parent.children('u:first');
				var calss_u = u_obj.attr('class');
				//alert(calss_u);return false;
				if(calss_u=='open-2'){
					$("#comment_list").hide();
					u_obj.removeClass('open-2').addClass('open-1');
				}else{
					$("#comment_list").show();
					u_obj.removeClass('open-1').addClass('open-2');
				}
			    return false;
			}
			$.post("/article/loadmorecomment",{id:"<?php echo $id;?>"},function(result){
				if(result.code == 1)
				{
					comment_flag = true;
					$("#comment_list").append(result.message);
				}else{
					alert('没有了');
				}
			},'json');
		});
		var view_flag = true;
		$("#view_more_id").live('click',function(){
			if(<?php echo $totalView;?>==0){
				alert('暂无浏览记录');return false;
				}
			if(view_flag){
				if(<?php echo $totalView;?><=4){
					alert('没有了');
				}
				$(".totalview_aaa").show();
				view_flag = false;
				return false;
			}
			var self = $(this);
			var parent = self.parents('h4:first');
			var u_obj = parent.children('u:first');
			var calss_u = u_obj.attr('class');
			if(calss_u=='open-2'){
				//$(".totalview_aaa").hide();
				$("#viewers_list").hide();
				u_obj.removeClass('open-2').addClass('open-1');
			}else{
				//$(".totalview_aaa").show();
				$("#viewers_list").show();
				u_obj.removeClass('open-1').addClass('open-2');
			}
			
		    return false;
			//alert($(this).parents("div:first").html());return false;
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
							//$(".likelist").find("a[id="+uid+"]").remove();
						}else{
							$("#uptext").text('已赞');
							totalPlace.text(parseInt(totalPlace.text())+1);
							//$("#alreadyUp").after(result.message);
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
		dataForWeixin.desc = '<?php echo preg_replace('/[\s|\&nbsp;]/', '', strip_tags($model->summary)); ?>';
		dataForWeixin.MsgImg = "<?php echo !empty($model->share_pic) ? Yii::app()->request->getBaseUrl(true) .'/'. $model->share_pic : Yii::app()->request->getBaseUrl(true).'/static/images/article_1.png'; ?>";
	})
</script>