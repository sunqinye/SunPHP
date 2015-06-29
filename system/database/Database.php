<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Database{
    private $type;
    private $host;
    private $port;
    private $user;
    private $password;
    private $name;
    private $prefix;
    private $charset;

    private $driver;

    private $table = NULL;
    private $field = '*';
    private $where = NULL;
    private $join = NULL;
    private $order = NULL;
    private $limit = NULL;
    private $other = NULL;

    private $datanum = NULL;
    private $pagesize = 1;

    public function __construct($dbconfig)
    {
        $this->type = $dbconfig['driver'];
        $this->host = $dbconfig['host'];
        $this->port = $dbconfig['port'];
        $this->user = $dbconfig['user'];
        $this->password = $dbconfig['password'];
        $this->name = $dbconfig['name'];
        $this->prefix = $dbconfig['prefix'];
        $this->charset = $dbconfig['charset'];

        switch ($this->type) {
            case 'mysql':
                require_once SYSTEM_PATH.'database/driver/mysql/Mysql_driver.php';
                $this->driver = new Mysql_driver();
                break;
            case 'mysqli':
                require_once SYSTEM_PATH.'database/driver/mysqli/Mysqli_driver.php';
                $this->driver = new Mysqli_driver();
                break;
            default:
                break;
        }

        $this->driver->connect($this->host, $this->port, $this->user, $this->password, $this->name);
        $this->driver->query("SET NAMES ".$this->charset);
    }

    /**
     * 选择数据表
     */
    public function table($table){
        $this->table = $this->prefix.$table;
        return $this;
    }

    /**
     * 指定字段
     */
    public function field($field){
        $this->field = $field;
        return $this;
    }

    /**
     * 添加WHERE条件
     */
    public function where($where){
        $this->where = " WHERE ".$where;
        return $this;
    }

    /**
     * 添加JOIN条件
     */
    public function join($join){
        $this->join = " ".$join;
        return $this;
    }

    /**
     * 设置ORDER条件
     * [ASC|DESC]
     */
    public function order($order){
        $this->order = " ORDER BY ".$order;
        return $this;
    }

    /**
     * 添加WHERE条件
     */
    public function limit($limit){
        $this->limit = " LIMIT ".$limit;
        return $this;
    }

    /**
     * 添加混杂条件
     */
    public function other($other){
        $this->other = " ".$other;
        return $this;
    }

    /**
     * 执行一条SQL语句
     */
    public function query($sql){
        return $this->driver->query($sql);
    }

    /**
     * 执行插入
     */
    public function insert($data){
        $field = '('.implode(',', array_map(function ($v){return '`'.$v.'`';}, array_keys($data))).')';
        $value = '('.implode(',', array_map(function ($v){return "'".$v."'";}, array_values($data))).')';
        $sql = "INSERT INTO ".$this->table.$field." VALUES ".$value;
        $this->free();
        return $this->driver->query($sql);
    }

    /**
     * 批量插入
     */
    public function multiInsert($data){
        $field = '('.implode(',', array_map(function ($v){return '`'.$v.'`';}, array_keys($data[0]))).')';
        $valueArray = array();
        foreach($data as $one){
            $valueArray[] = '('.implode(',', array_map(function ($v){return "'".$v."'";}, array_values($one))).')';
        }
        $values = implode(',', $valueArray);
        $sql = "INSERT INTO ".$this->table.$field." VALUES ".$values;
        $this->free();
        return $this->driver->query($sql);
    }

    /**
     * 执行更新
     */
    public function update($data){
        $datastr = '';
        foreach($data as $key => $value){
            if($datastr == ''){
                if(strstr($value, $key.'+'))
                    $datastr .= "`".$key."`=".$value;
                else
                    $datastr .= "`".$key."`='".$value."'";
            }else{
                if(strstr($value, $key.'+'))
                    $datastr .= ",`".$key."`=".$value;
                else
                    $datastr .= ",`".$key."`='".$value."'";
            }
        }
        $sql = "UPDATE ".$this->table." SET ".$datastr.$this->where.$this->join.$this->order.$this->limit.$this->other;
        $this->free();
        return $this->driver->query($sql);
    }

    /**
     * 执行删除
     */
    public function delete(){
        $sql = "DELETE FROM ".$this->table.$this->where.$this->order.$this->limit.$this->other;
        $this->free();
        return $this->driver->query($sql);
    }

    /**
     * 执行查询返回结果集
     */
    public function select(){
        $sql = "SELECT ".$this->field." FROM ".$this->table.$this->where.$this->join.$this->order.$this->limit.$this->other;
        $this->free();
        return $this->driver->query($sql);
    }

    /**
     * 执行查询并取出一条记录
     */
    public function selectOne($result_type = 'ASSOC'){
        $sql = "SELECT ".$this->field." FROM ".$this->table.$this->where.$this->join.$this->order.$this->limit.$this->other;
        $this->free();
        return $this->driver->fetch($this->driver->query($sql), $result_type);
    }

    /*
     * 执行查询并取出所有记录
     */
    public function selectAll($result_type = 'ASSOC'){
        $sql = "SELECT ".$this->field." FROM ".$this->table.$this->where.$this->join.$this->order.$this->other;
        $result = $this->driver->query($sql);
        $this->datanum = $this->driver->rowCount($result);
        $sql = "SELECT ".$this->field." FROM ".$this->table.$this->where.$this->join.$this->order.$this->limit.$this->other;
        $this->free();
        $result = $this->driver->query($sql);
        $all = array();
        while($one =  $this->driver->fetch($result, $result_type)){
            $all[] = $one;
        }
        return $all;
    }

    /**
     * 获取上一个INSERT操作产生的AUTO_INCREMENT的ID
     */
    public function getInsertId(){
        return $this->driver->getInsertId();
    }

    /**
     * 获取结果集中行的数目
     */
    public function rowCount($result){
        return $this->driver->rowCount($result);
    }

    /**
     * 获取结果集中字段的数目
     */
    public function columnCount($result){
        return $this->driver->columnCount($result);
    }

    /**
     * 清空私有属性
     */
    private function free(){
        $this->table = NULL;
        $this->field = '*';
        $this->where = NULL;
        $this->join = NULL;
        $this->order = NULL;
        $this->limit = NULL;
        $this->other = NULL;
    }


    /**
     * 设置分页
     */
    public function page($pagesize){
        $currentpage = isset($_GET['p'])?intval($_GET['p']):1;
        if($currentpage < 1) $currentpage = 1;
        $this->pagesize = $pagesize;
        $this->limit = " LIMIT ".($currentpage-1)*$this->pagesize.",".$this->pagesize;
        return $this;
    }

    /**
     * 显示页码
     */
    public function show($style){
        load_library("Page");
        $page = new Page($this->datanum, $this->pagesize);
        return $page->show($style);
    }
}