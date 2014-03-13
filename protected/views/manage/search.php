<div data-role="page" id="actpage">
    <div data-theme="b" data-role="header">
    	<a href="#" data-rel="back">后退</a>
        <h3>名片夹</h3>
    </div>
    <div data-role="content" style="padding:1%">
    	<div data-role="fieldcontain" style="min-height:2em">
			<form action="index.php" method="GET" data-ajax="false" id="form1">
				<input type="hidden" value="manage/search" name="r" />
				<input type="search" name="keyword" id="search_btn" value="<?php echo $keyword;?>" placeholder="请输入您要搜索的关键词" data-val="true" data-val-required="关键字不能为空" />
				<input type="submit" data-theme="a" value="搜索" id="submit_btn" />
			</form>
		</div>
		<?php if($from=='follow'):?>
			<style>
				#n_toolbar .ui-btn {background-image:-webkit-gradient(linear,left top,left bottom,from( #eeeeee ),to( #dddddd ));background-image:-webkit-linear-gradient( #eeeeee,#dddddd );background-image:-moz-linear-gradient( #eeeeee,#dddddd );background-image:-ms-linear-gradient( #eeeeee,#dddddd );background-image:-o-linear-gradient( #eeeeee,#dddddd );background-image:linear-gradient( #eeeeee,#dddddd );color: #333;font-weight: normal;}
				#n_toolbar .ui-btn-active {border:1px solid #ddd;background:#ffffff;font-weight:bold;color:#510000;cursor:pointer;text-shadow:0  1px  0  #eee;text-decoration:none;font-family:Helvetica,Arial,sans-serif;font-weight: bold;}
			</style>
			<div data-role="navbar" id="n_toolbar">  
				<ul> 
					<li><a href="<?php echo $this->createUrl('manage/interFollow');?>" <?php if($this->getAction()->getId() == 'interFollow') echo 'class="ui-btn-active"';?> data-ajax="false">好友<br/>(互相关注)</a></li> 
					<li><a href="<?php echo $this->createUrl('manage/myFollow');?>" <?php if($this->getAction()->getId() == 'myFollow') echo 'class="ui-btn-active"';?> data-ajax="false">收藏<br/>(我关注的)</a></li> 
					<li><a href="<?php echo $this->createUrl('manage/followMe');?>" <?php if($this->getAction()->getId() == 'followMe') echo 'class="ui-btn-active"';?> data-ajax="false">粉丝<br/>(关注我的)</a></li> 
				</ul> 
			</div>
		<?php endif;?>
		
        <?php 
        if(!empty($dataProvider))
        {
        	$data = $dataProvider->getData();
        	$pagination = $dataProvider->getPagination();
	        echo $this->renderPartial('_follow_table',array('data'=>$data,'pagination'=>$pagination));
        }
       ?>
        
    </div>
</div>
<?php echo $this->renderPartial('/layouts/footer'); ?> 
<script>
$(function(){
	$("#search_btn").css("width","100%").parent().css({"width":"76%","float":"left"});
	$("#submit_btn").parent().css({"width":"20%","float":"left","margin-left":"10px"});
	$("#submit_btn").prev().css("padding","6px");
})
</script>