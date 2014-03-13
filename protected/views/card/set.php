<div data-role="page" id="Create">
    <div data-theme="b" data-role="header">
        <h3>设置微名片隐藏项</h3>
    </div>
    <div data-role="content">
        <form action="<?php echo $this->createUrl('setDo');?>" method="post" data-ajax="false">
            
			<input type="hidden" name="openid" value="<?php echo $openid;?>">
			<input type="hidden" name="id" value="<?php echo $model->id;?>">
			
			<table border="0">
			<tr>
				<td align="right">手机号码</td>
				<td>
					<input type="checkbox" name="data[mobile]" value="0" <?php echo $this->ischeckd($model->show_item,'mobile',0);?> style="zoom:200%">
				</td>
			</tr>
			<tr>
				<td align="right">供给</td>
				<td>
					<input type="checkbox" name="data[supply]" value="0" <?php echo $this->ischeckd($model->show_item,'supply',0);?> style="zoom:200%">
				</td>
			</tr>
			<tr>
				<td align="right">需求</td>
				<td>
					<input type="checkbox" name="data[demand]" value="0" <?php echo $this->ischeckd($model->show_item,'demand',0);?> style="zoom:200%">
				</td>
			</tr>
			</table>
		
           

       
            <p>
                <input type="submit" value="提交" data-theme="b" />
                <span class="field-validation-valid" data-valmsg-for="createresult" data-valmsg-replace="true"></span>
            </p>
            <a data-role="button" data-theme="b" href="<?php echo $this->createUrl('view',array('view_openid'=>$openid));?>" data-ajax="false">查看我的微名片</a>
        </form>
    </div>
</div>

    <div id="operate-ok"></div>
    
