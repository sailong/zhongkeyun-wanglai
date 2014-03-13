<?php
class PrivateController extends Controller
{
	
    public function actionP()
    {   return;
    	header('content-type:text/html;charset=utf-8');
    	$emailArr = array('126.com','sina.com.cn','yeah.net','163.com','gmail.com','hotmail.com','foxmail.com');
    	$mobileArr = array('15','18','13');
    	$time = time();
    	$maxpage = 286;
    	$page = isset($_GET['page']) ? $_GET['page'] : 1;
    	$views = isset($_GET['views']) ? $_GET['views'] : 3499;
    	for ($i=1;$i<=105;$i++)
    	{
    		if($views)
    		{
    			if($i%15==0) $views--;
    		}else {
    			$views = 0;
    		}
    		$rand = mt_rand(0,6);
    		
    		$mobile = $email = '';
    		
    		$mobile = '91111111111';
    		$email = '91111111111@qq.com';
    		/* $mid = mt_rand(0, 2);
    		while(true)
    		{
    			$mobile = $mobileArr[$mid].mt_rand(0, 9).$i.$this->getNumber();
    			if(!Member::model()->checkExists('mobile',$mobile)) break;
    		}
 
    		while(true)
    		{
    			$email = mt_rand(0, 999).$this->getStr().mt_rand(0, 9999999).$emailArr[$rand];
    			if(!Member::model()->checkExists('email',$email)) break;
    		}
    		*/
    		
    		$model = new Member;
    		$model->weixin_openid = 'wanglai_wyj_'.md5(uniqid().'_m_t_'.mt_rand(1,9999999).date('is'));
    		$model->name=mt_rand(199999, 9999999);
    		$model->mobile= $mobile;
    		$model->email =$email;
    		$model->password = md5(time().mt_rand(1,999999));
    		$model->from =2;
    		$model->created_at = $time;
    		$model->views = $views;
    		$model->save(); 
    	}
    	//echo 'ok';
    	$page++;
    	if($page > $maxpage) 
    	{
    		echo 'ok';
    		die;
    	}
    	echo '下一组数据导入.'.$page.'.....<script>setTimeout("jump()",1000);function jump(){location.href="/index.php?r=Private/p&page='.$page.'&views='.$views.'"}</script>';
    	
    }
    
    
    
    
    private function getStr()
    {
    	$str='abcdefghijklmnopqrstuvwxyz';
    	$rndstr='';	//用来存放生成的随机字符串
    	for($i=0;$i<5;$i++)
    	{
    	$rndcode=rand(0,25);
    	$rndstr.=$str[$rndcode];
    	}
    	return $rndstr;
    }
    
    private function getNumber()
    {
    	$str='1234567890';
    	$rndstr='';	//用来存放生成的随机字符串
    	$i=0;
    	for($i;$i<7;$i++)
    	{
    	$rndcode=rand(0,9);
    	$rndstr.=$str[$rndcode];
    	}
    	return $rndstr;
    }
    
    
    
    
    
    public function actionAutoAdd()
    {
    	if(!isset($_GET['key']) || $_GET['key']!='QWEREETERTERT') return;
    	set_time_limit(0);
    	header('content-type:text/html;charset=utf-8');
    	$emailArr = array('126.com','sina.com.cn','yeah.net','163.com','gmail.com','hotmail.com','foxmail.com');
    	$mobileArr = array('15','18','13');
    	$time = time();
    	$views = 3500;
    	for ($i=1;$i<=10000;$i++)
    	{
    	    if($i>1)
    	    {
    	    	if($i%15==0) $views--;
    	    }
				    $rand = mt_rand(0,6);
				    $mobile = $email = '';
				    $mobile = '91111111111';
				    $email = '91111111111@qq.com';
		    		$model = new Member;
		    		$model->weixin_openid = 'wanglai_wyj_'.md5(uniqid().'_m_t_'.mt_rand(1,9999999).date('is'));
		    		$model->name=mt_rand(199999, 9999999);
		    		$model->mobile= $mobile;
		    		$model->email =$email;
		        	$model->password = md5(time().mt_rand(1,999999));
		        	$model->from =2;
		    		$model->created_at = $time;
		    		$model->views = $views;
		    		$model->save();
		    if($i==1)
		    {
		    	$views=3499;
		    }
        }	 
    }
    
    //----活动结束 给没有及时申请靓号的用户分配最后一个号码---------------------------------------
    public function actionAssignNumber()
    {return ;
    	header('content-type:text/html;charset=utf-8');
    	$statModel = InviteStat::model()->findAll('is_confirm_number = 0 AND all_counts >=3');
    	if($statModel)
    	{
    		$i=0;
    		$noC = 0;
    		foreach($statModel as $stat)
    		{
    			$member_id = $stat->member_id; 
    			//判断该用户是否已经有往来号
    			$memberModel = Member::model()->getMemberBy('id',$member_id,'wanglai_number,name');
    			if($memberModel->wanglai_number)
    			{
    				echo $memberModel->name.'===='.$memberModel->wanglai_number.'<br>';
    				$noC++;
    				continue;
    			}else
    			{
    				//echo $member_id;die;
    			}
    			
    				//查看是否已经有备选往来号：
    				$param = array();
    				$param['select']= 'id,number';
    				$param['condition'] = 'member_id = :a AND grant_way = 1';
    				$param['params'] = array(':a'=>$member_id);
    				$param['order'] = 'id desc';
    				//$param['limit'] = 1;
    				$spaceNumberModel = GrantNumberLog::model()->findAll($param);
    				$assignEd = false;
    				$number = '';
    				$myNumberArr = array();
    				if($spaceNumberModel)
    				{
    					foreach ($spaceNumberModel as $space)
    					{
    						if($space->number)
    						{
    							$status = Member::model()->getMemberBy('wanglai_number',$space->number,'id');
    							if(!$status) 
    							{
    								$myNumberArr[$space->id] = $space->number;
    							}
    							if($assignEd) continue;
    							//判断该号码是否被占用
    							//$status = Member::model()->getMemberBy('wanglai_number',$space->number,'id');
    							if(!$status) 
    							{
    								$number = $space->number;
    								//更新往来号：
    								Member::model()->updateByPk($member_id, array('wanglai_number'=>$number));
    								$assignEd = true;
    								//把该号码设置为使用	
    								Number::model()->updateAll(array('number_status'=>1),'number = :a',array(':a'=>$number));
    							}
    						}
    					}
    				}
    	
    				if(!$assignEd)
    				{    
    					
    					echo 'rand'.'<br>';
    					while (true)
    					{
    						//分配账号
    						$numberArr = Number::model()->assignNumber();
    						if(!$numberArr) continue;
    						$number = $numberArr['number'];
    						//判断该号码是否被占用
    						$status = Member::model()->getMemberBy('wanglai_number',$number,'id');
    						if(!$status)
    						{
    							//减掉邀请人数
    							$result = InviteStat::model()->updateCounters(array('left_counts'=>-3),'id = '.$stat->id);
    								
    							Number::model()->updateAll(array('number_status'=>1),'number = :a',array(':a'=>$number));
    							//更新往来号：
    							Member::model()->updateByPk($member_id, array('wanglai_number'=>$number));
    							$assignEd = true;
    							break;
    						}
    					}
    				}else
    				{
    					$n_id = array_search($number,$myNumberArr,true);
    					if($n_id)
    					{
    						unset($myNumberArr[$n_id]);
    						//-把被锁定的号码重置为正常-------------------------
    						if($myNumberArr)
    						{
    							$numberStr = implode(',', $myNumberArr);
    							Number::model()->updateAll(array('number_status'=>0),'number in ('.$numberStr.')');
    							echo $numberStr.'<br>';
    						}
    					}
    				}
    				if($assignEd)
    				{
    					$i++;
    					InviteStat::model()->updateAll(array('is_confirm_number'=>2),'member_id = '.$member_id);
    				}
    				echo $member_id.'-------------'.$number.'<br>';
    			}
    			echo '成功处理 '.$i.' 个=== 未处理 '.$noC;
    		
    	}
    	
    	
    }
    
    public function actionSendMessage()
    {return;
    	header('content-type:text/html;charset=utf-8');
    	
    	$path = dirname(dirname(__FILE__)).'/extensions/Sms/';
    	include_once($path.'sms.php');
    	$sms = new sms();
    	
    	
    	$page = isset($_GET['page']) ? $_GET['page'] : 1;
    	$model = Member::model();
    	$criteria = new CDbCriteria();
    	$criteria->order  = 'id desc';
    	$criteria->condition =' `from` = 1';
    	$criteria->select = 'id,name,mobile';
    	$count = $model->count($criteria);
    	$pager = new CPagination($count);
    	$pager->pageSize = 50;
    	$pager->applyLimit($criteria);
    	$result = $model->findAll($criteria);
    	$str = ',微名片将于今晚24点升级,届时需要重新登录,忘记密码可短信找回。客服QQ1085606688微信wanglairm';
    	if($result)
    	{
    		$i=0;
    		foreach ($result as $r)
    		{
    			if($r->mobile)
    			{
    				//开始发送
    				$return = $sms->send($r->mobile,$r->name.$str);
    				if(isset($return[1]) && trim($return[1]==0))
    				{
    					$i++;
    				} 
    			}
    		}
    		$page++;
    		if($page > $pager->getPageCount())
    		{
    			echo 'ok';
    			die;
    		}
    		echo '成功发送'.$i.'个,开始下一组短信发送.'.$page.'.....<script>setTimeout("jump()",1000);function jump(){location.href="/index.php?r=Private/SendMessage&page='.$page.'"}</script>';
    	}
    }
}
?>