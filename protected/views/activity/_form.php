<?php 
	Yii::app()->clientScript->registerCssFile('/static/css/mobiscroll.custom-2.6.2.min.css');
?>

<form action="<?php echo Yii::app()->getRequest()->getRequestUri(); ?>" id="form1" class="formstyle" method="post">
	<div class="formitems">
		<label for="title">活动标题：</label>
		<input type="text" name="title" id="title" value="<?php echo $model->title; ?>" class="inp" placeholder="请输入活动标题" required="required" />
	</div>
	<div class="formitems">
		<label for="introduction">活动介绍：</label>
		<textarea cols="40" rows="6" name="detail" id="introduction" class="inp" placeholder="请输入活动介绍" required="required"><?php echo $model->detail; ?></textarea>
	</div>
	<div class="formitems">
		<label for="provice">举办地点：</label>
		<div class="s-city">
			<input type="hidden" name="current_province" value="<?php echo $model->province; ?>">
			<div class="selectit" id="showProvince">
				请选择省份
			</div>
			<select name="province" id="province" required="required" class="sel">
				<option value="-1">请选择省份</option>
		　         </select>
		</div>
		<div class="s-city">
			<input type="hidden" name="current_area" value="<?php echo $model->area; ?>">
			<div class="selectit" id="showCity">
				请选择城市
			</div>
			<select name="area" id="city" required="required" class="sel">
				
		　        </select>
		</div>
	</div>
	<div class="formitems">
		<label for="starttime">活动开始时间：</label>
		<input type="text" name="begin_time" id="starttime" value="<?php echo !empty($model->begin_time) ? date('Y-m-d H:i', $model->begin_time) : date('Y-m-d H:i', mktime(9,0,0));?>" class="inp" placeholder="请输入活动开始时间" required="required" />
	</div>
	<div class="formitems">
		<label for="endtime">活动结束时间：</label>
		<input type="text" name="end_time" id="endtime" value="<?php echo !empty($model->end_time) ? date('Y-m-d H:i', $model->end_time) : date('Y-m-d H:i', mktime(20,0,0)); ?>" class="inp" placeholder="请输入活动结束时间" required="required" />
	</div>
	<div class="formitems">
		<label for="max">人数限制：</label>
		<input type="text" name="max" id="max" value="<?php echo $model->max > 0 ? $model->max : ''; ?>" class="inp" placeholder="不输入则不限制人数" />
	</div>
	<div class="formitems fix">
		<p>活动属性:</p>
		<div class="lab1  radio" data-val="1">公开</div>
		<div class="lab1 lab2 radio" data-val="2">私密</div>
		<input type="hidden" name="verify" id="verify" value="<?php echo $model->verify; ?>" />
	</div>
	<div class="formitems">
		<input type="submit" value="<?php echo $this->getAction()->getId() == 'create' ? '发布活动' : '保存活动'; ?>" class="submitbtn" />
	</div>
</form>

<script src="/static/weixin/mobiscroll.custom-2.6.2.min.js?v=1311204" type="text/javascript"></script>	
<script>
$(function(){
	//地区JSON
	
	var activity = function(){
		var strJSON={"province":{"1":"\u5317\u4eac\u5e02","2":"\u5929\u6d25\u5e02","3":"\u6cb3\u5317\u7701","4":"\u5c71\u897f\u7701","5":"\u5185\u8499\u53e4\u81ea\u6cbb\u533a","6":"\u8fbd\u5b81\u7701","7":"\u5409\u6797\u7701","8":"\u9ed1\u9f99\u6c5f\u7701","9":"\u4e0a\u6d77\u5e02","10":"\u6c5f\u82cf\u7701","11":"\u6d59\u6c5f\u7701","12":"\u5b89\u5fbd\u7701","13":"\u798f\u5efa\u7701","14":"\u6c5f\u897f\u7701","15":"\u5c71\u4e1c\u7701","16":"\u6cb3\u5357\u7701","17":"\u6e56\u5317\u7701","18":"\u6e56\u5357\u7701","19":"\u5e7f\u4e1c\u7701","20":"\u5e7f\u897f\u58ee\u65cf\u81ea\u6cbb\u533a","21":"\u6d77\u5357\u7701","22":"\u91cd\u5e86\u5e02","23":"\u56db\u5ddd\u7701","24":"\u8d35\u5dde\u7701","25":"\u4e91\u5357\u7701","26":"\u897f\u85cf\u81ea\u6cbb\u533a","27":"\u9655\u897f\u7701","28":"\u7518\u8083\u7701","29":"\u9752\u6d77\u7701","30":"\u5b81\u590f\u56de\u65cf\u81ea\u6cbb\u533a","31":"\u65b0\u7586\u7ef4\u543e\u5c14\u81ea\u6cbb\u533a","32":"\u53f0\u6e7e\u7701","33":"\u9999\u6e2f\u7279\u522b\u884c\u653f\u533a","34":"\u6fb3\u95e8\u7279\u522b\u884c\u653f\u533a","35":"\u6d77\u5916","36":"\u5176\u4ed6"},"city":{"1":{"37":"\u4e1c\u57ce\u533a","38":"\u897f\u57ce\u533a","39":"\u5d07\u6587\u533a","40":"\u5ba3\u6b66\u533a","41":"\u671d\u9633\u533a","42":"\u4e30\u53f0\u533a","43":"\u77f3\u666f\u5c71\u533a","44":"\u6d77\u6dc0\u533a","45":"\u95e8\u5934\u6c9f\u533a","46":"\u623f\u5c71\u533a","47":"\u901a\u5dde\u533a","48":"\u987a\u4e49\u533a","49":"\u660c\u5e73\u533a","50":"\u5927\u5174\u533a","51":"\u6000\u67d4\u533a","52":"\u5e73\u8c37\u533a","53":"\u5bc6\u4e91\u53bf","54":"\u5ef6\u5e86\u53bf"},"2":{"55":"\u548c\u5e73\u533a","56":"\u6cb3\u4e1c\u533a","57":"\u6cb3\u897f\u533a","58":"\u5357\u5f00\u533a","59":"\u6cb3\u5317\u533a","60":"\u7ea2\u6865\u533a","61":"\u5858\u6cbd\u533a","62":"\u6c49\u6cbd\u533a","63":"\u5927\u6e2f\u533a","64":"\u4e1c\u4e3d\u533a","65":"\u897f\u9752\u533a","66":"\u6d25\u5357\u533a","67":"\u5317\u8fb0\u533a","68":"\u6b66\u6e05\u533a","69":"\u5b9d\u577b\u533a","70":"\u5b81\u6cb3\u53bf","71":"\u9759\u6d77\u53bf","72":"\u84df\u53bf"},"3":{"73":"\u77f3\u5bb6\u5e84\u5e02","74":"\u5510\u5c71\u5e02","75":"\u79e6\u7687\u5c9b\u5e02","76":"\u90af\u90f8\u5e02","77":"\u90a2\u53f0\u5e02","78":"\u4fdd\u5b9a\u5e02","79":"\u5f20\u5bb6\u53e3\u5e02","80":"\u627f\u5fb7\u5e02","81":"\u8861\u6c34\u5e02","82":"\u5eca\u574a\u5e02","83":"\u6ca7\u5dde\u5e02"},"4":{"84":"\u592a\u539f\u5e02","85":"\u5927\u540c\u5e02","86":"\u9633\u6cc9\u5e02","87":"\u957f\u6cbb\u5e02","88":"\u664b\u57ce\u5e02","89":"\u6714\u5dde\u5e02","90":"\u664b\u4e2d\u5e02","91":"\u8fd0\u57ce\u5e02","92":"\u5ffb\u5dde\u5e02","93":"\u4e34\u6c7e\u5e02","94":"\u5415\u6881\u5e02"},"5":{"95":"\u547c\u548c\u6d69\u7279\u5e02","96":"\u5305\u5934\u5e02","97":"\u4e4c\u6d77\u5e02","98":"\u8d64\u5cf0\u5e02","99":"\u901a\u8fbd\u5e02","100":"\u9102\u5c14\u591a\u65af\u5e02","101":"\u547c\u4f26\u8d1d\u5c14\u5e02","102":"\u5df4\u5f66\u6dd6\u5c14\u5e02","103":"\u4e4c\u5170\u5bdf\u5e03\u5e02","104":"\u5174\u5b89\u76df","105":"\u9521\u6797\u90ed\u52d2\u76df","106":"\u963f\u62c9\u5584\u76df"},"6":{"107":"\u6c88\u9633\u5e02","108":"\u5927\u8fde\u5e02","109":"\u978d\u5c71\u5e02","110":"\u629a\u987a\u5e02","111":"\u672c\u6eaa\u5e02","112":"\u4e39\u4e1c\u5e02","113":"\u9526\u5dde\u5e02","114":"\u8425\u53e3\u5e02","115":"\u961c\u65b0\u5e02","116":"\u8fbd\u9633\u5e02","117":"\u76d8\u9526\u5e02","118":"\u94c1\u5cad\u5e02","119":"\u671d\u9633\u5e02","120":"\u846b\u82a6\u5c9b\u5e02"},"7":{"121":"\u957f\u6625\u5e02","122":"\u5409\u6797\u5e02","123":"\u56db\u5e73\u5e02","124":"\u8fbd\u6e90\u5e02","125":"\u901a\u5316\u5e02","126":"\u767d\u5c71\u5e02","127":"\u677e\u539f\u5e02","128":"\u767d\u57ce\u5e02","129":"\u5ef6\u8fb9\u671d\u9c9c\u65cf\u81ea\u6cbb\u5dde"},"8":{"130":"\u54c8\u5c14\u6ee8\u5e02","131":"\u9f50\u9f50\u54c8\u5c14\u5e02","132":"\u9e21\u897f\u5e02","133":"\u9e64\u5c97\u5e02","134":"\u53cc\u9e2d\u5c71\u5e02","135":"\u5927\u5e86\u5e02","136":"\u4f0a\u6625\u5e02","137":"\u4f73\u6728\u65af\u5e02","138":"\u4e03\u53f0\u6cb3\u5e02","139":"\u7261\u4e39\u6c5f\u5e02","140":"\u9ed1\u6cb3\u5e02","141":"\u7ee5\u5316\u5e02","142":"\u5927\u5174\u5b89\u5cad\u5730\u533a"},"9":{"143":"\u9ec4\u6d66\u533a","144":"\u5362\u6e7e\u533a","145":"\u5f90\u6c47\u533a","146":"\u957f\u5b81\u533a","147":"\u9759\u5b89\u533a","148":"\u666e\u9640\u533a","149":"\u95f8\u5317\u533a","150":"\u8679\u53e3\u533a","151":"\u6768\u6d66\u533a","152":"\u95f5\u884c\u533a","153":"\u5b9d\u5c71\u533a","154":"\u5609\u5b9a\u533a","155":"\u6d66\u4e1c\u65b0\u533a","156":"\u91d1\u5c71\u533a","157":"\u677e\u6c5f\u533a","158":"\u9752\u6d66\u533a","159":"\u5357\u6c47\u533a","160":"\u5949\u8d24\u533a","161":"\u5d07\u660e\u53bf"},"10":{"162":"\u5357\u4eac\u5e02","163":"\u65e0\u9521\u5e02","164":"\u5f90\u5dde\u5e02","165":"\u5e38\u5dde\u5e02","166":"\u82cf\u5dde\u5e02","167":"\u5357\u901a\u5e02","168":"\u8fde\u4e91\u6e2f\u5e02","169":"\u6dee\u5b89\u5e02","170":"\u76d0\u57ce\u5e02","171":"\u626c\u5dde\u5e02","172":"\u9547\u6c5f\u5e02","173":"\u6cf0\u5dde\u5e02","174":"\u5bbf\u8fc1\u5e02"},"11":{"175":"\u676d\u5dde\u5e02","176":"\u5b81\u6ce2\u5e02","177":"\u6e29\u5dde\u5e02","178":"\u5609\u5174\u5e02","179":"\u6e56\u5dde\u5e02","180":"\u7ecd\u5174\u5e02","181":"\u821f\u5c71\u5e02","182":"\u8862\u5dde\u5e02","183":"\u91d1\u534e\u5e02","184":"\u53f0\u5dde\u5e02","185":"\u4e3d\u6c34\u5e02"},"12":{"186":"\u5408\u80a5\u5e02","187":"\u829c\u6e56\u5e02","188":"\u868c\u57e0\u5e02","189":"\u6dee\u5357\u5e02","190":"\u9a6c\u978d\u5c71\u5e02","191":"\u6dee\u5317\u5e02","192":"\u94dc\u9675\u5e02","193":"\u5b89\u5e86\u5e02","194":"\u9ec4\u5c71\u5e02","195":"\u6ec1\u5dde\u5e02","196":"\u961c\u9633\u5e02","197":"\u5bbf\u5dde\u5e02","198":"\u5de2\u6e56\u5e02","199":"\u516d\u5b89\u5e02","200":"\u4eb3\u5dde\u5e02","201":"\u6c60\u5dde\u5e02","202":"\u5ba3\u57ce\u5e02"},"13":{"203":"\u798f\u5dde\u5e02","204":"\u53a6\u95e8\u5e02","205":"\u8386\u7530\u5e02","206":"\u4e09\u660e\u5e02","207":"\u6cc9\u5dde\u5e02","208":"\u6f33\u5dde\u5e02","209":"\u5357\u5e73\u5e02","210":"\u9f99\u5ca9\u5e02","211":"\u5b81\u5fb7\u5e02"},"14":{"212":"\u5357\u660c\u5e02","213":"\u666f\u5fb7\u9547\u5e02","214":"\u840d\u4e61\u5e02","215":"\u4e5d\u6c5f\u5e02","216":"\u65b0\u4f59\u5e02","217":"\u9e70\u6f6d\u5e02","218":"\u8d63\u5dde\u5e02","219":"\u5409\u5b89\u5e02","220":"\u5b9c\u6625\u5e02","221":"\u629a\u5dde\u5e02","222":"\u4e0a\u9976\u5e02"},"15":{"223":"\u6d4e\u5357\u5e02","224":"\u9752\u5c9b\u5e02","225":"\u6dc4\u535a\u5e02","226":"\u67a3\u5e84\u5e02","227":"\u4e1c\u8425\u5e02","228":"\u70df\u53f0\u5e02","229":"\u6f4d\u574a\u5e02","230":"\u6d4e\u5b81\u5e02","231":"\u6cf0\u5b89\u5e02","232":"\u5a01\u6d77\u5e02","233":"\u65e5\u7167\u5e02","234":"\u83b1\u829c\u5e02","235":"\u4e34\u6c82\u5e02","236":"\u5fb7\u5dde\u5e02","237":"\u804a\u57ce\u5e02","238":"\u6ee8\u5dde\u5e02","239":"\u83cf\u6cfd\u5e02"},"16":{"240":"\u90d1\u5dde\u5e02","241":"\u5f00\u5c01\u5e02","242":"\u6d1b\u9633\u5e02","243":"\u5e73\u9876\u5c71\u5e02","244":"\u5b89\u9633\u5e02","245":"\u9e64\u58c1\u5e02","246":"\u65b0\u4e61\u5e02","247":"\u7126\u4f5c\u5e02","248":"\u6fee\u9633\u5e02","249":"\u8bb8\u660c\u5e02","250":"\u6f2f\u6cb3\u5e02","251":"\u4e09\u95e8\u5ce1\u5e02","252":"\u5357\u9633\u5e02","253":"\u5546\u4e18\u5e02","254":"\u4fe1\u9633\u5e02","255":"\u5468\u53e3\u5e02","256":"\u9a7b\u9a6c\u5e97\u5e02","257":"\u6d4e\u6e90\u5e02"},"17":{"258":"\u6b66\u6c49\u5e02","259":"\u9ec4\u77f3\u5e02","260":"\u5341\u5830\u5e02","261":"\u5b9c\u660c\u5e02","262":"\u8944\u6a0a\u5e02","263":"\u9102\u5dde\u5e02","264":"\u8346\u95e8\u5e02","265":"\u5b5d\u611f\u5e02","266":"\u8346\u5dde\u5e02","267":"\u9ec4\u5188\u5e02","268":"\u54b8\u5b81\u5e02","269":"\u968f\u5dde\u5e02","270":"\u6069\u65bd\u571f\u5bb6\u65cf\u82d7\u65cf\u81ea\u6cbb\u5dde","271":"\u4ed9\u6843\u5e02","272":"\u6f5c\u6c5f\u5e02","273":"\u5929\u95e8\u5e02","274":"\u795e\u519c\u67b6\u6797\u533a"},"18":{"275":"\u957f\u6c99\u5e02","276":"\u682a\u6d32\u5e02","277":"\u6e58\u6f6d\u5e02","278":"\u8861\u9633\u5e02","279":"\u90b5\u9633\u5e02","280":"\u5cb3\u9633\u5e02","281":"\u5e38\u5fb7\u5e02","282":"\u5f20\u5bb6\u754c\u5e02","283":"\u76ca\u9633\u5e02","284":"\u90f4\u5dde\u5e02","285":"\u6c38\u5dde\u5e02","286":"\u6000\u5316\u5e02","287":"\u5a04\u5e95\u5e02","288":"\u6e58\u897f\u571f\u5bb6\u65cf\u82d7\u65cf\u81ea\u6cbb\u5dde"},"19":{"289":"\u5e7f\u5dde\u5e02","290":"\u97f6\u5173\u5e02","291":"\u6df1\u5733\u5e02","292":"\u73e0\u6d77\u5e02","293":"\u6c55\u5934\u5e02","294":"\u4f5b\u5c71\u5e02","295":"\u6c5f\u95e8\u5e02","296":"\u6e5b\u6c5f\u5e02","297":"\u8302\u540d\u5e02","298":"\u8087\u5e86\u5e02","299":"\u60e0\u5dde\u5e02","300":"\u6885\u5dde\u5e02","301":"\u6c55\u5c3e\u5e02","302":"\u6cb3\u6e90\u5e02","303":"\u9633\u6c5f\u5e02","304":"\u6e05\u8fdc\u5e02","305":"\u4e1c\u839e\u5e02","306":"\u4e2d\u5c71\u5e02","307":"\u6f6e\u5dde\u5e02","308":"\u63ed\u9633\u5e02","309":"\u4e91\u6d6e\u5e02"},"20":{"310":"\u5357\u5b81\u5e02","311":"\u67f3\u5dde\u5e02","312":"\u6842\u6797\u5e02","313":"\u68a7\u5dde\u5e02","314":"\u5317\u6d77\u5e02","315":"\u9632\u57ce\u6e2f\u5e02","316":"\u94a6\u5dde\u5e02","317":"\u8d35\u6e2f\u5e02","318":"\u7389\u6797\u5e02","319":"\u767e\u8272\u5e02","320":"\u8d3a\u5dde\u5e02","321":"\u6cb3\u6c60\u5e02","322":"\u6765\u5bbe\u5e02","323":"\u5d07\u5de6\u5e02"},"21":{"324":"\u6d77\u53e3\u5e02","325":"\u4e09\u4e9a\u5e02","326":"\u4e94\u6307\u5c71\u5e02","327":"\u743c\u6d77\u5e02","328":"\u510b\u5dde\u5e02","329":"\u6587\u660c\u5e02","330":"\u4e07\u5b81\u5e02","331":"\u4e1c\u65b9\u5e02","332":"\u5b9a\u5b89\u53bf","333":"\u5c6f\u660c\u53bf","334":"\u6f84\u8fc8\u53bf","335":"\u4e34\u9ad8\u53bf","336":"\u767d\u6c99\u9ece\u65cf\u81ea\u6cbb\u53bf","337":"\u660c\u6c5f\u9ece\u65cf\u81ea\u6cbb\u53bf","338":"\u4e50\u4e1c\u9ece\u65cf\u81ea\u6cbb\u53bf","339":"\u9675\u6c34\u9ece\u65cf\u81ea\u6cbb\u53bf","340":"\u4fdd\u4ead\u9ece\u65cf\u82d7\u65cf\u81ea\u6cbb\u53bf","341":"\u743c\u4e2d\u9ece\u65cf\u82d7\u65cf\u81ea\u6cbb\u53bf","342":"\u897f\u6c99\u7fa4\u5c9b","343":"\u5357\u6c99\u7fa4\u5c9b","344":"\u4e2d\u6c99\u7fa4\u5c9b\u7684\u5c9b\u7901\u53ca\u5176\u6d77\u57df"},"22":{"345":"\u4e07\u5dde\u533a","346":"\u6daa\u9675\u533a","347":"\u6e1d\u4e2d\u533a","348":"\u5927\u6e21\u53e3\u533a","349":"\u6c5f\u5317\u533a","350":"\u6c99\u576a\u575d\u533a","351":"\u4e5d\u9f99\u5761\u533a","352":"\u5357\u5cb8\u533a","353":"\u5317\u789a\u533a","354":"\u53cc\u6865\u533a","355":"\u4e07\u76db\u533a","356":"\u6e1d\u5317\u533a","357":"\u5df4\u5357\u533a","358":"\u9ed4\u6c5f\u533a","359":"\u957f\u5bff\u533a","360":"\u7da6\u6c5f\u53bf","361":"\u6f7c\u5357\u53bf","362":"\u94dc\u6881\u53bf","363":"\u5927\u8db3\u53bf","364":"\u8363\u660c\u53bf","365":"\u74a7\u5c71\u53bf","366":"\u6881\u5e73\u53bf","367":"\u57ce\u53e3\u53bf","368":"\u4e30\u90fd\u53bf","369":"\u57ab\u6c5f\u53bf","370":"\u6b66\u9686\u53bf","371":"\u5fe0\u53bf","372":"\u5f00\u53bf","373":"\u4e91\u9633\u53bf","374":"\u5949\u8282\u53bf","375":"\u5deb\u5c71\u53bf","376":"\u5deb\u6eaa\u53bf","377":"\u77f3\u67f1\u571f\u5bb6\u65cf\u81ea\u6cbb\u53bf","378":"\u79c0\u5c71\u571f\u5bb6\u65cf\u82d7\u65cf\u81ea\u6cbb\u53bf","379":"\u9149\u9633\u571f\u5bb6\u65cf\u82d7\u65cf\u81ea\u6cbb\u53bf","380":"\u5f6d\u6c34\u82d7\u65cf\u571f\u5bb6\u65cf\u81ea\u6cbb\u53bf","381":"\u6c5f\u6d25\u5e02","382":"\u5408\u5ddd\u5e02","383":"\u6c38\u5ddd\u5e02","384":"\u5357\u5ddd\u5e02"},"23":{"385":"\u6210\u90fd\u5e02","386":"\u81ea\u8d21\u5e02","387":"\u6500\u679d\u82b1\u5e02","388":"\u6cf8\u5dde\u5e02","389":"\u5fb7\u9633\u5e02","390":"\u7ef5\u9633\u5e02","391":"\u5e7f\u5143\u5e02","392":"\u9042\u5b81\u5e02","393":"\u5185\u6c5f\u5e02","394":"\u4e50\u5c71\u5e02","395":"\u5357\u5145\u5e02","396":"\u7709\u5c71\u5e02","397":"\u5b9c\u5bbe\u5e02","398":"\u5e7f\u5b89\u5e02","399":"\u8fbe\u5dde\u5e02","400":"\u96c5\u5b89\u5e02","401":"\u5df4\u4e2d\u5e02","402":"\u8d44\u9633\u5e02","403":"\u963f\u575d\u85cf\u65cf\u7f8c\u65cf\u81ea\u6cbb\u5dde","404":"\u7518\u5b5c\u85cf\u65cf\u81ea\u6cbb\u5dde","405":"\u51c9\u5c71\u5f5d\u65cf\u81ea\u6cbb\u5dde"},"24":{"406":"\u8d35\u9633\u5e02","407":"\u516d\u76d8\u6c34\u5e02","408":"\u9075\u4e49\u5e02","409":"\u5b89\u987a\u5e02","410":"\u94dc\u4ec1\u5730\u533a","411":"\u9ed4\u897f\u5357\u5e03\u4f9d\u65cf\u82d7\u65cf\u81ea\u6cbb\u5dde","412":"\u6bd5\u8282\u5730\u533a","413":"\u9ed4\u4e1c\u5357\u82d7\u65cf\u4f97\u65cf\u81ea\u6cbb\u5dde","414":"\u9ed4\u5357\u5e03\u4f9d\u65cf\u82d7\u65cf\u81ea\u6cbb\u5dde"},"25":{"415":"\u6606\u660e\u5e02","416":"\u66f2\u9756\u5e02","417":"\u7389\u6eaa\u5e02","418":"\u4fdd\u5c71\u5e02","419":"\u662d\u901a\u5e02","420":"\u4e3d\u6c5f\u5e02","421":"\u601d\u8305\u5e02","422":"\u4e34\u6ca7\u5e02","423":"\u695a\u96c4\u5f5d\u65cf\u81ea\u6cbb\u5dde","424":"\u7ea2\u6cb3\u54c8\u5c3c\u65cf\u5f5d\u65cf\u81ea\u6cbb\u5dde","425":"\u6587\u5c71\u58ee\u65cf\u82d7\u65cf\u81ea\u6cbb\u5dde","426":"\u897f\u53cc\u7248\u7eb3\u50a3\u65cf\u81ea\u6cbb\u5dde","427":"\u5927\u7406\u767d\u65cf\u81ea\u6cbb\u5dde","428":"\u5fb7\u5b8f\u50a3\u65cf\u666f\u9887\u65cf\u81ea\u6cbb\u5dde","429":"\u6012\u6c5f\u5088\u50f3\u65cf\u81ea\u6cbb\u5dde","430":"\u8fea\u5e86\u85cf\u65cf\u81ea\u6cbb\u5dde"},"26":{"431":"\u62c9\u8428\u5e02","432":"\u660c\u90fd\u5730\u533a","433":"\u5c71\u5357\u5730\u533a","434":"\u65e5\u5580\u5219\u5730\u533a","435":"\u90a3\u66f2\u5730\u533a","436":"\u963f\u91cc\u5730\u533a","437":"\u6797\u829d\u5730\u533a"},"27":{"438":"\u897f\u5b89\u5e02","439":"\u94dc\u5ddd\u5e02","440":"\u5b9d\u9e21\u5e02","441":"\u54b8\u9633\u5e02","442":"\u6e2d\u5357\u5e02","443":"\u5ef6\u5b89\u5e02","444":"\u6c49\u4e2d\u5e02","445":"\u6986\u6797\u5e02","446":"\u5b89\u5eb7\u5e02","447":"\u5546\u6d1b\u5e02"},"28":{"448":"\u5170\u5dde\u5e02","449":"\u5609\u5cea\u5173\u5e02","450":"\u91d1\u660c\u5e02","451":"\u767d\u94f6\u5e02","452":"\u5929\u6c34\u5e02","453":"\u6b66\u5a01\u5e02","454":"\u5f20\u6396\u5e02","455":"\u5e73\u51c9\u5e02","456":"\u9152\u6cc9\u5e02","457":"\u5e86\u9633\u5e02","458":"\u5b9a\u897f\u5e02","459":"\u9647\u5357\u5e02","460":"\u4e34\u590f\u56de\u65cf\u81ea\u6cbb\u5dde","461":"\u7518\u5357\u85cf\u65cf\u81ea\u6cbb\u5dde"},"29":{"462":"\u897f\u5b81\u5e02","463":"\u6d77\u4e1c\u5730\u533a","464":"\u6d77\u5317\u85cf\u65cf\u81ea\u6cbb\u5dde","465":"\u9ec4\u5357\u85cf\u65cf\u81ea\u6cbb\u5dde","466":"\u6d77\u5357\u85cf\u65cf\u81ea\u6cbb\u5dde","467":"\u679c\u6d1b\u85cf\u65cf\u81ea\u6cbb\u5dde","468":"\u7389\u6811\u85cf\u65cf\u81ea\u6cbb\u5dde","469":"\u6d77\u897f\u8499\u53e4\u65cf\u85cf\u65cf\u81ea\u6cbb\u5dde"},"30":{"470":"\u94f6\u5ddd\u5e02","471":"\u77f3\u5634\u5c71\u5e02","472":"\u5434\u5fe0\u5e02","473":"\u56fa\u539f\u5e02","474":"\u4e2d\u536b\u5e02"},"31":{"475":"\u4e4c\u9c81\u6728\u9f50\u5e02","476":"\u514b\u62c9\u739b\u4f9d\u5e02","477":"\u5410\u9c81\u756a\u5730\u533a","478":"\u54c8\u5bc6\u5730\u533a","479":"\u660c\u5409\u56de\u65cf\u81ea\u6cbb\u5dde","480":"\u535a\u5c14\u5854\u62c9\u8499\u53e4\u81ea\u6cbb\u5dde","481":"\u5df4\u97f3\u90ed\u695e\u8499\u53e4\u81ea\u6cbb\u5dde","482":"\u963f\u514b\u82cf\u5730\u533a","483":"\u514b\u5b5c\u52d2\u82cf\u67ef\u5c14\u514b\u5b5c\u81ea\u6cbb\u5dde","484":"\u5580\u4ec0\u5730\u533a","485":"\u548c\u7530\u5730\u533a","486":"\u4f0a\u7281\u54c8\u8428\u514b\u81ea\u6cbb\u5dde","487":"\u5854\u57ce\u5730\u533a","488":"\u963f\u52d2\u6cf0\u5730\u533a","489":"\u77f3\u6cb3\u5b50\u5e02","490":"\u963f\u62c9\u5c14\u5e02","491":"\u56fe\u6728\u8212\u514b\u5e02","492":"\u4e94\u5bb6\u6e20\u5e02"},"32":{"493":"\u53f0\u5317\u5e02","494":"\u9ad8\u96c4\u5e02","495":"\u57fa\u9686\u5e02","496":"\u53f0\u4e2d\u5e02","497":"\u53f0\u5357\u5e02","498":"\u65b0\u7af9\u5e02","499":"\u5609\u4e49\u5e02","500":"\u53f0\u5317\u53bf","501":"\u5b9c\u5170\u53bf","502":"\u6843\u56ed\u53bf","503":"\u65b0\u7af9\u53bf","504":"\u82d7\u6817\u53bf","505":"\u53f0\u4e2d\u53bf","506":"\u5f70\u5316\u53bf","507":"\u5357\u6295\u53bf","508":"\u4e91\u6797\u53bf","509":"\u5609\u4e49\u53bf","510":"\u53f0\u5357\u53bf","511":"\u9ad8\u96c4\u53bf","512":"\u5c4f\u4e1c\u53bf","513":"\u6f8e\u6e56\u53bf","514":"\u53f0\u4e1c\u53bf","515":"\u82b1\u83b2\u53bf"},"33":{"516":"\u4e2d\u897f\u533a","517":"\u4e1c\u533a","518":"\u4e5d\u9f99\u57ce\u533a","519":"\u89c2\u5858\u533a","520":"\u5357\u533a","521":"\u6df1\u6c34\u57d7\u533a","522":"\u9ec4\u5927\u4ed9\u533a","523":"\u6e7e\u4ed4\u533a","524":"\u6cb9\u5c16\u65fa\u533a","525":"\u79bb\u5c9b\u533a","526":"\u8475\u9752\u533a","527":"\u5317\u533a","528":"\u897f\u8d21\u533a","529":"\u6c99\u7530\u533a","530":"\u5c6f\u95e8\u533a","531":"\u5927\u57d4\u533a","532":"\u8343\u6e7e\u533a","533":"\u5143\u6717\u533a"},"34":{"534":"\u6fb3\u95e8\u7279\u522b\u884c\u653f\u533a"},"35":{"535":"\u7f8e\u56fd","536":"\u52a0\u62ff\u5927","537":"\u6fb3\u5927\u5229\u4e9a","538":"\u65b0\u897f\u5170","539":"\u82f1\u56fd","540":"\u6cd5\u56fd","541":"\u5fb7\u56fd","542":"\u6377\u514b","543":"\u8377\u5170","544":"\u745e\u58eb","545":"\u5e0c\u814a","546":"\u632a\u5a01","547":"\u745e\u5178","548":"\u4e39\u9ea6","549":"\u82ac\u5170","550":"\u7231\u5c14\u5170","551":"\u5965\u5730\u5229","552":"\u610f\u5927\u5229","553":"\u4e4c\u514b\u5170","554":"\u4fc4\u7f57\u65af","555":"\u897f\u73ed\u7259","556":"\u97e9\u56fd","557":"\u65b0\u52a0\u5761","558":"\u9a6c\u6765\u897f\u4e9a","559":"\u5370\u5ea6","560":"\u6cf0\u56fd","561":"\u65e5\u672c","562":"\u5df4\u897f","563":"\u963f\u6839\u5ef7","564":"\u5357\u975e","565":"\u57c3\u53ca"},"36":{"566":"\u5176\u4ed6"}}};

		var opt = {
	        preset: 'datetime', //日期
	        theme: 'android-ics light', //皮肤样式
	        height: '30',
	        display: 'modal', //显示方式 
	        mode: 'scroller', //日期选择模式
	        dateFormat: 'yy-mm-dd', // 日期格式
	        timeFormat: 'HH:ii',
	        timeWheels: 'HHii',
	        stepMinute: 10,
	        setText: '确定', //确认按钮名称
	        cancelText: '取消',//取消按钮名籍我
	        dateOrder: 'yymmdd', //面板中日期排列格式
	        dayText: '日', monthText: '月', yearText: '年', //面板中年月日文字
	        hourText: '分', minuteText: '秒',
	        endYear:2020 //结束年份
	    };
	    /**
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
	    **/
		// 编辑时,当前的省份和城市
		var param = {
			currentProvince : $(":hidden[name=current_province]").val(),
			currentArea : $(":hidden[name=current_area]").val(),
			currentPrivacy: $("#verify").val()
		};
		// 初始化,显示省
		var init = function(){
			var option1 = '';
			$.each(strJSON.province, function(index, indexItems) {
				option1 += "<option value=" + index + ">" + indexItems + "</option>";
			});
			$("#province").append(option1);
			$('#starttime,#endtime').mobiscroll(opt);
			setDefault();
		};
		// 编辑时,设置初始默认值
		var setDefault = function(){
			if(param.currentProvince != '')
			{
				var pro = $("#province option[value="+param.currentProvince+"]").text();
				$("#showProvince").text(pro);
				$("#province option[value="+param.currentProvince+"]").attr("selected", "selected");
				if(param.currentProvince == 1 || param.currentProvince == 2 || param.currentProvince ==9 || param.currentProvince == 22){
					$("#city").attr("disabled",true);
					$("#showCity").addClass('unselectit').text(pro);
				}else{
					selectCity();
					$("#showCity").text($("#city option[value="+param.currentArea+"]").text());
				}
			}
			$(".radio[data-val="+param.currentPrivacy+"]").addClass("lab");
		};
		
		var bind = function(){
			// 省市二级级联
			$("#province").bind("change", function(){
				clear();
				var provinceId = $(this).val();
				if(provinceId != -1){
					var pro = this.options[$(this).get(0).selectedIndex].text;
					$("#showProvince").text(pro);
					if(provinceId == 1 || provinceId == 2 || provinceId ==9 || provinceId == 22){
						$("#city").attr("disabled",true);
						$("#showCity").addClass('unselectit').text(pro);
					}else{
						$("#city").attr("disabled",false);
						$("#showCity").removeClass('unselectit').text(pro);
						param.currentProvince = provinceId;
						selectCity();
					}
				}else{
					$("#showProvince").text('请选择省份');
				}
			});

			// 城市变更时,设置城市
			$("#city").bind("change", function(){
				var cityId = $(this).val();
				$("#showCity").text(this.options[$(this).get(0).selectedIndex].text);
			});
			
			// 结束时间默认填充
			$("#starttime").bind('change', function(){
				var starttime = $(this).val();
				if(starttime != '')
				{
					if($("#endtime").val() == '')
					   $("#endtime").val(starttime);
				}
			});

			// 表单提交时
			$("form").submit(function(){
				var provinceId = $("#province :selected").val();
				if(provinceId == -1){
					alert('请选择省份');
					return false;
				}
				var starttime = $("#starttime").val();
				if(starttime == ''){
					alert('请输入开始时间');
					return false;
				}
				var endtime = $("#endtime").val();
				if(endtime == ''){
					alert('请输入结束时间');
					return false;
				}
				if(starttime > endtime){
					alert('开始时间大于结束时间');
					return false;
				}
				$(":submit").attr("disabled","disabled");
				return true;
			});

			$(".radio").bind('click',function(){
				$(this).addClass("lab").siblings().removeClass("lab");
				$("#verify").val($(this).attr("data-val"));
			})
			
		};
		// 选择城市
		var selectCity = function(){
			var option2 = '';
			$.each(strJSON.city, function(i,k){
				if(i != param.currentProvince) return;
				$.each(k, function(m,n){
					option2 += '<option value="'+m+'">'+n+'</option>';
				});
			});
			$("#city").append(option2);
			$("#showCity").text($("#city")[0].options[0].text);
		};
		// 级联时清除操作
		var clear = function(){
			$("#city").empty();
			$("#showCity").text('请选择城市');
		};
		// 返回
		return function(){
			init();
			init = null;
			bind();
		};
	}();
	
	activity();
})

</script>