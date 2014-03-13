<?php /* @var $this Controller  */?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->renderPartial('//layouts/qiye/includes/head');?>
</head>
<body>
	<!-- topbar starts -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="<?php echo Yii::app()->baseUrl?>">
					<span>后台管理</span>
				</a>
				<!-- user dropdown starts -->
				<div class="btn-group pull-right">
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i
						class="icon-user"></i><span class="hidden-phone"> <?php echo Yii::app()->user->name?>
					</span> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><?php echo CHtml::link('个人中心',array('/qiye/profile'));?></li>
						<li class="divider"></li>
						<li><?php echo CHtml::link('退出',array('/qiye/logout'));?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- topbar ends -->
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2 main-menu-span">
				<div class="well nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<li class="nav-header hidden-tablet">Main</li>
						<li><a class="ajax-link" href="/qiye"><i class="icon icon-blue icon-home"></i><span class="hidden-tablet">首页</span></a></li>
						<li><a class="ajax-link" href="/qiye/card"><i class="icon icon-blue icon-flag"></i><span class="hidden-tablet">企业名片</span></a></li>
						<li><a class="ajax-link" href="/qiye/employee"><i class="icon icon-blue icon-users"></i><span class="hidden-tablet">企业员工</span></a></li>
						<li><a class="ajax-link" href="/qiye/contacts"><i class="icon icon-blue icon-contacts"></i><span class="hidden-tablet">企业微群</span></a></li>
						<li><a class="ajax-link" href="/qiye/activity"><i class="icon icon-blue icon-date"></i><span class="hidden-tablet">企业活动</span></a></li>
						<li><a class="ajax-link" href="/qiye/profile"><i class="icon icon-blue icon-user"></i><span class="hidden-tablet">个人中心</span></a></li>
					</ul>
				</div>
			</div>

			<div id="content" class="span10">
				<!-- content starts -->
				<div>
					<div class="breadcrumb">
						<?php
						if(empty($this->breadcrumbs))
							$this->breadcrumbs = array ('');
						$this->widget('zii.widgets.CBreadcrumbs', array(
					 		'links'=>$this->breadcrumbs,
							'homeLink'=>'<a href="/qiye">首页</a>'
						)); ?>
						<!-- breadcrumbs -->

					</div>
				</div>
				<?php foreach (Yii::app()->user->getFlashes() as $key=>$message){
					echo '<div class="alert alert-'.$key.'">
					<button type="button" class="close" data-dismiss="alert">×</button>'.ucfirst($message).'</div>';
				}?>
				<?php echo $content;?>

				<!-- content ends -->
			</div>
			<!--/#content.span10-->
		</div>
		<!--/fluid-row-->
		<hr>
		<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">�</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a> <a href="#"
					class="btn btn-primary">Save changes</a>
			</div>
		</div>
		<footer>
			<p class="pull-left">
			</p>
			<p class="pull-right">
			</p>
		</footer>
	</div>
	<!--/.fluid-container-->
	<?php $this->renderPartial('//layouts/qiye/includes/footer');?>
</body>
</html>
