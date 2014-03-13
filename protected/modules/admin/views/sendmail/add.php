
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
				    'id'=>'content',   //Textarea id
				    'language'=>'zh_CN', // example: spanish
				    // Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
				    'items' => array(
						'langType'=>'zh_CN', // example: en  (INVOKE)
				      	'width'=>'700px',
						'height'=>'300px',
						'themeType'=>'simple',
						'allowImageUpload'=>true,
						'items'=>array(
							'formatblock','fontname', 'fontsize','lineheight','|', 'forecolor', 'hilitecolor', 'bold', 'italic',
							'underline', 'removeformat', '|', 'justifyleft', 'justifycenter',
							'justifyright', 'insertorderedlist','insertunorderedlist', '|',
							'emoticons', 'image', 'link','|','clearhtml','quickformat','baidumap','source','undo', 'redo'
						),
					)
				)
			); ?>
	<div class="main">
		
	<div class="brand">当前位置：首页 > <em>发送邮件&nbsp;>&nbsp;</em></div>
	<div class="conArea">
	<div class="content-box">
		<div class="content-box-header">
			<h3>发送邮件&nbsp;&nbsp;&nbsp;&nbsp;<a href="/admin/sendmail/index">返回列表</a></h3>
		</div>
		<div class="content-box-content">


		<form enctype="multipart/form-data" id="member-form" action="/admin/sendmail/send" method="post">	
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
		<tr>
			<td width="10%"></td>
			<td>
				</td>
			<td width="30%">
						
			</td>
		</tr>
		
		<tr>
			<td width="10%">
				<label for="title" class="required"><span class="required">*</span>标题 </label>
			</td>
			<td>
				<input size="100" style="height:20px;" name="title" id="title" type="text" maxlength="100" />			
			</td>
			<td width="30%">
						
			</td>
		</tr>
		
		<tr>
			<td width="10%">
				<label><span class="required">*</span>发送类型</label>
			</td>
			<td>
				<span class="required"><?php echo $send_name;?></span>		
			</td>
			<td width="30%">
						
			</td>
		</tr>
		<?php if($send_type==3){?>
		<tr>
			<td width="10%">
				<label><span class="required">*</span>发送对象</label>
			</td>
			<td>
				<?php 
			
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
							'name'=>'contacts_ids',
							'sourceUrl' => $this->createUrl('/admin/Sendmail/GetContacts'),
							//'source'=>array('ac1', 'ac2', 'ac3'),
							// additional javascript options for the autocomplete plugin
							'options'=>array(
								'minLength'=>'1',
								
							),
							'htmlOptions'=>array(
								'style'=>'height:20px;',
								'size' => 100
							),
					));
			
				?>				
			</td>
			<td width="30%">
						
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td width="10%">
				<label for="content" class="required"><span class="required">*</span>内容</label>
			</td>
			<td>
				<textarea name="content" id="content"></textarea>			
			</td>
			<td width="30%">
					
			</td>
		</tr>
		<tr >
			<td ></td>
			<td>
				<div class="row buttons">
					<input type="submit" name="yt1" value="发送" />			
					<input type='hidden' name="send_type" value='<?php echo $send_type;?>'>
				</div>
			</td>
		</tr>
	
	</table>
	
</form>
</div>
</div>
</div>	
</div>
