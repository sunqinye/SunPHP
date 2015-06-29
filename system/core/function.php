<?php
/**
 * 核心函数库
 */
if(!defined('SYSTEM_PATH')) exit('Access Denied');

/**
 * 加载config文件
 */
function load_config($filename, $key=''){
    if(ENVIRONMENT == ''){
        if(file_exists($file_path = APP_PATH."config/".$filename.".php"))
			require $file_path;
		else
			return false;
    }else{
		if(file_exists($file_path = APP_PATH."config/".ENVIRONMENT."/".$filename.".php"))
			require $file_path;
		else if(file_exists($file_path = APP_PATH."config/".$filename.".php"))
			require $file_path;
		else
			return false;
    }

    if(empty($key)){
        return $config[$filename];
    }else{
        return $config[$key];
    }
}

/**
 * 加载library文件
 */
function load_library($filename)
{
	if(file_exists($file_path = APP_PATH.'library/'.$filename.'.php'))
		require_once $file_path;
	else if(file_exists($file_path = SYSTEM_PATH.'library/'.$filename.'.php'))
		require_once $file_path;
	else
		return false;
	return true;
}

/**
 * 加载helper文件
 */
function load_helper($filename)
{
	if(file_exists($file_path = APP_PATH.'helper/'.$filename.'.php'))
		require_once $file_path;
	else if(file_exists($file_path = SYSTEM_PATH.'helper/'.$filename.'.php'))
		require_once $file_path;
	else
		return false;
	return true;
}

/**
 * 是否是通过命令行执行
 */
function is_cli(){
	return PHP_SAPI=='cli' ? 1 : 0;
}

/**
 * 当前PHP版本号与指定版本比较
 */
function compare_version($symbol, $target_version){
	return version_compare(PHP_VERSION, $target_version, $symbol);
}

/*
 * 数据过滤
 */
function clean($data)
{
	if(is_array($data)){
		$newString = array();
		foreach($data as $key => $str){
			if(is_array($str)){
				$newString[$key] = clean($str);
			}else{
				if(!get_magic_quotes_gpc()){
					$newString[$key] = trim(htmlspecialchars(addslashes($str)));
				}else{
					$newString[$key] = trim(htmlspecialchars($str));
				}
			}
		}
	}else{
		if(!get_magic_quotes_gpc()){
			$newString = trim(htmlspecialchars(addslashes($data)));
		}else{
			$newString = trim(htmlspecialchars($data));
		}
	}
	return $newString;
}

/*
 * 数据解过滤
 */
function declean($data)
{
	if(is_array($data)){
		$newString = array();
		foreach($data as $key => $str){
			if(is_array($str)){
				$newString[$key] = clean($str);
			}else{
				$newString[$key] = stripcslashes(htmlspecialchars_decode($str));
			}
		}
	}else{
		$newString = stripcslashes(htmlspecialchars_decode($data));
	}
	return $newString;
}

/*
 * 跳转
 */
function Jump($url, $alert = '')
{
	if($alert == ''){
		echo "<script type='text/javascript'>location='$url';</script>";
	}else{
		echo "<script type='text/javascript'>alert('$alert');location='$url';</script>";
	}
}

/*
 * 返回
 */
function back($alert = '')
{
	if($alert == ''){
		echo "<script>history.back();</script>";
	}else{
		echo "<script>alert('$alert');history.back();</script>";
	}
}

/**
 * 输出404页面
 */
function show_404(){
	header('HTTP/1.1 404 Not Found');
	exit('404 Not Found');
}