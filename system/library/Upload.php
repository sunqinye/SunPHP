<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Upload{
    private $field; //文件域
    private $file;  //上传的文件对象
    private $oldname;   //原文件名
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
    public function doUpload($field = 'userfile'){
        $this->field = $field;
        $this->file = $_FILES[$this->field];

        if(empty($this->file)){
            $this->stateInfo = array(
                'error' => 11,
                'message' => $this->errorMessage[11]
            );
            return false;
        }
        if($this->file['error'] != 0){
            $this->stateInfo = array(
                'error' => $this->file['error'],
                'message' => $this->errorMessage[$this->file['error']]
            );
            return false;
        }
        if(!is_uploaded_file($this->file['tmp_name'])){
            $this->stateInfo = array(
                'error' => 12,
                'message' => $this->errorMessage[12]
            );
            return false;
        }

        $this->oldname = $this->file['name'];
        $this->size = $this->file['size'];
        $this->type = $this->getFileExt();

        if(!$this->checkSize()){
            $this->stateInfo = array(
                'error' => 13,
                'message' => $this->errorMessage[13]
            );
            return false;
        }
        if(!$this->checkType()){
            $this->stateInfo = array(
                'error' => 14,
                'message' => $this->errorMessage[14]
            );
            return false;
        }

        $this->saveurl = $this->getFolder().'/'.$this->getNewname();
        if(!move_uploaded_file($this->file["tmp_name"], $this->saveurl)){
            $this->stateInfo = array(
                'error' => 15,
                'message' => $this->errorMessage[15]
            );
        }
        return true;
    }

    /**
     * 获取上传状态信息
     */
    public function getStateInfo(){
        return $this->stateInfo;
    }

    /**
     * 获取上传文件信息
     */
    public function getFileInfo(){
        return array(
            "oldname" => $this->oldname,
            "newname" => $this->newname,
            "saveurl" => $this->saveurl,
            "size" => $this->size,
            "type" => $this->type
        );
    }

    /**
     * 获取新文件名
     * @return string
     */
    private function getNewname(){
        if(empty($this->newname))
            $this->newname = time().rand(1, 10000).$this->getFileExt();
        return $this->newname;
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt(){
        return strtolower(strrchr($this->file["name"], '.'));
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