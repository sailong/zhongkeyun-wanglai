<footer id="footer">
	<span class="mbtn"><img src="/static/images/menu.png" alt="" />菜单</span>
	<a href="javascript:window.history.go(-1);" class="goback"><img src="/static/images/goback.png" alt="" />返回</a>
	<?php if(isset($updateUrl)):?>
		<a href="<?php echo $updateUrl; ?>" class="eac"><img src="/static/images/eac.png" alt="" />编辑</a>
	<?php endif;?>	
</footer>