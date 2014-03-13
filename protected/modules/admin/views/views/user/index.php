<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
			<div class="conArea">
				<div class="searchBox">
				    <span class="txs">一共查询到 <?php echo $count;?> 条数据</span>
				</div>
				<div class="content-box">
					<div class="content-box-header">
						<h3><?php echo $this->nav;?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->createUrl('update');?>">添加</a>
					</div>
					<div class="content-box-content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<thead>
								<tr>
								   <th width="5%">ID</th>
								   <th width="10%">用户名</th>
								 
								   <th width="10%">操作</th> 
								</tr>
							</thead>
							<tbody>
							
							<?php
							if($data)
							{
								foreach($data as $val)
								{
							?>
									<tr id="tr_<?php echo $val->uid;?>">
										<td><?php echo $val->uid;?></td>
										<td><?php echo $val->nickname;?></td>
										<td>
										     <a href="<?php echo $this->createUrl('update',array('id'=>$val->uid));?>">修改</a>
										     <?php if($val->role_id!=1){?>
										     <a href="javascript:;" class='delete_data' url="<?php echo $this->createUrl('delete',array('id'=>$val->uid));?>" data_id="<?php echo $val->uid;?>">删除</a>
											 <a href="<?php echo $this->createUrl('set',array('id'=>$val->uid));?>">分配权限</a>
											<?php }?>
										</td>
									</tr>
							<?php 
								}
							}
							?>	
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3" class="pages">
									<div class="fr">
									<?php  
								    $this->widget('Yspage',array(  
								        'header'=>'',  
								        'firstPageLabel' => '首页',  
								        'lastPageLabel' => '末页',  
								        'prevPageLabel' => '上一页',  
								        'nextPageLabel' => '下一页',  
								        'pages' => $pages,  
								        'maxButtonCount'=>13,
								        )  
								    );  
		    						?>  
		    						</div>
									</td>
								</tr>
							</tfoot>
						</table>
						
					</div>
				</div>
			</div>