<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Router{

    public function __construct(){
        $this->setPathInfo();   //实例化时即设置PATH_INFO以提升性能，可选
    }

    /**
     * 获取控制器
     */
    public function getController(){
        if(is_cli()){    //如果是命令行访问，先将命令行参数转化为标准的GET变量
            $this->_argvToGET();
        }
        if(($pathinfo = $this->getPathInfo()) != ''){
            $pathinfo = $this->ruleConvert($pathinfo);
            $url = explode('/', $pathinfo);
            $c = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';
        }else{
            $c = isset($_GET['c']) ? $_GET['c'] : 'index';
        }

        return $c;
    }

    /**
     * 获取方法
     */
    public function getMethod(){
        if(is_cli()){    //如果是命令行访问，先将命令行参数转化为标准的GET变量
            $this->_argvToGET();
        }
        if(($pathinfo = $this->getPathInfo()) != ''){
            $pathinfo = $this->ruleConvert($pathinfo);
            $url = explode('/', $pathinfo);
            $m = isset($url[2]) && !empty($url[2]) ? $url[2] : 'index';
        }else{
            $m = isset($_GET['m']) ? $_GET['m'] : 'index';
        }
        if(is_numeric($m)) $m = 'index';

        return $m;
    }

    /**
     * 将命令行参数转化为标准的GET变量
     */
    private function _argvToGET(){
        if(isset($_SERVER['argv'][1])){
            $url = explode('?', $_SERVER['argv'][1]);
            if(!empty($url[1])){
                $params = explode('&', $url[1]);
                foreach($params as $param){
                    $result = explode('=', $param);
                    $_GET[$result[0]] = $result[1];
                }
            }
        }
    }

    /**
     * 将PATH_INFO组装成$_GET并设置
     */
    public function setSuperGET(){
        $pathinfo = $this->getPathInfo();
        if(!empty($pathinfo)){
            $url = explode('/', $pathinfo);
            $_GET = array_merge($_GET, $url);
            array_shift($url);  //移除0
            array_shift($url);  //移除controller
            array_shift($url);  //移除method
            $paramnum = count($url);
            if($paramnum % 2 == 0){
                for($i=0; $i<$paramnum; $i+=2){
                    $_GET[$url[$i]] = $url[$i+1];
                }
            }
        }
    }

    /**
     * 获取PATH_INFO
     */
    public function getPathInfo(){
        if(isset($_SERVER["PATH_INFO"])){
            return $_SERVER["PATH_INFO"];
        }else if(is_cli()){
            if(isset($_SERVER['argv'][1])){
                $url = explode('?', $_SERVER['argv'][1]);
                return $url[0];
            }else{
                return '';
            }
        }else{
            $_SERVER['REQUEST_URI'] = str_replace('/index.php', '', $_SERVER['REQUEST_URI']);
            $prefix = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
            $suffix = empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '?'.$_SERVER['QUERY_STRING'];
            $_SERVER['REQUEST_URI'] = str_replace($prefix, '', $_SERVER['REQUEST_URI']);
            return str_replace($suffix, '', $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * 设置$_SERVER['PATH_INFO']
     */
    public function setPathInfo(){
        $_SERVER['PATH_INFO'] = $this->getPathinfo();
    }

    /**
     * 路由规则转换
     */
    public function ruleConvert($pathinfo){
        $route_config = load_config('route',  'route');
        $keyPathinfo = trim($pathinfo, '/');
        if(array_key_exists($keyPathinfo, $route_config)){
            $pathinfo = str_replace($keyPathinfo, $route_config[$keyPathinfo], $pathinfo);
        }
        return $pathinfo;
    }
}