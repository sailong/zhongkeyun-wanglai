<form action="<?php echo Yii::app()->request->getRequestUri(); ?>" id="form1" class="formstyle" method="post" target="_top">
	<input type="hidden" name="sign" value="<?php echo Util::getSign(); ?>">
	<div class="formitems">
		<label for="title">名称：</label>
		<input type="text" name="contacts[title]" id="title" value="<?php echo $model->title; ?>" class="inp" placeholder="请输入标题(必填)" required="required" />
	</div>
	<div class="formitems">
		<label for="introduction">通讯录介绍：</label>
		<textarea cols="40" rows="6" name="contacts[description]" id="introduction" class="inp" placeholder="请输入说明(选填)" required="required"><?php echo $model->description; ?></textarea>
	</div>
	<div class="formitems">
		<label for="provice">通讯录类型：</label>
		<div class="s-city">
			<input type="hidden" name="current_province" value="">
			<div class="selectit" id="showType">
				好友通讯录
			</div>
			<input type="hidden" name="originalType" value="<?php echo $model->type; ?>">
			<select name="contacts[type]" id="contactsType" required="required" class="sel">
				<?php 
					$types = Contacts::model()->types();
					foreach ($types as $key => $type):
				?>
					<option value="<?php echo $key; ?>"><?php echo $type; ?></option>
				<?php endforeach;?>
		　         </select>
		</div>
	</div>
	<div class="formitems">
		<label for="provice">隐私保护：</label>
		<div class="s-city">
			<input type="hidden" name="current_province" value="">
			<div class="selectit" id="showPrivacy">
				公开，联系方式群友可见
			</div>
			<input type="hidden" value="<?php echo $model->privacy; ?>" name="originalPrivacy">
			<select name="contacts[privacy]" id="contactsPrivacy" required="required" class="sel">
				<option value="1">公开，联系方式群友可见</option>
				<option value="2">私密，联系方式相互关注后可见</option>
		　         </select>
		</div>
	</div>
	<div class="formitems">
		<input type="submit" value="<?php echo !empty($model->id) ? '保存' : '发起通讯录' ;?>" class="submitbtn"/>
	</div>
</form>

<script>
$(function(){

	var single = function(){
		var param = {
			originalType: 	$(":hidden[name=originalType]").val(),
			originalPrivacy:$(":hidden[name=originalPrivacy]").val()
		};
		
		var init = function(){
			if(param.originalType >0 ){
				$("#contactsType option[value="+param.originalType+"]").attr("selected", "selected");
				$("#showType").text($("#contactsType :selected").text());
			}
			if(param.originalPrivacy >0 ){
				$("#contactsPrivacy option[value="+param.originalPrivacy+"]").attr("selected", "selected");
				$("#showPrivacy").text($("#contactsPrivacy :selected").text());
			}
		};

		var bind = function(){
			$("#contactsType").bind("change",function(){
				var type = $(this).val();
				$("#showType").text(this.options[$(this).get(0).selectedIndex].text);
			});

			$("#contactsPrivacy").bind("change",function(){
				var privacy = $(this).val();
				$("#showPrivacy").text(this.options[$(this).get(0).selectedIndex].text);
			});

			$("form").submit(function(){
				$(":submit").removeClass('submitbtn').addClass('btn_b6').attr("disabled","disabled");
				return true;				
			});

		};

		return function(){
			init();
			init = null;
			bind();
		};
	}();

	single();
})

</script>