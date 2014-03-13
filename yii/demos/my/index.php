<?php
//服务器根目录
define('WEB_ROOT',dirname(dirname(dirname(__FILE__))));
//项目根目录
define('CURRENT_ROOT',dirname(__FILE__));
// 在生产环境中请删除此行
defined('YII_DEBUG') or define('YII_DEBUG',true);
// 包含Yii引导文件
require_once(WEB_ROOT.'/framework/yii.php');
// 创建一个应用实例并执行
$config=CURRENT_ROOT.'/config/main.php';

Yii::createWebApplication($config)->run();