<?php
class TestBlock{

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

    public function test(){
    }
}