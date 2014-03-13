<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
			<div class="conArea">
				<div class="searchBox">
				   <?php $form=$this->beginWidget('CActiveForm', array('method'=>'get','action'=>$this->createUrl('index'),'htmlOptions'=>array('class'=>'xhform initform'),'enableAjaxValidation'=>true,'enableClientValidation'=>true,)); ?>
					<?php echo $form->textField($model, 'keyword', array('id'=>'sword', 'class'=>'inp1 nofocus','size'=>40,'placeholder'=>'请输入姓名或手机号码或往来号')); ?>
				    <?php echo CHtml::submitButton('搜 索', array('class'=>'btn1')) ?>
				    <span class="txs">一共查询到 <?php echo $count*3;?> 条数据&nbsp;&nbsp;
				    总转发数：<?php echo $stat['share_counts']*3;?>&nbsp;&nbsp;总浏览数<?php echo $stat['views']*3 ;?>
				    &nbsp;&nbsp;&nbsp;
				    <a href="/admin/qiye">+创建企业名片+</a>
				    </span>
				    <?php $this->endWidget();?>
		
				</div>
				
				<div class="content-box">
					<div class="content-box-header">
						<h3><?php echo $this->nav;?>&nbsp;&nbsp;&nbsp;&nbsp;
						<!-- <select><option value="0">请选择名称</option><option value="1">热度(UV)</option><option value="2">浏览量(PV)</option></select>
						<select><option value="DESC">由多到少</option><option value="ASC">由少到多</option></select> -->
						
						<a href="<?php echo $this->createUrl('update');?>"></a>
					</div>
					<div class="content-box-content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
							<thead>
								<tr>
								   <th width="5%">ID</th>
								   <th width="10%">姓名</th>
								   <th width="10%">手机</th>
								   <th width="10%">邮箱</th>
								   <th width="5%">往来号</th>
								   <th width="7%">名片链接</th>
								   <th width="10%">
								   	<a href="<?php echo $this->createUrl('index',array('order'=>'uv-desc'));?>" <?php if($order=='uv-desc') echo 'style="color: green"';?>>↓</a>热度
								   	<a href="<?php echo $this->createUrl('index',array('order'=>'uv-asc'));?>" <?php if($order=='uv-asc') echo 'style="color: green"';?>>↑</a></th>
								   <th width="10%">
								   	<a href="<?php echo $this->createUrl('index',array('order'=>'pv-desc'));?>" <?php if($order=='pv-desc') echo 'style="color: green"';?>>↓</a>浏览量
								    <a href="<?php echo $this->createUrl('index',array('order'=>'pv-asc'));?>" <?php if($order=='pv-asc') echo 'style="color: green"';?>>↑</a>
								   	</th>
								   <th width="10%">创建时间</th>
								   <th width="12%">操作</th> 
								</tr>
							</thead>
							<tbody>
							
							<?php
							if($data)
							{
								foreach($data as $val)
								{
									$pv = isset($pvArr[$val->id]) ? $pvArr[$val->id]['pv_counts'] : 0;
							?>
									<tr id="tr_<?php echo $val->id;?>">
										<td><?php echo $val->id;?></td>
										<td><?php echo $val->name;?></td>
										<td><?php echo $val->mobile;?></td>
										<td><?php echo $val->email;?></td>
										<td><?php echo $val->wanglai_number;?></td>
										<td><a href="<?php echo $this->createUrl('/member/view',array('id' => $val->id)); ?>" target="_blank">名片</a></td>
										<td><?php echo $val->views*3;?></td>
										<td><?php echo $pv*3;?></td>
										<td><?php echo date('Y-m-d H:i',$val->created_at);?></td>
										<td>
										   <a href="<?php echo $this->createUrl('view',array('id'=>$val->id));?>">详情</a>
										   <a href="<?php echo $this->createUrl('update',array('id'=>$val->id));?>">修改</a>
										   <a href="javascript:;" class='delete_data' url="<?php echo $this->createUrl('delete',array('id'=>$val->id));?>" data_id="<?php echo $val->id;?>">删除</a>
										</td>
									</tr>
							<?php 
								}
							}
							?>	
							</tbody>
							<tfoot>
								<tr>
									<td colspan="11" class="pages">
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