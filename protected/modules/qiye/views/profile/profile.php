<?php $this->pageTitle='个人中心';
$this->breadcrumbs=array(
	'个人中心'
);
$this->menu=array(
    array('label'=>'编辑', 'url'=>array('edit')),
    array('label'=>'修改密码', 'url'=>array('changepassword')),
    array('label'=>'退出', 'url'=>array('/qiye/logout')),
);

$this->title = '个人信息';
?>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="alert alert-warning">
	<a data-dismiss="alert" class="close">×</a>
	<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>


<?php 
$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'username','email','create_at'
		)
	));
	
?>