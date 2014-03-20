<?php

ini_set('memory_limit', '1576M');
set_time_limit (360);

//die (print_r(hash_algos()));


date_default_timezone_set('Europe/London');

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii-1.1.14/framework/yii.php';

// ---------------------------------------------------------------------------
// Define environment specific details, includnig where yii is, in a single file.
// ---------------------------------------------------------------------------
include_once(dirname(__FILE__).'/protected/config/environments.php') ;

$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', !IS_PUBLIC_FACING);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
