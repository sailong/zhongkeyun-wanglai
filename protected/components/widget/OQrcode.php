<?php

/**
 * 二维码
 * @author JZLJS00
 *
 */
class OQrcode extends CWidget
{
	
	
	
	public function run()
	{

		$script = <<<EOF
$(document).ready(function(){
	$("#orcode_btn").bind("click",function(){
		$("#codebg,#orcode").show();
		$("#orcode").css("top", ( $(window).height() - $("#orcode").height() ) / 2+$(window).scrollTop() + "px"); 
	});
	
	$("#orcode .closewin").bind("click",function(e){
		$("#codebg,#orcode,#sul").hide();
	});
})	
EOF;
		Yii::app()->clientScript->registerScript('getQrcode',$script,CClientScript::POS_END);
		$url = Yii::app()->createUrl('member/getQRcodeLocal');
		
		$view = <<<EOF
<div id="operate-ok"></div>
<div id="orcode">
	<img src="{$url}" />
	<p>用微信“扫一扫”上面的二维码图案，<br />直接将名片信息保存至您的手机通讯录。</p>
	<a href="#" class="closewin">X</a>
</div>
<div id="codebg"></div>
EOF;
		echo $view;
	}
	
}