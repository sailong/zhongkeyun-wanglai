
<?php echo $this->renderPartial('/common/header',array('title'=>'名片二维码'));?>

<div id="content">
	<p class="qr1"><img src="<?php echo $this->createUrl("member/getQRcodeLocal"); ?>" alt="" /></p>
	<p class="qr2">
		让你的朋友用微信“扫一扫”上面的二维码，<br />直接将名片信息保存至手机通讯录。
	</p>
</div>