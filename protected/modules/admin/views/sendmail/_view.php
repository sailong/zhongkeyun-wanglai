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
		'title','content','send_type','add_time',
		array('type' => 'html','name' => 'title','value' => CHtml::image(Member::model()->getPhoto($model,'s'), '用户头像', array('width' => 140, 'height' => 120))),
		array('name' => 'is_vip', 'value' => $model->is_vip == Member::TYPE_VIP ? '加V' : '不加V'),
		array('name' => 'subscribe', 'value' => $model->subscribe == Member::SUBSCRIBE ? '关注' : '已取消关注'),
		'wanglai_number',
		array('name' => 'wanglai_number_grade', 'value' => $model->wanglai_number_grade == Member::WANG_LAI_NUMBER_GOLDEN ? '是' : '不是'),
		array('name' => 'is_qiye', 'value' => $model->is_qiye == Member::QIYE_YES ? '是' : '不是')
	)
));