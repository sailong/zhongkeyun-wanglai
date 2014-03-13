<?php
$this->breadcrumbs=array(
	'企业名片'=>array('/'),
	'更改头像'
);
$id = Util::encode($model->id);
$this->menu=array(
	array('label'=>'更改信息','url'=>array('update','id'=>$id)),
	array('label'=>'修改头像','url'=>array('photo','id'=>$id)),
);
$this->title = '更换头像';
?>

<?php if(Yii::app()->user->hasFlash('photoMessage')): ?>
<div class="alert alert-error">
	<a data-dismiss="alert" class="close">×</a>
	<?php echo Yii::app()->user->getFlash('photoMessage'); ?>
</div>
<?php endif; ?>
<form enctype="multipart/form-data" class="form-horizontal" method="post">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="fileInput">选择头像</label>
			<div class="controls">
				<input class="input-file uniform_on" id="fileInput" type="file" name="avatar">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="fileInput">当前头像</label>
			<div class="controls">
				<?php $photo = Member::model()->getPhoto($model);?>
				<img src="<?php echo $photo;?>" width="200px" height="200px" >
			</div>
		</div>
	
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">保存</button>
		</div>
	 </fieldset>
</form>

<?php 

$script = <<<EOF
$(function(){
	$(":file").uniform({fileBtnText: '选择头像'});
})
EOF;

// 美化样式
Yii::app()->clientScript->registerScript('photo', $script, CClientScript::POS_END);


?>