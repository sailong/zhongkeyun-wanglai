
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
		
	<div class="brand">当前位置：首页 > <em>发布文章&nbsp;>&nbsp;</em></div>
	<div class="conArea">
	<div class="content-box">
		<div class="content-box-header">
			<h3>发送文章&nbsp;&nbsp;&nbsp;&nbsp;<a href="/admin/article/index">返回列表</a></h3>
		</div>
		<div class="content-box-content">


		<form enctype="multipart/form-data" id="member-form" action="/admin/article/adddo" method="post">	
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
				<label><span class="required">*</span>发布人</label>
			</td>
			<td>
				<?php 
			
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
							'name'=>'publish_uids',
							'sourceUrl' => $this->createUrl('/admin/article/GetCreater'),
							//'source'=>array('ac1', 'ac2', 'ac3'),
							// additional javascript options for the autocomplete plugin
							'options'=>array(
								'minLength'=>'2',
								
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
		<tr>
			<td width="10%">分享图片：</td>
			<td>
				<input type="file" name="share_pic"/>
			</td>
			<td width="30%">
					
			</td>
		</tr>
		
		<?php if(!empty($model->share_pic)):?>
		<tr>
			<td width="10%"><label>原图片</label></td>
			<td>
				<img src="/<?php echo $model->share_pic; ?>" height="100">
			</td>
			<td width="30%">
				
			</td>
		</tr>
		<?php endif;?>
		<tr >
			<td ></td>
			<td>
				<div class="row buttons">
					<input type="button" name="yt1" value="提交" id="submit_but"/>	
				</div>
			</td>
		</tr>
	
	</table>
	<script>
	   var a = $("#submit_but").click(function(){
		        $(this).attr('disabled',true);
		        document.forms[0].submit();
		        //$("#member-form").submit();
		   });
	</script>
</form>
</div>
</div>
</div>	
</div>
