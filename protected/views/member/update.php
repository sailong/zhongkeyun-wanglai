<?php 
	$css = '/static/css/mobiscroll.custom-2.6.2.min.css';
	$clientScript = Yii::app()->clientScript;
	$clientScript->registerCssFile($css);
	$javascript = '/static/js/mobiscroll.custom-2.6.2.min.js';
	$clientScript->registerScriptFile($javascript,CClientScript::POS_BEGIN);
?>
<?php echo $this->renderPartial('/common/header',array('title'=>'修改微名片'));?>

<div id="content">
	<form action="<?php echo $this->createUrl('member/update',array('id'=>$model->id))?>" id="form1" class="formstyle" method="post">
		<div class="formitems">
			<p class="htit">
				<span>基本信息</span>
			</p>
			<input type="text" name="member[name]" placeholder="姓名【必填】"  value="<?php echo $model->name;?>" class="inp"required="required" />
			<input type="text" name="member[mobile]" placeholder="手机【必填】" value="<?php echo $model->mobile;?>" class="inp" required="required" />
			<div class="lab1 checkbtn" data-val="1">隐藏名片手机号码</div>
			<input type="hidden" name="member[show_item][mobile]" id="mobile"/>
		</div>
		<div class="formitems">
			<p class="htit">
				<span>选填信息</span>
			</p>	
			<input type="email" name="member[email]" placeholder="邮箱(选填)" value="<?php echo $model->email;?>" class="inp" />
			<input type="text" id="birthday" name="member[birthday]" placeholder="生日(选填)" value="<?php echo !empty($model->birthday) ? date('Y-m-d',$model->birthday) : '' ;?>" class="inp" />
			<input type="text" name="member[position]" placeholder="职位(选填)" value="<?php echo $model->position;?>" class="inp" />
			<input type="text" name="member[company]" placeholder="公司(选填)" value="<?php echo $model->company;?>" class="inp" />
			<textarea cols="40" rows="2" name="member[address]" class="inp" placeholder="地址(选填)"><?php echo $model->address;?></textarea>
			<span id="showitems" class="btn_b4">展开填写更多详细信息</span>
		</div>
		<div class="formitems" style="display: none" id="itemshide">
			<p class="htit">
				<span>更多详细信息</span>
			</p>
			<input type="text" name="member[company_url]" placeholder="公司微站(选填)" value="<?php echo $model->company_url;?>" class="inp" />
			<textarea cols="40" rows="2" name="member[main_business]" class="inp" placeholder="业务介绍(选填)"><?php echo $model->main_business; ?></textarea>
			<textarea cols="40" rows="2" name="member[supply]" class="inp" placeholder="供给(选填)"><?php echo $model->supply; ?></textarea>
			<div class="lab1 checkbtn" data-val="1">隐藏名片供给信息</div>
			<input type="hidden" name="member[show_item][supply]" id="supply"/>
			<textarea cols="40" rows="2" name="member[demand]" class="inp" placeholder="需求(选填)"><?php echo $model->demand; ?></textarea>
			<div class="lab1 checkbtn" data-val="1">隐藏名片需求信息</div>
			<input type="hidden" name="member[show_item][demand]" id="demand"/>
			<input type="text" name="member[weixin]" placeholder="微信号(选填)" value="<?php echo $model->weixin; ?>" class="inp" />
			<input type="text" name="member[yixin]" placeholder="易信号(选填)" value="<?php echo $model->yixin; ?>" class="inp" />
			<input type="text" name="member[laiwang]" placeholder="来往号(选填)" value="<?php echo $model->laiwang; ?>" class="inp" />
			<input type="text" name="member[qq]" placeholder="QQ号(选填)" value="<?php echo $model->qq; ?>" class="inp" />
			<input type="text" name="member[social_position]" placeholder="社会职务(选填)" value="<?php echo $model->social_position; ?>" class="inp" />
			<textarea cols="40" rows="2" name="member[profile]" class="inp" placeholder="个人简介(选填)"><?php echo $model->profile; ?></textarea>
			<textarea cols="40" rows="2" name="member[hobby]" class="inp" placeholder="兴趣爱好(选填)"><?php echo $model->hobby; ?></textarea>
		</div>
		<div class="formitems">
			<input type="submit" value="保存设置" class="submitbtn" />
		</div>
	</form>
</div>
<script>

$(function(){
	
	var single = function(){
		var showItem = <?php echo $model->show_item; ?>;
		// 1 显示 0隐藏
		var init = function(){
			for(var item in showItem){
				var value = showItem[item];
				var obj = $("#"+item);
				obj.val(value);
				if(value == 0){
					obj.prev().addClass("lab");
				}
			}
		};		
		// 显示隐藏项赋值
		var bind = function(){
			$(":submit").bind("click", function(){
				$(".checkbtn").each(function(){
					if($(this).hasClass("lab")){
						$(this).next().val(0);
					}else{
						$(this).next().val(1);
					}
				});
			});
		};
		
		return function(){
			init();
			bind();
		};

	}();
	
	single();
	
	// 隐藏手机供给需求等信息
	$(".checkbtn").bind("click",function(){
		$(this).toggleClass('lab');
	});
	// 显示更多信息
	$("#showitems").bind("click",function(){
		$("#itemshide").show();
		$(this).hide();
	});
	
	var opt = {
        preset: 'date', //日期
        theme: 'android-ics light', //皮肤样式
        display: 'modal', //显示方式 
        mode: 'scroller', //日期选择模式
        dateFormat: 'yy-mm-dd', // 日期格式
        setText: '确定', //确认按钮名称
        cancelText: '取消',//取消按钮名籍我
        dateOrder: 'yymmdd', //面板中日期排列格式
        dayText: '日', monthText: '月', yearText: '年', //面板中年月日文字
        endYear:2020 //结束年份
    };
	$('#birthday').mobiscroll(opt);
	
	
})
</script>