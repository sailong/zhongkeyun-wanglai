<?php echo $this->renderPartial('/common/header',array('title'=>'送贺卡'));?>

<div id="content">
	<h2 class="cardh2">复制并生成新贺卡</h2>
	<form action="<?php echo $this->createUrl('bless/copy',array('id'=>$model->id,'hash'=>Util::getCookie('hash'))); ?>" method="post" class="cform">
		<div class="formitems">
			<label for="senduser">寄件人：</label>
			<input type="text" name="from" id="senduser" value="<?php echo !empty($username) ? $username : ''; ?>" class="inp" placeholder="请输入寄件人" required="required" />
		</div>
		<div class="formitems">
			<label for="recipient">收件人：</label>
			<input type="text" name="to" id="recipient" value="" class="inp" placeholder="送给多人可不填" />
		</div>
		<div class="formitems">
			<input type="submit" value="生成贺卡" class="submitbtn" />
		</div>
	</form>
</div>

<?php echo $this->renderPartial('/common/footer'); ?>