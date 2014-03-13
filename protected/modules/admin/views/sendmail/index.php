<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
<div class="conArea">
    <div class="searchBox">
        <div style="float:left;">
	    <?php $form=$this->beginWidget('CActiveForm', array('method'=>'get','action'=>$this->createUrl('index'),'htmlOptions'=>array('class'=>'xhform initform'))); ?>
		<?php echo $form->textField($searchModel, 'keyword', array('id'=>'sword', 'class'=>'inp1 nofocus','size'=>40,'placeholder'=>'请输入邮件标题关键字')); ?>
	    <?php echo CHtml::submitButton('搜 索', array('class'=>'btn1')) ?>
	    <span class="txs">一共查询到 <?php echo $dataProvider->getTotalItemCount();?> 条数据&nbsp;&nbsp;
	    </span>
	    <?php $this->endWidget();?>
	    </div>
	    <div style="padding-left:30px;float:left;">
	    <?php $form=$this->beginWidget('CActiveForm', array('method'=>'get','action'=>$this->createUrl('Add'),'htmlOptions'=>array('class'=>'xhform initform'))); ?>
		<?php echo $form->dropDownList($searchModel,'send_type',array(1 => '所有会员',2=> '所有通讯录发起者',3=>'某个通讯录成员')); ?>	
	    <?php echo CHtml::submitButton('发送邮件', array('class'=>'btn1')) ?>
	    <?php $this->endWidget();?>
	    </div>
	</div>
	<div style="clear:both;"></div>
	<div class="content-box">
    	<div class="content-box-content">
    		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
    			<thead>
    				<tr>
    				   <th width="5%">ID</th>
    				   <th>标题</th>
    				   <th>发送类型</th>
    				   <th>发送时间</th>
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
    					    echo '<tr id="tr_'.$row['id'].'">';
    					    echo '<td>' . $row['id'] . '</td>';
    					    echo '<td>' . $row['title'].'</td>';
    					    echo '<td>' . $sendTypeArr[$row['send_type']].'</td>';
    					    echo '<td>'.date('Y-m-d H:i:s', $row['add_time']).'</td>';
    					    echo '<td><a href="'.$this->createUrl('MailDetail', array('id'=>$row['id'])).'">查看详情</a></td>';
     						echo '</tr>';
/*     						$memberName = isset($memberArr[$row['create_mid']]) ? $memberArr[$row['create_mid']]['name'] : '';
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
	                                   <a href="'.$this->createUrl('viewJoiners', array('id'=>$row['id'])).'">查看详情</a>
    						      </td>';
     						echo '</tr>'; */
//     						echo '<td>
//     								  <a href="'.$this->createUrl('update', array('id'=>$row['id'])).'">修改</a>
//     								  <a href="javascript:;" class="delete_data" data_id="'.$row['id'].'" url="'.$this->createUrl('delete',array("id"=>$row['id'])).'" notice="">删除</a>
//     				   				  <a href="'.$this->createUrl('viewJoiners', array('id'=>$row['id'])).'">查看报名</a>
    						
    						
//     								<a href="'.$this->createUrl('extra/index',array('id'=>$row['id'],'type'=>Extra::TYPE_ACTIVITY_EMAIL)).'">管理邮箱</a></td>';
//     						echo '</tr>';
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
	