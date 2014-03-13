<?php
$main = require(dirname(__FILE__).'/main.php');

unset($main['defaultController']);
//去悼proload user
unset($main['preload']);
unset($main['behaviors']);
// controllerMap在CConsoleApplication中没有必要

return $main;
