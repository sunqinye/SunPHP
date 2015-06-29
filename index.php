<?php
/*
 * 定义调试模式
 * false:生产环境   true:开发环境
 */
define('DEBUG', false);
/*
 * 定义配置目录
 */
define('ENVIRONMENT', '');
/*
 * 定义编码
 */
define('CHARSET', 'utf8');
/*
 * 定义应用域名
 */
define('APP_DOMAIN', 'http://127.0.0.1');
/*
 * 定义应用文件夹名称
 */
define('APP_FOLDER', 'application');
/*
 * 定义框架文件夹名称
 */
define('SYSTEM_FOLDER', 'system');

require_once SYSTEM_FOLDER.'/core/sun.php';