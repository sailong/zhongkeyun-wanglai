<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
<div class="conArea">
    <div class="searchBox">
       
	    <?php $form=$this->beginWidget('CActiveForm', array('method'=>'get','action'=>$this->createUrl('index'),'htmlOptions'=>array('class'=>'xhform initform'))); ?>
		<?php echo $form->textField($searchModel, 'keyword', array('id'=>'sword', 'class'=>'inp1 nofocus','size'=>40,'placeholder'=>'请输入文章标题关键字')); ?>
	    <?php echo CHtml::submitButton('搜 索', array('class'=>'btn1')) ?>
	    <span class="txs">一共查询到 <?php echo $dataProvider->getTotalItemCount();?> 条数据&nbsp;&nbsp;
	       <a href="/admin/article/add">+创建文章+</a>
	    </span>
	    <?php $this->endWidget();?>
	    
	</div>
	<div style="clear:both;"></div>
	<div class="content-box">
    	<div class="content-box-content">
    		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
    			<thead>
    				<tr>
    				   <th width="5%">ID</th>
    				   <th width="8%">标题</th>
    				   <th width="8%">发布人</th>
    				   <!-- <th>是否发布</th> -->
    				   <th width="20%">摘要</th>
    				   <th width="8%">浏览数</th>
    				   <th width="8%">分享数</th>
    				   <th width="8%">点赞数</th>
    				   <th width="8%">评论数</th>
    				   <th>链接</th>
    				   <th>发布时间</th>
    				   <!-- <th>创建时间</th> -->
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
    					    echo '<td><a href="'.$this->createUrl('card/view', array('id'=>$row['create_mid'])).'" target="_blank">'.$row->creater->name.'</a></td>';
    					    echo '<td>' . $row['summary'].'</td>';
    					    echo '<td>' . $row['views'].'</td>';
    					    echo '<td>' . $row['share_counts'].'</td>';
    					    echo '<td>' . $comment_count[$row['id']].'</td>';
    					    echo '<td>' . $mark_count[$row['id']].'</td>';
    					    echo '<td><a href="'.$this->createAbsoluteUrl('/article/view',array('id'=>Util::encode($row['id'])))
    					    .'" target="_blank">连接</a></td>';
    					    //echo '<td>' . $publishArr[$row['publish']].'</td>';
    					    echo '<td>'.date('Y-m-d H:i:s', $row['publish_time']).'</td>';
    					    //echo '<td>'.date('Y-m-d H:i:s', $row['create_time']).'</td>';
    					    echo '<td>
	                                   <a href="'.$this->createUrl('update', array('id'=>$row['id'])).'">编辑</a>
	                                   <a href="'.$this->createUrl('delete', array('id'=>$row['id'])).'">删除</a>
	                              </td>';
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
    	<div>
    	  <?php  $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'mydialog',
                // additional javascript options for the dialog plugin
                'options'=>array(
                    'title'=>'Dialog box 1',
                    'autoOpen'=>false,
                ),
            ));
            
                echo 'dialog content here';
            
            $this->endWidget('zii.widgets.jui.CJuiDialog');
            
            // the link that may open the dialog
            echo CHtml::link('open dialog', '#', array(
                    'onclick'=>'$("#mydialog").dialog("open"); return false;',
            ));
        ?>
    	</div>
    </div>
</div>
	