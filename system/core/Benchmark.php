<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Benchmark{
    private $startTime;
    private $endTime;

    public function start(){
        $this->startTime = microtime(TRUE);
    }

    public function end(){
        $this->endTime = microtime(TRUE);
        echo 'takes '.($this->endTime - $this->startTime).' s';
    }
}