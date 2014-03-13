<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
<div class="conArea">
	<div class="content-box">
		<div class="content-box-header">
			<h3>今日数据统计</h3>&nbsp;&nbsp;&nbsp;&nbsp;
		</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
			<thead>
				<tr class="altrow">
				   <th width="10%">用户ID</th>
				   <th width="15%">用户名</th>
				   <th width="15%">电话</th>
				   <th width="15%">职位</th>
				   <th width="20%">创建时间</th>
				   <th>最近登录时间</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($data as $value) {
				$url = $this->createUrl('/admin/card/view',array('id'=>$value['id']));
				?>
				<tr>
					<td><a href="<?php echo $url; ?>"><?php echo $value['id']; ?></a></td>
					<td><a href="<?php echo $url; ?>"><?php echo $value['name']; ?></a></td>
					<td><?php echo $value['mobile']; ?></td>
					<td><?php echo $value['position']; ?></td>
					<td><?php echo date('Y-m-d H:i:s',intval($value['created_at'])); ?></td>
					<td><?php echo date('Y-m-d H:i:s',intval($value['last_login_at'])); ?></td>
				</tr>
			<?php }?>
			</tbody>
			
			
			<tfoot>
				<?php 
					if($pagination->getPageCount() > 1){
				?>
				<tr>
					<td colspan="6" class="pages">
					<div class="fr">
					<?php  
						    $this->widget('Yspage',array(  
						        'header'=>'',  
						        'firstPageLabel' => '首页',  
						        'lastPageLabel' => '末页',  
						        'prevPageLabel' => '上一页',  
						        'nextPageLabel' => '下一页',  
						        'pages' => $pagination,  
						        'maxButtonCount'=>13,
						        )  
						    );  
    						?>  
    					</div>
					</td>
				</tr>
				
				<?php }?>
			</tfoot>
		</table>
	</div>
</div>