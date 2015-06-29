<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Download{
    private $url;   //文件下载链接

    private $newname;   //新文件名
    private $size;  //文件大小
    private $type;   //文件类型
    private $maxsize = 2048;    //最大大小，单位KB
    private $allowtype = array();    //允许文件类型
    private $savepath;  //保存路径
    private $saveurl;   //保存地址

    private $stateInfo = array();            //上传状态信息
    private $errorMessage = array(
        0 => "上传成功",
        1 => "文件大小超过了php.ini中upload_max_filesize选项限制的值",
        2 => "文件大小超过了表单中MAX_FILE_SIZE选项指定的值",
        3 => "文件只有部分被上传",
        4 => "没有文件被上传",
        6 => "找不到临时文件夹",
        7 => "文件写入失败",
        10 => "未知错误",
        11 => "FILES变量为空",
        12 => "文件不是通过POST上传",
        13 => "文件大小超出指定限制",
        14 => "不允许的文件类型",
        15 => "文件保存时出错",
        16 => "目录创建失败",
    );

    /**
     * 执行上传
     */
    public function doDownload($url){
        $this->url = $url;
        $this->type = $this->getFileExt();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
        $filedata = curl_exec($curl);
        curl_close($curl);

        $this->saveurl = $this->getFolder().'/'.$this->getNewname();
        $fp = @fopen($this->saveurl, 'a');
        fwrite($fp, $filedata);
        fclose($fp);
        return true;
    }

    /**
     * 获取下载状态信息
     */
    public function getStateInfo(){
        return $this->stateInfo;
    }

    /**
     * 获取下载文件信息
     */
    public function getFileInfo(){
        return array(
            "saveurl" => $this->saveurl,
        );
    }

    /**
     * 获取新文件名
     * @return string
     */
    private function getNewname(){
        return time().rand(1, 10000).$this->getFileExt();
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt(){
        return strtolower(strrchr($this->url, '.'));
    }

    /**
     * 获取存储文件夹，若不存在按照日期创建
     * @return string
     */
    private function getFolder(){
        $path = $this->savepath;
        if(strrchr($path, "/") != "/") $path .= "/";
        $path .= date("Ymd");
        if(!file_exists($path)){
            if(!mkdir($path, 0777, true)){
                $this->stateInfo = array(
                    'error' => 16,
                    'message' => $this->errorMessage[16]
                );
                return false;
            }
        }
        return $path;
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType(){
        return in_array($this->type, $this->allowtype);
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private function checkSize(){
        return $this->size <= ($this->maxsize * 1024);
    }

    /**
     * 设置保存路径
     */
    public function setSavePath($savepath){
        $this->savepath = $savepath;
    }

    /**
     * 设置最大大小
     */
    public function setMaxSize($maxsize){
        $this->maxsize = $maxsize;
    }

    /**
     * 设置允许类型
     */
    public function setAllowType($allowtype){
        $this->allowtype = $allowtype;
    }
}