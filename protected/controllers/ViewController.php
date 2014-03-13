<?php
class ViewController extends Controller
{
	
    public function actionIndex()
    {   
    	//$this->render('index');
    }
    
    public function actionTest()
    {
    	
    	$useragent = $_SERVER['HTTP_USER_AGENT'];
    	
    	Yii::log($useragent);
    	Yii::app()->end();
    	echo $useragent;
    	die;
    	$openid = 'opve0t8cwg88F3VZ0ZgnHkxNcawQ';
    	
    	
    	$s = WeixinBridge::responseTextImgMessage($openid, 'aaaaaa', Weixin::handleClickEvent());
    	echo $s;die;

    	//$message = '<a href="http://www.wl.com/index.php?r=member/view&id=174234">zhoujianjun</a>%E5%85%B3%E6%B3%A8%E4%BA%86<a href="http://www.wl.com/index.php?r=member/view&id=1">%E4%BD%A0</a>';
    	$message = urlencode('hello,这个是测试');
    	WeixinBridge::sendServiceMessage($openid, $message);
    	
    	die;
    	
    	
    	
    	$useragent = $_SERVER['HTTP_USER_AGENT'];
    	echo $useragent;
    	die;
    	
    	
    	$mailer = Yii::createComponent(array('class'=>'ext.Mail.Mail'));
    	Yii::app()->setComponent('mail', $mailer);
    	
    	Yii::app()->mail->send('zhoujianjun@zhongkeyun.com','测试1','测试');
    	
    	Yii::app()->mail->send('zhoujianjun307@163.com','测试2','测试');
    	Yii::app()->mail->send('573932979@qq.com','测试3','测试');
    	
    	die;
    	$db = Yii::app()->db;
    	$sql = "select * from activity where id=1";
    	$result = Yii::app()->db->createCommand($sql)->queryRow();
    	print_r($result);die;
    	
    	print_r(Yii::app()->db);die;
    	
    	//Yii::log('test','info');
    	//Yii::app()->end();die;
    	
    	
//     	header('content-type:text/html;charset=utf-8');
//     	$mobile = '18701533591,13683573386,13911168607,18646588891,18600805024,15810010585,13718220294,15011229576';
// 	    $content = '短信测试';
	    
// 	   // $url='http://sms.webchinese.cn/web_api/?Uid=w18767162914&Key=a193bd99366043fdbb8c&smsMob='.$mobile.'&smsText=新年快乐【中科云健康公司】';
// 	  //  $url = mb_convert_encoding($url, 'GBK', 'UTF-8');
	    
// 	    $url = 'http://smsapi.c123.cn/OpenPlatform/OpenApi?action=sendOnce&ac=1001@500815250001&authkey=F872A10CB3FA3C57D5C62CC066597337&cgid=52&c='.urlencode('短信测试').'&m='.$mobile;
	    
// 	    	if(function_exists('file_get_contents'))
// 	    	{
// 	    		$file_contents = file_get_contents($url);
// 	    	}
// 	    	else
// 	    	{
// 	    		$ch = curl_init();
// 	    		$timeout = 5;
// 	    		curl_setopt ($ch, CURLOPT_URL, $url);
// 	    		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
// 	    		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
// 	    		$file_contents = curl_exec($ch);
// 	    		curl_close($ch);
// 	    	}
	    	
	    	
// 	    var_dump($file_contents);
// 	    echo date('Y-m-d H:i:s');
//     	//Util::sendSms($mobile, $content);
    }
   
    
    public function actionChange()
    {
    	error_reporting(E_ALL);
    	set_time_limit(0);
    	header('content-type:text/html;charset=utf-8');
    	spl_autoload_unregister(array('YiiBase','autoload'));
    	require Yii::getPathOfAlias('ext') . '/Excel/PHPExcel.php';
		$file = Yii::app()->getBasePath().'/data/1.xls';
		$PHPExcel = PHPExcel_IOFactory::load($file);
		$data = array();
		$contactsId = 10067;
		foreach ($PHPExcel->getWorksheetIterator() as $key => $sheet)
		{
			$data[] = $sheet->toArray();
		}
		
		$data = $data[0];
		unset($data[0]);
		
		spl_autoload_register(array('YiiBase','autoload'));
		
		foreach ($data as $value)
		{
			if(!empty($value))
			{
				$name = trim($value[1]);
				$company = trim($value[2]);
				$mobile = trim($value[3]);
				$address = trim($value[5]);
				if(!empty($name) && !empty($mobile))
				{
					$member = Member::model()->findByAttributes(array('mobile'=>$mobile));
					if(empty($member))
					{
						$member = new Member();
						$member->weixin_openid = 'temp_wanglai_'.md5(uniqid().mt_rand(1,999999));
						$member->name = $name;
						$member->mobile = $mobile;
						$member->company = $company;
						$member->address = $address;
						$member->initial = Util::getFirstLetter($name);
						$member->wanglai_number = Number::model()->getNumber();
						$member->created_at = time();
						if(!$member->save())
						{
							print_r($value);
							echo $name . '添加失败' . "<br>";
						}
					}
					
					$contactsMember = new ContactsMember();
					$contactsMember->attributes = array(
						'member_id' => $member->id,
						'contacts_id' => $contactsId,
						'state' => ContactsMember::STATE_PASS,
						'apply_time' => time(),
						'update_time' => time()	
					);
					if(!$contactsMember->save())
					{
						echo $member->id . '保存失败' . "<br>";
					}
				}
			}
			
		}
    }
    
    /**
     * 获取首字母
     */
//     public function actionLetter()
//     {
//     	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
//     	set_time_limit(0);
//     	error_reporting(E_ERROR);
//     	$exclude = array(
//     		'逍'	=> 'X','郜' => 'G','裘' => 'Q','禚' => 'Z',
//     		'闫' => 'Y','蔺' => 'L','缪' => 'M','栾' => 'L',
//     		'邬' => 'W', '覃' => 'Q', '臧' => 'Z','谌' => 'C',
//     		'郏' => 'J','佟' => 'T','褚' => 'C','佘' => 'S',
//     		'岑' => 'C','闵' => 'M','莘' => 'S','晏' => 'Y','椁' => 'G',
//     		'瞿' => 'Q','胥' => 'X','郇' => 'X','玺' => 'X','阙' => 'Q',
//     		'滕' => 'T','笪' => 'D','昃' => 'Z','冼' => 'X','鄢' => 'Y'
//     	);
//     	$sql = "select count(*) as total from member where `from`=1";
//     	$total = Yii::app()->db->createCommand($sql)->queryScalar();
//     	$n = ceil($total/100);
//     	for($i=0;$i<$n;$i++)
//     	{
//     		$start = $i*100;
//     		$sql = "select id,name from member where `from`=1 limit $start,100";
//     		$result = Yii::app()->db->createCommand($sql)->queryAll();
//     		foreach ($result as $row)
//     		{
//     			$id = $row['id'];
//     			if(!empty($row['name']))
//     			{
//     				$name = mb_substr(trim($row['name']),0,1,'UTF-8');
//     				if(key_exists($name, $exclude))
//     				{
//     					$leter = $exclude[$name];
//     				}else{
// 		    			$leter = Util::fanToJian($name);
// 	    				$leter = Util::getFirstLetter($leter);
// 		    			if($leter == null)
// 		    			{
// 		    				echo $id . '  ' . $row['name'];
// 		    				echo "<br/>";
// 		    				continue;
// 		    			}
//     				}
// 	    			if(!is_numeric($leter))
// 	    			{
// 	    				$leter = strtoupper($leter);
// 		    			$sql = "update member set `initial`='$leter' where id={$id}";
// 	    			}
// 	    			Yii::app()->db->createCommand($sql)->execute();
//     			}
//     		}
//     	}
//     }


}