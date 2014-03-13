<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>调查结果</title>
</head>
<body>

<h1>用户信息</h1>

	<table width="100%" cellpadding="0" cellspacing="1" border="0">
			<tr style="color:#fff;line-height: 2em;background-color: #007FBA">
				<th>姓名</th>
				<th>手机号</th>
				<th>公司名</th>
				<th>职位</th>
				<th>公司地址</th>
				<th>邮箱</th>
			</tr>
			<tr>
				<th><?php echo $member->name;?></th>
				<th><?php echo $member->mobile;?></th>
				<th><?php echo $member->company;?></th>
				<th><?php echo $member->position;?></th>
				<th><?php echo $member->company_url;?></th>
				<th><?php echo $member->email;?></th>
			</tr>
	</table>

<h1>调查问卷结果信息</h1>

	<p>企业名称：<?php echo $model->company;?></p>
	
	<p>姓名职位：<?php echo $model->position;?></p>
	
	<p>行业类型：<?php echo $model->type;?></p>
	
	<p>产品/服务：<?php echo $model->products;?></p>
	
	<p>员工人数：<?php echo $model->employee;?></p>
	
	<p>企业发展阶段：<?php echo $model->stage;?></p>
	
	<p>企业2013年销售收入：<?php echo $model->income;?></p>
	
	<p>毛利率：<?php echo $model->profile_ratio;?></p>
	
	<p>销售收入平均增长率：<?php echo $model->growth_ratio;?></p>
	
	<p>企业所属行业市场容量：：<?php echo $model->capacity;?></p>
	
	<p>企业经营总成本构成比例：<?php echo $model->cost;?></p>
	
	<p>各自成本占比：<?php echo $model->cost_ratio;?></p>
	
	<p>采用的IT信息系统：<?php echo $model->information;?></p>
	
	<p>是否建了网站：<?php echo $model->web;?></p>
	
	<p>企业网站主要功能：<?php echo $model->function;?></p>
	
	<p>企业市场销售渠道：<?php echo $model->sale_channel;?></p>
	
	<p>市场销售渠道分别占比：<?php echo $model->sale_channel_ratio;?></p>
	
	<p>企业市场推广渠道：<?php echo $model->promotion_channel;?></p>
	
	<p>市场推广渠道分别占比：<?php echo $model->promotion_channel_ratio;?></p>
	
	<p>是否了解互联网思维：<?php echo $model->internet;?></p>
	
	<p>大互联网时代对你企业产生了哪些影响冲击：<?php echo $model->impact;?></p>
	
	<p>是否愿意参与电商化驱动的企业转型升级：<?php echo $model->change;?></p>
	
	<p>企业的核心竞争优势：<?php echo $model->advantage;?></p>
	
	<p>企业目前存在哪些问题：<?php echo $model->disadvantage;?></p>
	
	<p>围绕本次会议主题，你想了解哪些问题：<?php echo $model->question;?></p>
	
</body>
</html>