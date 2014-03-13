
<div data-role="page" id="carddetail">
<div data-theme="b" data-role="header">
		<h3>Ta的小伙伴</h3>
</div>
<div data-role="content">
	<ul class="mylist fix">
		<?php 
		$openid = $this->getOpenid();
		if ($openid !== $model->weixin_openid)
		{
			
			if(!empty($data))
			{
	       		foreach ($data as $val)
	       		{
	       			$str.='<li><a href="javascript:void(0)" data-ajax="false" style="text-decoration:none"><img src="'.Member::model()->getAvatar($val['avatar']).'" alt="" />'.$val['name'].'</a></li>';
	       		}
	       		echo $str;
			}
			
		}
		
		?>
		
	</ul>
<p class="copyright">欢迎关注往来微信公众号：wanglairm<br />往来人脉-让人人都有自己的微名片<br />Powered by <a href="http://www.wanglai.cc">wanglai.cc</a></p>
</div>

</div>