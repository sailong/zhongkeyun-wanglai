<?php

class WeixinBridge
{
	
	private $_token = 'WEQR123423432DERREWRWE';
	
	// 微信测试号,APPId
	private static $_appId = 'wxc61d5668292f8a0d';
	private static $_appSecret = '1c2cc8e92058879cf15b55233593fd0c';
	
	// 找地铁
	//private static $_appId = 'wx78223aa019d5595f';
	//private static $_appSecret = '6cac988cefe7fe95ff57afe356377dd2';
	
	// 正式
	//private static $_appId = 'wxac3940a5a72eda2a';
	//private static $_appSecret = 'eed0e73141d05e6c904da0d359ef49ab';
	
	/**
	 * 发送客服消息(48小时内和微信公众号交互过)
	 * @param string openid 发送的用户的openid
	 * @param string $message 发送的消息
	 */
	public static function sendServiceMessage($openid,$message)
	{
		$accessToken = self::getAccessToken();
		if($accessToken)
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$accessToken;
			$data = array(
				'touser' => $openid,
				'msgtype' => 'text',
				'text' => array(
					'content' => $message		
				)
			);
			$result = Yii::app()->getComponent('curl')->post($url,urldecode(json_encode($data)));
			if(!empty($result))
			{
				$result = json_decode($result,true);
				//print_r($result);
			}
		}
	}
	
	/**
	 * 创建菜单接口
	 * @param array $buttons 要创建的菜单数组
	 */
	public static function createMenu($buttons=array())
	{
		$accessToken = self::getAccessToken();
		if($accessToken)
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$accessToken;
			$post = array('button'=>$buttons);
			$result = Yii::app()->getComponent('curl')->post($url,urldecode(json_encode($post)));
			if(!empty($result))
			{
				$result = json_decode($result,true);
				print_r($result);
			}
		}
	}
	/**
	 * 删除菜单接口
	 */
	public static function deleteMenu()
	{
		$accessToken = self::getAccessToken();
		if($accessToken)
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$accessToken;
			$result = Yii::app()->getComponent('curl')->get($url);
			print_r(json_decode($result,true));
		}
	}
	
	/**
	 * 回复文本消息
	 * @param string $from  openid
	 * @param string $to    openid
	 * @param string $content
	 */
	public static function responseTextMessage($from,$to,$content)
	{
		$formatData = array(
			'ToUserName' => $to,
			'FromUserName' => $from,
			'CreateTime' => time(),
			'MsgType' => 'text',
			'Content' => $content	
		);
		return self::getXml($formatData);
	}
	
	/**
	 * 回复图文消息
	 * @param string $from
	 * @param string $to
	 * @param array $data,图文消息数组,需要包含(title,
	 */
	public static function responseTextImgMessage($from,$to,$data)
	{
		$total = count($data);
		if($total>0) 
		{
			$formatData = array(
				'ToUserName' => $to,
				'FromUserName' => $from,
				'CreateTime' => time(),
				'MsgType' => 'news',
				'ArticleCount' => $total,
				'Articles' => $data	
			);
			return self::getXml($formatData);
		}
	}
	/**
	 * 获取AccessToken
	 */
	private static function getAccessToken()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::$_appId.'&secret='.self::$_appSecret;
		$result = Yii::app()->getComponent('curl')->get($url);
		$result = json_decode($result,true);
		return isset($result['access_token']) ? $result['access_token'] : false;
	}
	
	/**
	 * xml格式输出
	 * @param array $formatData 格式化后的数组数据
	 * @return string
	 */
	private static function getXml($formatData=array())
	{
		$xml = '<xml>' . PHP_EOL;
		$tpl = "	<%s><![CDATA[%s]]></%s>";
		foreach ($formatData as $key => $value)
		{
			if(is_array($value))
			{
				$xml .= '	<'.$key.'>' . PHP_EOL;
				foreach ($value as $item)
				{
					$xml .= '		<item>' . PHP_EOL;
					foreach ($item as $k => $v)
					{
						$k = ucfirst($k);
						$xml .= '		' . sprintf($tpl, $k, $v, $k) . PHP_EOL;
					}
					$xml .= '		</item>' . PHP_EOL;
				}
				$xml .= '	</'.$key.'>' . PHP_EOL;
			}else
			{
				if(is_numeric($value))
				{
					$xml .= '	' . sprintf("<%s>%d</%s>", $key, $value, $key) . PHP_EOL;
				}else 
				{
					$xml .=  sprintf($tpl,$key, $value, $key) . PHP_EOL;
				}
			}
		}
		$xml .= '</xml>';
		return $xml;
	}
	
}