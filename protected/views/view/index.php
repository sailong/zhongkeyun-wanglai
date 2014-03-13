<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>往来</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
    <link rel="stylesheet" type="text/css" href="/static/temp/jquery.mobile.css" />
    <link href="/static/temp/Headin_Jobs_Mobile.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/static/temp/jquery.1.7.2.js"></script>
    <script type="text/javascript" src="/static/temp/jquery.mobile.js"></script>
    <script type="text/javascript" src="/static/temp/headin_jobs_mobile.js?v=301"></script>
</head>
<body>

	<div data-role="page" id="carddetail">
    <div data-theme="b" data-role="header">
        <h3>张朝磊的微名片</h3>
    </div>
    <div data-role="content">
        <div class="counts">
        	<span class="hot_count">全球名片排名：第<em>10425</em>名</span>
        	<p style="margin-top: 0">共有40人看过该名片，累计浏览量136次</p>
        </div>
        <div class="carddetail drop-shadow raised">
         		<div style="height:120px;">
	                  <span style="float:left;"><img src="http://<?php echo Yii::app()->request->getHostInfo();?>/attachments/avatar/201310/31/90803020080_s.jpg" style=" vertical-align:top; padding-right:10px; width:100px; height:100px"></span>  
	                  <ul style="padding-left: 110px">
	                     <li class="wusername" style="font-family: 微软雅黑;font-size: 22px;font-weight: bold;color: #333333;">张朝磊</li>
	                     <li class="wuserpost">已收到 <strong>39</strong> 张微名片</li>
	                     <li class="wuserpost">已关注 <strong>39</strong> 张微名片</li>                   
	                     <li class="wuserpost"><a data-role="button" data-theme="b" href="#" class="sendcard">递名片(关注)</a></li>                   
	                  </ul>
                </div>
                <ul class="carddetail_new">
                	<li class="np"><a href="#" class="f14">中卫云健康科技有限公司</a></li>
                    <li><em class="mobile">手机：</em>13800138000</li>
                    <li><em class="job">职位：</em>系统分析员</li>
                    <li><em class="email">邮箱：</em><a href="mailto:123456@qq.com">123456@qq.com</a></li>                
                    <li><em class="email">地址：</em>北京朝外大街38号保罗大厦7层</li>
                    <li><em class="email">主营：</em>为大客户进行精准营销匹配方案，量身定制大数据营销策略</li>
                    <li><em class="email">供给：</em>移动互联网软件</li>
					<li><em class="email">需求：</em>政府关系</li>
					<li><em class="email">微信：</em>upsolo</li>
					<li><em class="email">易信：</em>upsolo</li>
					<li><em class="email">来往：</em>upsolo</li>
					<li><em class="email">Q Q：</em>13242342</li>
					<li><em class="email">爱好：</em>项目投资</li>
               </ul>
        </div>
        
        <a data-role="button" data-theme="a" href="#" data-ajax="false" id="sendtofriendbtn">关注往来公众号获得高级服务</a>
        
        <a data-role="button" data-theme="b" href="" data-ajax="false">管理我的微名片 <span class="newmsg"></span></a>
        
 
        <a data-role="button" data-inline="true" data-icon="send" id="sendtofriendbtn" data-theme="f" class="sharebtn">发送给朋友</a>
        
        <a data-role="button" data-inline="true" data-theme="f" style="float: right" data-icon="friend" id="sharebtn" class="sharebtn">分享到朋友圈</a>
        
        <p class="myfriends">我的最新小伙伴：习近平  李克强  洪韶光 毛新宇  </p>

        <p class="copyright">欢迎关注往来微信公众号：wanglairm<br />往来人脉-让人人都有自己的微名片<br />Powered by <a href="http://www.wanglai.cc">wanglai.cc</a></p>
    </div>
    <div id="sharebg">&nbsp;</div>
    <div id="sharebox">
        <img src="/static/temp/follow.png" />
    </div>
</div>
    <div id="operate-ok"></div>
    
     <script>
        var shareLink = '/index.php?r=card/share&id=7';
        var title = '张朝磊的微名片';
        var desc = '张朝磊的微名片,保存在微信上并可发送给朋友或分享到朋友圈、微信群！';
        var weibodesc = '#微名片#，这是张朝磊的微名片，请大家惠存，你也来制作自己的微名片吧！';
        var MsgImg = 'http://<?php echo Yii::app()->request->getHostInfo(); ?>/attachments/avatar/201310/31/90803020080_s.jpg';
        dataForWeixin.callback = function () {
          
       	 $.post("/index.php?r=card/Stat", { id: "7-0c989d1138a9c89462f9e822f3e94fe6" }, function () {
             $("#sharebg,#sharebox").hide();
         });
            
           
        }
        SetupWeiXinShareInfo(shareLink, title, desc, weibodesc);
    </script>
</body>
</html>
