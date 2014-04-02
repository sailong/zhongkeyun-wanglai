<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
			<div class="conArea">
			<div class="searchBox">
				   <?php $form=$this->beginWidget('CActiveForm', array('method'=>'get','action'=>$this->createUrl('index'),'htmlOptions'=>array('class'=>'xhform initform'))); ?>
					<?php echo $form->dropDownList($searchModel,'activity_create',array(0 => '所有活动',1=> '官方创建',2=>'用户创建')); ?>	
					<?php echo $form->textField($searchModel, 'keyword', array('id'=>'sword', 'class'=>'inp1 nofocus','size'=>40,'placeholder'=>'请输入活动标题关键字')); ?>
				    <?php echo CHtml::submitButton('搜 索', array('class'=>'btn1')) ?>
				    <span class="txs">一共查询到 <?php echo $dataProvider->getTotalItemCount();?> 条数据&nbsp;&nbsp;
				    <a href="<?php echo $this->createUrl('update');?>">+ 创建活动 + </a>
				    </span>
				    <?php $this->endWidget();?>
				</div>
				<div class="content-box">
					<div class="content-box-content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<thead>
								<tr>
								   <th width="5%">ID</th>
								   <th width="15%">活动标题</th>
								   <th width="8%">发起人</th>
								   <th width="5%">链接</th>
								   <th width="14%">开始时间</th>
								   <th width="14%">结束时间</th>
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
										$memberName = isset($memberArr[$row['create_mid']]) ? $memberArr[$row['create_mid']]['name'] : '';
										$isEndStr=$row['end_time']<strtotime(date('Y-m-d')) ? '<font color="red">[结束]</font>' : '';
										if($row['create_mid']==Member::SERVICE_MEMBER_ID) $isEndStr.='<font color="red">[官方]</font>';
										echo '<tr id="tr_'.$row['id'].'">';
										echo '<td>' . $row['id'] . '</td>';
										echo '<td>' . $row['title'].$isEndStr.'</td>';
										echo '<td><a href="'.$this->createUrl('card/view', array('id'=>$row['create_mid'])).'" target="_blank">'.$memberName.'</a></td>';
										echo '<td><a href="'.$this->createUrl('/activity/detail', array('id'=>$row['id'])) . '" target="_blank">链接</a></td>';
										echo '<td>'.date('Y-m-d H:i', $row['begin_time']).'</td>';
										echo '<td>'.date('Y-m-d H:i', $row['end_time']).'</td>';
										echo '<td>'.date('Y-m-d H:i:s', $row['create_time']).'</td>';
										echo '<td>
												  <a href="'.$this->createUrl('update', array('id'=>$row['id'])).'">修改</a>
												  <a href="javascript:;" class="delete_data" data_id="'.$row['id'].'" url="'.$this->createUrl('delete',array("id"=>$row['id'])).'" notice="">删除</a>
								   				  <a href="'.$this->createUrl('viewJoiners', array('id'=>$row['id'])).'">查看报名</a>
												  <a href="'.$this->createUrl('apply', array('id'=>$row['id'])).'">代报名</a>
										
										
												<a href="'.$this->createUrl('extra/index',array('id'=>$row['id'],'type'=>Extra::TYPE_ACTIVITY_EMAIL)).'">管理邮箱</a></td>';
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