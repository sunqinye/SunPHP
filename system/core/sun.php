<?php
/**
 * SunPHP For utf-8
 * This is an open-source software, follow the Apache License 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * Copyright: Author All rights reserved.
 * @Author: Sun Qinye  sunqinye@gmail.com
 * @Github: https://github.com/sunqinye/SunPHP
 * @Version: 1.0.0
 * @Date: 2014-12-24
 */

defined('DEBUG') or define('DEBUG', false);
defined('ENVIRONMENT') or define('ENVIRONMENT', '');
defined('CHARSET') or define('CHARSET', 'utf-8');
defined('APP_DOMAIN') or define('APP_DOMAIN', 'http://127.0.0.1/');
defined('APP_FOLDER') or define('APP_FOLDER', 'application');
defined('SYSTEM_FOLDER') or define('SYSTEM_FOLDER', 'system');
//定义应用路径
define('APP_PATH', realpath(__DIR__.'/../../').'/'.APP_FOLDER.'/');
//定义框架路径
define('SYSTEM_PATH', realpath(__DIR__.'/../../').'/'.SYSTEM_FOLDER.'/');

//调试模式设置
if(DEBUG){
    ini_set('display_errors', 1);
	error_reporting(E_ALL);
}else{
    ini_set('display_errors', 0);
	error_reporting(0);
}

//设置页面编码:utf-8/gbk
header("content-type:text/html; charset=".CHARSET);
//设置时区
date_default_timezone_set('PRC');
//设置自动加载
spl_autoload_register('autoload');
//关闭魔术引号
if(version_compare(PHP_VERSION, '5.4', '<')){
	ini_set('magic_quotes_runtime', 0);
}

/*
 * 加载框架文件
 */
require_once SYSTEM_PATH.'config/config.php';
require_once SYSTEM_PATH.'core/function.php';
require_once SYSTEM_PATH.'core/Controller.php';
require_once SYSTEM_PATH.'core/Model.php';
require_once SYSTEM_PATH.'core/Router.php';
require_once SYSTEM_PATH.'core/Log.php';
require_once SYSTEM_PATH.'core/Benchmark.php';
require_once SYSTEM_PATH.'database/Database.php';

/*
 * 加载应用文件
 */
if(file_exists($file_path = APP_PATH."common.php"))	require_once $file_path; //加载APP的common文件

/*
 * 路由设置
 */
$router = new Router();
$c = $router->getController();
$m = $router->getMethod();
$router->setSuperGET();

/*
 * 实例化控制器
 */
if(class_exists($c = ucfirst($c)."Controller")){
	$controller = new $c;
	if(method_exists($controller, $m)){
		$controller->before();
		$controller->$m();
		$controller->after();
	}else{
	    show_404();
	}
}else{
	show_404();
}

/*
 * 类文件自动加载
 */
function autoload($class)
{
    if(substr($class, -10) == "Controller" && file_exists($file_path = APP_PATH."controller/".$class.".php"))
		require_once $file_path;
    if(substr($class, -5) == "Block" && file_exists($file_path = APP_PATH."block/".$class.".php"))
		require_once $file_path;
    if(substr($class, -5) == "Model" && file_exists($file_path = APP_PATH."model/".$class.".php"))
		require_once $file_path;
}