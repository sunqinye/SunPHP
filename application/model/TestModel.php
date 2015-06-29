<?php
class TestModel extends Model{
    private static $instance;
    
    public static function &getInstance() {
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public static function newInstance() {
        return new self();
    }
    
    public function getUserInfoById($uid){
        /*
         * 三种方式
         */
        //$this->db->query("SELECT * FROM user WHERE `uid`='1'");

        //$this->db->table("user");
        //$this->db->where("`uid`='1'");
        //$this->db->selectOne();

        //$this->db->table("user")->where("`uid`='1'")->selectone();
    }
}