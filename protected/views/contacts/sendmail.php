<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Document</title>
</head>
<body>
	<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr style="color:#fff;line-height: 2em;background-color: #007FBA">
			<th>姓名</th>
			<th>手机号</th>
			<th>公司名</th>
			<th>职位</th>
			<th>公司地址</th>
			<th>邮箱</th>
		</tr>
		
	<?php if(!empty($data))
			$i = 0;
			foreach ($data as $value)
			{	
				$background = $i%2 == 0 ? '#eee' : '#f9f9f9';
	?>
		<tr style="color:#333;height:2.2em;background-color: <?php echo $background; ?>;text-align: center;font-size: 0.9em">
			<td><?php echo $value['name'];?></td>
			<td><?php echo $value['mobile']; ?></td>
			<td><?php echo $value['company']; ?></td>
			<td><?php echo $value['position']; ?></td>
			<td><?php echo $value['address']; ?></td>
			<td><?php echo $value['email']; ?></td>
		</tr>
	<?php }?>
	
	</table>
</body>
</html>