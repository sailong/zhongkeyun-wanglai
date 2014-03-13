<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
			<div class="conArea">
				<div class="content-box">
					<div class="content-box-content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<thead>
								<tr>
								   <th width="7%">ID</th>
								   <th width="15%">姓名</th>
								   <th width="30%">反馈内容</th>
								   <th width="25%">反馈时间</th>
								   <th>是否处理</th>
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
										echo '<td>' . $row['id'] . '</td>';
										echo '<td><a href="'.$this->createUrl('card/view', array('id'=>$row['mid'])).'">' . $row['member_name'].'</a></td>';
										echo '<td>'.$row['content'].'</td>';
										echo '<td>'.date('Y-m-d H:i:s', $row['create_time']).'</td>';
										if($row['resolved'] == Feedback::RESOLVED)
											echo '<td>已解决</td>';
										else
											echo '<td><a href="'.$this->createUrl('feedback/resolve',array('id'=>$row['id'])).'" onclick="return false" class="resolved">解决</a></td>';
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

<script>

$(document).ready(function(){
	$(".resolved").bind("click",function(){
		var that = this;
		if(window.confirm('确定解决')){
			var url = this.href;
			$.getJSON(url,function(data){
				console.log(data);
				if(data.code == 1){
					$(that).parent().html('已解决');
				}
			});
		}
	});
})

</script>