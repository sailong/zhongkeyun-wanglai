<?php
/**
 * wyj add
 */
class Helper extends CController {
   
	/**
	 * 字符串截取
	 * @param unknown_type $string
	 * @param unknown_type $length
	 * @param unknown_type $etc
	 * @return Ambigous <unknown, string>
	 */
	public static  $badWords;
	public static function cutStr($string, $length, $etc = '')
    {
            $result = '';
            $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
            $strlen = strlen($string);
            for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
                {
                if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
                        {
                    if ($length < 1.0)
                                {
                        break;
                    }
                    $result .= substr($string, $i, $number);
                    $length -= 1.0;
                    $i += $number - 1;
                }
                        else
                        {
                    $result .= substr($string, $i, 1);
                    $length -= 0.5;
                }
            }
            $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
            if ($i < $strlen)  $result .= $etc;
            
            return $result;
     }
     
     public static function getLocalTime()
     {
     	return time();
     }
     
     public static function getLocalDate($unixTime,$format='Y-m-d H:i')
     {
     	return date($format,$unixTime);
     }
     
     /**
      * 获取ip地址
      */
     public static function get_real_ip()
     {
     	$ip=false;
     	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
     		$ip = $_SERVER["HTTP_CLIENT_IP"];
     	}
     	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
     		$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
     		if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
     		for ($i = 0; $i < count($ips); $i++) {
     			if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
     				$ip = $ips[$i];
     				break;
     			}
     		}
     	}
     	return ($ip ? $ip : ($_SERVER && isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null));
     }
     
     /**
      * 返回图片完整的url地址
      * @param string $path    bg/201304/13/1122.jpg|bg/201304/13/1122_s.jpg
      * @param string $sizeStr m|b
      */
     public static function getImage($path,$sizeStr='')
     {
     	if(!$path) return '';
     	$sizeStr = $sizeStr ? strtolower($sizeStr) : '';
     	$imageRootPath = Yii::getPathOfAlias('webroot').'/attachments/';
     	if(!file_exists($imageRootPath.$path)) return '';
     	if($sizeStr) 
     	{
     		$orginalImage = str_ireplace('_s', '', $path);
     		$sizePath = str_ireplace('.', '_'.$sizeStr.'.', $orginalImage);
     		if(file_exists($imageRootPath.$sizePath))
     		{
     			$path = $sizePath;
     		}else 
     		{
     			if(file_exists($imageRootPath.$orginalImage))
     			{
     				$path = $orginalImage;
     			}else
     			{
     				$path = '';
     			}
     		}
     	}
     	if(!$path) return '';
     	return Yii::app()->request->hostInfo.'/attachments/'.$path;
     }
     /***
      * 验证email
      */
     public static function checkEmail($email)
     {
     
     	$ret=false;
     	if(strstr($email, '@') && strstr($email, '.'))
     	{
     		if(preg_match("/^([_a-z0-9]+([._a-z0-9-]+)*)@([a-z0-9]{2,}(.[a-z0-9-]{2,})*.[a-z]{2,3})$/i", $email)) $ret=true;
     	}
     	return $ret;
     }
	 /**
	  * 对数组进行重新索引
	  * @param unknown_type $arr
	  * @return unknown|multitype:unknown
	  */
     public static function resetDataIndex($arr)
     {
     	if(!is_array($arr) || empty($arr)) return $arr;
     	$newArr = array();
     	foreach($arr as $key=>$val)
     	{
     		if(is_numeric($key)) $newArr[] = $val;
     		else $newArr[$key] = $val;
     	}
     	return $newArr;
     }
     
     /**
      * 删除图片
      * @param string $path
      */
     public static function unlinkImage($path)
     {
     	if(!$path) return '';
     	$imageRootPath = Yii::getPathOfAlias('webroot').'/attachments/';
     	//小图 _s.jpg|gif
     	$file = $imageRootPath.$path;
     	if(file_exists($file)) @unlink($file);
     	//原图
     	$orginalImage = str_ireplace('_s', '', $path);   	
     	$file = $imageRootPath.$orginalImage;
     	if(file_exists($file)) @unlink($file);
     	//删除其他尺寸的图片
     	$otherArr= array('m','b');
     	foreach ($otherArr as $sizeStr)
     	{
     		$file = $imageRootPath.str_ireplace('.', '_'.$sizeStr.'.', $orginalImage);
     		if(file_exists($file)) @unlink($file);
     	}
     }
     /**
      * 过滤非法字符
      */
     public static function badWordFilter($str,$badWordsArr)
     {
     	if(!$str) return $str;
     	if(!self::$badWordsArr)
     	{
     		$words = file_get_contents(Yii::getPathOfAlias('webroot').'/protected/data/badwords.txt');
     		if($words) self::$badWordsArr=explode('#', $words);
     	}
     	if(!self::$badWordsArr) return $str;
     	
     	foreach (self::$badWordsArr as $word)
     	{
     		$str = str_ireplace($word,'***', $str);
     	}
     	return $str;
     }
     /**
      * 获取图片的宽高比
      * @param unknown $imagePath
      * @return number
      */
     public static function getImageWH($imagePath) 
     {
     	if(!$imagePath) return;
     	$image = str_ireplace(Yii::app()->request->hostInfo, Yii::getPathOfAlias('webroot'), $imagePath);
     	$image = trim($image);
     	if(!file_exists($image)) return;
     	//echo $image;
     	$info = getimagesize($image);
     	if($info[0] <=0 || $info[1] <= 0) return;
        return $info;
     }
     /**
      * 判断输入 只允许输入 汉字 数字 字母
      * @param unknown $str
      * @return boolean
      */
     public static function checkOnlyCEN($str)
     {
     	if(preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u", $str)) return true;
     	return false;
     }
     
     public static function checkMobile($mobile)
     {
     	if(!$mobile) return false;
     	$pattern = "/^\d{10,12}/";
     	
     	return preg_match($pattern, $mobile) ? true : false;
     	//$pattern = "/^(13|14|15|18)\d{9}$/";
     }
     
     public static function getAttachPath()
     {
     	return Yii::getPathOfAlias('webroot').'/attachments/';
     }
     
     public static function getNewAttachFolder()
     {
     	$folder = date('Y/m/d');
     	$uploaddir = Helper::getAttachPath();
     	if(!is_dir($uploaddir.$folder))
     	{
     		$subFolderarr = explode('/',$folder);
     	
     		foreach($subFolderarr as $sub)
     		{
     			$uploaddir.= $sub.'/';
     			if(!is_dir($uploaddir)) mkdir($uploaddir);
     		}
     	}
     	
     	return $folder;
     }
     
     public static function is_mobile(){
     	$user_agent = $_SERVER['HTTP_USER_AGENT'];
     	$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
     	$is_mobile = false;
     	foreach ($mobile_agents as $device) {
     		if (stristr($user_agent, $device)) {
     			$is_mobile = true;
     			break;
     		}
     	}
     	return $is_mobile;
     }
    
     public static function createSign($id)
     {
     	return md5($id.'wanglai123');
     }
     /**
      * 判断是否关注过公众账号
      * @param unknown $openid
      * @return boolean
      */
     public static function checkIsSubscribe($openid)
     {
     	if(!$openid) return false;
     	return substr($openid,0,4)=='opve' ? true : false;
     }
}