<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class CookieHelper{
    /**
     * 设置Cookie
     */
    public static function cookie($name, $value, $expire=0)
    {
        load_library('Edcrypt');
        $cookieconfig = load_config('config', 'cookie');
        $prefix = $cookieconfig['prefix'];
        $path = $cookieconfig['path'];
        $domain = $cookieconfig['domain'];
        $name = $prefix.$name;
        $name = base64_encode($name);
        $name = strtr($name,'=','S');
        $ed = new Edcrypt();
        $value = $ed->encrypt($value);
        setcookie($name, $value, time()+$expire, $path, $domain);
    }

    /**
     * 清除Cookie
     */
    public static function delcookie($name)
    {
        $cookieconfig = load_config('config', 'cookie');
        $prefix = $cookieconfig['prefix'];
        $path = $cookieconfig['path'];
        $domain = $cookieconfig['domain'];
        $name = $prefix.$name;
        $name = base64_encode($name);
        $name = strtr($name,'=','S');
        setcookie($name, '', time()-1, $path, $domain);
    }

    /**
     * 获取Cookie值
     */
    public static function getcookie($name)
    {
        load_library('Edcrypt');
        $cookieconfig = load_config('config', 'cookie');
        $prefix = $cookieconfig['prefix'];
        $name = $prefix.$name;
        $name = base64_encode($name);
        $name = strtr($name,'=','S');
        $ed = new Edcrypt();
        if(isset($_COOKIE[$name])){
            return $ed->decrypt($_COOKIE[$name]);
        }else{
            return false;
        }
    }

    /**
     * 检测Cookie是否存在
     */
    public static function checkcookie($name)
    {
        $cookieconfig = load_config('config', 'cookie');
        $prefix = $cookieconfig['prefix'];
        $name = $prefix.$name;
        $name = base64_encode($name);
        $name = strtr($name,'=','S');
        if(isset($_COOKIE[$name])){
            return true;
        }else{
            return false;
        }
    }
}