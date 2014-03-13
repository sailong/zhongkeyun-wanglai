<div class="brand">当前位置：首页 > <em><?php echo $this->nav;?></em></div>
<div class="conArea">
	<div class="content-box">
		<div class="content-box-header">
			<h3>今日数据统计</h3>&nbsp;&nbsp;&nbsp;&nbsp;
		</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
			<thead>
				<tr class="altrow">
				   <th width="5%">日期</th>
				   <th width="10%">当日新增用户数</th>
				   <th width="12%">当日UV总量</th>
				   <th width="13%">当日PV总量</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $current['date']; ?></td>
					<td><?php echo $current['new']; ?></td>
					<td><?php echo $current['uv']*3; ?></td>
					<td><?php echo $current['pv']*3; ?></td>
				</tr>
			
			<?php foreach ($data as $val) {?>
				<tr>
					<td><?php echo date('Y-m-d',intval($val['date'])); ?></td>
					<td><?php echo $val['new']; ?></td>
					<td><?php echo $val['uv']*3; ?></td>
					<td><?php echo $val['pv']*3; ?></td>
				</tr>
				
			<?php }?>
			</tbody>
			
			
			<tfoot>
			
			</tfoot>
		</table>
	</div>
</div>