<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
			<div class="conArea">
				<div class="searchBox">
					
				   
				   <?php $form=$this->beginWidget('CActiveForm', array('method'=>'get','action'=>$this->createUrl('index'),'htmlOptions'=>array('class'=>'xhform initform'),'enableAjaxValidation'=>true,'enableClientValidation'=>true,)); ?>
				
					<?php echo $form->textField($model, 'keyword', array('id'=>'sword', 'class'=>'inp1 nofocus','size'=>40)); ?>
				    <?php echo CHtml::submitButton('搜 索', array('class'=>'btn1')) ?>
				    <span class="txs">一共查询到 <?php echo $count;?> 条数据</span>
				    <?php $this->endWidget(); ?>
		
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
								   <th width="10%">名称</th>
								   <th width="20%">LOGO</th>
								   <th width="20%">所属分类</th>
								   <th width="10%">操作</th> 
								</tr>
							</thead>
							<tbody>
							
							<?php
							if($data)
							{
								foreach($data as $val)
								{
									$image = Helper::getImage($val->image_path);
									$image = $image ? '<img src="'.$image.'" width="153" height="74"/>' : '';
									
									$cate_name = isset($cate_name_arr[$val->cate_id]) ? $cate_name_arr[$val->cate_id] : '';
							?>
									<tr id="tr_<?php echo $val->id;?>">
										<td><?php echo $val->id;?></td>
										<td><?php echo $val->name;?></td>
										<td><?php echo $image;?></td>
										<td><?php echo $cate_name;?></td>
										<td>
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
									<td colspan="5" class="pages">
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