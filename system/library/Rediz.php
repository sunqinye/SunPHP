<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

/**
 * 需要安装phpredis扩展
 * 继承自Redis，用于操作redis
 */
class Rediz extends Redis{
    public function __construct($redisconfig = array()){
        parent::__construct();
        if(empty($redisconfig)){
            $redisconfigs = load_config("redis");
            $redisconfig = $redisconfigs['default'];
        }
        if(!empty($redisconfig['host']) && empty($redisconfig['port']) && empty($redisconfig['timeout']))
            parent::connect($redisconfig['host']);
        else if(!empty($redisconfig['host']) && !empty($redisconfig['port']) && empty($redisconfig['timeout']))
            parent::connect($redisconfig['host'], $redisconfig['port']);
        else if(!empty($redisconfig['host']) && !empty($redisconfig['port']) && !empty($redisconfig['timeout']))
            parent::connect($redisconfig['host'], $redisconfig['port'], $redisconfig['timeout']);
        if(!empty($redisconfig['auth']))
            parent::auth($redisconfig['auth']);
    }
}