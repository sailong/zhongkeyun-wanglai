<?php
class sms
{
	private $apiUrl='http://3g.3gxcm.com/sms/push_mt.jsp';
	private $account;
	private $ch;
	
	public function __construct()
	{
		$this->account['cpid'] = 'wanglai';
		$this->account['cppwd'] = 'wanglai';
	}
	/*
	 * 根据接口获取产品信息
	 * 多个手机号用 英文逗号分开
	 */
	public function send($mobile,$content)
	{
		if(!$mobile || !$content) return;
		$this->init_curl();
		$data = $this->account;
		$data['phone'] = $mobile;
		$data['msgcont'] = $content;
		$data['spnumber'] = '';
		$returnData = $this->send_post($data);
		
		$return[1] = 0;
		if($returnData =='0')  $return[1] = 1;
		//var_dump($returnData);
		return $return;
		return explode(',',$returnData);
	}
	
	
	private function send_post($post_data,$url='')
	{
		if(!$url) $url = $this->apiUrl;
		curl_setopt($this->ch , CURLOPT_URL, $url);
		curl_setopt($this->ch , CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->ch , CURLOPT_POST, TRUE); //指定post数据
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_data);
		return curl_exec($this->ch);
	}
	
	private function send_get($data)
	{
		$url= $this->apiUrl.'?'.http_build_query($data);
		curl_setopt($this->ch , CURLOPT_URL, $url);
		curl_setopt($this->ch , CURLOPT_RETURNTRANSFER, TRUE);
		return curl_exec($this->ch);
	}

	/**
	 * 初始化curl
	 */
	private function init_curl()
	{
		$this->ch  = curl_init();
		
	}
	private function close_curl()
	{
		if($this->ch) curl_close($this->ch);
	}
	
	function __destruct()
	{
		$this->close_curl();
	}

}
?>