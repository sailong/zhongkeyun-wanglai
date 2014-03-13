<?php $this->title = '申请审核'; ?>
<?php
if(!empty($data))
{
	$script = <<<EOF
	
	$("body").delegate("button.btn","click",function(){
		var url = $(this).attr("link");
		var that = $(this);
		$.get(url,function(result){
			if(result.status == 1){
				that.siblings().remove();
				that.replaceWith(result.msg);
			}else{
				alert(result.msg);
				that.removeAttr("disabled");
			}
		},'JSON');
		that.attr("disabled","disabled");
	})
	
EOF;
	Yii::app()->clientScript->registerScript('button',$script);
}

?>
<div class="box-content">
	<?php 
		if(!empty($data))
			echo '<ul class="dashboard-list">';
			foreach ($data as $item)
			{
				$str = $item['type'] == 'activity' ? '申请参加活动' : '申请加入群';
				echo '<li style="line-height:32px">';
				echo '<a href="'.$this->createUrl('/qiye/member/view',array('id'=>Util::encode($item['member_id']))).'" target="_blank">'.$item['username'].'</a>&nbsp;';
				if($item['type'] == 'activity')
				{
					echo '报名参加&nbsp;<span class="label label-warning">活动</span>&nbsp;';
					echo '<a href="'.$this->createUrl('/qiye/activity/view',array('id'=>Util::encode($item['object_id']))).'" target="_blank">'.$item['title'].'</a>';
					echo '&nbsp;(申请时间：'.date('Y-m-d H:i:s',$item['apply_time']).')';
					echo '<span class="pull-right">
							<button class="btn btn-small btn-warning" link="'.$this->createUrl('passActivity',array('id'=>Util::encode($item['apply_id']))).'">同意</button>
							<button class="btn btn-small btn-success" link="'.$this->createUrl('refuseActivity',array('id'=>Util::encode($item['apply_id']))).'">拒绝</button>
					</span>';
				}else{
					echo '申请加入&nbsp;<span class="label label-success">微群通讯录</span>&nbsp;';
					echo '<a href="'.$this->createUrl('/qiye/contacts/view',array('id'=>Util::encode($item['object_id']))).'" target="_blank">'.$item['title'].'</a>';
					echo '&nbsp;(申请时间：'.date('Y-m-d H:i:s',$item['apply_time']).')';
					echo '<span class="pull-right">
							<button class="btn btn-small btn-warning" link="'.$this->createUrl('passContacts',array('id'=>Util::encode($item['apply_id']))).'">同意</button>
							<button class="btn btn-small btn-success" link="'.$this->createUrl('refuseContacts',array('id'=>Util::encode($item['apply_id']))).'">拒绝</button>
					</span>';
				}
			}
			echo '</ul>';	
	?>			
</div>
