
<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
			<div class="conArea">
			<div class="searchBox">
				    <span class="txs">一共查询到 <?php echo $dataProvider->getTotalItemCount();?> 条数据&nbsp;&nbsp;
				</div>
				<div class="content-box">
					<div class="content-box-content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<thead>
								<tr>
								   <th width="5%">ID</th>
								   <th width="15%">标题</th>
								   <th width="8%">创建人</th>
								   <th width="14%">创建时间</th>
								    <th width="8%">PV</th>
								   <th width="14%">分享次数</th>
								   <th width="14%">已报名人数</th>
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
										echo '<tr>';
										echo '<td>' . $row->id. '</td>';
										echo '<td>' . $row->title . '</td>';
										echo '<td>' . $row->creater->name . '</td>';
										echo '<td>' . date('Y-m-d H:i:s',$row->create_time).'</td>';
										echo '<td>' . $row->pv_counts . '</td>';
										echo '<td>' . $row->share_counts . '</td>';
										echo '<td>' .ContactsMember::model()->countPass($row->id) .'</td>';
										echo '<td><a href="'.$this->createUrl('extra/index',array('id'=>$row->id,'type'=>Extra::TYPE_CONTACTS_EMAIL)).'">邮箱管理</a></td>';
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