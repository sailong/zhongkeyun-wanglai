<?php $controller_id = strtolower($this->id);
	  $action_id = strtolower($this->getAction()->getId());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>往来人脉内部业务支撑系统</title>
<link rel="stylesheet" type="text/css" href="/static/css/manage.css?v=1111" />
<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript" src="/static/js/common.js?v=1328"></script>
<script type="text/javascript" src="/static/layer/layer.min.js"></script>
</head>
<body>
<?php 
$menuList = $this->getMenuList();
?>
<div class="topbar">
		当前用户：<?php echo Yii::app()->user->getState('nickname');?>  <a href="<?php echo Yii::app()->createUrl('/admin/User/Update',array('from'=>1));?>">修改密码</a> 
		 <a href="<?php echo Yii::app()->createUrl('/admin/login/logout')?>">退出登录</a>
	</div>
	
	<div class="main">
		<div class="menu">
			<p class="time"> </p>
			<ul>
			<?php 
			$purview = Yii::app()->user->getState('purview');
			$isManage = $this->checkIsManage();
			foreach ($menuList as $m)
			{
				if(!$isManage)
				{
					if(!in_array($m['id'],$purview['menu'])) continue;
				}
				$id = strtolower($m['id']);
				
			?>
			    <li <?php if($controller_id . '/'.$action_id == $id) { ?> class="cur" <?php } ?> ><a href="<?php echo Yii::app()->createUrl('/admin/'.$m['id'])?>">+<?php echo $m['name'];?></a></li>
			<?php 
			}
			if($isManage)
			{
			?>
			<li  <?php if($controller_id=='user') { ?> class="cur" <?php } ?>><a href="<?php echo Yii::app()->createUrl('/admin/user/index')?>">+系统用户管理</a></li>
			<?php }?>
				
				
			</ul>
		</div>
		<div class="content">
			<?php echo $content;?>
		</div>
	</div>
	
</body>
</html>
