/*下拉框美化插件 闭包*/
/*(function($){var defaultOptions={preloadImg:true};var jqTransformHideSelect=function(oTarget){var ulVisible=$('.selectWrapper ul:visible');ulVisible.each(function(){var oSelect=$(this).parents(".selectWrapper:first").find("select").get(0);});};var jqTransformCheckExternalClick=function(event){if($(event.target).parents('.selectWrapper').length===0){jqTransformHideSelect($(event.target));};};var jqTransformAddDocumentListener=function(){$(document).mousedown(jqTransformCheckExternalClick);};var jqTransformReset=function(f){var sel;$('.selectWrapper select',f).each(function(){sel=(this.selectedIndex<0)?0:this.selectedIndex;$('ul',$(this).parent()).each(function(){$('a:eq('+sel+')',this).click();});});$('a.jqTransformCheckbox, a.jqTransformRadio',f).removeClass('jqTransformChecked');$('input:checkbox, input:radio',f).each(function(){if(this.checked){$('a',$(this).parent()).addClass('jqTransformChecked');}});};$.fn.jqTransSelect=function(){return this.each(function(index){var $select=$(this);if($select.hasClass('jqTransformHidden')){return;};if($select.attr('multiple')){return;};var $wrapper=$select.addClass('jqTransformHidden').wrap('<div class="selectWrapper"></div>').parent().css({zIndex:10-index});$wrapper.prepend('<p class="selectHead"><span class="jqTransformSelectOpen"></span></p><div class="selectulwrap"><ul class="selectBody"></ul></div>');var $ul=$('ul',$wrapper).hide();$('option',this).each(function(i){var oLi=$('<li index="'+i+'">'+$(this).html()+'</li>');$ul.append(oLi);});$('ul',$wrapper).find("li").hover(function(){$(this).toggleClass("cur");})
$ul.find('li').click(function(){$('li.selected',$wrapper).removeClass('selected');$(this).addClass('selected');if($select[0].selectedIndex!=$(this).attr('index')&&$select[0].onchange){$select[0].selectedIndex=$(this).attr('index');$select[0].onchange();};$select[0].selectedIndex=$(this).attr('index');$('span:eq(0)',$wrapper).html($(this).html());$ul.hide();return false;});$('li:eq('+this.selectedIndex+')',$ul).click();$(".jqTransformSelectOpen",$wrapper).trigger('click');var oLinkOpen=$('.selectHead',$wrapper).click(function(){if($ul.css('display')=='none'){jqTransformHideSelect();};if($select.attr('disabled')){return false;};$ul.slideToggle('fast',function(){var offSet=($('li.selected',$ul).offset().top-$ul.offset().top);$ul.animate({scrollTop:offSet});});return false;});$wrapper.css('width',$('select',$wrapper).outerWidth()*1.2+10);$ul.css({display:'block',visibility:'hidden'});var iSelectHeight=($('li',$ul).length)*($('li:first',$ul).height());(iSelectHeight<$ul.height())&&$ul.css({height:iSelectHeight,'overflow':'hidden'});$ul.css({display:'none',visibility:'visible'});});};$.fn.jqTransform=function(options){var opt=$.extend({},defaultOptions,options);return this.each(function(){var selfForm=$(this);if(selfForm.hasClass('jqtransformdone')){return;};selfForm.addClass('jqtransformdone');if($('select',this).jqTransSelect().length>0){jqTransformAddDocumentListener();};selfForm.bind('reset',function(){var action=function(){jqTransformReset(this);};window.setTimeout(action,10);});});};})(jQuery);
*/
$(function(){
	

	/*登录界面用户输入框提示*/
	//var nVal = $("#uname").val();
	//$("#uname").click( function() {if ($(this).val() == this.defaultValue) { $(this).val(""); };$(this).removeClass("nofocus") }).blur(function () {if (!$(this).val()){$(this).val(this.defaultValue);$(this).addClass("nofocus")}})
	$("#loginform").submit(function(){
		if($("#uname").val()==""){
			alert("用户ID不能为空！");
			return false;
		};
		if($("#upsw").val()==""){
			alert("密码不能为空！");
			return false;
		};
	})
	
	/*搜索界面输入框提示*/
	///var sVal = $("#sword").val();
	///$(".xhform .inp1").click( function() {if ($(this).val() == this.defaultValue) { $(this).val(""); };$(this).removeClass("nofocus") }).blur(function () {if (!$(this).val()){$(this).val(this.defaultValue);$(this).addClass("nofocus")}})
//	$(".xhform").submit(function(){
//		if($("#sword").val()=="" || $("#sword").val()== sVal){
//			alert("搜索条件不能为空！");
//			return false;
//		};
//	})
	

	/*表格的间隔样式*/
	$(".table1").each(function(){
		$(this).find("tr:odd").addClass("altrow");
		$(this).find("tr:last,#last_row").removeClass("altrow");
		$(this).find("tr").hover(function(){
			$(this).addClass("cur");
		},function(){
			$(this).removeClass("cur");
		});
		$(this).find("tr:first,tr:last,#last_row").unbind("hover");
	})

	
	//时间
	function currentTime(){
	var d = new Date(),str = '';
	 str += d.getFullYear()+'年';
	 str  += d.getMonth() + 1+'月';
	 str  += d.getDate()+'日 ';
	 str += d.getHours()+'时'; 
	 str  += d.getMinutes()+'分'; 
	str+= d.getSeconds()+'秒'; 
	return str;
	}
	setInterval(function(){$('.time').html(currentTime)},1000);
	
     //删除数据
	$(".delete_data").click(function(){
		var id = $(this).attr("data_id");
		if(!id) return;
		var url = $(this).attr("url");
		var notice='';
		if($(this).attr("notice")) notice = $(this).attr("notice")+',';
		
		$.layer({
			title : ['删除数据' , true],
			shade : ['',false],
			border : [5 , 0.3 , '#000', true],
			area : ['auto' , 'auto'],
			dialog : {
				msg:'<span style=\'color:#f00\'>'+notice+'确定要进行此操作吗？</span>',
				btns : 2,
				btn : ['确定','取消'],
				yes : function(){
					  $.get(url,function(result){
							if(result.code==1)
							{
								$('#tr_'+id).remove();
							}
							//alert(result.msg);
							layer.msg(result.msg,3,1);
					  },"json");	
				}
			}
		});		
	})
	
})