<?php
$this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'itemCssClass' => false,
	'htmlOptions' => array(
		'class' => 'table1',
		'border' => 0,
		'cellpadding' => 0,
		'cellspacing' => 0
	),
	'attributes' => array(
		'name','weixin_openid','mobile','email','position','company','address','company_url','main_business','supply','demand',
		'views','show_item','weixin','yixin','laiwang','qq','hobby','notes','share_counts','profile','social_position',
		array('type' => 'html','name' => 'avatar','value' => CHtml::image(Helper::getImage($model->avatar), '用户头像', array('width' => 140, 'height' => 120))),
		array('name' => 'is_vip', 'value' => $model->is_vip == Member::TYPE_VIP ? '加V' : '不加V')	
	)
));