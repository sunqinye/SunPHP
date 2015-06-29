<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Mysql_driver{
    private $link;

    /**
     * 连接数据库
     * @param $host
     * @param $port
     * @param $user
     * @param $password
     * @param $name
     */
    public function connect($host, $port, $user, $password, $name)
    {
        $this->link = @mysql_connect($host.':'.$port, $user, $password) or exit('MySQL connect failure');
        @mysql_select_db($name, $this->link) or exit('Database '.$name.' connect failure');
    }

    /**
     * 执行一条SQL语句
     */
    public function query($sql){
        return mysql_query($sql, $this->link);
    }

    /**
     * 提取结果
     */
    public function fetch($result, $result_type){
        switch($result_type){
            case 'BOTH':
                return @mysql_fetch_array($result);
                break;
            case 'ASSOC':
                return @mysql_fetch_assoc($result);
                break;
            case 'NUM':
                return @mysql_fetch_row($result);
                break;
            case 'OBJECT':
                return @mysql_fetch_object($result);
                break;
            default:
                break;
        }
    }

    /**
     * 释放结果内存
     */
    public function freeResult($result){
        return mysql_free_result($result);
    }

    /**
     * 获取上一个INSERT操作产生的AUTO_INCREMENT的ID
     */
    public function getInsertId(){
        return mysql_insert_id($this->link);
    }

    /**
     * 获取结果集中行的数目
     */
    public function rowCount($result){
        return mysql_num_rows($result);
    }

    /**
     * 获取结果集中字段的数目
     */
    public function columnCount($result){
        return mysql_num_fields($result);
    }

    /**
     * 关闭MySQL连接
     */
    public function close(){
        return mysql_close($this->link);
    }
}