<header id="header">
	<h1 style="font-size: 1em">大互联网时代下企业升级转型问卷调查</h1>
</header>
<div id="content">
	<span class="red" id="researchOver">报名前请先填写调查问卷</span>
<?php 
			$script = <<<EOF
			setTimeout(function(){
				$("#researchOver").remove();
			},5000);
EOF;
			Yii::app()->clientScript->registerScript('research',$script,CClientScript::POS_END);
?>
	
	<form id="form1" class="formstyle" method="post">
		<h3>一、	企业基本简况</h3>
		<div class="formitems">
			<label for="title">1、您所在企业名称：</label>
			<input type="text" class="inp" name="Research[company]"/>
		</div>
		<div class="formitems">
			<label for="title">2、您的姓名职位：</label>
			<input type="text" class="inp" name="Research[position]"/>
		</div>
		<div class="formitems">
			<label for="title">3、企业所在行业类型：[多选题] <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn" data-val="1">IT信息服务</div>
				<div class="lab1 checkbtn" data-val="2">节能环保</div>
				<div class="lab1 checkbtn" data-val="3">健康医疗</div>
				<div class="lab1 checkbtn" data-val="4">新能源</div>
				<div class="lab1 checkbtn" data-val="5">新材料</div>
				<div class="lab1 checkbtn" data-val="6">制造业</div>
				<div class="lab1 checkbtn" data-val="7">餐饮服务</div>
				<div class="lab1 checkbtn" data-val="8">金融服务</div>
				<div class="lab1 checkbtn" data-val="9">物流服务</div>
				<div class="lab1 checkbtn" data-val="10">其他</div>
			</div>
			<input type="hidden" name="Research[type]"/>
			<input type="text" class="inp" name="Research[type_else]"/>
		</div>
		<div class="formitems">
			<label for="title">4、您所在企业的主要产品/服务包括：</label>
			<input type="text" class="inp" name="Research[products]"/>
		</div>
		<div class="formitems">
			<label for="title">5、您所在企业现有员工人数：</label>
			<input type="text" class="inp" name="Research[employee]"/>
		</div>
		<div class="formitems">
			<label for="title">6、你所在企业所处的发展阶段：[单选] <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn radios" data-val="1">创业期</div>
				<div class="lab1 checkbtn radios" data-val="2">迅速扩张期</div>
				<div class="lab1 checkbtn radios" data-val="3">稳定发展期</div>
				<div class="lab1 checkbtn radios" data-val="4">多元发展期</div>
				<div class="lab1 checkbtn radios" data-val="5">业务停滞期</div>
				<div class="lab1 checkbtn radios" data-val="6">出现下滑趋势期</div>
				<div class="lab1 checkbtn radios" data-val="7">其他：</div>
			</div>
			<input type="hidden" name="Research[stage]"/>
			<input type="text" class="inp" name="Research[stage_else]"/>
		</div>
		<div class="formitems">
			<p>
			<label for="title">7、您所在企业2013年销售收入为：(单位：人民币)：</label>
			<input type="text" class="inp" name="Research[income]"/>
			</p>
			<p>
			<label for="title">毛利率为：</label>
			<input type="text" class="inp" name="Research[profile_ratio]"/>
			</p>
			<p>
			<label for="title">销售收入平均增长率：</label>
			<input type="text" class="inp" name="Research[growth_ratio]"/>
			</p>
		</div>
		<div class="formitems">
			<label for="title">8、您所在企业所属行业市场容量为：</label>
			<input type="text" class="inp" name="Research[capacity]"/>
		</div>
		<div class="formitems">
			<label for="title">9、您所在企业经营总成本构成比例：[多选题] <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn" data-val="1">产品成本</div>
				<div class="lab1 checkbtn" data-val="2">服务成本</div>
				<div class="lab1 checkbtn" data-val="3">运营成本</div>
				<div class="lab1 checkbtn" data-val="4">物料成本</div>
				<div class="lab1 checkbtn" data-val="5">人工成本</div>
				<div class="lab1 checkbtn" data-val="6">机器设备成本</div>
			</div>
			<input type="hidden" name="Research[cost]"/>
			<p>
			<label for="title">各自成本占比分别是？：</label>
			<input type="text" class="inp" name="Research[cost_ratio]" />
			</p>
		</div>
		<div class="formitems">
			<label for="title">10、您所在企业目前采用的IT信息系统有哪些：[多选题] <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn" data-val="1">没有任何系统</div>
				<div class="lab1 checkbtn" data-val="2">财务管理系统</div>
				<div class="lab1 checkbtn" data-val="3">办公自动系统(OA)</div>
				<div class="lab1 checkbtn" data-val="4">管理信息系统</div>
				<div class="lab1 checkbtn" data-val="5">计算机辅助设计(CAD)计算机辅助制造(CAM)物料需求计划(MRP)</div>
				<div class="lab1 checkbtn" data-val="6">制造资源计划(MRPII)企业资源计划(ERP)</div>
				<div class="lab1 checkbtn" data-val="7">决策支持系统(DSS)</div>
				<div class="lab1 checkbtn" data-val="8">客户关系管理系统(CRM)计算机集成制造系统(CIMS)</div>
				<div class="lab1 checkbtn" data-val="9">供应链管理(SCM)经理信息系统</div>
				<div class="lab1 checkbtn" data-val="10">人力资源信息系统(HR)</div>
				<div class="lab1 checkbtn" data-val="11">采购系统</div>
				<div class="lab1 checkbtn" data-val="12">订单管理系统</div>
				<div class="lab1 checkbtn" data-val="13">营销信息系统</div>
				<div class="lab1 checkbtn" data-val="14">库存管理系统</div>
				<div class="lab1 checkbtn" data-val="15">业务管理系统</div>
				<div class="lab1 checkbtn" data-val="16">经营分析系统</div>
				<div class="lab1 checkbtn" data-val="17">电子商务系统</div>
				<div class="lab1 checkbtn" data-val="18">其他：</div>
			</div>
			<input type="hidden" name="Research[information]"/>
			<input type="text" class="inp" name="Research[information_else]"/>
		</div>
		<div class="formitems">
			<label for="title">11、您所在企业是否建了网站(单选) <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn radios" data-val="1">是</div>
				<div class="lab1 checkbtn radios" data-val="2">否，计划建</div>
				<div class="lab1 checkbtn radios" data-val="3">否，暂时没有计划</div>
			</div>
			<input type="hidden" name="Research[web]"/>
		</div>
		<div class="formitems">
			<label for="title">12、您所在企业网站主要功能是：(单选) <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn radios" data-val="1">宣传</div>
				<div class="lab1 checkbtn radios" data-val="2">产品介绍</div>
				<div class="lab1 checkbtn radios" data-val="3">开展电子商务交易</div>
				<div class="lab1 checkbtn radios" data-val="4">其他</div>
			</div>
			<input type="hidden" name="Research[function]"/>
			<input type="text" class="inp" name="Research[function_else]"/>
		</div>
		<div class="formitems">
			<label for="title">13、您所在企业市场销售渠道有哪些？[多选] <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn" data-val="1">自营店面零售</div>
				<div class="lab1 checkbtn" data-val="2">商超零售</div>
				<div class="lab1 checkbtn" data-val="3">线下批发代理</div>
				<div class="lab1 checkbtn" data-val="4">电商零售</div>
				<div class="lab1 checkbtn" data-val="5">电商批发</div>
			</div>
			<input type="hidden" name="Research[sale_channel]"/>
			<p>
			<label for="title">市场销售渠道分别占比是多少？</label>
			<input type="text" class="inp" name="Research[sale_channel_ratio]"/>
			</p>
		</div>
		<div class="formitems">
			<label for="title">14、您所在企业市场推广渠道有哪些？[多选题] <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn" data-val="1">自建网站</div>
				<div class="lab1 checkbtn" data-val="2">微博</div>
				<div class="lab1 checkbtn" data-val="3">微信</div>
				<div class="lab1 checkbtn" data-val="4">新浪搜狐等线上媒体渠道</div>
				<div class="lab1 checkbtn" data-val="5">淘宝京东等电商渠道</div>
				<div class="lab1 checkbtn" data-val="6">百度等搜索引擎渠道</div>
			</div>
			<input type="hidden" name="Research[promotion_channel]"/>
			<p>
			<label for="title">市场推广渠道分别占比是多少？</label>
			<input type="text" class="inp" name="Research[promotion_channel_ratio]"/>
			</p>
		</div>
		<h3>二、企业现状问题</h3>
		<div class="formitems">
			<label for="title">1、您是否了解互联网思维？如果了解，是什么？</label>
			<input type="text" class="inp" name="Research[internet]"/>
		</div>
		<div class="formitems">
			<label for="title">2、大互联网时代对你企业产生了哪些影响冲击：[多选题] <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn" data-val="1">生产流程</div>
				<div class="lab1 checkbtn" data-val="2">工作流程</div>
				<div class="lab1 checkbtn" data-val="3">管理模式</div>
				<div class="lab1 checkbtn" data-val="4">组织结构</div>
				<div class="lab1 checkbtn" data-val="5">市场营销</div>
				<div class="lab1 checkbtn" data-val="6">其他：</div>
			</div>
			<input type="hidden" name="Research[impact]"/>
			<input type="text" class="inp" name="Research[impact_else]"/>
		</div>
		<div class="formitems">
			<label for="title">3、你是否愿意参与电商化驱动的企业转型升级？</label>
			<input type="text" class="inp" name="Research[change]"/>
		</div>
		<div class="formitems">
			<label for="title">4、您认为您所在企业的核心竞争优势是：(单选) <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn radios" data-val="1">产品品质</div>
				<div class="lab1 checkbtn radios" data-val="2">服务品质</div>
				<div class="lab1 checkbtn radios" data-val="3">劳动效率</div>
				<div class="lab1 checkbtn radios" data-val="4">技术</div>
				<div class="lab1 checkbtn radios" data-val="5">品牌</div>
				<div class="lab1 checkbtn radios" data-val="6">商业模式</div>
				<div class="lab1 checkbtn radios" data-val="7">组织结构</div>
				<div class="lab1 checkbtn radios" data-val="8">管理模式</div>
				<div class="lab1 checkbtn radios" data-val="9">市场营销</div>
				<div class="lab1 checkbtn radios" data-val="10">成本优势(廉价提供产品服务)</div>
				<div class="lab1 checkbtn radios" data-val="11">增值优势(更吸引人的增值产品服务)</div>
				<div class="lab1 checkbtn radios" data-val="12">聚焦优势(准确锁定顾客群需求)</div>
				<div class="lab1 checkbtn radios" data-val="13">速度优势(快速满足顾客需求)</div>
				<div class="lab1 checkbtn radios" data-val="14">机动优势(适应竞争变化)</div>
			</div>
			<input type="hidden" name="Research[advantage]"/>
		</div>
		<div class="formitems">
			<label for="title">5、您认为你所在企业目前存在哪些问题？[多选] <span class="red">*</span></label>
			<div class="citems">
				<div class="lab1 checkbtn" data-val="1">利润增长</div>
				<div class="lab1 checkbtn" data-val="2">市场份额</div>
				<div class="lab1 checkbtn" data-val="3">战略管理</div>
				<div class="lab1 checkbtn" data-val="4">品牌经营</div>
				<div class="lab1 checkbtn" data-val="5">网络营销</div>
				<div class="lab1 checkbtn" data-val="6">组织结构</div>
				<div class="lab1 checkbtn" data-val="7">管理模式</div>
				<div class="lab1 checkbtn" data-val="8">管理团队</div>
				<div class="lab1 checkbtn" data-val="9">其他：</div>
			</div>
			<input type="hidden" name="Research[disadvantage]"/>
			<input type="text" class="inp" name="Research[disadvantage_else]"/>
		</div>
		<div class="formitems">
			<label for="title">6、围绕本次会议主题，你想了解哪些问题？</label>
			<input type="text" class="inp" name="Research[question]"/>
		</div>
		<div class="formitems">
			<input type="submit" value="提交答卷" class="btn_b2" />
		</div>
	</form>
</div>


<script>
	$(function(){
		$(".checkbtn").bind("click",function(){
			$(this).toggleClass('lab');
			var v = $(this).attr("data-val");
			var $t = $(this).parent().siblings(":hidden");
			if($(this).hasClass("lab")){
				$t.val($t.val()+v+',');
			}else{
				$t.val($t.val().replace(v+',',''));
			}
		});
		
		$(".radios").on('click',function(){
			$(this).addClass("lab").siblings().removeClass("lab");
			$(this).parent().next().val($(this).attr("data-val"));
		});

		$("form").bind("submit",function(){
			if($("input[name='Research[type]']").val() == '')
			{
				alert('请选择行业类型');
				return false;
			}

			if($("input[name='Research[stage]']").val() == '')
			{
				alert('请选择企业发展阶段');
				return false;
			}

			var count = $("input[name='Research[employee]']").val();
			if(count != '')
			{
				if(!$.isNumeric(count))
				{
					alert('员工数需要为整数');
					return false;
				}
			}
			if($("input[name='Research[cost]']").val() == '')
			{
				alert('请选择“企业经营总成本构成比例”');
				return false;
			}

			if($("input[name='Research[information]']").val() == '')
			{
				alert('请选择“您所在企业目前采用的IT信息系统”');
				return false;
			}

			if($("input[name='Research[web]']").val() == '')
			{
				alert('请选择“您所在企业是否建了网站”');
				return false;
			}

			if($("input[name='Research[function]']").val() == '')
			{
				alert('请选择“您所在企业网站主要功能”');
				return false;
			}

			if($("input[name='Research[sale_channel]']").val() == '')
			{
				alert('请选择“您所在企业市场销售渠道”');
				return false;
			}

			if($("input[name='Research[promotion_channel]']").val() == '')
			{
				alert('请选择“您所在企业市场推广渠道”');
				return false;
			}

			if($("input[name='Research[impact]']").val() == '')
			{
				alert('请选择“大互联网时代对你企业产生了哪些影响冲击”');
				return false;
			}

			if($("input[name='Research[advantage]']").val() == '')
			{
				alert('请选择“您认为您所在企业的核心竞争优势是”');
				return false;
			}

			if($("input[name='Research[disadvantage]']").val() == '')
			{
				alert('请选择“您认为你所在企业目前存在哪些问题”');
				return false;
			}
				return true;

		});
	})
</script>