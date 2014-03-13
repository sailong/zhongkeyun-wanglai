<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
			<div class="conArea">
			<div class="searchBox">
				<a href="<?php echo $this->createUrl('create');?>">+ 创建签名 + </a>
			</div>
				<div class="content-box">
					<div class="content-box-content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<thead>
								<tr>
								   <th width="5%">ID</th>
								   <th width="10%">活动标题</th>
								   <th width="40%">活动内容</th>
								   <th width="10%">活动链接</th>
								   <th width="10%">签名人数</th>
								    <th width="8%">PV</th>
								   <td width="10%">创建时间</td>
								   <th>操作</th>
								</tr>
							</thead>
							<tbody>
							
							<?php
							
								$data = $dataProvider->getData(); 
								if(!empty($data))
								{
									foreach ($data as $row)
									{
										echo '<tr>';
										echo '<td>' . $row->id . '</td>';
										echo '<td>' . $row->title.'</td>';
										echo '<td>' . mb_substr(strip_tags($row->content),0,50,'UTF-8').'</td>';
										echo '<td><a href="' . $this->createAbsoluteUrl('/signature/index',array('id'=>Util::encode($row->id))) . '">链接</a></td>';
										echo '<td>' . SignActivity::model()->calculateTotal($row->id).'</td>';
										echo '<td>' . $row->pv_counts . '</td>';
										echo '<td>'. date('Y-m-d H:i:s', $row->create_time).'</td>';
										echo '<td>
								   			<a href="'.$this->createUrl('update', array('id'=>$row->id)).'">修改</a>';
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
										if($pagination->getPageCount()>1)
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