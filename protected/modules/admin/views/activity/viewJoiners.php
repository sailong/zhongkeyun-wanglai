<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
			<div class="conArea">
			<div class="searchBox"><span class="txs">一共查询到 <?php echo $dataProvider->getTotalItemCount();?> 条数据&nbsp;&nbsp;</span></div>
				<div class="content-box">
				<div class="content-box-header">
			<h3><?php echo $this->nav;?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->createUrl('index');?>">返回列表</a></h3>
		</div>
					<div class="content-box-content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<thead>
								<tr>
								   <th width="7%">ID</th>
								   <th width="15%">姓名</th>
								   <th width="15%">头像</th>
								   <th width="15%">报名时间</th>
								   <th>操作</th>
								</tr>
							</thead>
							<tbody>
							
							<?php
							
								$data = $dataProvider->getData(); 
								if(!empty($data))
								{
									$time = time();
									foreach ($data as $row)
									{
										$url = $this->createUrl('card/view', array('id'=>$row['member_id']));
										
										$memberName = isset($memberArr[$row['member_id']]) ? $memberArr[$row['member_id']]['name'] : '';
										$memberAvatar = isset($memberArr[$row['member_id']]) ? $memberArr[$row['member_id']]['avatar'] : '';
										
										
										echo '<tr id="tr_'.$row['id'].'">';
										echo '<td>' . $row['id'] . '</td>';
										echo '<td><a href="'.$url.'">' . $memberName.'</a></td>';
										echo '<td><a href="'.$url.'"><img src="'.$memberAvatar.'" width="80" height="80"/></a></td>';
										echo '<td>'.date('Y-m-d H:i', $row['create_time']).'</td>';
										echo '<td><a href="javascript:;" class="delete_data" data_id="'.$row['id'].'" url="'.$this->createUrl('deleteJoiner',array("id"=>$row['id'])).'">删除</a>';
										echo '</tr>';
									}
								}
							?>
							
							
							</tbody>
							<tfoot>
								<tr>
									<td colspan="8" class="pages">
									<div class="fr">
									<?php 
										$pagination = $dataProvider->getPagination();
										$totalPage = $pagination->getPageCount();
										if($totalPage>1)
										{
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
										}
										?>
		    						</div>
									</td>
								</tr>
							</tfoot>
						</table>
						
					</div>
				</div>
</div>