<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?>&nbsp;>&nbsp;</em></div>
<div class="conArea">
	<div class="content-box">
		<div class="content-box-header">
			<h3><?php echo $this->nav;?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->createUrl('index');?>">返回列表</a></h3>
		</div>
		<div class="content-box-content">
			<?php echo $this->renderPartial('_form', $data); ?>
		</div>
	</div>
</div>