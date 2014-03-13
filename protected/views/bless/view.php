<?php 
	if($model->cid == 6 && $model->sub_cid == 1)
	{
		$style = <<<EOF
		 #stage {
            position:absolute;
            top:0px;
            width:100%;
			max-width:640px;
            height: 450px;
            overflow: hidden;
        }


        #cardTo {
            position: absolute;
            top: 5%;
            left: 15%;
            opacity: 0;
        }

        #cardBody {
            position: absolute;
            width: 75%;
            overflow: hidden;
            visibility: hidden;
            height: auto;
            text-indent: 2em;
        }

        #cardFrom {
            position: absolute;
            top: 50%;
            opacity: 0;
        }

        #split_Lines {
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0%;
            top: 0%;
        }
        
        
        #moon 
        {
            position:relative;
			float:right;
            width:18%;
            height:50%;
			background-image:url('/static/weixin/images/card/6/moon.png');
            background-repeat: no-repeat;  
			background-size:80%;
            animation:mymove 3s linear both;
			-moz-animation:mymove 3s linear both; /* Firefox */
			-webkit-animation:mymove 3s linear both; /* Safari and Chrome */
			-o-animation:mymove 3s linear both; /* Opera */ 
        }
       @keyframes mymove
		{
		100%   {top:0px; width:90px;}
		0% {top:100px; width:200px;}
		}
		
		@-moz-keyframes mymove /* Firefox */
		{
		100%   {top:0px; width:90px;}
		0% {top:100px; width:200px;}
		}
		
		@-webkit-keyframes mymove /* Safari and Chrome */
		{
		100%   {top:0px; width:90px;}
		0% {top:100px; width:200px;}
		}
		
		@-o-keyframes mymove /* Opera */
		{
		100%   {top:0px; width:90px;}
		0% {top:100px; width:200px;}
		}
        @-webkit-keyframes lion {
            0%,20%,50%,80%,100%{-webkit-transform:translateY(0%);}
            40%{-webkit-transform:translateY(-10%);}
            60%{-webkit-transform:translateY(-10%);}
        }
        @-moz-keyframes lion {
            0%,20%,50%,80%,100%{-moz-transform:translateY(0%);}
            40%{-moz-transform:translateY(-10%);}
            60%{-moz-transform:translateY(-10%);}
        }
        @keyframes lion {
            0%,20%,50%,80%,100%{transform:translateY(0%);}
            40%{transform:translateY(-10%);}
            60%{transform:translateY(-10%);}
        }

         @-webkit-keyframes moonshow {
            70% {
                -webkit-transform:translate(300%,-100%);
            }

            100% {
                 -webkit-transform:translate(500%,-80%);
            }
        }
        @-moz-keyframes moonshow {
            70% {
                -moz-transform:translate(300%,-100%);
            }

            100% {
                 -moz-transform:translate(500%,-80%);
            }
        }
        @keyframes moonshow {
            70% {
                transform:translate(300%,-100%);
            }

            100% {
                 transform:translate(500%,-80%);
            }
        }
        @-webkit-keyframes scaleAnimate {
            0% {
            -webkit-transform:scale(0);
            }
        }
        @-moz-keyframes scaleAnimate {
            0% {
            -moz-transform:scale(0);
            }
        }
        @keyframes scaleAnimate {
            0% {
            transform:scale(0);
            }
        }
		.yhScreen{ position:relative; top:30px;}
		.yhScreen img:nth-child(1) {
			position:relative;
            left:25%;
            top:20%;
            animation:anyanhua 3s linear infinite 1s;
            -webkit-animation:anyanhua 3s linear infinite 1s;
            -moz-animation:anyanhua 3s linear infinite 1s;
            -o-animation:anyanhua 3s linear infinite 1s;
			width:14%;
        }

        .yhScreen img:nth-child(2) {
			position:relative;
            left:25%;
            top:20%;
            animation:anyanhua 5s linear infinite 2s;
            -webkit-animation:anyanhua 2s linear infinite 2s;
            -moz-animation:anyanhua 2s linear infinite 2s;
            -o-animation:anyanhua 2s linear infinite 2s;
			width:14%;
        }

        .yhScreen img:nth-child(3) {
			position:relative;
            left:27%;
            top:20%;
            animation:anyanhua 3s linear infinite 1.5s;
            -webkit-animation:anyanhua 3s linear infinite 1.5s;
            -moz-animation:anyanhua 3s linear infinite 1.5s;
            -o-animation:anyanhua 3s linear infinite 1.5s;
			width:14%;
        }

        .yhScreen img:nth-child(4) {
			position:relative;
            left:85%;
            top:30%;
            animation:anyanhua 4s linear infinite 2.5s;
            -webkit-animation:anyanhua 4s linear infinite 2.5s;
            -moz-animation:anyanhua 4s linear infinite 2.5s;
            -o-animation:anyanhua 4s linear infinite 2.5s;
			width:14%;
        }

        .yhScreen img:nth-child(5) {
			position:relative;
            left:60%;
            top:30%;
            animation:anyanhua 3.5s linear infinite 3s;
            -webkit-animation:anyanhua 3.5s linear infinite 3s;
            -moz-animation:anyanhua 3.5s linear infinite 3s;
            -o-animation:anyanhua 3.5s linear infinite 3s;
			width:14%;
        }

        .yhScreen img:nth-child(6) {
			position:relative;
            left:30%;
            top:30%;
            animation:anyanhua 4s linear infinite 4s;
            -webkit-animation:anyanhua 4s linear infinite 4s;
            -moz-animation:anyanhua 4s linear infinite 4s;
            -o-animation:anyanhua 4s linear infinite 4s;
			width:14%;
        }
        .yhScreen img:nth-child(7) {
            left:50%;
            top:48%;
            animation:anyanhua 4s linear infinite 4s;
            -webkit-animation:anyanhua 4s linear infinite 4s;
            -moz-animation:anyanhua 4s linear infinite 4s;
            -o-animation:anyanhua 4s linear infinite 4s;
			width:10%;
        }
		 @-moz-keyframes anyanhua
        {
            0%{opacity:1;  -moz-transform:scale(0.2);}
            85%{opacity:1; -moz-transform:scale(1.1);}
            100%{opacity:0;-moz-transform:scale(1.2);}
        }
        @-webkit-keyframes anyanhua
        {
            0%{opacity:1;  -webkit-transform:scale(0.2);}
            85%{opacity:1; -webkit-transform:scale(1.1);}
            100%{opacity:0;-webkit-transform:scale(1.2);}
        }

        @-o-keyframes anyanhua
        {
            0%{opacity:1;  -o-transform:scale(0.2);}
            85%{opacity:1; -o-transform:scale(1.1);}
            100%{opacity:0;-o-transform:scale(1.2);}
        }
        @keyframes anyanhua
        {
            0%{opacity:1;  transform:scale(0.2);}
            85%{opacity:1; transform:scale(1.1);}
            100%{opacity:0;transform:scale(1.2);}
        }
        .common
        {
            background: none;
            position: absolute;
            z-index: 1;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -o-user-select: none;
        }
        #glim
        {
            position: absolute;
            left: 2%;
            top:0%;
            width: 22%;
            height: 20%;
            opacity: 0.5;
            -webkit-animation:light 2s linear infinite;
            -moz-animation:light 2s linear infinite;
            -o-animation:light 2s linear infinite;
            animation:light 2s linear infinite;
        }
    @-webkit-keyframes light
    {
        50%{opacity: 1;}
        100%{opacity: 0.5}
    }
        @-moz-keyframes light
        {
            50%{opacity: 1;}
            100%{opacity: 0.5}
        }
        @keyframes light
        {
            50%{opacity: 1;}
            100%{opacity: 0.5}
        }
      
EOF;

		Yii::app()->clientScript->registerCss('yuanxiao', $style);
		
	}
	
?>

<div id="content" style="padding:0">
	<div class="cardpage">
		<div class="cardimg">
			<a href="javascript:;" class="ia"><img src="/static/weixin/sendcardbg.png" alt="" /></a>
			<img src="<?php echo str_replace('_s', '', $img);?>" />
		</div>
		<?php if($model->cid == 6 && $model->sub_cid == 1):?>
			<div id="stage" style="clear:both;">
			 <div id="moon"></div>   
			 <div id="yhScreen" class="yhScreen">
	            <img id="yh1" class="yh" src="/static/weixin/images/card/6/1.png"/>
	            <img id="yh2" class="yh" src="/static/weixin/images/card/6/2.png"/>
	            <img id="yh3" class="yh" src="/static/weixin/images/card/6/3.png"/><br>
	            <img id="yh4" class="yh" src="/static/weixin/images/card/6/4.png"/>
	            <img id="yh5" class="yh" src="/static/weixin/images/card/6/5.png"/>
	            <img id="yh6" class="yh" src="/static/weixin/images/card/6/4.png"/>
	        </div>
	        </div>
		<?php endif;?>
		<div class="cardtxtarea fix">
			<p class="p1"><?php echo $model->to_user;?></p>
			<p><?php echo $model->content;?></p>
			<p class="p2">
			<?php
				$linkUrl = !empty($model->from_mid) ? $this->createShareUrl($model->from_mid) : '';
				
				if(!empty($photo))
				{
					if(!empty($linkUrl))
					{
						echo '<a href="'.$linkUrl.'"><img src="'.$photo.'" /></a>';
					}else{
						echo '<img src="'.$photo.'" />';
					}
				}
				if(!empty($linkUrl))
				{
					echo '<a href="'.$linkUrl.'">'.$model->from_user.'</a>';
				}else{
					echo $model->from_user;
				}
				
				if(!empty($model->from_mid))
				{
					echo '<a href="'.$this->createShareUrl($model->from_mid).'" class="gzt">关注TA</a>';
				}
			?>
			</p>
		</div>
		<div class="cardbtn">
			<a href="<?php echo $this->createUrl('bless/copy',array('id'=>$model->id));?>" class="btn_b1">复制并生成新贺卡</a>
			<a href="<?php echo $this->createUrl('bless/index',array('from'=>$model->id)); ?>" class="btn_b2">设计全新贺卡</a>
		</div>
		
		<p class="copyright">欢迎关注往来微信公众号：wanglairm<br />Powered by <a href="http://www.wanglai.cc">wanglai.cc</a></p>
	</div>
</div>
<?php echo $this->renderPartial('/common/footer'); ?>

<script>
$(document).ready(function(){
	dataForWeixin.callback = function () {
	 	 $.post("<?php echo $this->createUrl('bless/countShare',array('id'=>$model->id));?>", function () {
		 	
	   });
	}
	dataForWeixin.url = '<?php echo $this->createUrl('bless/view',array('id'=>$model->id));?>';
 	dataForWeixin.title = '来自<?php echo $model->from_user;?>的祝福';
 	dataForWeixin.desc = '<?php echo htmlspecialchars(mb_substr(preg_replace('/\s/', '', $model->content),0,50,'UTF-8'),ENT_QUOTES,'UTF-8');?>';
 	dataForWeixin.MsgImg = "<?php echo $shareImg ?>";
})
</script>