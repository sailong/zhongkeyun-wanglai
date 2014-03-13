<div class="row-fluid">
	<div class="span12 center login-header">
		<h2>欢迎来到往来企业后台管理系统</h2>
	</div><!--/span-->
</div><!--/row-->
			
<div class="row-fluid">
	<div class="well span6 center login-box">
		
		<div class="alert alert-warning">
			<?php if(Yii::app()->user->hasFlash('loginMessage')): ?>
				<?php echo Yii::app()->user->getFlash('loginMessage'); ?>
			<?php else:?>
				请输入用户名和密码
			<?php endif;?>
		</div>
		<?php /** @var BootActiveForm $form */
			$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			    'id'=>'userLogin',
			    'type'=>'horizontal',
		)); ?>
 
<fieldset>
		
		<?php echo $form->textFieldRow($model, 'username'); ?>
		
		<?php echo $form->passwordFieldRow($model, 'password'); ?>
		
		<?php echo $form->textFieldRow($model, 'verifyCode'); ?>
		<div class="control-group">
			<div class="controls">
				<?php $this->widget('CCaptcha',array('buttonLabel'=>'换一张')); ?>
			</div>
			
		</div>
		<?php echo $form->checkBoxRow($model, 'rememberMe'); ?>
		<div class="control-group">
			<div class="controls">
				<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'size'=>'large', 'label'=>'登录')); ?>
			</div>
		</div>
</fieldset>


<?php $this->endWidget(); ?>
	</div><!--/span-->
		
		
		
		
		
</div><!--/row-->

