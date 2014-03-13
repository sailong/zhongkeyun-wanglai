<?php //echo $this->renderPartial('/common/header',array('title'=>'三步定制贺卡'));?>
<header id="header">
	<h1><?php echo '三步定制贺卡';?></h1>
</header>

<div id="content">
	<h2 class="cardh2">第三步：填名字</h2>
	<form action="<?php echo $this->createUrl('bless/generate'); ?>" method="post" class="cform">
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

<?php //echo $this->renderPartial('/common/footer'); ?> 