<?php
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'往来',
	'sourceLanguage'=>'zh_cn',
	'timeZone'=>'PRC',
	'behaviors' => array(
		'stat'=>'application.components.behaviors.StatBehavior'
	),
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.admin.controllers.AdminController',
	),

	'modules'=>array('admin','qiye'),
	
	'defaultController'=>'index',

	// application components
	'components'=>array(
		'user'=>array(
			'allowAutoLogin'=>true,
			'autoRenewCookie'=>true,
			'loginUrl'=>array('/admin/login'),
		),
		
		 'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,//注意false不要用引号括上
			'rules'=>array(
				'/' => 'index/index',
				'/adminwl'=>'/admin/login/index',	
				'hk'=>'bless/index',
				'<number:\d+>'=>'member/number',
				'shaoyifu'=>'signature/index/id/FmJNyyHiSNyq',
				'lina'=>'signature/index/id/FzmH6imyBy6e',
				'liudonghua'=>'signature/index/id/FNySzzNBJJzW',
			),
		),
		 
		'curl' => array(
			'class' => 'ext.ECurl'
		),
		// send Mail
		'mail' => array(
			'class' => 'ext.Mail.Mail',
		),
		// 加密解密id
		'encode' => array(
			'class' => 'ext.Encode'
		),
		// 检测各种设备
		'mobileDetect' => array(
			'class' => 'ext.MobileDetect.MobileDetect'
		),
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=hzwcity',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		 'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error,info',
				)
			)
		),
	),

	'params'=>array(),
);
