<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class CharsetHelper{
    /*
     * 判断字符串编码是否为UTF8
     */
    public static function is_utf8($string)
    {
        if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$string) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$string) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$string) == true){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 判断字符串编码是否为Ascii
     */
    public static function is_ascii($string)
    {
        return (preg_match('/[^\x00-\x7F]/S', $string) === 0);
    }
}