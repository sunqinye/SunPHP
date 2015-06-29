<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Page{
    private $datanum = 1;   //数据数
    private $pagesize = 1;  //每页数据数
    private $currentpage = NULL;   //当前页码
    private $pagenum = NULL;    //页数

    public function __construct($datanum = 1, $pagesize = 1){
        $this->datanum = $datanum;
        $this->pagesize = $pagesize;
        $this->currentpage = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $this->pagenum = intval(($this->datanum-1)/$this->pagesize) + 1;
    }

    public function show($style){
        if($this->currentpage < 1){
            $this->currentpage = 1;
        }else if($this->currentpage > $this->pagenum){
            $this->currentpage = $this->pagenum;
        }
        $pageup = $this->currentpage - 1;
        $pagedowm = $this->currentpage + 1;
        $url = $_SERVER['REQUEST_URI'];
        $parse_url = parse_url($url);
        $url_path = $parse_url['path'];
        $url_path_array = explode('/',$url_path);
        $url_path = end($url_path_array);
        $url_query = isset($parse_url['query'])?$parse_url['query']:'';
        $url_query = preg_replace("/&p=./","",$url_query);

        switch ($style)
        {
            case 1:
                if($this->currentpage == 1){
                    $bar = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }else{
                    $bar = "<a href='$url_path?$url_query&p=$pageup'>上一页</a> ";
                }
                if($this->currentpage == $this->pagenum){
                    $bar = $bar.'';
                }else{
                    $bar = $bar."<a href='$url_path?$url_query&p=$pagedowm'>下一页</a>";
                }
                return $bar;
                break;
            case 2:
                $bar = '第'.$this->currentpage.'页/共'.$this->pagenum.'页&nbsp;';
                if($this->currentpage == 1){
                    $bar = $bar.'';
                }else{
                    $bar = $bar."<a href='$url_path?$url_query&p=$pageup'>上一页</a> ";
                }
                if($this->currentpage == $this->pagenum){
                    $bar = $bar.'';
                }else{
                    $bar = $bar."<a href='$url_path?$url_query&p=$pagedowm'>下一页</a>";
                }
                $bar = $bar.'&nbsp;&nbsp;跳至<select>';
                for($i=1; $i<=$this->pagenum; $i++){
                    $bar = $bar."<option value='$i' onclick=location='$url_path?$url_query&p=$i'>$i</option>";
                }
                $bar = $bar.'</select>';
                return $bar;
                break;
            case 3:
                $bar = "<a href='$url_path?$url_query&p=1'>首页</a> ";
                if($this->pagenum <= 7){
                    for($i=1; $i<=$this->pagenum; $i++){
                        $bar = $bar."<a href='$url_path?$url_query&p=$i'>$i</a> ";
                    }
                }else{
                    if($this->currentpage <= 4){
                        for($i=1; $i<=7; $i++){
                            $bar = $bar."<a href='$url_path?$url_query&p=$i'>$i</a> ";
                        }
                        $bar = $bar.'...';
                    }else if($this->currentpage >= $this->pagenum-3){
                        $bar = $bar.'...';
                        for($i=$this->pagenum-6; $i<=$this->pagenum; $i++){
                            $bar = $bar."<a href='$url_path?$url_query&p=$i'>$i</a> ";
                        }
                    }else{
                        $bar = $bar.'...';
                        for($i=$this->currentpage-3; $i<=$this->currentpage+3; $i++){
                            $bar = $bar."<a href='$url_path?$url_query&p=$i'>$i</a> ";
                        }
                        $bar = $bar.'...';
                    }
                }
                $bar = $bar."<a href='$url_path?$url_query&p=$this->pagenum'>尾页</a>";
                return $bar;
            default:
                $bar = '';
                break;
        }
    }
}