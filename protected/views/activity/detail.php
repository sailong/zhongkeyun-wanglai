<?php echo $this->renderPartial('/common/header',array('title'=>'活动详情'));?>

<div id="content">
	<div id="actinfo">
	<?php if(Yii::app()->user->hasFlash('apply')):?>
		<span class="red" id="researchOver"><?php echo Yii::app()->user->getFlash('apply');?></span>
		<?php 
			$script = <<<EOF
			setTimeout(function(){
				$("#researchOver").remove();
			},5000);
EOF;
			Yii::app()->clientScript->registerScript('research',$script,CClientScript::POS_END);
		?>
	<?php endif;?>
		
		<h1><?php echo CHtml::encode($model->title); ?></h1>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td class="tdtit">时&nbsp;&nbsp;&nbsp;&nbsp;间：</td>
			<td>
			<?php
				if($model->begin_time == $model->end_time)
				{
					echo date('Y年m月d日',$model->begin_time);
				}elseif(strtotime(date('ymd',$model->begin_time)) == strtotime(date('ymd',$model->end_time)))
				{
					echo date('Y年m月d日  H:i',$model->begin_time) . '-' . date('H:i', $model->end_time);
				}else{
					echo date('Y年m月d日   H:i',$model->begin_time) . '-' . date('Y年m月d日   H:i', $model->end_time);
				}
			?>	
			</td>
		  </tr>
		  <tr>
			<td class="tdtit">地&nbsp;&nbsp;&nbsp;&nbsp;点：</td>
			<td><?php echo $model->provinceName->name; ?> <?php echo $model->areaName->name; ?> <?php echo $model->district; ?></td>
		  </tr>
		  <tr>
			<td class="tdtit">已报名：</td>
			<td id="applyCount"><?php echo $count; ?>人</td>
		  </tr>
		  <?php if($model->max>0):?>
			  <tr>
				<td class="tdtit">限报名：</td>
				<td><?php echo $model->max; ?>人</td>
			  </tr>
		  <?php endif;?>
		  <tr>
			<td class="tdtit">浏&nbsp;&nbsp;&nbsp;&nbsp;览：</td>
			<td><?php echo $model->views; ?></td>
		  </tr>
		  <tr>
			<td class="tdtit">转&nbsp;&nbsp;&nbsp;&nbsp;发：</td>
			<td id="sharesCount"><?php echo $model->shares; ?></td>
		  </tr>
		</table>
		<div id="actcontent">
			<h1>活动介绍</h1>
			<div class="actcon">
				<?php 
					$details = $model->detail;
					preg_match_all('/(.*)\\n?/', $details,$matches);
		 			if(!empty($matches[1]))
			 			{
			 				$details = '';
			 				foreach ($matches[1] as $match)
				 				{
			 					$details .= "<p>".$match."</p>";
			 				}
			 			}
			 		echo preg_replace("/width:\d+px;?|height:\d+px;?|width=\"\d+\"|height=\"\d+\"/i",'', $details);
				?>
			</div>
		</div>
	</div>
	<section class="btngroup">
	<?php 
		
		if(time()>$model->end_time && strtotime(date('Y-m-d',time())) > strtotime(date('Y-m-d',$model->end_time))){
			echo '<a href="javascript:;" class="btn_b3">活动已结束</a>';
		}else if($model->state==Activity::VERIFY_STATE_GOING){
			echo '<a href="javascript:;" class="btn_b3">活动审核中</a>';
		}else if(Yii::app()->user->getIsGuest()){
			echo '<a href="'.$this->createUrl('activity/apply').'" class="btn_b1">我要报名</a>';
		}else if(Yii::app()->user->id == $model->create_mid){
			echo '<a href="'.$this->createUrl('activity/update',array('id'=>$model->id)).'" class="btn_b1" id="apply">编辑活动</a>';
			if($model->verify == Activity::APPLY_VIRIFY_WITH && ($model->state == Activity::VERIFY_STATE_WITHOUT || $model->state == Activity::VERIFY_STATE_PASS))
			{
				// 私密活动,有审核管理
				$applying = ActivityMember::model()->countApplying($model->id);
				echo '<a href="'.$this->createUrl('activity/applyList',array('id'=>$model->id)).'" class="btn_b1">审核管理('.$applying.')</a>';
			}
				
		}else{
			$exist = ActivityMember::model()->checkApply(Yii::app()->user->id,$model->id);
			if(!empty($exist))
			{
				$msg = '';
				$cancel = false;
				switch($exist['state'])
				{
					case  ActivityMember::VERIFY_STATE_APPLY:
						$msg = '已报名,等待审核';
						$cancel = true;
						break;
					case  ActivityMember::VERIFY_STATE_PASS:
						$msg = '已报名,审核通过';
						$cancel = true;
						break;
					case ActivityMember::VERIFY_STATE_REFUSE:
						$msg = '已被拒绝';
						break;
					default:
						$msg = '已报名';
						$cancel = true;
						break;
				}
				echo '<a href="javascript:;" class="btn_b3" id="applyed">'.$msg.'</a>';
				if($cancel)
					echo '<a href="javascript:;" class="btn_b1" id="cancel">取消报名</a>';
				
			}else{
				if(Yii::app()->user->id != $model->create_mid)
				{
					if(!empty($model->max) && $count >= $model->max)
						echo '<a href="javascript:;" class="btn_b3">报名人数已满,下次抓紧</a>';
					else
					{
						$research = Research::model()->findByAttributes(array('mid'=>Yii::app()->user->id));
						
						if(empty($research) && ($model->id == Activity::$special_id))
						{
							echo '<a href="'.$this->createUrl('/research/index',array('id'=>Util::encode($model->id))).'" class="btn_b1">我要报名</a>';
						}else{
							echo '<a href="javascript:;" class="btn_b1" id="apply">我要报名</a>';
						}
					}
				}
			}
		}
	?>
		<a href="<?php echo $this->createUrl('activity/getApplicants',array('id'=>$model->id)); ?>" class="btn_b1">看看谁报名了</a>
		<?php if($model->create_mid == Yii::app()->user->id):?>
		 	<a href="javascript:;" id="prom" class="btn_b1">发送报名用户列表到我的邮箱</a>
		<?php endif;?>
		
		<a href="<?php echo $this->createUrl('activity/create'); ?>" class="btn_b2">我要发起活动</a>
	</section>
</div>

<?php if(Yii::app()->user->id == $model->create_mid):?>
	<?php echo $this->renderPartial('/common/footer',array('updateUrl'=>$this->createUrl('activity/update',array('id'=>$model->id)))); ?>
<?php else:?>
	<?php echo $this->renderPartial('/common/footer'); ?>
<?php endif;?>
<script>
$(function(){

	var activity = function(){
		
		var activityId = <?php echo $model->id?>;

		var id = <?php echo (isset($exist) && !empty($exist)) ? $exist['id'] : 0 ;?>;
		// 申请及取消报名的url
		var url = {
			apply: "<?php echo $this->createUrl('activity/apply');?>",
			cancel: "<?php echo $this->createUrl('activity/cancel');?>",
			sendMail:"<?php echo $this->createUrl('activity/sendMail',array('id'=>$model->id)); ?>"
		};
		// 已报名
		var applyed = $("#applyed").length>0 ? $("#applyed") : {};

		var email = "<?php echo $this->_member->email;?>";

		var send = false;  // if sending mail

		var applying = false;  // if applying

		var canceling = false; // if canceling
		
		// 绑定报名及取消报名事件
		var bind = function(){
			$("#apply").live("click",function(){
				var that = $(this);
				if(!applying){
					applying = true;
					that.addClass('btn_b3');
					$.post(url.apply,{activityId:activityId},function(result){
						if(result.code == 1){
							var count = parseInt($("#applyCount").text())+1;
							$("#applyCount").text(count);
							that.text('报名成功（点击可取消报名）');
							that.attr("id","cancel");
							id = result.data;
						}else{
							alert(result.message);
						}
						that.removeClass('btn_b3');
						applying = false;
					},'json');
				}
			});

			$("#cancel").live("click",function(){
				var that = $(this);
				if(!canceling){
					canceling = true;
					that.addClass('btn_b3');
					$.post(url.cancel,{id:id},function(data){
						if(data.code == 1){
							var count = parseInt($("#applyCount").text())-1;
							if(count>=0)
								$("#applyCount").text(count)
							that.attr("id","apply");
							that.text('我要报名');
							if(!$.isEmptyObject(applyed)){
								applyed.remove();
								applyed = {};
							}
						}else{
							alert(data.message);
						}
						that.removeClass('btn_b3');
						canceling = false;
					},'json');
				}
			});

			// 发送报名人到邮箱
			$("#content").delegate("#prom", "click", function(event){
				event.preventDefault() ? event.preventDefault() : event.returnValue = false ;
				if(email == ''){
					var email2=prompt("请输入您的邮箱地址","");
				    if(!email2){
				    	return false;
				    }
				    email = email2;
				}
				var that = $(this);
				
				if(!send){
					send = true;
					that.addClass('btn_b3');
					$.get(url.sendMail+'/email/'+email, function(result){
						if(result.code == 1){
							alert('发送成功');
						}else{
							alert(result.message);
							email = '';
						}
						that.removeClass('btn_b3');
						send = false;
					},'JSON');
					
				}
			});
		};

		// 返回值
		return function(){
			bind();
		};
		
	}();

	activity();

	dataForWeixin.callback = function () {
	 	 $.post("<?php echo $this->createUrl('activity/countShare',array('id'=>$model->id));?>", function () {
		 	var count = parseInt($("#sharesCount").text())+1;
			 $("#sharesCount").text(count);
	   });
	}
	dataForWeixin.url = "<?php echo $this->createUrl('activity/detail',array('id'=>$model->id,'#'=>'mp.weixin.qq.com')); ?>";
	dataForWeixin.title = '<?php echo $model->title;?>';
	dataForWeixin.desc = '<?php echo htmlspecialchars(mb_substr(preg_replace('/\s/', '', strip_tags($model->detail)),0,50,'UTF-8'),ENT_QUOTES,'UTF-8');?>';
	dataForWeixin.MsgImg = "<?php echo Yii::app()->request->getHostInfo().'/static/weixin/activity1.png'; ?>";
})

</script>