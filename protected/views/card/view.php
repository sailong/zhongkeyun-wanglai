<?php $model->company_url = $this->getUrl($model->company_url);?>
<div data-role="page" id="carddetail">
    <div data-theme="b" data-role="header">
        <h3><?php echo $model->name;?>的微名片</h3>
    </div>
    <div data-role="content">
        <div class="counts">
        	<span class="hot_count">全球名片排名：第<em><?php echo $this->loadModel()->getOrder($model->views);?></em>名</span>
        	<p style="margin-top: 0">共有<?php echo $model->views;?>人看过该名片，累计浏览量<?php echo $pv_counts ? $pv_counts : 0;?>次</p>
        </div>
        <div class="carddetail drop-shadow raised">
         		<div style="height:120px;">
	                  <span style="float:left;">
	                  		<?php if(!empty($big)){?>
	                  			<a data-ajax="false" href="<?php echo $big; ?>"><img src="<?php echo $model->avatar;?>" style=" vertical-align:top; margin-right:10px; width:100px; height:100px;border-radius: 0.5em"></a>
	                  		<?php }else{?>
	                  	 		<img src="<?php echo $model->avatar;?>" style=" vertical-align:top; margin-right:10px; width:100px; height:100px;border-radius: 0.5em">
	                 		<?php }?>
	                  </span>  
	                  <ul style="padding-left: 110px">
	                     <li class="wusername" style="font-family: 微软雅黑;font-size: 22px;font-weight: bold;color: #333333;" <?php if($model->is_vip) echo 'id="vtips"';?>>
	                     	<?php echo $model->name;if($model->is_vip) echo ' <img src="/static/weixin/yellow_v.png"><div id="tipsbox">往来官方个人认证</div>';?>
	                     </li>
	                     <li class="wuserpost">已收到 <strong id="follow_me"><?php echo $follow['follow_me_counts'] ? $follow['follow_me_counts'] : 0;?></strong> 张微名片</li>
	                     <li class="wuserpost">已关注 <strong id="my_follow"><?php echo $follow['my_follow_counts'] ? $follow['my_follow_counts'] : 0;?></strong> 张微名片</li> 
	                     <?php if($openid!=$model->weixin_openid){?>                  
	                      <li class="wuserpost"><a data-role="button" data-theme="b" href="javascript:;" class="sendcard" id="maketag" json-data="<?php echo $is_follow > 0 ? 0 : 1;?>"><?php echo $is_follow > 0 ? '取消关注' : '+ 关注';?></a></li>                  
	                 	<?php }?>
	                  </ul>
                </div>
                <?php $access=$this->checkAccess($model); ?>
                <ul class="carddetail_new">
                	<?php if($model->company){ ?><li class="np"><a href="<?php echo $model->company_url;?>" class="f14" data-ajax="false"><?php echo $model->company;?></a></li><?php }?>
                	<?php if($model->wanglai_number){ $style=$model->wanglai_number_grade ? 'color:#f90' : '';?><li class='nc'><em class="wlnum">往来号：</em><strong style="<?php echo $style;?>"><?php echo $model->wanglai_number;?></strong></li><?php }?>
                	<?php if($model->mobile) { ?><li><em class="mobile">手机：</em><?php echo $access ? '<a href="tel:'.$model->mobile.'">'.$model->mobile.'</a>' : '<span style="color:#cccccc">（互相关注后可见）</span>';?></li><?php }?>
					<?php if($model->position){?><li><em class="job">职位：</em><?php echo $model->position;?></li><?php }?>
					<?php if($model->email){?><li><em class="email">邮箱：</em><a <?php if($access) echo 'href="mailto:'.$model->email.'"'; ?>><?php echo $access ? $model->email : '<span style="color:#cccccc">（互相关注后可见）</span>';?></a></li><?php }?>
					<?php if($model->address){?><li><em class="address">地址：</em><a data-ajax="false" href="http://api.map.baidu.com/geocoder?address=<?php echo $model->address; ?>&output=html"><?php echo $model->address;?></a></li><?php }?>  
           			<?php 
                    $show = array('supply'=>'供给','demand'=>'需求','qq'=>'Q Q','weixin'=>'微信号','yixin'=>'易信号','laiwang'=>'来往号','main_business'=>'业务介绍','social_position'=>'社会职务','profile'=>'个人简介','hobby'=>'兴趣爱好');
                    foreach ($show as $key=>$val)
                    { 
                    	if($model->$key)
						{
                    ?>
                     	<li <?php if(!in_array($key,array('supply','demand','qq'))){if(iconv_strlen($val,'UTF-8')==3) echo "class='nc'";else echo "class='nb'";}?>><em class="ot"><?php echo $val;?>：</em>
                     	<?php 
                     		if(in_array($key, array('qq','weixin','yixin','laiwang')) && !$access)
                     			echo '<span style="color:#cccccc">（互相关注后可见）</span>';
                     		else 
                     			echo $model->$key;
                     	
                     	?>
                     	</li>
                    <?php 
                    	}
                    }
                    ?>		
               </ul>
        </div>
         <?php if($openid)
        	{
        		if($openid!=$model->weixin_openid)
        		{
        			echo '<a data-role="button" data-theme="b" href="'.$this->createShareUrl($member_id).'" data-ajax="false">查看我的微名片</a>';
        		}
				$str= '<a data-role="button" data-theme="b" href="'.$this->createUrl('card/update',array('openid'=>$this->getOpenid())).'" data-ajax="false">修改名片';
				/**
				if($last_update_at > 0)
				{
					$str.='<img src="static/weixin/newmsg.png" alt="" class="newmsg" />';
				}
				**/
				echo $str.'</a>';
			}
			
			if($openid)
			{
				/* if(!Helper::checkIsSubscribe($openid))
				{ */
					echo '<a data-role="button" data-theme="a" href="http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA4MjA5MDQwNg==&appmsgid=10000001&itemidx=1&sign=21b860703a27747f6194401619e96bf8&uin=MjcwODc0Mzc1&key=8118eafa408d7ddcc3e5a0535e653bd3cd980a00cf5a413c0b9c3111cf421e6a453fd00569a451162e6153a29353ac75788ebe7eccaa14cd4f20ac17b975ac6d6d784df36c9984227e43000e742e704de09a1f45a30f59b29f72505eb6c08bbb14baf09a3eb81ccd0ef68df733c55baa5411d3ac63fcb110fc089e6e7a17ef77&devicetype=iPhone+OS7.0.2&version=15000311&lang=zh_CN" data-ajax="false">关注往来公众号抢6位往来号</a>';
				//}
			}
       ?>
        <a data-role="button" data-inline="true" data-icon="send" id="sendtofriendbtn" data-theme="f" class="sharebtn">发送给朋友</a>
        <a data-role="button" data-inline="true" data-theme="f" style="float: right" data-icon="friend" id="sharebtn" class="sharebtn">分享到朋友圈</a>
        <p class="copyright">欢迎关注往来微信公众号：wanglairm<br />往来人脉-让人人都有自己的微名片<br />Powered by <a href="http://www.wanglai.cc">wanglai.cc</a></p>
    </div>
    <div id="sharebg">&nbsp;</div>
    <div id="sharebox">
        <img src="/static/weixin/follow.png" />
    </div>
<script>
        dataForWeixin.callback = function () {
       	 $.post("<?php echo $this->createUrl('Stat');?>", { id: "<?php echo $model->id.'-'.md5($model->id.'wanglai123');?>" }, function () {
             $("#sharebg,#sharebox").hide();
         });
        }
        dataForWeixin.url = '<?php echo $this->createShareUrl($model->id);?>';
 	    dataForWeixin.title = '<?php echo $model->name;?>的微名片';
 	    dataForWeixin.desc = '<?php echo $model->name;?>的微名片,保存在微信上并可发送给朋友或分享到朋友圈、微信群！';
 	    dataForWeixin.weibodesc = '#微名片#，这是<?php echo $model->name;?>的微名片，请大家惠存，你也来制作自己的微名片吧！';
  	    dataForWeixin.MsgImg = "<?php echo $model->avatar;?>";

  	    var follow_me = '<?php echo $follow['follow_me_counts'] ? $follow['follow_me_counts'] : 0;?>';
  	   	 $(function(){
  	  	   	var follow_url = '/index.php?r=Follow/Follow&mid=<?php echo $member_id;?>&follow_mid=<?php echo $model->id;?>&sign=<?php echo Helper::createSign($member_id.'-'.$model->id);?>';
			//var follow_url = "";
  			var $m = $("#maketag");
  			$m.bind("click",function(){
  				var jd = $m.attr("json-data");
  				if(jd==1){
  					$.post(follow_url+"&tag="+jd,function(result){
  						if(result.code==1){
  							follow_me= parseInt(follow_me)+1;
  	  						$('#follow_me').html(follow_me);
  							$m.attr("json-data",0);
  							$m.find(".ui-btn-text").text("取消关注");
  						}
  						else{
  							alert(result.message);
  							if(result.code==-1)
  							{
  	  							location.href='/index.php?r=member/follow';
  							}
  							return false;
  						}
  					},type="json")
  				}else{
  					$.post(follow_url+"&tag="+jd,function(result){
  						if(result.code==1){
  							follow_me= parseInt(follow_me)-1;
  	  						$('#follow_me').html(follow_me);
  							$m.attr("json-data",1);
  							$m.find(".ui-btn-text").text("+ 关注");
  						}
  						else{
  							alert(result.message);
  						
  							return false;
  						}
  					},type="json")
  				}
  			});
  			
  			$("#vtips").tap(function(e){
  				e?e.stopPropagation():event.cancelBubble = true;
  				$("#tipsbox").toggle();
  			});
  			
  			$(document).tap(function(){
				$("#tipsbox").hide();
			});
  			
  		})
</script>
</div>
    <div id="operate-ok"></div>