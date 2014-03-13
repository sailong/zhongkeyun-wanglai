<?php

class Weixin extends CApplicationComponent
{
	
	/**
	 * 接收文本消息
	 * @param array $param 微信传递过来的所有参数
	 */
	public static function handelTextMsg($param=array())
	{
		$content = $param['Content'];
		$data = array();
		switch ($content)
		{
			case '1':
				$data = array(
					array(
						'title' 	  => '我的名片',
						'description' => '',
						'picUrl'      => Yii::app()->request->getHostInfo() . '/static/weixin/2.jpg',
						'url'         => Yii::app()->createAbsoluteUrl('member/index')
					)
				);
				break;
		}
		return $data;
	}
	

	/**
	 * 关注公众号事件
	 * @param array $param 微信传递过来的所有参数
	 * @return string 关注公众号后返回的信息
	 */
	public static function handleSubscribeEvent($param=array())
	{
		$model = Member::model()->getMember($param['FromUserName']);
		// 如果是再次关注,更改关注状态
		if(!empty($model))
		{
			$model->saveAttributes(array('subscribe' => Member::SUBSCRIBE));
		}
		
		$str = <<< EOF
欢迎您关注往来微名片！
回复1进入往来微名片

客服QQ：1085606688
客服热线：4000737088
微信客服：wanglairm
客服工作时间：周一至周五9:00-17:00
EOF;
		return $str;
	}
	
	/**
	 * 取消关注公众号事件
	 * @param array $param 微信传递过来的所有参数
	 */
	public static function handleUnSubscribeEvent($param=array())
	{
		$model = Member::model()->getMember($param['FromUserName']);
		if($model) $model->saveAttributes(array('subscribe' => Member::UNSUBSCRIBE));
		return true;
	}
	
	/**
	 * 点击自定义菜单事件
	 * @param array $param 微信传递过来的所有参数
	 */
	public static function handleClickEvent($param=array())
	{
		$key = $param['EventKey'];
		if($key == 'CUSTOM_QUESTION')
		{
			// 常见问题
			$host = Yii::app()->request->getHostInfo();
			$data = array(
				array(
					'title' => '如何创建微名片',
					'description' => '',
					'picUrl' => $host . '/static/weixin/5.jpg',
					'url' => 'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA4MjA5MDQwNg==&appmsgid=10001013&itemidx=1&sign=727e2050bb6e8d4106179bb0d6f03328#wechat_redirect'
				),
				array(
					'title' => '如何发起微活动',
					'description' => '',
					'picUrl' => $host . '/static/weixin/7.jpg',
					'url' => 'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA4MjA5MDQwNg==&appmsgid=10001013&itemidx=3&sign=4911130a8a2f7a5baf39e76d7d949ed9&uin=Mjc2OTAxMDkyMA%3D%3D&key=234b3ec6051a4a54d22130270831692980ad69ff2540078386382656b4163709f0acb8a3afc15b09193cdd57b5666f1d&devicetype=iPhone+OS5.1&version=15000311&lang=zh_TW'
				),
				array(
					'title' => '如何找回名片',
					'description' => '',
					'picUrl' => $host . '/static/weixin/8.jpg',
					'url' => 'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA4MjA5MDQwNg==&appmsgid=10001013&itemidx=4&sign=0b212d2d753d98374ae855ed436fedb5&uin=Mjc2OTAxMDkyMA%3D%3D&key=234b3ec6051a4a54fe1e49b7049d2f8fef0988189884aabf81c6da528241cd6eb4683244143ddc45ae6465f4579b99c7&devicetype=iPhone+OS5.1&version=15000311&lang=zh_TW'
				)					
			);
			return $data;
		}
	}
	
	/**
	 * 获取公众号底部导航菜单
	 */
	public static function getMenus()
	{
		$app = Yii::app();
		$buttons = array(
			array(
				'name' => urlencode('我的名片'),
				'sub_button' => array(
						array(
							'type' => 'view',
							'name' => urlencode('我的名片'),
							'url'  => $app->createAbsoluteUrl('member/index')
						),
						array(
							'type' => 'view',
							'name' => urlencode('名片夹'),
							'url'  => $app->createAbsoluteUrl('manage/index')
						),
						array(
							'type' => 'view',
							'name' => urlencode('修改/完善名片'),
							'url'  => $app->createAbsoluteUrl('member/update')
						),
						array(
							'type' => 'view',
							'name' => urlencode('名片二维码'),
							'url'  => $app->createAbsoluteUrl('member/GetQRcode')
						),
						array(
							'type' => 'view',
							'name' => urlencode('微群通讯录'),
							'url'  => $app->createAbsoluteUrl('contacts/myCreated')
						),
				)
			),
			array(
				'name' => urlencode('微活动'),
				'sub_button' => array(
					array(
						'type' => 'view',
						'name' => urlencode('发起活动'),
						'url'  => $app->createAbsoluteUrl('activity/create')
					),
					array(
						'type' => 'view',
						'name' => urlencode('我发起的'),
						'url'  => $app->createAbsoluteUrl('activity/myCreated')
					),
					array(
						'type' => 'view',
						'name' => urlencode('我参与的'),
						'url'  => $app->createAbsoluteUrl('activity/myJoined')
					),
					array(
						'type' => 'view',
						'name' => urlencode('送贺卡'),
						'url'  => $app->createAbsoluteUrl('bless/index')
					)
				)
			),
	
			array(
				'name' => urlencode('更多'),
				'sub_button' => array(
					array(
						'type' => 'view',
						'name' => urlencode('客户服务'),
						'url'  => $app->createAbsoluteUrl('feedback/service')
					),
					array(
						'type' => 'click',
						'name' => urlencode('常见问题'),
						'key'  => 'CUSTOM_QUESTION'
					)
				)
			),
		);
		
		return $buttons;
	}
	
	
}