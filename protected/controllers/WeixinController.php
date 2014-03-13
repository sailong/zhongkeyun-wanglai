<?php 

/**
 * 与微信交互
 * @author JZLJS00
 *
 */
class WeixinController extends CController
{
	
	/**
	 * token
	 * @var string
	 */
	private $_token = 'WEQR123423432DERREWRWE';
	
	/**
	 * 微信端发送的数据
	 * @var array
	 */
	private $_data = array();
	
	
	/**
	 * 验证消息的合法性
	 * @see CController::beforeAction()
	 */
	protected function beforeAction()
	{
		$echoStr = Yii::app()->request->getParam('echostr','');
		if(!empty($echoStr))
		{
			$request = Yii::app()->request;
			$signature = $request->getParam('signature','');
			$tmpArr = array($this->_token, $request->getParam('timestamp',''), $request->getParam('nonce',''));
			sort($tmpArr);
			if(sha1(implode($tmpArr)) == $signature)
			{
				echo $echoStr;exit();
			}
		}
		return true;
	}
	
	/**
	 * 获取微信端发送的数据
	 */
	public function actionIndex()
	{
		$data = file_get_contents("php://input");
		if (!empty($data))
		{
			$this->_data = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)),true);
			$this->dispatch();
		}
	}
	
	/**
	 * 根据$_data数据的不同,调用不同的函数
	 */
	protected function dispatch()
	{
		$msgType = $this->_data['MsgType'];
		$action = '';
		switch ($msgType)
		{
			case 'text':
				$action = 'handelResponseMsg';
				break;
			case 'event':
				$map = array('subscribe' => 'handleSubscribeEvent','unsubscribe' => 'handleUnSubscribeEvent', 'CLICK' => 'handleClickEvent');
				$action = $map[$this->_data['Event']];
				break;
		}
		if(!empty($action))
			$this->$action();
	}
	
	/**
	 * 接收文本消息,回复图文消息
	 */
	protected function handelResponseMsg()
	{
		echo WeixinBridge::responseTextImgMessage($this->_data['ToUserName'], $this->_data['FromUserName'], Weixin::handelTextMsg($this->_data));
	}
	
	/**
	 * 关注公众号事件
	 */
	protected function handleSubscribeEvent()
	{
		echo WeixinBridge::responseTextMessage($this->_data['ToUserName'], $this->_data['FromUserName'], Weixin::handleSubscribeEvent($this->_data));
	}
	
	/**
	 * 取消关注公众号事件
	 */
	protected function handleUnSubscribeEvent()
	{
		Weixin::handleUnSubscribeEvent($this->_data);
	}
	
	/**
	 * 点击自定义菜单事件
	 */
	protected function handleClickEvent()
	{
		echo WeixinBridge::responseTextImgMessage($this->_data['ToUserName'], $this->_data['FromUserName'], Weixin::handleClickEvent($this->_data));
	}
	
	
	
	public function actionCreateMenu()
	{
		return WeixinBridge::createMenu(Weixin::getMenus());
	}
}