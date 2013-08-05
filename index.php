<?php
ob_start();
session_start();

// 错误报告设置
error_reporting(E_ALL);

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 定义DIRECTORY_SEPARATOR简写
if(!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

// 定义是否windows平台的全局变量
if(!defined('IS_WIN')) {
    define('IS_WIN', (DS != '/'));
}

// 定义当前目录的全局变量(项目根目录)
if(!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(realpath(__FILE__)));
}

// 定义当前时间常量
if(!defined('CUR_DATE')) {
    define('CUR_DATE', date('Y-m-d'));
}
if(!defined('CUR_DATE_TIME')) {
    define('CUR_DATE_TIME', date('Y-m-d H:i:s'));
}

// 定义载入类库路径
$includes = array(
    ROOT_PATH . DS . 'lib',
    ROOT_PATH . DS . 'ext'
);
ini_set('include_path', implode(IS_WIN ? ';' : ':', $includes));


include ROOT_PATH . DS . "lib/init.php";

import('com.zcx.conf.ContextConfig');
import('com.zcx.core.Application');

$config         = ref(new ContextConfig());
$application    = ref(new Application($config->getContext()));

def('CONFIG', $config);
def('APPLICATION', $application);

$application->run();
?>
