<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Log{
    private $log_path;

    /**
     * $isCustomPath 值为0时使用默认路径，值为1时使用$filename指定路径
     */
    public function __construct($filename, $isCustomPath = 0){
        $logconfig = load_config('config', 'log');
        if($isCustomPath == 0){
            if(isset($logconfig['path']) && !empty($logconfig['path'])){
                $this->log_path = $logconfig['path'].$filename;
            }else{
                $this->log_path = APP_PATH.'log/'.$filename;
            }
        }else{
            $this->log_path = $filename;
        }
        if(!file_exists($dirname = dirname($this->log_path)))
            mkdir($dirname, 0777, true);
    }

    /**
     * 写日志
     */
    public function w($content){
        $data = date("Y-m-d H:i:s").','.$content."\n";
        error_log($data, 3, $this->log_path);
    }
}