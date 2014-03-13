<?php
header('content-type:text/html;charset=utf-8');
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
//$config=dirname(__FILE__).'/protected/config/main.php';
$config=dirname(__FILE__).'/protected/config/common.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
error_reporting(E_ERROR);
require_once($yii);
Yii::createWebApplication($config)->run();
